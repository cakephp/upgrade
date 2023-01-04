<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\FileTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - protected $_defaultConfig => protected array $_defaultConfig
 */
class TypedPropertyTask extends Task implements FileTaskInterface {

	/**
	 * @param string $path
	 *
	 * @return array<string>
	 */
	public function getFiles(string $path): array {
		return $this->collectFiles($path, 'php', ['src/', 'tests/TestCase/', 'tests/test_app/', 'tests/test_app/plugins/']);
	}

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$content = (string)file_get_contents($path);
		$newContent = preg_replace('#\bprotected \$(\w+) = \[#', 'protected array $\1 = [', $content);

		$this->persistFile($path, $content, $newContent);
	}

}
