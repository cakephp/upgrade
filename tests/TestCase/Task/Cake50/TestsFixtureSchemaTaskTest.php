<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\TestsFixtureSchemaTask;

class TestsFixtureSchemaTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;

		$task = new TestsFixtureSchemaTask(['path' => $path, 'skipSchemaCheck' => true]);
		$task->run($path);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = '$class = \'Foo\\\\Bar\\\\Test\\\\Fixture\\\\\' . $name . \'Fixture\';';
		$this->assertTextContains($expected, $changesString);
	}

}
