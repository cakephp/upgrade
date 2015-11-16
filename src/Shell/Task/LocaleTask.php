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
 * Locale Task.
 *
 * Updates Locale PO files.
 */
class LocaleTask extends BaseTask {

	use ChangeTrait;

	public $tasks = ['Stage'];

	/**
	 * Converts placeholders from 2.x to 3.x syntax.
	 *
	 * @return void
	 */
	protected function _process($path) {
		$original = $contents = $this->Stage->source($path);

		$contents = $this->_adjustLocales($contents);
		return $this->Stage->change($path, $original, $contents);
	}

	/**
	 * Adjusts msgid and msgstring from %s to {n}.
	 *
	 * @param string $contents
	 * @return string
	 */
	protected function _adjustLocales($contents) {
		// Basic functions
		$pattern = '#(msgid|msgstr)\s*"((?:[^\\"]|\\.)*)"#';

		$replacement = function ($matches) {
			$string = $matches[2];
			$count = 0;

			$c = 1;
			while ($c) {
				$repString = '{' . $count . '}';
				$string = preg_replace('/%[sdefc]/', $repString, $string, 1, $c);
				$count++;
			}
			return '' . $matches[1] . ' "' . $string . '"';
		};

		$contents = preg_replace_callback($pattern, $replacement, $contents, -1, $count);

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
		$ending = substr($path, -3);
		return $ending === '.po';
	}

}
