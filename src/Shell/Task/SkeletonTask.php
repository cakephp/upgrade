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

use Cake\Upgrade\Shell\Task\BaseTask;

/**
 * Create and setup missing files and folders via app repo.
 */
class SkeletonTask extends BaseTask {

	use ChangeTrait;

	public $tasks = ['Stage'];

/**
 * Add files.
 *
 * @param mixed $path
 * @return bool
 */
	protected function _process($path) {
		//TODO
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
		if ($path === APP) {
			return true;
		}
		return false;
	}

}
