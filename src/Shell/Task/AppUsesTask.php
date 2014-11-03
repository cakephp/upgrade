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

use Cake\Console\Shell;
use Cake\Upgrade\Shell\Task\BaseTask;

/**
 * App Uses Task.
 *
 * Updates App::uses() calls.
 */
class AppUsesTask extends BaseTask {

	use ChangeTrait;

	public $tasks = ['Stage'];

/**
 * implicitMap
 *
 * A map of old => new for use statements that are missing
 *
 * @var array
 */
	public $implicitMap = [
		'App' => 'Cake\Core\App',
		'AppController' => 'App\Controller\AppController',
		'AppHelper' => 'App\View\Helper\AppHelper',
		'AppModel' => 'App\Model\AppModel',
		'Cache' => 'Cake\Cache\Cache',
		'CakeEventListener' => 'Cake\Event\EventListener',
		'CakeLog' => 'Cake\Log\Log',
		'CakePlugin' => 'Cake\Core\Plugin',
		'CakeTestCase' => 'Cake\TestSuite\TestCase',
		'CakeTestFixture' => 'Cake\TestSuite\Fixture\TestFixture',
		'Component' => 'Cake\Controller\Component',
		'ComponentRegistry' => 'Cake\Controller\ComponentRegistry',
		'Configure' => 'Cake\Core\Configure',
		'ConnectionManager' => 'Cake\Database\ConnectionManager',
		'Controller' => 'Cake\Controller\Controller',
		'Debugger' => 'Cake\Error\Debugger',
		'ExceptionRenderer' => 'Cake\Error\ExceptionRenderer',
		'Helper' => 'Cake\View\Helper',
		'HelperRegistry' => 'Cake\View\HelperRegistry',
		'Inflector' => 'Cake\Utility\Inflector',
		'Model' => 'Cake\Model\Model',
		'ModelBehavior' => 'Cake\Model\Behavior',
		'Object' => 'Cake\Core\Object',
		'Router' => 'Cake\Routing\Router',
		'Shell' => 'Cake\Console\Shell',
		'View' => 'Cake\View\View',
	];

/**
 * Rename classes
 *
 * A list of classes which have had the Cake prefix removed
 *
 * @var mixed
 */
	public $rename = [
		'CakePlugin',
		'CakeEvent',
		'CakeEventListener',
		'CakeEventManager',
		'CakeValidationRule',
		'CakeSocket',
		'CakeRoute',
		'CakeRequest',
		'CakeResponse',
		'CakeSession',
		'CakeLog',
		'CakeNumber',
		'CakeTime',
		'CakeEmail',
		'CakeLogInterface',
		'CakeSessionHandlerInterface',
	];

/**
 * Convert App::uses() to normal use statements.
 * Order App::uses statements
 * andreplace the class in the source if it appears
 *
 * @return void
 */
	protected function _process($path) {
		$original = $contents = $this->Stage->source($path);

		$contents = $this->_replaceAppUses($contents);
		$contents = $this->_removeDynamicAppUses($contents);
		$contents = $this->_addImplicitUses($contents);
		$contents = $this->_orderUses($contents);
		$contents = $this->_replaceReferences($contents);

		return $this->Stage->change($path, $original, $contents);
	}

/**
 * Replace App::uses with use <Classname>;
 *
 * @param string $contents
 * @return string
 */
	protected function _replaceAppUses($contents) {
		$pattern = '#App::uses\(\s*[\'"]([a-z0-9_]+)[\'"]\s*,\s*[\'"]([a-z0-9/_]+)(?:\.([a-z0-9/_]+))?[\'"]\)#i';

		$replacement = function ($matches) {
			$matches = $this->_mapClassName($matches);
			// Chop Lib out as locations moves those files to the top level.
			if (isset($matches[3]) && $matches[3] === 'Lib') {
				$use = $matches[2] . '\\' . $matches[1];
			} elseif (count($matches) === 4) {
				$use = $matches[2] . '\\' . $matches[3] . '\\' . $matches[1];
			} elseif ($matches[2] === 'Vendor') {
				$this->out(
					sprintf('<info>Skip %s as it is a vendor library.</info>', $matches[1]),
					1,
					Shell::VERBOSE
				);
				return $matches[0];
			} else {
				$use = 'Cake\\' . $matches[2] . '\\' . $matches[1];
				if (!class_exists($use) && !interface_exists($use)) {
					$use = 'App\\' . substr($use, 5);
				}
			}

			$use = str_replace('/', '\\', $use);
			return 'use ' . $use;
		};

		return preg_replace_callback($pattern, $replacement, $contents, -1, $count);
	}

/**
 * _removeDynamicAppUses
 *
 * @param string $contents
 * @return string
 */
	protected function _removeDynamicAppUses($contents) {
		$pattern = '#(App::uses\(.+\);?)#';
		return preg_replace($pattern, '/* TODO: \1 */', $contents);
	}

/**
 * Add implicit uses
 *
 * Account for:
 *
 * + parent classes and interfaces are frequently just assumed to exist in useland code
 * + also in function arguments
 * + static class calls for basic Cake classes
 *
 * @param string $contents
 * @return string
 */
	protected function _addImplicitUses($contents) {
		preg_match(
			'/class\s+\S+(\s+extends\s+(\S+))?(\s+implements\s+(\S+))?/',
			$contents,
			$matches
		);

		$toCheck = [];
		if (isset($matches[2])) {
			$toCheck[] = $matches[2];
		}
		if (isset($matches[4])) {
			$toCheck[] = $matches[4];
		}

		preg_match_all(
			'/function.*\(.*\b(\S+)\b\s+\$/',
			$contents,
			$matches
		);

		$toCheck = array_filter(array_unique(array_merge($toCheck, $matches[1])));

		preg_match_all(
			'/\b([A-Z][a-zA-Z0-9]+)::/',
			$contents,
			$matches
		);

		$toCheck = array_filter(array_unique(array_merge($toCheck, $matches[1])));

		preg_match_all('/use .+;[\n]/', $contents, $useMatches);
		$useStatements = $useMatches[0];

		foreach ($toCheck as $check) {
			if (preg_match("/use .+\b$check;/", $contents)) {
				continue;
			}

			if (!isset($this->implicitMap[$check])) {
				$this->out(sprintf('<warning>%s is not in the implicit class map</warning>', $check));
				continue;
			}

			$class = $this->implicitMap[$check];
			$useStatement = "use $class;\n";

			$contents = preg_replace(
				'/(namespace [\S+]+;[\n]{1,})/',
				'\1' . $useStatement,
				$contents
			);
		}

		return $contents;
	}

/**
 * Order use statements
 *
 * For code standards, use statements should be alphabetical but in addition, this
 * Moves all use staements to the top of the class
 *
 * @param string $contents
 * @return string
 */
	protected function _orderUses($contents) {
		preg_match_all('/use .+;[\n]/', $contents, $matches);
		sort($matches[0]);
		$matches[0] = array_unique($matches[0]);
		$contents = str_replace($matches[0], '', $contents);

		return preg_replace(
			'/(namespace [\S+]+;[\n]{2})/',
			'\1' . implode($matches[0], ''),
			$contents
		);
	}

/**
 * Replace references to old classes
 *
 * @param string $contents
 * @return string
 */
	protected function _replaceReferences($contents) {
		$rename = $this->rename;
		foreach ($rename as &$val) {
			$val = substr($val, 4);
		}
		$regex = '/\bCake(' . implode($rename, '|') . ')\b/';
		return preg_replace($regex, '\1', $contents);
	}

/**
 * Convert old classnames to new ones.
 * Strips the Cake prefix off of classes that no longer have it.
 *
 * @param array $matches
 * @return array
 */
	protected function _mapClassName($matches) {
		if (empty($matches[3])) {
			unset($matches[3]);
		}
		if (in_array($matches[1], $this->rename)) {
			$matches[1] = substr($matches[1], 4);
		}
		return $matches;
	}

/**
 * _shouldProcess
 *
 * If App::uses is nowhere - bail, otherwise use the default (php files only)
 *
 * @param string $path
 * @return bool
 */
	protected function _shouldProcess($path) {
		$contents = $this->Stage->source($path);
		if (!strpos($contents, 'App::uses')) {
			return false;
		}

		return (substr($path, -4) === '.php');
	}

}
