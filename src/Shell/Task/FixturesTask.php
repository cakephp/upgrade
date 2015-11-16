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

/**
 * Updates fixtures for 3.0
 */
class FixturesTask extends BaseTask {

	use ChangeTrait;

	public $tasks = ['Stage'];

/**
 * Process fixture content and update it for 3.x
 *
 * @param string $content Fixture content.
 * @return bool
 */
	protected function _process($path) {
		$original = $contents = $this->Stage->source($path);

		// Serializes data from PHP data into PHP code.
		// Basically a code style conformant version of var_export()
		$export = function ($values) use (&$export) {
			$vals = [];
			if (!is_array($values)) {
				return $vals;
			}
			foreach ($values as $key => $val) {
				if (is_array($val)) {
					$vals[] = "'{$key}' => [" . implode(', ', $export($val)) . ']';
				} else {
					$val = var_export($val, true);
					if ($val === 'NULL') {
						$val = 'null';
					}
					if (!is_numeric($key)) {
						$vals[] = "'{$key}' => {$val}";
					} else {
						$vals[] = "{$val}";
					}
				}
			}
			return $vals;
		};

		// Process field property.
		$processor = function ($matches) use ($export) {
			eval('$data = [' . $matches[2] . '];');
			$constraints = [];
			$out = [];
			foreach ($data as $field => $properties) {
				// Move primary key into a constraint
				if (isset($properties['key']) && strtolower($properties['key']) === 'primary') {
					$constraints['primary'] = [
						'type' => 'primary',
						'columns' => [$field],
					];
				}
				if (isset($properties['key'])) {
					unset($properties['key']);
				}
				if ($field !== 'indexes' && $field !== 'tableParameters') {
					$out[$field] = $properties;
				}
			}

			// Process indexes. Unique keys work differently now.
			if (isset($data['indexes'])) {
				foreach ($data['indexes'] as $index => $indexProps) {
					if (isset($indexProps['column'])) {
						$indexProps['columns'] = $indexProps['column'];
						unset($indexProps['column']);
					}

					if (strtolower($index) === 'primary' && isset($constraints['primary'])) {
						continue;
					}

					// Move unique indexes over
					if (!empty($indexProps['unique'])) {
						unset($indexProps['unique']);
						$constraints[$index] = ['type' => 'unique'] + $indexProps;
						continue;
					}
					$out['_indexes'][$index] = $indexProps;
				}
			}
			if (count($constraints)) {
				$out['_constraints'] = $constraints;
			}

			// Process table parameters
			if (isset($data['tableParameters'])) {
				$out['_options'] = $data['tableParameters'];
			}
			return $matches[1] . "\n\t\t" . implode(",\n\t\t", $export($out)) . "\n\t" . $matches[3];
		};

		$contents = preg_replace_callback(
			'/(public \$fields\s+=\s+(?:array\(|\[))(.*?)(\);|\];)/ms',
			$processor,
			$contents,
			-1,
			$count
		);

		// Pluralize fixtures
		$name = pathinfo($path, PATHINFO_FILENAME);
		$name = substr($name, 0, -7);
		$fixtureName = Inflector::pluralize($name);

		// We do not want to run it more than once.
		$isAlreadyPlural = Inflector::pluralize(Inflector::singularize($fixtureName)) === $name;
		$className = $fixtureName . 'Fixture';

		$pluralProcessor = function ($matches) use ($className) {
			return $matches[1] . ' ' . $className;
		};

		$result = true;
		if (!$isAlreadyPlural) {
			// Replace both in doc block and class declaration.
			$contents = preg_replace_callback(
				'/(\bclass|\\*) ([a-z]+)Fixture\b/msi',
				$pluralProcessor,
				$contents,
				-1,
				$count
			);
			$newPath = dirname($path) . DS . $className . '.php';
		}

		$result = $this->Stage->change($path, $original, $contents);
		if (!empty($newPath)) {
			$result &= $this->Stage->move($path, $newPath);
		}
		return (bool)$result;
	}

/**
 * _shouldProcess
 *
 * Only process files in fixture folders
 *
 * @param string $path
 * @return bool
 */
	protected function _shouldProcess($path) {
		return (
			substr($path, -4) === '.php' &&
			strpos($path, DS . 'tests' . DS . 'Fixture' . DS)
		);
	}

}
