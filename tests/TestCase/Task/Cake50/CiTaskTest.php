<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\CiTask;

class CiTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;

		$task = new CiTask(['path' => $path]);
		$task->run($path);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<TXT
.github/workflows/ci.yml
-        php-version: ['7.4', '8.2']
+        php-version: ['8.1', '8.2']
-          - php-version: '7.4'
+          - php-version: '8.1'
-        if [[ \${{ matrix.php-version }} == '7.4' && \${{ matrix.db-type }} == 'sqlite' ]]; then
+        if [[ \${{ matrix.php-version }} == '8.1' ]]; then
-      if: success() && matrix.php-version == '7.4' && matrix.db-type == 'sqlite'
+      if: success() && matrix.php-version == '8.1'
-        php-version: '7.4'
+        php-version: '8.1'

TXT;
		$this->assertTextEquals($expected, $changesString);
	}

}
