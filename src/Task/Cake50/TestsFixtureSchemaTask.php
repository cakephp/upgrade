<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\RepoTaskInterface;
use Cake\Upgrade\Task\Task;
use Cake\Upgrade\Utility\ComposerJson;

/**
 * Adjusts:
 * - Creates tests/schema.php if needed
 */
class TestsFixtureSchemaTask extends Task implements RepoTaskInterface {

	/**
	 * @var string
	 */
	protected const FILE_SCHEMA = 'tests/schema.php';

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$filePath = $path . static::FILE_SCHEMA;
		if (file_exists($filePath)) {
			return;
		}

		$files = $this->collectFiles($path, 'php', ['tests/Fixture/']);
		if (!$this->containsFixtureSchema($files)) {
			return;
		}

		$templateFilePath = ROOT . DS . 'resources' . DS . 'schema.php';
		$content = (string)file_get_contents($templateFilePath);

		$namespace = $this->getNamespace($path) ?: 'App';
		$newContent = str_replace('{{namespace}}', $namespace, $content);

		$this->persistFile($filePath, '', $newContent);
	}

	/**
	 * @param array<string> $files
	 *
	 * @return bool
	 */
	protected function containsFixtureSchema(array $files): bool {
		foreach ($files as $file) {
			$content = file_get_contents($file);
			if (strpos($content, 'public $fields = [') || strpos($content, 'public array $fields = [')) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	protected function getNamespace(string $path): string {
		$composerJson = ComposerJson::fromFile($path . 'composer.json');
		$autoload = $composerJson['autoload']['psr-4'] ?? [];
		if ($autoload) {
			foreach ($autoload as $k => $v) {
				if ($v !== 'src/') {
					continue;
				}

				$namespace = rtrim($k, '\\');

				return str_replace('\\', '\\\\', $namespace);
			}
		}

		//TODO: Other fallback using Plugin.php etc?

		return '';
	}

}
