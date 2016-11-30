<?php

namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\TestsTask;

/**
 * TestsTaskTest
 */
class TestsTaskTest extends TestCase {

	/**
	 * Task instance
	 *
	 * @var \Cake\Upgrade\Shell\Task\TestsTask|\PHPUnit_Framework_MockObject_MockObject
	 */
	public $task;

	/**
	 * setUp
	 *
	 * Create a mock for all tests to use
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$io = $this->getMockBuilder(ConsoleIo::class)->getMock();

		$this->task = $this->getMockBuilder(TestsTask::class)
			->setMethods(['in', 'out', 'hr', 'err', '_stop'])
			->setConstructorArgs([$io])
			->getMock();
		$this->task->loadTasks();
	}

	/**
	 * TestsTaskTest::testProcess()
	 *
	 * @return void
	 */
	public function testProcess() {
		$path = TESTS . 'test_files' . DS;
		$this->task->process($path . 'tests_before.php');

		$result = $this->task->Stage->source($path . 'tests_before.php');
		$expected = file_get_contents($path . 'tests_after.php');

		$this->assertTextEquals($expected, $result);
	}

}
