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
class NamespacesTask extends Shell {

	use ChangeTrait;

	public $tasks = ['Stage'];

/**
 * Adds the namespace to a given file.
 *
 * @param string $filePath The file to add a namespace to.
 * @param string $ns The base namespace to use.
 * @param boolean $dry Whether or not to operate in dry-run mode.
 * @return boolean
 */
	protected function _process($path) {
		$namespace = $this->_getNamespace($path);
		$original = $contents = $this->Stage->source($path);

		$patterns = [
			[
				'Namespace to ' . $namespace,
				'#^(<\?(?:php)?\s+(?:\/\*.*?\*\/\s{0,1})?)#s',
				"\\1namespace " . $namespace . ";\n",
			]
		];
		$contents = $this->_updateContents($contents, $patterns);

		return $this->Stage->change($path, $original, $contents);
	}

/**
 * _getNamespace
 *
 * Derive the root namespace from the path. Use the application root as a basis, and strip
 * Off anything before Plugin directory - the plugin directory is a root of sorts.
 *
 * @param string $path
 * @return string
 */
	protected function _getNamespace($path) {
		$ns = $this->params['namespace'];
		$path = str_replace(ROOT, '', dirname($path));
		$path = preg_replace('@.*(Plugin|plugins)[/\\\\]@', '', $path);

		return $ns . trim(str_replace(DS, '\\', $path), '\\');
	}

/**
 * _shouldProcess
 *
 * If it already has a namespace - bail, otherwise use the default (php files only)
 *
 * @param string $path
 * @return boolean
 */
	protected function _shouldProcess($path) {
		$contents = $this->Stage->source($path);
		if (preg_match('/namespace\s+[a-z0-9\\\\]+;/i', $contents)) {
			return false;
		}

		return (substr($path, -4) === '.php');
	}
}
