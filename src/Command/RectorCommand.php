<?php
namespace Cake\Upgrade\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\Exception\StopException;

class RectorCommand extends Command {

	/**
	 * @var string|false
	 */
	public $modelClass = false;

	/**
	 * The name of this command.
	 *
	 * @var string
	 */
	protected $name = 'rector';

	/**
	 * Any levels can always include previous ones.
	 *
	 * @var array
	 */
	protected $levels = [
		'3.4' => 'cakephp34',
		'3.5' => 'cakephp35',
		'3.6' => 'cakephp36',
		'3.7' => 'cakephp37',
		'3.8' => 'cakephp38',
		//'3.9' => 'cakephp39',
		'4.0' => 'cakephp40',
	];

	/**
	 * E.g.:
	 * bin/cake cs /path/to/app --level=type-order
	 *
	 * @param \Cake\Console\Arguments $args The command arguments.
	 * @param \Cake\Console\ConsoleIo $io The console io
	 *
	 * @return int|null The exit code or null for success
	 * @throws \Cake\Console\Exception\StopException
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
			$io->error('Path to app or plugin not found: ' . $args->getArgumentAt(0));
			throw new StopException();
		}

		$this->process($path, $args, $io);
	}

	/**
	 * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
	 *
	 * @return \Cake\Console\ConsoleOptionParser The built parser.
	 */
	protected function buildOptionParser(ConsoleOptionParser $parser) {
		$parser = parent::buildOptionParser($parser)
			->setDescription('A wrapper around rector to help upgrading between 3.x and 4.x. ' .
				'Be sure to have a backup of your application before running these commands.'
			)->addArgument('path', [
				'help' => 'Path to project or plugin.',
				'required' => true,
			])->addOption('level', [
				'help' => 'Level to use.',
				'required' => true,
			])->addOption('fix', [
				'help' => 'Fix fixable issues.',
				'short' => 'f',
				'boolean' => true,
			])->addOption('exact', [
				'help' => 'Dont include previous ones.',
				'short' => 'e',
				'boolean' => true,
			])->addOption('autoload-file', [
				'help' => 'Autoload file to use. Only needed if no composer.json can be found in that path.',
				'short' => 'a',
			]);

		return $parser;
	}

	/**
	 * @param string $path
	 * @param \Cake\Console\Arguments $args
	 * @param \Cake\Console\ConsoleIo $io
	 * @return void
	 * @throws \Cake\Console\Exception\StopException
	 */
	protected function process(string $path, Arguments $args, ConsoleIo $io): void {

		$command = 'vendor/bin/rector process';
		if (!$args->getOption('fix')) {
			$command .= ' --dry-run';
		}

		$level = $args->getOption('level');
		if (!$level) {
			$io->error('No level provided.');
			throw new StopException();
		}

		if (!$args->getOption('exact')) {
			//TODO: add previous levels automatically?
		}

		$autoloadFile = $args->getOption('autoload-file');
		if (!$autoloadFile) {
			$autoloadFile = $this->guessAutoloadFile($path);
		}

		$command .= ' --set=' . $level;
		if ($autoloadFile) {
			$command .= ' --autoload-file=' . $autoloadFile;
		}

		$command .= ' ' . $path;

		$io->out('Running `' . $command . '`...');

		exec($command, $output, $returnVar);
		$io->out($output);
		if ($returnVar === 0) {
			$io->success('All good.');
		} else {
			$io->err('Return code: ' . $returnVar);
		}
	}

	/**
	 * @param string $level
	 *
	 * @return array
	 */
	protected function standard(string $level): array {
		if (!empty($this->levels[$level])) {
			$level = $this->levels[$level];
		}

		return [
			//TODO
		];
	}

	/**
	 * Guess autoload file based on composer vendor dir.
	 *
	 * @param string $path
	 *
	 * @return string|null
	 */
	protected function guessAutoloadFile(string $path): ?string {
		if (!file_exists($path . 'composer.json')) {
			return null;
		}

		$autoloadPath = $path . 'vendor' . DS . 'autoload.php';

		return $autoloadPath;
	}

}
