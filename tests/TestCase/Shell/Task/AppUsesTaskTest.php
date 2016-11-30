<?php

namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\AppUsesTask;

/**
 * AppUsesTaskTest
 */
class AppUsesTaskTest extends TestCase {

	/**
	 * Task instance
	 *
	 * @var \Cake\Upgrade\Shell\Task\AppUsesTask|\PHPUnit_Framework_MockObject_MockObject
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

		$this->task = $this->getMockBuilder(AppUsesTask::class)
			->setMethods(['in', 'out', 'hr', 'err', '_shouldProcess'])
			->setConstructorArgs([$io])
			->getMock();
		$this->task->loadTasks();
	}

	/**
	 * Testing the `use` transformation.
	 *
	 * @return void
	 */
	public function testAppUses() {
		$this->task->expects($this->any())
			->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$this->task->process($path . 'AppUses.php');

		$result = $this->task->Stage->source($path . 'AppUses.php');
		$expected = file_get_contents($path . 'AppUsesAfter.php');
		$this->assertTextEquals($expected, $result);
	}

	/**
	 * Testing the implicit `use` adding.
	 *
	 * @return void
	 */
	public function testAppUsesImplicit() {
		$this->task->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$this->task->process($path . 'AppUsesImplicit.php');

		$result = $this->task->Stage->source($path . 'AppUsesImplicit.php');
		$expected = file_get_contents($path . 'AppUsesImplicitAfter.php');
		$this->assertTextEquals($expected, $result);
	}

	/**
	 * Testing the implicit `use` adding.
	 *
	 * Asserts that TestCase (former CakeTestCase) gets also added, and that
	 * already existing use statements are skipped.
	 *
	 * @return void
	 */
	public function testAppUsesImplicitTestCase() {
		$this->task->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$this->task->process($path . 'AppUsesImplicitTestCase.php');

		$result = $this->task->Stage->source($path . 'AppUsesImplicitTestCase.php');
		$expected = file_get_contents($path . 'AppUsesImplicitTestCaseAfter.php');
		$this->assertTextEquals($expected, $result);
	}

}
