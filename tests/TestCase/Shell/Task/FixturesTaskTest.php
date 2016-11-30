<?php

namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\FixturesTask;

/**
 * FixturesTaskTest
 */
class FixturesTaskTest extends TestCase {

	/**
	 * Task instance
	 *
	 * @var \Cake\Upgrade\Shell\Task\FixturesTask|\PHPUnit_Framework_MockObject_MockObject
	 */
	public $task;

	/**
	 * Create a mock for all tests to use
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$io = $this->getMockBuilder(ConsoleIo::class)->getMock();

		$this->task = $this->getMockBuilder(FixturesTask::class)
			->setMethods(['in', 'out', 'hr', 'err', '_shouldProcess'])
			->setConstructorArgs([$io])
			->getMock();
		$this->task->loadTasks();
	}

	/**
	 * SkeletonTaskTest::testProcess()
	 *
	 * @return void
	 */
	public function testProcess() {
		$this->task->expects($this->any())
			->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$this->task->process($path . 'ArticleFixture.php');

		$result = $this->task->Stage->source($path . 'ArticleFixture.php');
		$expected = file_get_contents($path . 'ArticleFixtureAfter.php');
		$this->assertTextEquals($expected, $result);
	}

}
