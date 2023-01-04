<?php

namespace Cake\Upgrade\Task\Cake50;

use Cake\Upgrade\Task\RepoTaskInterface;
use Cake\Upgrade\Task\Task;

/**
 * Adjusts:
 * - Adds schema loader to bootstrap if needed
 */
class TestsBootstrapFixtureTask extends Task implements RepoTaskInterface {

	/**
	 * @param string $path
	 *
	 * @return void
	 */
	public function run(string $path): void {
		$filePath = $path . 'tests/bootstrap.php';
		if (!file_exists($filePath)) {
			return;
		}

		$files = $this->collectFiles($path, 'php', ['tests/TestCase/']);
		if (!$this->containsFixtures($files)) {
			return;
		}

		$content = (string)file_get_contents($filePath);
		if (strpos($content, 'env(\'FIXTURE_SCHEMA_METADATA\')')) {
			return;
		}

		$newContent = $content . <<<'TXT'

if (env('FIXTURE_SCHEMA_METADATA')) {
	$loader = new Cake\TestSuite\Fixture\SchemaLoader();
	$loader->loadInternalFile(env('FIXTURE_SCHEMA_METADATA'));
}

TXT;

		$this->persistFile($filePath, $content, $newContent);
	}

	/**
	 * @param array<string> $files
	 *
	 * @return bool
	 */
	protected function containsFixtures(array $files): bool {
		foreach ($files as $file) {
			$content = file_get_contents($file);
			if (strpos($content, 'protected $fixtures = [') || strpos($content, 'protected array $fixtures = [')) {
				return true;
			}
		}

		return false;
	}

}
