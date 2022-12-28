<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\ReadmeTask;

class ReadmeTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;

		$task = new ReadmeTask(['path' => $path]);
		$task->run($path);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = <<<TXT
README.md
-[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
+[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)
-This branch is for use with **CakePHP 4.2+**. For details see ...
+This branch is for use with **CakePHP 5.0+**. For details see ...

TXT;

		$this->assertTextEquals($expected, $changesString);
	}

}
