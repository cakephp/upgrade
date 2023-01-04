<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\RepoTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - composer constraint for new next branch
 */
class ComposerPsr2rTask extends Task implements RepoTaskInterface {

	/**
	 * @var string
	 */
	protected const TARGET_VERSION = 'dev-next';

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$filePath = $path . 'composer.json';
		$content = (string)file_get_contents($filePath);

		$newContent = str_replace('"fig-r/psr2r-sniffer": "dev-master"', '"fig-r/psr2r-sniffer": "' . static::TARGET_VERSION . '"', $content);

		$this->persistFile($filePath, $content, $newContent);
	}

}
