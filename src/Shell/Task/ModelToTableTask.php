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

use Cake\Console\ConsoleOptionParser;
use Cake\Utility\Inflector;

/**
 * Make Model classes to Table classes.
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 */
class ModelToTableTask extends BaseTask {

	use ChangeTrait;
	use HelperTrait;

	/**
	 * @var array
	 */
	public $tasks = ['Stage'];

	/**
	 * Check all moves, and stage moving the file to new location.
	 *
	 * @param mixed $path
	 * @return bool
	 */
	protected function _process($path) {
		$normalizedPath = str_replace(DS, '/', $path);
		if (!preg_match('#/Model/([a-z0-9]+?)(Test)*\.php#i', $normalizedPath, $matches)) {
			return false;
		}

		$modelClass = $matches[1];
		$tableClass = Inflector::pluralize($modelClass) . 'Table';

		$new = str_replace(DS . 'Model' . DS . $modelClass, DS . 'Model' . DS . 'Table' . DS . $tableClass, $path);

		$original = $contents = $this->Stage->source($path);

		$plugin = !empty($this->params['plugin']) ? $this->params['plugin'] : $this->params['namespace'];
		$contents = str_replace('class ' . $modelClass . ' extends ' . $plugin . 'AppModel', 'class ' . $tableClass . ' extends Table', $contents);
		$contents = str_replace('class ' . $modelClass . ' extends AppModel', 'class ' . $tableClass . ' extends Table', $contents);

		$contents = str_replace('use App\Model\AppModel;', 'use Cake\ORM\Table;', $contents);

		$contents = str_replace('class ' . $modelClass . 'Test extends', 'class ' . $tableClass . 'Test extends', $contents);

		$contents = preg_replace('#\bnamespace ([a-z\\\\]+)\\\\Model;#i', 'namespace \1\Model\Table;', $contents);

		$changed = $this->Stage->change($path, $original, $contents);
		$moved = $this->Stage->move($path, $new);

		return $changed & $moved;
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
		$relativeFromRoot = $this->_getRelativePath($path);

		if (strpos($relativeFromRoot, DS . 'Plugin' . DS) || strpos($relativeFromRoot, DS . 'plugins' . DS)) {
			return false;
		}
		if (strpos($relativeFromRoot, DS . 'Vendor' . DS) || strpos($relativeFromRoot, DS . 'vendors' . DS)) {
			return false;
		}

		foreach (array_keys($this->_moves()) as $substr) {
			if (strpos($relativeFromRoot, DS . $substr . DS) !== false) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Key value map of from and to
	 *
	 * @return array
	 */
	protected function _moves() {
		return [
			'Model' => 'Model' . DS . 'Table',
		];
	}

	/**
	 * Get the option parser for this shell.
	 *
	 * @return \Cake\Console\ConsoleOptionParser
	 */
	public function getOptionParser(): ConsoleOptionParser {
		return parent::getOptionParser()
			->addOptions([
				'root' => [
					'default' => '',
					'help' => 'Set an application\'s root path. Not defining it makes the current path the root one.',
				],
			]);
	}

}
