<?php

namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\Console\ConsoleIo;
use Cake\Filesystem\Folder;
use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\SkeletonTask;

/**
 * SkeletonTaskTest
 */
class SkeletonTaskTest extends TestCase {

	/**
	 * Task instance
	 *
	 * @var \Cake\Upgrade\Shell\Task\SkeletonTask|\PHPUnit_Framework_MockObject_MockObject
	 */
	public $task;

	/**
	 * setUp
	 *
	 * Create a mock for all tests to use
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$io = $this->getMockBuilder(ConsoleIo::class)->getMock();

		$this->task = $this->getMockBuilder(SkeletonTask::class)
			->setMethods(['in', 'out', 'hr', 'err', '_stop'])
			->setConstructorArgs([$io])
			->getMock();
		$this->task->loadTasks();
	}

	/**
	 * Teardown
	 *
	 * @return void
	 */
	public function tearDown(): void {
		$Folder = new Folder(TMP . 'skeleton_test' . DS);
		$Folder->delete();

		parent::tearDown();
	}

	/**
	 * SkeletonTaskTest::testProcess()
	 *
	 * @return void
	 */
	public function testProcess() {
		$path = TMP . 'skeleton_test' . DS;

		$this->assertFalse(file_exists($path . 'logs' . DS . 'empty'));

		$this->task->process($path . 'composer.json');

		$this->assertTrue(file_exists($path . 'logs' . DS . 'empty'));
		$this->assertTrue(file_exists($path . 'tests' . DS . 'bootstrap.php'));
	}

}
