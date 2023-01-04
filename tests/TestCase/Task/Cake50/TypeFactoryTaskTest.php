<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\TypeFactoryTask;

class TypeFactoryTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;
		$filePath = $path . 'src' . DS . 'Model' . DS . 'Behavior' . DS . 'SomeBehavior.php';

		$task = new TypeFactoryTask(['path' => $path]);
		$task->run($filePath);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<'TXT'
src/Model/Behavior/SomeBehavior.php
-        Type::map('array', ArrayType::class);
+        \Cake\Database\TypeFactory::map('array', ArrayType::class);

TXT;
		$this->assertTextEquals($expected, $changesString);
	}

}
