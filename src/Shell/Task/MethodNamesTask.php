<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link http://cakephp.org CakePHP(tm) Project
 * @since 3.0.0
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Upgrade\Shell\Task;

/**
 * Update method names task.
 *
 * Handles updating method names that have been changed.
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 */
class MethodNamesTask extends BaseTask {

	use ChangeTrait;

	/**
	 * @var array
	 */
	public $tasks = ['Stage'];

	/**
	 * Processes a path.
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _process($path) {
		$otherPatterns = [
			[
				'slug usage',
				'#\bInflector::slug\(#',
				'\Cake\Utility\Text::slug(',
			],
		];

		$patterns = [];
		/*
		if (
			strpos($path, DS . 'Controller' . DS) !== false
		) {
			$patterns = $controllerPatterns;
		} elseif (
			strpos($path, DS . 'Template' . DS) !== false ||
			strpos($path, DS . 'View' . DS) !== false
		) {
			$patterns = $helperPatterns;
		} elseif (strpos($path, DS . 'Command' . DS . 'Task' . DS)) {
			$patterns = $taskPatterns;
		}
		*/
		$patterns = array_merge($patterns, $otherPatterns);

		$original = $contents = $this->Stage->source($path);
		$contents = $this->_updateContents($contents, $patterns);

		return $this->Stage->change($path, $original, $contents);
	}

	/**
	 * _shouldProcess
	 *
	 * Bail for invalid files (php/ctp files only)
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _shouldProcess($path) {
		$ending = substr($path, -4);

		return $ending === '.php';
	}

}
