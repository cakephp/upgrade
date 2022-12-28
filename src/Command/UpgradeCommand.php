<?php

namespace Cake\Upgrade\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\Exception\StopException;

class UpgradeCommand extends Command {

	/**
	 * The name of this command.
	 *
	 * @var string
	 */
	protected $name = 'upgrade';

	/**
	 * Any levels always include previous ones.
	 *
	 * @var array
	 */
	protected $levels = [
		'cakephp38',
		'cakephp40',
	];

	/**
	 * E.g.:
	 * bin/cake upgrade /path/to/app --level=cakephp40
	 *
	 * @param \Cake\Console\Arguments $args The command arguments.
	 * @param \Cake\Console\ConsoleIo $io The console io
	 *
	 * @throws \Cake\Console\Exception\StopException
	 * @return int|null The exit code or null for success
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
				'name' => 'Path to app',
				'required' => true,
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
		//TODO delegate to stack of tasks
	}

}
