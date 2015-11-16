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
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Upgrade\Shell\Task;

/**
 * Move files around as directories have changed in 3.0
 */
class LocationsTask extends BaseTask {

	use ChangeTrait;

	public $tasks = ['Stage'];

	/**
	 * Check all moves, and stage moving the file to new location.
	 *
	 * @param mixed $path
	 * @return bool
	 */
	protected function _process($path) {
		$new = $path;
		foreach ($this->_moves() as $from => $to) {
			$from = $this->_relativeFromRoot($from, $new);
			if (!$this->_isInRoot($to)) {
				$to = 'src' . DS . $to;
			}
			if ($from === 'Lib') {
				$pieces = explode(DS . $from . DS, $new);
				$ending = array_pop($pieces);
				if (strpos($ending, DS) === false) {
					$to .= DS . 'Lib';
				}
			}

			$new = str_replace(DS . $from . DS, DS . $to . DS, $new);
		}

		if ($new === $path) {
			return false;
		}

		return $this->Stage->move($path, $new);
	}

	/**
	 * _shouldProcess
	 *
	 * Is the current path within the scope of any move?
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _shouldProcess($path) {
		$root = !empty($this->params['root']) ? $this->params['root'] : $this->args[0];
		$root = rtrim($root, DS);
		$relativeFromRoot = str_replace($root, '', $path);

		if (strpos($relativeFromRoot, DS . 'Plugin' . DS) || strpos($relativeFromRoot, DS . 'plugins' . DS)) {
			return false;
		}
		if (strpos($relativeFromRoot, DS . 'Vendor' . DS) || strpos($relativeFromRoot, DS . 'vendors' . DS)) {
			return false;
		}

		foreach (array_keys($this->_moves()) as $substr) {
			if (strpos($relativeFromRoot, DS . $substr . DS) !== false) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Key value map of from and to
	 *
	 * @return array
	 */
	protected function _moves() {
		return [
			'Config' => 'config',
			'Console' => 'bin',
			'Console' . DS . 'Command' => 'Shell',
			'Console' . DS . 'Command' . DS . 'Task' => 'Shell' . DS . 'Task',
			'Controller' . DS . 'Component' . DS . 'Auth' => 'Auth',
			'Lib' => 'src',
			'Test' . DS . 'Case' => 'tests' . DS . 'TestCase',
			'View' . DS . 'Elements' => 'Template' . DS . 'Element',
			'View' . DS . 'Emails' => 'Template' . DS . 'Email',
			'View' . DS . 'Layouts' => 'Template' . DS . 'Layout',
			'Template' . DS . 'Layout' . DS . 'Emails' => 'Template' . DS . 'Layout' . DS . 'Email',
			'View' . DS . 'Scaffolds' => 'Template' . DS . 'Scaffold',
			'View' . DS . 'Errors' => 'Template' . DS . 'Error',
			'View' . DS . 'Themed' => 'Template' . DS . 'Themed',

			'Auth' => 'Auth',
			'Controller' => 'Controller',
			'Model' => 'Model',
			'Template' => 'Template',
			'View' . DS . 'Helper' => 'View' . DS . 'Helper',
			'View' => 'Template',
			'Test' => 'tests',
		];
	}

	/**
	 * Get the relative path from ROOT for a specific folder.
	 *
	 * @param string $folder
	 * @param string $path
	 * @return string $path
	 */
	protected function _relativeFromRoot($folder, $path) {
		$root = !empty($this->params['root']) ? $this->params['root'] : $this->args[0];

		$split = explode(DS . $folder . DS, $path);
		if (empty($split[0]) || strpos($split[0], $root) !== 0) {
			return $folder;
		}

		$relativePath = substr($split[0], strlen($root));
		if (!$relativePath) {
			return $folder;
		}

		return $relativePath . DS . $folder;
	}

	/**
	 * Detect if a target folder should be in ROOT.
	 *
	 * @param string $folder
	 * @return bool Success
	 */
	protected function _isInRoot($folder) {
		$rootFolders = [
			'config',
			'bin',
			'tests',
			'src',
		];
		$pieces = explode(DS, $folder);
		$firstFolder = !empty($pieces[0]) ? $pieces[0] : $folder;
		return in_array($firstFolder, $rootFolders, true);
	}

	/**
	 * Get the option parser for this shell.
	 *
	 * @return \Cake\Console\ConsoleOptionParser
	 */
	public function getOptionParser() {
		return parent::getOptionParser()
			->addOptions([
				'root' => [
					'default' => '',
					'help' => 'Set an application\'s root path. Not defining it makes the current path the root one.',
				],
			]);
	}

}
