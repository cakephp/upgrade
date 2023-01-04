<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\LoadModelTask;

class LoadModelTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;
		$filePath = $path . 'src' . DS . 'Controller' . DS . 'SomeController.php';

		$task = new LoadModelTask(['path' => $path]);
		$task->run($filePath);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<'TXT'
src/Controller/SomeController.php
-        $modelName = $this->getController()->loadModel()->getAlias();
+        $modelName = $this->getController()->fetchTable()->getAlias();
-        $modelName = $this->X->loadModel()
-            ->getAlias();
+        $modelName = $this->X->fetchTable()->getAlias();

TXT;
		$this->assertTextEquals($expected, $changesString);
	}

}
