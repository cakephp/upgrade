<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\RepoTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - PHP version in badge
 * - CakePHP version in description
 */
class ReadmeTask extends Task implements RepoTaskInterface {

	/**
	 * Default value (min) if no other **higher** value is set within composer.json
	 *
	 * @var string
	 */
	protected const TARGET_VERSION_PHP = '8.1';

	/**
	 * @var string
	 */
	protected const TARGET_VERSION_CAKEPHP = '5.0';

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$filePath = $path . 'README.md';
		$content = (string)file_get_contents($filePath);

		$version = static::TARGET_VERSION_PHP;
		//TODO: read from composer if higher value there

		$versionCake = static::TARGET_VERSION_CAKEPHP;
		//TODO: read from composer if higher value there

		$newContent = preg_replace('#php-%3E%3D%20(.+?)-8892BF\.svg#', 'php-%3E%3D%20' . $version . '-8892BF.svg', $content);
		$newContent = preg_replace('#http://img\.shields\.io#', 'https://img.shields.io', $newContent);

		$newContent = preg_replace('#\*\*CakePHP (.+?)\*\*#', '**CakePHP ' . $versionCake . '+**', $newContent);

		$this->persistFile($filePath, $content, $newContent);
	}

}
