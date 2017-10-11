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
namespace Cake\Upgrade\Shell\Task;

use Cake\Console\Shell;
use Cake\Error\Debugger;

/**
 * Provides helper functionality for the tasks
 *
 * @mixin \Cake\Console\Shell
 */
trait HelperTrait {

	/**
	 * @return string
	 */
	protected function _getRoot() {
		$root = !empty($this->params['root']) ? $this->params['root'] : $this->args[0];
		$root = str_replace(['/', '\\'], DS, $root);
		$root = rtrim($root, DS);

		return $root;
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	protected function _getRelativePath($path) {
		return str_replace($this->_getRoot(), '', $path);
	}

}
