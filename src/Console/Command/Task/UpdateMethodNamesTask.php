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
 * Update method names task.
 *
 * Handles updating method names that have been changed.
 *
 */
class UpdateMethodNamesTask extends Shell {

	use ChangeTrait;

	public $tasks = ['Stage'];

/**
 * Processes a path.
 *
 * @param string $path
 * @return void
 */
	protected function _process($path) {
		$patterns = [
			[
				'Replace $this->Paginator->url() with $this->Paginator->generateUrl',
				'#\$this->Paginator->url#',
				'$this->Paginator->generateUrl',
			],
			[
				'Replace $this->Cookie->type() with $this->Cookie->encryption()',
				'#\$this->Cookie->type#',
				'$this->Cookie->encryption',
			],
		];

		$original = $contents = $this->Stage->source($path);
		$contents = $this->_updateContents($contents, $patterns);

		return $this->Stage->change($path, $original, $contents);
	}

}
