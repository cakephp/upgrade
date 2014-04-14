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
namespace Cake\Upgrade\Console\Command\Task;

use Cake\Console\Shell;

/**
 * Upgrade stage task
 *
 * Handles staging changes for the upgrade process
 */
class RenameCollectionsTask extends Shell {

	use ChangeTrait;

	public $tasks = ['Stage'];

/**
 * RenameCollectionsTask::_process()
 *
 * @param string $path
 * @return void
 */
	protected function _process($path) {
		$patterns = [
			[
				'Replace $this->_Collection with $this->_registry',
				'#\$this->_Collection#',
				'$this->_registry',
			],
			[
				'Replace ComponentCollection arguments',
				'#ComponentCollection#',
				'ComponentRegistry',
			],
			[
				'Rename ComponentCollection',
				'#ComponentCollection#',
				"ComponentRegistry",
			],
			[
				'Rename HelperCollection',
				'#HelperCollection#',
				"HelperRegistry",
			],
			[
				'Rename TaskCollection',
				'#TaskCollection#',
				"TaskRegistry",
			],
		];

		$original = $contents = $this->Stage->source($path);
		$contents = $this->_updateContents($contents, $patterns);

		return $this->Stage->change($path, $original, $contents);
	}

}
