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

use Cake\Upgrade\Shell\Task\LocationsTask;

/**
 * Move files around as directories have changed in 3.0
 */
class CleanupTask extends LocationsTask {

	use ChangeTrait;

	public $tasks = ['Stage'];

/**
 * Cleans out the empty folders after location has been changed for class files.
 *
 * @param mixed $path
 * @return bool
 */
	protected function _process($path) {
		$path = dirname($path);

		foreach (array_keys($this->_moves()) as $substr) {
			$this->_deleteIfEmpty($path, $substr);
		}
		return true;
	}

	protected function _deleteIfEmpty($path, $substr) {
		$dir = $path . DS . $substr;

		if (!is_dir($dir)) {
			if (($pos = strrpos($substr, DS)) !== false) {
				$substr = substr($substr, 0, $pos);
				$this->_deleteIfEmpty($path, $substr);
			}
			return;
		}

		$this->out('Path: ' . $dir);
		$iterator = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
		$isDirEmpty = !$iterator->valid();
		if (!$isDirEmpty) {
			return;
		}
		$this->out('Empty dir: ' . $dir, 1, static::VERBOSE);
		if (empty($this->params['dry-run'])) {
			rmdir($dir);
			clearstatcache();
		}
		if (($pos = strrpos($substr, DS)) !== false) {
			$substr = substr($substr, 0, $pos);
			$this->_deleteIfEmpty($path, $substr);
		}
	}

/**
 * _shouldProcess
 *
 * Is the current path within the scope of this task?
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

		if (basename($path) === 'composer.json' && empty($this->params['plugin'])) {
			return true;
		}
		return false;
	}

}
