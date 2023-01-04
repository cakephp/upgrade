<?php

namespace Cake\Upgrade\Test\TestCase\Task\Cake50;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Task\Cake50\PhpcsPsr2rTask;

class PhpcsPsr2rTaskTest extends TestCase {

	/**
	 * Basic test to simulate running on this repo
	 *
	 * Should return all files in the src directory of this repo
	 *
	 * @return void
	 */
	public function testRun() {
		$path = TESTS . 'test_files' . DS . 'Task' . DS . 'Cake50' . DS;

		$task = new PhpcsPsr2rTask(['path' => $path]);
		$task->run($path);

		$changes = $task->getChanges();
		$this->assertCount(1, $changes);

		$changesString = (string)$changes;
		$expected = '<rule ref="SlevomatCodingStandard.TypeHints.ParameterTypeHint">';
		$this->assertTextContains($expected, $changesString);
	}

}
