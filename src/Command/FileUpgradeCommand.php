<?php

namespace Cake\Upgrade\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\Exception\StopException;
use Cake\Core\Configure;
use Cake\Upgrade\Processor\Processor;
use Cake\Upgrade\Task\Cake50\BasicsTask;
use Cake\Upgrade\Task\Cake50\CiTask;
use Cake\Upgrade\Task\Cake50\ComposerPsr2rTask;
use Cake\Upgrade\Task\Cake50\ComposerTask;
use Cake\Upgrade\Task\Cake50\DatabaseTypeDriverTask;
use Cake\Upgrade\Task\Cake50\LoadModelTask;
use Cake\Upgrade\Task\Cake50\ModelHookTask;
use Cake\Upgrade\Task\Cake50\ModelValidatorTask;
use Cake\Upgrade\Task\Cake50\PhpunitXmlTask;
use Cake\Upgrade\Task\Cake50\ReadmeTask;
use Cake\Upgrade\Task\Cake50\RemoveOutdatedCodeTask;
use Cake\Upgrade\Task\Cake50\ShellToCommandTask;
use Cake\Upgrade\Task\Cake50\TemplatesFormHelperTask;
use Cake\Upgrade\Task\Cake50\TestsBootstrapFixtureTask;
use Cake\Upgrade\Task\Cake50\TestsCommandTask;
use Cake\Upgrade\Task\Cake50\TestsControllerInstantiationTask;
use Cake\Upgrade\Task\Cake50\TestsFixtureSchemaTask;
use Cake\Upgrade\Task\Cake50\TypedClosureTask;
use Cake\Upgrade\Task\Cake50\TypedPropertyEntityTask;
use Cake\Upgrade\Task\Cake50\TypedPropertyFixtureTask;
use Cake\Upgrade\Task\Cake50\TypedPropertyPluginTask;
use Cake\Upgrade\Task\Cake50\TypedPropertyTask;
use Cake\Upgrade\Task\Cake50\TypedPropertyTestCaseTask;
use Cake\Upgrade\Task\Cake50\TypeFactoryTask;
use Cake\Upgrade\Task\ChangeSet;
use DirectoryIterator;
use InvalidArgumentException;

class FileUpgradeCommand extends Command {

	/**
	 * The name of this command.
	 *
	 * @var string
	 */
	protected $name = 'upgrade';

	/**
	 * Any levels always include previous ones.
	 *
	 * @var array<string>
	 */
	protected array $levels = [
		'cake45',
		'cake50',
	];

	/**
	 * E.g.:
	 * bin/cake upgrade /path/to/app --level=cakephp40
	 *
	 * @param \Cake\Console\Arguments $args The command arguments.
	 * @param \Cake\Console\ConsoleIo $io The console io
	 *
	 * @throws \Cake\Console\Exception\StopException
	 * @return int|null|void The exit code or null for success
	 */
	public function execute(Arguments $args, ConsoleIo $io) {
		$path = $args->getArgumentAt(0);
		if ($path) {
			$path = realpath($path);
		}
		if ($path) {
			$path .= DS;
		}

		if (!is_dir($path)) {
			$io->error('Project path not found: ' . $args->getArgumentAt(0));

			throw new StopException();
		}
		if (!file_exists($path . 'composer.json')) {
			$io->error('Composer.json not found in ' . $args->getArgumentAt(0));

			throw new StopException();
		}

		$this->process($path, $args, $io);

		$io->out('Done :)');
		if (!$args->getOption('verbose')) {
			$io->out('Tip: Use -v (verbose mode) and -d (dry-run) to see diff/changes without executing them.');
		}
	}

	/**
	 * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
	 *
	 * @return \Cake\Console\ConsoleOptionParser The built parser.
	 */
	protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
		$parser = parent::buildOptionParser($parser)
			->setDescription('A tool to help automate upgrading CakePHP apps and plugins. ' .
				'Be sure to have a backup of your application before running these commands.')->addArgument('path', [
				'name' => 'Path to app/plugin ROOT (where composer.json is)',
				'required' => true,
			]);
		$parser->addOption('set', [
			'help' => 'What set to use (TODO: defaults to all available up to the one defined in composer.json)',
		]);
		$parser->addOption('dry-run', [
			'help' => 'Dry run.',
			'short' => 'd',
			'boolean' => true,
		]);

		return $parser;
	}

	/**
	 * @param string $path
	 * @param \Cake\Console\Arguments $args
	 * @param \Cake\Console\ConsoleIo $io
	 * @return void
	 */
	protected function process(string $path, Arguments $args, ConsoleIo $io) {
		$io->out('Processing: ' . $path);
		$io->out('Sets:');
		$levels = $this->levels($args->getOption('set'));

		$changeSet = new ChangeSet();
		foreach ($levels as $level) {
			$io->out(' - ' . $level);

			$options = $args->getOptions() + [
					'path' => $path,
				];
			$changes = $this->taskProcessor($level, $options)->process($path);
			$changeSet->add($changes);
		}

		$io->success(count($changeSet) . ' changes');
		if ($args->getOption('verbose')) {
			$io->success((string)$changeSet);
		}
	}

	/**
	 * @param string|null $set
	 *
	 * @return array<string>
	 */
	private function levels(?string $set): array {
		if (!$set) {
			return $this->levels;
		}

		foreach ($this->levels as $level) {
			if ($set === $level) {
				return [$set];
			}
		}

		throw new InvalidArgumentException('No such set/level found: ' . $set);
	}

	/**
	 * @param string $level
	 * @param array<string, mixed> $config
	 *
	 * @return \Cake\Upgrade\Processor\Processor
	 */
	protected function taskProcessor(string $level, array $config): Processor {
		$tasks = $this->tasks();

		return new Processor($tasks, $config);
	}

	/**
	 * @param \Cake\Console\ConsoleOptionParser $parser
	 * @param \Cake\Console\Arguments $args
	 * @param \Cake\Console\ConsoleIo $io
	 *
	 * @return void
	 */
	protected function displayHelp(ConsoleOptionParser $parser, Arguments $args, ConsoleIo $io): void {
		parent::displayHelp($parser, $args, $io);

		if (!$args->getOption('verbose')) {
			return;
		}

		$availableTasks = $this->availableTasks();
		$tasks = $this->tasks();

		$io->out(count($availableTasks) . ' available native tasks:');

		foreach ($availableTasks as $task) {
			$message = ' - ' . $task;
			if (in_array($task, $tasks, true)) {
				$io->success($message . ' (active)');
			} else {
				$io->warning($message);
			}
		}
	}

	/**
	 * @return array<string>
	 */
	protected function tasks(): array {
		//TODO: make more flexible
		if (Configure::read('Upgrade.tasks')) {
			return Configure::read('Upgrade.tasks');
		}

		$tasks = [
			ComposerTask::class,
			ComposerPsr2rTask::class,
			ReadmeTask::class,
			CiTask::class,
			BasicsTask::class,
			DatabaseTypeDriverTask::class,
			ModelValidatorTask::class,
			ModelHookTask::class,
			LoadModelTask::class,
			TypeFactoryTask::class,
			ShellToCommandTask::class,
			TypedClosureTask::class,
			TypedPropertyTask::class,
			TypedPropertyPluginTask::class,
			TypedPropertyEntityTask::class,
			TypedPropertyFixtureTask::class,
			TypedPropertyTestCaseTask::class,
			TestsBootstrapFixtureTask::class,
			TestsFixtureSchemaTask::class,
			TestsControllerInstantiationTask::class,
			TestsCommandTask::class,
			PhpunitXmlTask::class,
			//PhpcsPsr2rTask::class,
			RemoveOutdatedCodeTask::class,
			TemplatesFormHelperTask::class,
		];

		return $tasks;
	}

	/**
	 * @return array<string>
	 */
	protected function availableTasks(): array {
		$tasks = [];

		/**
		 * @var \DirectoryIterator<\DirectoryIterator> $iterator
		 */
		$iterator = new DirectoryIterator(ROOT . DS . 'src' . DS . 'Task' . DS . 'Cake50');
		foreach ($iterator as $file) {
			if (!preg_match('/(\w+)Task.php$/', (string)$file, $matches)) {
				continue;
			}

			$name = $matches[1];
			$class = 'Cake\\Upgrade\\Task\\Cake50\\' . $name . 'Task';

			$tasks[] = $class;
		}

		return $tasks;
	}

}
