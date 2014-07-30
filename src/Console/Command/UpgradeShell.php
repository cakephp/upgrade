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

/**
 * Tasks loaded.
 *
 * @var array
 */
	public $tasks = array(
		'AppUses',
		'Fixtures',
		'Locations',
		'Namespaces',
		'RenameClasses',
		'RenameCollections',
		'Stage',
		'UpdateMethodNames',
	);

	public function main() {
		if ($this->params['dry-run']) {
			$this->out(__d('cake_console', '<warning>Dry-run mode enabled!</warning>'), 1, Shell::QUIET);
		}

		$exclude = ['.git', '.svn', 'vendor', 'Vendor', 'webroot', 'tmp'];
		$files = $this->Stage->files($exclude);

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
 * Get the option parser
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		return parent::getOptionParser()
			->description(__d('cake_console', "A shell to help automate upgrading from CakePHP 2.x to 3.x. " .
				"Be sure to have a backup of your application before running these commands.")
			)
			->addSubcommand('locations', [
				'help' => __d('cake_console', 'Move files/directories around. Run this *before* adding namespaces with the namespaces command.'),
				'parser' => $this->Locations->getOptionParser(),
			])
			->addSubcommand('namespaces', [
				'help' => __d('cake_console', 'Add namespaces to files based on their file path. Only run this *after* you have moved files.'),
				'parser' => $this->Namespaces->getOptionParser(),
			])
			->addSubcommand('rename_classes', [
				'help' => __d('cake_console', 'Rename classes that have been moved/renamed. Run after replacing App::uses().'),
				'parser' => $this->RenameClasses->getOptionParser(),
			])
			->addSubcommand('rename_collections', [
				'help' => __d('cake_console', "Rename HelperCollection, ComponentCollection, and TaskCollection. Will also rename component constructor arguments and _Collection properties on all objects."),
				'parser' => $this->RenameCollections->getOptionParser(),
			])
			->addSubcommand('app_uses', [
				'help' => __d('cake_console', 'Replace App::uses() with use statements'),
				'parser' => $this->AppUses->getOptionParser(),
			])
			->addSubcommand('update_method_names', [
				'help' => __d('cake_console', 'Update many of the methods that were renamed during 2.x -> 3.0'),
				'parser' => $this->UpdateMethodNames->getOptionParser(),
			])
			->addSubcommand('fixtures', [
				'help' => __d('cake_console', 'Update fixtures to use new index/constraint features. This is necessary before running tests.'),
				'parser' => $this->Fixtures->getOptionParser(),
			]);
	}

}
