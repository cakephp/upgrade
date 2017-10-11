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

/**
 * Update namespaces.
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 */
class NamespacesTask extends BaseTask {

	use ChangeTrait;
	use HelperTrait;

	/**
	 * @var array
	 */
	public $tasks = ['Stage'];

	/**
	 * Adds the namespace to a given file.
	 *
	 * @param string $path The file to add a namespace to.
	 * @return bool
	 */
	protected function _process($path) {
		$namespace = $this->_getNamespace($path);
		if (!$namespace) {
			return false;
		}

		$original = $contents = $this->Stage->source($path);

		$patterns = [
			[
				'Namespace to ' . $namespace,
				'#^(<\?(?:php)?\s+(?:\/\*.*?\*\/\s{0,1})?)#s',
				'\\1namespace ' . $namespace . ";\n\n",
			],
		];
		$contents = $this->_updateContents($contents, $patterns);

		return $this->Stage->change($path, $original, $contents);
	}

	/**
	 * _getNamespace
	 *
	 * Derives the root namespace from the path. Use the application root as a basis, and strip
	 * off anything before Plugin directory - the plugin directory is a root of sorts.
	 *
	 * @param string $path
	 * @return string
	 */
	protected function _getNamespace($path) {
		$ns = $this->param('namespace');
		if (!$ns) {
			$ns = 'App';
		}

		$path = str_replace(realpath($this->args[0]), '', dirname($path));
		$path = preg_replace('@.*(Plugin|plugins)[/\\\\]@', '', $path);
		$path = preg_replace('@[/\\\\]src@', '', $path);
		$path = preg_replace('@tests[/\\\\]@', 'Test' . DS, $path);

		return trim(implode('\\', [$ns, str_replace(DS, '\\', $path)]), '\\');
	}

	/**
	 * _shouldProcess
	 *
	 * If it already has a namespace - bail, otherwise use the default (php files only)
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _shouldProcess($path) {
		$relativeFromRoot = $this->_getRelativePath($path);

		if (strpos($relativeFromRoot, DS . 'Plugin' . DS) || strpos($relativeFromRoot, DS . 'plugins' . DS)) {
			return false;
		}
		if (strpos($relativeFromRoot, DS . 'Vendor' . DS) || strpos($relativeFromRoot, DS . 'vendors' . DS)) {
			return false;
		}

		// Skip bootstrap files and alike
		$filename = basename($path);
		$excludes = [
			'bootstrap.php',
			'routes.php',
			'core.php',
			'configs.php',
			'index.php',
		];
		if (in_array($filename, $excludes, true)) {
			return false;
		}

		$contents = $this->Stage->source($path);
		if (preg_match('/namespace\s+[a-z0-9\\\\]+;/i', $contents)) {
			return false;
		}

		return substr($path, -4) === '.php';
	}

}
