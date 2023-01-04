<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\FileTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - Shell:: class constants to ConsoleIo:: and Command::
 */
class ShellToCommandTask extends Task implements FileTaskInterface {

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

		$newContent = preg_replace('#\bShell::(NORMAL|VERBOSE|QUIET)#', '\Cake\Console\ConsoleIo::\1', $content);
		$newContent = preg_replace('#\bShell::(CODE\_ERROR|CODE\_SUCCESS)#', '\Cake\Command\Command::\1', $newContent);

		$this->persistFile($path, $content, $newContent);
	}

}
