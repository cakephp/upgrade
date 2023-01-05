<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\FileTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - ->loadModel()-> to ->fetchTable()->
 */
class BasicsTask extends Task implements FileTaskInterface {

	/**
	 * @param string $path
	 *
	 * @return array<string>
	 */
	public function getFiles(string $path): array {
		return $this->collectFiles($path, 'php', ['src/', 'tests/TestCase/']);
	}

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$content = (string)file_get_contents($path);

		$newContent = preg_replace('#\bTableRegistry::exists\(#', 'TableRegistry::getTableLocator()->exists(', $content);
		$newContent = preg_replace('#\bTableRegistry::get\(#', 'TableRegistry::getTableLocator()->get(', $newContent);

		$newContent = preg_replace('#\bprotected \$modelClass =#', 'protected ?string $defaultTable = ', $newContent);

		$this->persistFile($path, $content, $newContent);
	}

}
