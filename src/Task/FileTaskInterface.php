<?php

namespace Cake\Upgrade\Task;

/**
 * Runs per file.
 */
interface FileTaskInterface extends TaskInterface {

	/**
	 * @param string $path
	 *
	 * @return array<string>
	 */
	public function getFiles(string $path): array;

}
