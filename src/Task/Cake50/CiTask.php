<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\RepoTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - composer version (if x.y of `>=x.y` is less than 8.1)
 * - TODO: Cake version and related plugin versions?
 */
class CiTask extends Task implements RepoTaskInterface {

	/**
	 * @var string
	 */
	protected const VERSION_MIN = '8.1';

	/**
	 * @var string
	 */
	protected const VERSION_MAX = '8.2';

	/**
	 * @var string
	 */
	protected const FILE_NAME = 'ci.yml';

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$filePath = $path . '.github/workflows/' . static::FILE_NAME;
		$content = (string)file_get_contents($filePath);

		$callable = function ($matches) {
			$version = version_compare($matches[1], static::VERSION_MIN . '.0', '<') ? static::VERSION_MIN : $matches[1];

			return 'php-version: \'' . $version . '\'';
		};
		$newContent = (string)preg_replace_callback('/php-version: \'(.+?)\'/', $callable, $content);

		$callable = function ($matches) {
			$versionMin = version_compare($matches[1], static::VERSION_MIN . '.0', '<') ? static::VERSION_MIN : $matches[1];
			$versionMax = version_compare($matches[2], static::VERSION_MAX . '.0', '<') ? static::VERSION_MAX : $matches[1];

			return 'php-version: [\'' . $versionMin . '\', \'' . $versionMax . '\']';
		};
		$newContent = (string)preg_replace_callback('/php-version: \[\'(.+?)\', \'(.+?)\'\]/', $callable, $newContent);

		$callable = function ($matches) {
			$version = version_compare($matches[1], static::VERSION_MIN . '.0', '<') ? static::VERSION_MIN : $matches[1];

			return '== \'' . $version . '\'';
		};
		$newContent = (string)preg_replace_callback('/== \'(7\..+?)\'/', $callable, $newContent);

		$this->persistFile($filePath, $content, $newContent);
	}

}
