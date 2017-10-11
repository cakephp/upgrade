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

use Cake\Utility\Inflector;
use Cake\Utility\Text;

/**
 * Update method names task.
 *
 * Handles updating baked templates
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 */
class TemplatesTask extends BaseTask {

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
		$original = $contents = $this->Stage->source($path);

		/* needs to be adjusted better (case sensitive)
		$patterns = [
			[
				'Replace deep record array $model[Model][field] with simple $model[field]',
				'/\\$([a-z][a-z0-9]+)\[["\']\1["\']](\[.+?\])/i',
				'$\1\2',
			],
		];
		$contents = $this->_updateContents($contents, $patterns)
		*/

		$contents = $this->_replaceRelations($contents, $path);

		$contents = $this->_replaceCustom($contents, $path);

		return $this->Stage->change($path, $original, $contents);
	}

	/**
	 * Replace basic template stuff
	 *
	 * @param string $contents
	 * @param string $path
	 * @return string
	 */
	protected function _replaceRelations($contents, $path) {
		// Avoid false positives in model/behavior etc
		if (strpos($path, DS . 'Model' . DS) !== false) {
			return $contents;
		}

		$pattern = '/\\$([a-z][a-z0-9]+)\[["\']([a-z][a-z0-9]+)["\']](\[.+?\])/i';
		$replacement = function ($matches) {
			return '$' . $matches[1] . '->' . lcfirst($matches[2]) . $matches[3];
		};
		return preg_replace_callback($pattern, $replacement, $contents);
	}

	/**
	 * Custom stuff
	 *
	 * @param string $contents
	 * @param string $path
	 * @return string
	 */
	protected function _replaceCustom($contents, $path) {
		$pattern = '/\$this-\>Form-\>value\(\'(.+?).id\'\)/';
		$replacement = function ($matches) {
			$entity = lcfirst($matches[1]);
			return '$' . $entity . '->id';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		// View
		$pattern = '/\$this-\>Form-\>create\(\'(.+?)\'\)/i';
		$replacement = function ($matches) {
			$entity = lcfirst($matches[1]);
			return '$this->Form->create($' . $entity . ')';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		// Model
		$pattern = '/public \$uses = (array\(|\[)([^\]]+?)(\]|\))/i';
		$replacement = function ($matches) {
			$models = Text::tokenize($matches[2]);
			$class = array_shift($models);
			return 'public $modelClass = ' . $class;
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		// Mainly controller
		$pattern = '/catch \(Exception $(.+?)\)/i';
		$replacement = function ($matches) {
			return 'catch (\\Exception $\1)';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		$pattern = '/-\>Behaviors->(attach|load)\(/i';
		$replacement = function ($matches) {
			return '->addBehavior(';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		$pattern = '/-\>Behaviors->(deattach|unload)\(/i';
		$replacement = function ($matches) {
			return '->removeBehavior(';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		// The following is only for Controllers
		if (strpos($path, DS . 'Controller' . DS) === false) {
			return $contents;
		}

		$pattern = '/\$this-\>([a-z][a-z0-9]+)-\>create\(\)/i';
		$replacement = function ($matches) {
			$entity = lcfirst($matches[1]);
			$table = Inflector::pluralize($matches[1]);
			return '$' . $entity . ' = $this->' . $table . '->newEntity($this->request->data)';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		$pattern = '/\$this-\>([a-z][a-z0-9]+)-\>save\(\$this-\>request-\>data\)/i';
		$replacement = function ($matches) {
			$entity = lcfirst($matches[1]);
			$table = Inflector::pluralize($matches[1]);
			return '$this->' . $table . '->save($' . $entity . ')';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		$pattern = '/\$this-\>([a-z][a-z0-9]+)-\>delete\(\$id\)/i';
		$replacement = function ($matches) {
			$entity = lcfirst($matches[1]);
			$table = Inflector::pluralize($matches[1]);
			return '$this->' . $table . '->delete($' . $entity . ')';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		preg_match('#' . preg_quote(DS . 'Controller' . DS) . '([a-z][a-z0-9]+)Controller\.php#i', $path, $matches);
		if ($matches) {
			$controllerName = $matches[1];
			$model = Inflector::singularize($controllerName);

			$pattern = '/\$this-\>(' . $model . ')-\>/i';
			$replacement = function ($matches) {
				$table = Inflector::pluralize($matches[1]);
				return '$this->' . $table . '->';
			};
			$contents = preg_replace_callback($pattern, $replacement, $contents);
		}

		$pattern = '/-\>request-\>(allowMethod|onlyAllow)\(([^\[)]+)\)/';
		$replacement = function ($matches) {
			$methods = Text::tokenize($matches[2]);
			if (count($methods) < 2) {
				return $matches[0];
			}
			return '->request->allowMethod([' . implode(', ', $methods) . '])';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		$pattern = '/\$this-\>Auth-\>(allow|deny)\(([^\[)]+)\)/';
		$replacement = function ($matches) {
			$methods = Text::tokenize($matches[2]);
			if (count($methods) < 2) {
				return $matches[0];
			}
			return '$this->Auth->' . $matches[1] . '([' . implode(', ', $methods) . '])';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		return $contents;
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
		return $ending === '.php' || $ending === '.ctp';
	}

}
