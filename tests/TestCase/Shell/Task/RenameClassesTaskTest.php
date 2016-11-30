<?php

namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\RenameClassesTask;

/**
 * RenameClassesTaskTest
 */
class RenameClassesTaskTest extends TestCase {

	/**
	 * Task instance
	 *
	 * @var \Cake\Upgrade\Shell\Task\RenameClassesTask|\PHPUnit_Framework_MockObject_MockObject
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

		$this->task = $this->getMockBuilder(RenameClassesTask::class)
			->setMethods(['in', 'out', 'hr', 'err', '_shouldProcess'])
			->setConstructorArgs([$io])
			->getMock();
		$this->task->loadTasks();
	}

	/**
	 * Testing the rename of string class
	 *
	 * @return void
	 */
	public function testRenameClasses() {
		$this->task->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$this->task->process($path . 'RenameClasses.php');

		$result = $this->task->Stage->source($path . 'RenameClasses.php');
		$expected = file_get_contents($path . 'RenameClassesAfter.php');
		$this->assertTextEquals($expected, $result);
	}

	/**
	 * Testing the rename of string class
	 *
	 * @return void
	 */
	public function testRenameStringClass() {
		$this->task->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$this->task->process($path . 'RenameString.php');

		$result = $this->task->Stage->source($path . 'RenameString.php');
		$expected = file_get_contents($path . 'RenameStringAfter.php');
		$this->assertTextEquals($expected, $result);
	}

}
