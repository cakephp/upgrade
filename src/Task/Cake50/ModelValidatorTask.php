<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\FileTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - allowEmpty() to allowEmptyString()
 */
class ModelValidatorTask extends Task implements FileTaskInterface {

	/**
	 * @param string $path
	 *
	 * @return array<string>
	 */
	public function getFiles(string $path): array {
		return $this->collectFiles($path, 'php', ['src/Model/Table/']);
	}

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$content = (string)file_get_contents($path);

		$newContent = preg_replace('#\$validator-\>allowEmpty\(#', '$validator->allowEmptyString(', $content);

		$this->persistFile($path, $content, $newContent);
	}

}
