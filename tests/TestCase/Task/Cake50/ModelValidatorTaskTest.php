<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\ModelValidatorTask;

class ModelValidatorTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;
		$filePath = $path . 'src' . DS . 'Model' . DS . 'Table' . DS . 'SomeTable.php';

		$task = new ModelValidatorTask(['path' => $path]);
		$task->run($filePath);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<'TXT'
src/Model/Table/SomeTable.php
-        $validator->allowEmpty('x', 'y');
+        $validator->allowEmptyString('x', 'y');

TXT;
		$this->assertTextEquals($expected, $changesString);
	}

}
