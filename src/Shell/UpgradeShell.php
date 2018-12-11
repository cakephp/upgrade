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
namespace Cake\Upgrade\Shell;

use Cake\Console\Shell;
use Cake\Error\Debugger;
use Cake\Utility\Inflector;

/**
 * A shell class to help developers upgrade applications to CakePHP 3.0
 *
 * @property \Cake\Upgrade\Shell\Task\LocationsTask $Locations
 * @property \Cake\Upgrade\Shell\Task\NamespacesTask $Namespaces
 * @property \Cake\Upgrade\Shell\Task\AppUsesTask $AppUses
 * @property \Cake\Upgrade\Shell\Task\RenameClassesTask $RenameClasses
 * @property \Cake\Upgrade\Shell\Task\RenameCollectionsTask $RenameCollections
 * @property \Cake\Upgrade\Shell\Task\FixturesTask $Fixtures
 * @property \Cake\Upgrade\Shell\Task\MethodNamesTask $MethodNames
 * @property \Cake\Upgrade\Shell\Task\MethodSignaturesTask $MethodSignatures
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 * @property \Cake\Upgrade\Shell\Task\I18nTask $I18n
 * @property \Cake\Upgrade\Shell\Task\LocaleTask $Locale
 * @property \Cake\Upgrade\Shell\Task\TestsTask $Tests
 * @property \Cake\Upgrade\Shell\Task\SkeletonTask $Skeleton
 * @property \Cake\Upgrade\Shell\Task\TemplatesTask $Templates
 * @property \Cake\Upgrade\Shell\Task\PrefixedTemplatesTask $PrefixedTemplates
 * @property \Cake\Upgrade\Shell\Task\ModelToTableTask $ModelToTable
 * @property \Cake\Upgrade\Shell\Task\CustomTask $Custom
 * @property \Cake\Upgrade\Shell\Task\UrlTask $Url
 * @property \Cake\Upgrade\Shell\Task\CleanupTask $Cleanup
 * @property \Cake\Upgrade\Shell\Task\FixtureLoadingTask $FixtureLoading
 * @property \Cake\Upgrade\Shell\Task\TableToEntityTask $TableToEntity
 */
class UpgradeShell extends Shell {

	/**
	 * Tasks loaded.
	 *
	 * @var array
	 */
	public $tasks = [
		'Locations',
		'Namespaces',
		'AppUses',
		'RenameClasses',
		'RenameCollections',
		'Fixtures',
		'MethodNames',
		'MethodSignatures',
		'Stage',
		'I18n',
		'Locale',
		'Tests',
		'FixtureLoading',
		'Skeleton',
		'Templates',
		'PrefixedTemplates',
		'ModelToTable',
		'TableToEntity',
		'Custom',
		'Url',
		'Cleanup',
	];

	/**
	 * All command.
	 *
	 * @return void
	 */
	public function all() {
		if (!empty($this->params['dry-run'])) {
			$this->out('<warning>Dry-run mode enabled!</warning>', 1, Shell::QUIET);
		}

		$exclude = ['.git', '.svn', 'vendor', 'Vendor', 'webroot', 'tmp', 'logs'];
		if (empty($this->params['plugin']) && !empty($this->params['namespace']) && $this->params['namespace'] === 'App') {
			$exclude[] = 'plugins';
			$exclude[] = 'Plugin';
		}
		$files = $this->Stage->files($exclude);

		$actions = $this->_getActions();

		foreach ($actions as $action) {
			$this->out(sprintf('<info>*** Upgrade step %s ***</info>', $action));
			if (!empty($this->params['interactive'])) {
				$continue = $this->in('Continue with `' . $action . '`?', ['y', 'n', 'q'], 'y');
				if ($continue === 'q') {
					$this->abort('Aborted. Changes are not commited.');
				}
				if ($continue === 'n') {
					$this->out('Skipping this step.');
					continue;
				}
			}

			foreach ($files as $file) {
				$this->out(sprintf('<info> * Processing %s</info>', Debugger::trimPath($file)), 1, Shell::VERBOSE);
				$this->$action->Stage = $this->Stage;
				$this->$action->process($file);

				if (!empty($this->params['interactive'])) {
					$this->Stage->commit();
					$this->Stage->clear();
				}
			}
		}

		if (empty($this->params['interactive'])) {
			$this->Stage->commit();
		}
	}

	/**
	 * _getActions
	 *
	 * If the all function is called, derive which tasks to call, and in what order based on the
	 * option parser info
	 *
	 * @return array
	 */
	protected function _getActions() {
		$all = [];
		foreach ($this->OptionParser->subcommands() as $command) {
			$name = $command->name();
			if ($name === 'all' || $name === 'skeleton') {
				continue;
			}
			$className = ucfirst(Inflector::camelize($name));
			$all[$name] = $className;
		}

		$result = [];
		foreach ($this->tasks as $prio => $null) {
			$prio = Inflector::underscore($prio);
			if (isset($all[$prio])) {
				$result[$prio] = $all[$prio];
			}
		}

		$result += $all;

		return $result;
	}

	/**
	 * Get the option parser
	 *
	 * @return \Cake\Console\ConsoleOptionParser
	 */
	public function getOptionParser() {
		$parser = parent::getOptionParser()
			->description('A shell to help automate upgrading from CakePHP 2.x to 3.x. ' .
				'Be sure to have a backup of your application before running these commands.'
			)
			->addSubcommand('locations', [
				'help' => 'Move files/directories around. Run this *before* adding namespaces with the namespaces command.',
				'parser' => $this->Locations->getOptionParser(),
			])
			->addSubcommand('namespaces', [
				'help' => 'Add namespaces to files based on their file path. Only run this *after* you have moved files.',
				'parser' => $this->Namespaces->getOptionParser(),
			])
			->addSubcommand('app_uses', [
				'help' => 'Replace App::uses() with use statements',
				'parser' => $this->AppUses->getOptionParser(),
			])
			->addSubcommand('rename_classes', [
				'help' => 'Rename classes that have been moved/renamed. Run after replacing App::uses() with use statements.',
				'parser' => $this->RenameClasses->getOptionParser(),
			])
			->addSubcommand('rename_collections', [
				'help' => 'Rename HelperCollection, ComponentCollection, and TaskCollection. Will also rename component constructor arguments and _Collection properties on all objects.',
				'parser' => $this->RenameCollections->getOptionParser(),
			])
			->addSubcommand('method_names', [
				'help' => 'Update many of the methods that were renamed during 2.x -> 3.0',
				'parser' => $this->MethodNames->getOptionParser(),
			])
			->addSubcommand('method_signatures', [
				'help' => 'Update many of the method signatures that were changed during 2.x -> 3.0',
				'parser' => $this->MethodSignatures->getOptionParser(),
			])
			->addSubcommand('fixtures', [
				'help' => 'Update fixtures to use new index/constraint features. This is necessary before running tests.',
				'parser' => $this->Fixtures->getOptionParser(),
			])
			->addSubcommand('tests', [
				'help' => 'Update test cases regarding fixtures.',
				'parser' => $this->I18n->getOptionParser(),
			])
			->addSubcommand('fixture_loading', [
				'help' => 'Update test cases regarding fixture loading.',
				'parser' => $this->I18n->getOptionParser(),
			])
			->addSubcommand('templates', [
				'help' => 'Update view templates.',
				'parser' => $this->Templates->getOptionParser(),
			])
			->addSubcommand('i18n', [
				'help' => 'Update translation functions regarding placeholders.',
				'parser' => $this->I18n->getOptionParser(),
			])
			->addSubcommand('locale', [
				'help' => 'Update locales.',
				'parser' => $this->Locale->getOptionParser(),
			])
			->addSubcommand('skeleton', [
				'help' => 'Add basic skeleton files and folders from the "app" repository.',
				'parser' => $this->Skeleton->getOptionParser(),
			])
			->addSubcommand('model_to_table', [
				'help' => 'Make models to tables.',
				'parser' => $this->ModelToTable->getOptionParser(),
			])
			->addSubcommand('table_to_entity', [
				'help' => 'Make entities from tables.',
				'parser' => $this->ModelToTable->getOptionParser(),
			])
			->addSubcommand('prefixed_templates', [
				'help' => 'Move view templates for prefixed actions.',
				'parser' => $this->PrefixedTemplates->getOptionParser(),
			])
			->addSubcommand('cleanup', [
				'help' => 'Remove empty namespace folders after location changes. Should be run last.',
				'parser' => $this->Cleanup->getOptionParser(),
			])
			->addSubcommand('url', [
					'help' => 'URL stuff.',
					'parser' => $this->Url->getOptionParser(),
			])
			->addSubcommand('custom', [
				'help' => 'Custom stuff.',
				'parser' => $this->PrefixedTemplates->getOptionParser(),
			]);

		$subcommands = $parser->subcommands();
		$allParser = null;
		foreach ($subcommands as $subcommand) {
			if ($allParser === null) {
				$allParser = $subcommand->parser();
				continue;
			}
			$allParser->merge($subcommand->parser());
		}
		$allParser->addOption('interactive', [
				'short' => 'i',
				'help' => 'Run all commands in an interactive mode. Allows you to selectively apply specific steps.',
				'boolean' => true,
			]);

		return $parser->addSubcommand('all', [
			'help' => 'Run all tasks except for skeleton. That task should only be run manually, and only for apps (not plugins).',
			'parser' => $allParser,
		]);
	}

}
