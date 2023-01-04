<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\TestsControllerInstantiationTask;

class TestsControllerInstantiationTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;
		$filePath = $path . 'tests' . DS . 'TestCase' . DS . 'Controller' . DS . 'SomeControllerTest.php';

		$task = new TestsControllerInstantiationTask(['path' => $path]);
		$task->run($filePath);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<'TXT'
tests/TestCase/Controller/SomeControllerTest.php
-        $this->Controller = new Controller();
+        $this->Controller = new Controller(new \Cake\Http\ServerRequest());

TXT;
		$this->assertTextEquals($expected, $changesString);
	}

}
