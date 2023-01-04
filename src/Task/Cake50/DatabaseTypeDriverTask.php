<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\FileTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - DriverInterface to Driver
 */
class DatabaseTypeDriverTask extends Task implements FileTaskInterface {

	/**
	 * @param string $path
	 *
	 * @return array<string>
	 */
	public function getFiles(string $path): array {
		return $this->collectFiles($path, 'php', ['src/Database/Type/']);
	}

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$content = (string)file_get_contents($path);
		$newContent = preg_replace('#Cake\\\Database\\\DriverInterface#', 'Cake\Database\Driver', $content);

		$newContent = preg_replace('#([(, ])DriverInterface \$#', '\1Driver $', $newContent);

		$newContent = preg_replace('#\bpublic function marshal\(\$value\) \{#', 'public function marshal(mixed $value): mixed {', $newContent);
		$newContent = preg_replace('#\bpublic function (toDatabase|toPHP)\(\$value, Driver \$driver\) \{#', 'public function \1(mixed $value, Driver $driver): mixed {', $newContent);
		$newContent = preg_replace('#\bpublic function toStatement\(\$value, Driver \$driver\) \{#', 'public function toStatement(mixed $value, Driver $driver): int {', $newContent);

		$this->persistFile($path, $content, $newContent);
	}

}
