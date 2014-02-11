<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Upgrade\Console\Command;

use Cake\Console\Shell;
use Cake\Core\App;
use Cake\Core\Plugin;
use Cake\Utility\Debugger;
use Cake\Utility\Folder;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

/**
 * A shell class to help developers upgrade applications to CakePHP 3.0
 *
 */
class UpgradeShell extends Shell {

	public $tasks = array(
		'AppUses',
		'Fixtures',
		'Locations',
		'Namespaces',
		'RenameClasses',
		'RenameCollections',
		'Stage',
	);

/**
 * Files
 *
 * @var array
 */
	protected $_files = [];

/**
 * Paths
 *
 * @var array
 */
	protected $_paths = [];

	public function main() {
		if (!empty($this->params['dryRun'])) {
			$this->out(__d('cake_console', '<warning>Dry-run mode enabled!</warning>'), 1, Shell::QUIET);
		}

		$exclude = ['.git', '.svn', 'vendor', 'Vendor', 'webroot', 'tmp'];
		$files = $this->files($exclude);

		$actions = $this->_getActions();

		foreach ($files as $file) {
			$this->out(__d('cake_console', '<info>Processing %s</info>', Debugger::trimPath($file)));
			foreach ($actions as $action) {
				$this->out(__d('cake_console', '<info> * upgrade step %s</info>', $action), 0, Shell::VERBOSE);
				$this->$action->Stage = $this->Stage;
				$this->$action->process($file);
			}
		}

		$this->Stage->commit();
	}

/**
 * Searches the paths and finds files based on extension.
 *
 * @param array $excludes
 * @param bool $reset
 * @return array
 */
	public function files($excludes = [], $reset = false) {
		if ($reset) {
			$this->_files = [];
		}

		if (!$this->_files) {
			if (!$this->_paths) {
				$this->_paths = [$this->_getPath()];
			}

			foreach ($excludes as &$exclude) {
				$exclude = preg_quote($exclude);
			}
			$excludePattern = '@[\\/](' . implode($excludes, '|') . ')([\\/]|$)@';

			foreach ($this->_paths as $path) {
				if (!is_dir($path)) {
					if (is_file($path)) {
						$this->_files[] = $path;
					}
					continue;
				}
				$Iterator = new \RecursiveIteratorIterator(
					new \RecursiveDirectoryIterator($path)
				);
				foreach ($Iterator as $file) {
					$path = $file->getPathname();
					if (!$file->isFile() || preg_match($excludePattern, $path)) {
						continue;
					}
					$this->_files[] = $path;
				}
			}
		}

		return $this->_files;
	}

/**
 * _getActions
 *
 * If the main function is called, derive which tasks to call, and in what order based on the
 * option parser info
 *
 * @return array
 */
	protected function _getActions() {
		$all = [];
		foreach ($this->OptionParser->subcommands() as $command) {
			$name = $command->name();
			if ($name === 'all') {
				continue;
			}
			$className = ucfirst(Inflector::Camelize($name));
			$all[$name] = $className;
		}
		return $all;
	}

/**
 * Get the path to operate on. Uses either the first argument,
 * or the plugin parameter if its set.
 *
 * @return string
 */
	protected function _getPath() {
		if (count($this->args) === 1) {
			return realpath($this->args[0]);
		}

		return realpath($this->args[1]);
	}

/**
 * get the option parser
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$plugin = [
			'short' => 'p',
			'help' => __d('cake_console', 'The plugin to update. Only the specified plugin will be updated.')
		];
		${'dry-run'} = [
			'short' => 'd',
			'help' => __d('cake_console', 'Dry run the update, no files will actually be modified.'),
			'boolean' => true
		];
		$git = [
			'help' => __d('cake_console', 'Perform git operations. eg. git mv instead of just moving files.'),
			'boolean' => true
		];
		$namespace = [
			'help' => __d('cake_console', 'Set the base namespace you want to use. Defaults to App or the plugin name.'),
			'default' => '',
		];
		$exclude = [
			'help' => __d('cake_console', 'Comma separated list of top level diretories to exclude.'),
			'default' => '',
		];
		$path = [
			'help' => __d('cake_console', 'The path to operate on. Will default to APP or the plugin option.'),
			'required' => false,
		];

		$options = compact(['plugin', 'dry-run', 'git']);

		return parent::getOptionParser()
			->description(__d('cake_console', "A shell to help automate upgrading from CakePHP 2.x to 3.x. \n" .
				"Be sure to have a backup of your application before running these commands."))
			->addArgument('path', [
				'help' => __d('cake_console', 'Path to code to upgrade'),
				'required' => true
			])
			->addOptions($options + compact('namespace', 'exclude'))
			->addSubcommand('locations', [
				'help' => __d('cake_console', 'Move files/directories around. Run this *before* adding namespaces with the namespaces command.'),
			])
			->addSubcommand('namespaces', [
				'help' => __d('cake_console', 'Add namespaces to files based on their file path. Only run this *after* you have moved files.'),
			])
			->addSubcommand('rename_classes', [
				'help' => __d('cake_console', 'Rename classes that have been moved/renamed. Run after replacing App::uses().'),
			])
			->addSubcommand('rename_collections', [
				'help' => __d('cake_console', "Rename HelperCollection, ComponentCollection, and TaskCollection. Will also rename component constructor arguments and _Collection properties on all objects."),
			])
			->addSubcommand('app_uses', [
				'help' => __d('cake_console', 'Replace App::uses() with use statements'),
			])
			->addSubcommand('fixtures', [
				'help' => __d('cake_console', 'Update fixtures to use new index/constraint features. This is necessary before running tests.'),
			]);
	}

}
