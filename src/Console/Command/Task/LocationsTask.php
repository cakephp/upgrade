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
namespace Cake\Upgrade\Console\Command\Task;

use Cake\Console\Shell;

/**
 * Base class for Bake Tasks.
 *
 */
class LocationsTask extends Shell {

	use ChangeTrait;

/**
 * check all moves, and stage moving the file to new location
 *
 * @param mixed $path
 * @return bool
 */
	protected function _process($path) {
		$new = $path;
		foreach ($this->_moves() as $from => $to) {
			$new = str_replace(DS . $from, DS . $to, $new);
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
		foreach (array_keys($this->_moves()) as $substr) {
			if (strpos($path, $substr) !== false) {
				return true;
			}
		}

		return false;
	}

/**
 * key value map of from and to
 *
 * @return array
 */
	protected function _moves() {
		return array(
			'Lib' . DS => '',
			'Test' . DS . 'Case' => 'Test' . DS . 'TestCase',
			'View' . DS . 'Elements' => 'Template' . DS . 'Element',
			'View' . DS . 'Emails' => 'Template' . DS . 'Email',
			'View' . DS . 'Layouts' => 'Template' . DS . 'Layout',
			'Template' . DS . 'Layout' . DS . 'Emails' => 'Template' . DS . 'Layout' . DS . 'Email',
			'View' . DS . 'Scaffolds' => 'Template' . DS . 'Scaffold',
			'View' . DS . 'Errors' => 'Template' . DS . 'Error',
			'View' . DS . 'Themed' => 'Template' . DS . 'Themed',
		);
	}
}
