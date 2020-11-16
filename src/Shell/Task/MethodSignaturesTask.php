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

use Cake\Upgrade\Snippets\ComponentSnippets;
use Cake\Upgrade\Snippets\ControllerSnippets;
use Cake\Upgrade\Snippets\FormSnippets;
use Cake\Upgrade\Snippets\GenericSnippets;
use Cake\Upgrade\Snippets\ShellSnippets;
use Cake\Upgrade\Snippets\ShellTaskSnippets;
use Cake\Upgrade\Snippets\TableSnippets;
use Cake\Upgrade\Snippets\TestsSnippets;

/**
 * Update method signatures task for CakePHP 4.
 *
 * Handles updating method signatures that have been changed.
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 */
class MethodSignaturesTask extends BaseTask {

	use ChangeTrait;

	/**
	 * @var array
	 */
	public $tasks = ['Stage'];

	/**
	 * @var string[]
	 */
	protected $snippets = [
		ShellTaskSnippets::class => ShellTaskSnippets::class,
		ShellSnippets::class => ShellSnippets::class,
	];

	/**
	 * Processes a path.
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _process($path) {
		$patterns = [];

		if (strpos($path, 'src' . DS . 'Controller' . DS . 'Component' . DS) !== false) {
			$patterns = array_merge((new ComponentSnippets())->snippets(), $patterns);
		} elseif (strpos($path, 'src' . DS . 'Controller' . DS) !== false) {
			$patterns = array_merge((new ControllerSnippets())->snippets(), $patterns);
		} elseif (strpos($path, 'src' . DS . 'Model' . DS) !== false) {
			$patterns = array_merge((new TableSnippets())->snippets(), $patterns);
		} elseif (strpos($path, 'src' . DS . 'Form' . DS) !== false) {
			$patterns = array_merge((new FormSnippets())->snippets(), $patterns);
		} elseif (strpos($path, 'tests' . DS . 'TestCase' . DS) !== false) {
			$patterns = array_merge((new TestsSnippets())->snippets(), $patterns);
		}

		$patterns = array_merge((new GenericSnippets())->snippets(), $patterns);

		$snippets = [];
		foreach ($this->snippets as $snippetClass) {
			$class = new $snippetClass();
			$snippets += $class->snippets($path);
		}

		foreach ($snippets as $name => $snippet) {
			array_unshift($snippet, $name);
			$snippets[$name] = $snippet;
		}

		$patterns = array_merge($patterns, $snippets);

		$original = $contents = $this->Stage->source($path);
		$contents = $this->_updateContents($contents, $patterns);

		return $this->Stage->change($path, $original, $contents);
	}

	/**
	 * _shouldProcess
	 *
	 * Default to PHP files only
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _shouldProcess($path) {
		return substr($path, -4) === '.php';
	}

}
