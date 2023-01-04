<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\TypedPropertyPluginTask;

class TypedPropertyPluginTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;

		$task = new TypedPropertyPluginTask(['path' => $path]);
		$task->run($path);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<'TXT'
src/Plugin.php
-    protected $bootstrapEnabled = true;
+    protected bool $bootstrapEnabled = true;
-    protected $consoleEnabled = true;
+    protected bool $consoleEnabled = true;
-    protected $middlewareEnabled = true;
+    protected bool $middlewareEnabled = true;
-    protected $servicesEnabled = true;
+    protected bool $servicesEnabled = true;
-    protected $routesEnabled = true;
+    protected bool $routesEnabled = true;

TXT;
		$this->assertTextEquals($expected, $changesString);
	}

}
