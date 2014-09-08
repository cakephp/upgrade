<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 3.0.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Cake\Upgrade\Console\Command\Task;

use Cake\Console\Shell;
use Cake\Error\Debugger;

/**
 * Provides features for modifying the contents of a file
 *
 */
trait ChangeTrait {

/**
 * Make tasks callable
 *
 * @return void
 */
	public function main() {
		if (!empty($this->params['dry-run'])) {
			$this->out('<warning>Dry-run mode enabled!</warning>', 1, Shell::QUIET);
		}

		$exclude = ['.git', '.svn', 'vendor', 'Vendor', 'webroot', 'tmp'];
		$files = $this->Stage->files($exclude);

		foreach ($files as $file) {
			$this->out(sprintf('<info>Processing %s</info>', Debugger::trimPath($file)));
			$this->process($file);
		}

		$this->Stage->commit();
	}

/**
 * process
 *
 * @param string $path
 * @return void
 */
	public function process($path) {
		if (!$this->_shouldProcess($path)) {
			$this->out('<info>skipping</info>', 1, Shell::VERBOSE);
			return false;
		}

		$return = $this->_process($path);
		if ($return) {
			$this->out('<warning>updated</warning>', 1, Shell::VERBOSE);
		} else {
			$this->out('<info>no change</info>', 1, Shell::VERBOSE);
		}

		return $return;
	}

/**
 * Default noop
 *
 * @param string $path
 * @return void
 */
	protected function _process($path) {
	}

/**
 * _shouldProcess
 *
 * Default to php files only
 *
 * @param string $path
 * @return boolean
 */
	protected function _shouldProcess($path) {
		return (substr($path, -4) === '.php');
	}

/**
 * Update the contents of a file using an array of find and replace patterns
 *
 * @param string $contents The file contents to update
 * @param array $patterns The replacement patterns to run.
 * @return string
 */
	protected function _updateContents($contents, $patterns) {
		foreach ($patterns as $pattern) {
			$contents = preg_replace($pattern[1], $pattern[2], $contents);
		}
		return $contents;
	}
}
