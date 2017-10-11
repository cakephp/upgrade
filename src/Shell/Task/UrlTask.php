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
 * Handles custom stuff
 *
 * @property \Cake\Upgrade\Shell\Task\StageTask $Stage
 */
class UrlTask extends BaseTask {

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

		$contents = $this->_fixActions($contents, $path);

		$contents = $this->_fixUrls($contents);

		if (empty($contents)) {
			$contents = $original;
		}
		return $this->Stage->change($path, $original, $contents);
	}

	/**
	 * @param string $contents
	 * @param string $path
	 * @return string
	 */
	protected function _fixActions($contents, $path) {
		$patterns = [
			[
				'public function my_action_name to public function myActionName',
				'/\bpublic function ([a-z0-9]+\_[a-z0-9_]+)\(/i',
				function ($matches) {
					if (strpos($matches[1], 'admin_') === 0) {
						return $matches[0];
					}

					$action = lcfirst(Inflector::camelize($matches[1]));
					return 'public function ' . $action . '(';
				},
			],
		];
		if (strpos($path, DS . 'Controller' . DS) !== false) {
			foreach ($patterns as $pattern) {
				$contents = preg_replace_callback($pattern[1], $pattern[2], $contents);
			}
		}

		return $contents;
	}

	/**
	 * @param string $contents
	 * @return string
	 */
	protected function _fixUrls($contents) {
		// Controller and action Names
		$pattern = '/\'controller\'\s*=\>\s*[\'"](.+?)[\'"](.+?)\'action\'\s*=\>\s*[\'"](.+?)[\'"]/i';
		$replacement = function ($matches) {
			$res = '\'controller\' => \'' . Inflector::camelize($matches[1]) . '\'';

			$res .= $matches[2];

			$res .= '\'action\' => \'' . lcfirst(Inflector::camelize($matches[3])) . '\'';

			return $res;
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		// Prefixes
		$pattern = '/(\'admin\')\s*=\>\s*(.+?)\s*(,\s*\'controller\'\s*=\>.+?)/i';
		$replacement = function ($matches) {
			$res = '\'prefix\' => ';

			if ($matches[2] === 'true' || $matches[2] === '1') {
				$res .= $matches[2];
				return $res . $matches[3];
			}
			if ($matches[2] === 'false' || $matches[2] === '0') {
				$res .= 'false';
				return $res . $matches[3];
			}

			return $matches[0];
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

		$pattern = '/(\'controller\'\s*=\>.+?,\s*)(\'admin\')\s*=\>\s*(.+?)([,\]])/i';
		$replacement = function ($matches) {
			//debug($matches);die();
			$res = $matches[1] . '\'prefix\' => ';

			if ($matches[3] === 'true' || $matches[3] === '1') {
				$res .= $matches[2];
				return $res . $matches[4];
			}
			if ($matches[3] === 'false' || $matches[3] === '0') {
				$res .= 'false';
				return $res . $matches[4];
			}

			return $matches[0];
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
