<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\RepoTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - composer version (if x.y of `>=x.y` is less than 8.1)
 * - TODO: Cake version and related plugin versions?
 */
class ComposerTask extends Task implements RepoTaskInterface {

	/**
	 * @var string
	 */
	protected const CHAR = '>=';

	/**
	 * @var string
	 */
	protected const TARGET_VERSION = '8.1';

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$filePath = $path . 'composer.json';
		$content = (string)file_get_contents($filePath);
		$callable = function ($matches) {
			$version = version_compare($matches[1], static::TARGET_VERSION . '.0', '<') ? static::TARGET_VERSION : $matches[1];

			return '"php": "' . static::CHAR . $version . '"';
		};
		$newContent = (string)preg_replace_callback('/"php": "' . static::CHAR . '(.+)"/', $callable, $content);

		$this->persistFile($filePath, $content, $newContent);
	}

}
