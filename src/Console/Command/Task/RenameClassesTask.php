<?php
/**
 * Upgrade stage task
 *
 * Handles staging changes for the upgrade process
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 3.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Upgrade\Console\Command\Task\Upgrade;

use Cake\Console\Shell;

/**
 * Base class for Bake Tasks.
 *
 */
class RenameClassesTask extends Shell {

	use ChangeTrait;

/**
 * Rename the classes in a given file.
 *
 * @param string $path The path to operate on.
 * @return bool
 */
	protected function _process($path) {
		$replacements = [
			'Cake\Network\Http\HttpSocket' => 'Cake\Network\Http\Client',
			'HttpSocket' => 'Client',
			'Cake\Model\ConnectionManager' => 'Cake\Database\ConnectionManager',
		];

		$original = $contents = $this->Stage->source($path);

		$contents = str_replace(
			array_keys($replacements),
			array_values($replacements),
			$contents
		);

		return $this->Stage->change($path, $original, $contents);
	}

}
