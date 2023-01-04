<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\FileTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - protected $_defaultConfig => protected array $_defaultConfig
 */
class TypeFactoryTask extends Task implements FileTaskInterface {

	/**
	 * @param string $path
	 *
	 * @return array<string>
	 */
	public function getFiles(string $path): array {
		return $this->collectFiles($path, 'php', ['src/', 'config/', 'tests/TestCase/Database/']);
	}

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$content = (string)file_get_contents($path);
		$newContent = preg_replace('#\bType::map\(\'([A-Za-z0-9]+)\',#', '\Cake\Database\TypeFactory::map(\'\1\',', $content);
		$newContent = preg_replace('#\bType::build\(\'([A-Za-z0-9]+)\'\)#', '\Cake\Database\TypeFactory::build(\'\1\')', $newContent);
		$newContent = preg_replace('#\bType::(buildAll|clear)\(\)#', '\Cake\Database\TypeFactory::\1()', $newContent);

		$this->persistFile($path, $content, $newContent);
	}

}
