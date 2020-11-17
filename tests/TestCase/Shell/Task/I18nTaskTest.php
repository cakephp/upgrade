<?php

namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\I18nTask;

/**
 * I18nTaskTest
 */
class I18nTaskTest extends TestCase {

	/**
	 * Task instance
	 *
	 * @var \Cake\Upgrade\Shell\Task\I18nTask|\PHPUnit_Framework_MockObject_MockObject
	 */
	public $task;

	/**
	 * Create a mock for all tests to use
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$io = $this->getMockBuilder(ConsoleIo::class)->getMock();

		$this->task = $this->getMockBuilder(I18nTask::class)
			->setMethods(['in', 'out', 'hr', 'err', '_stop'])
			->setConstructorArgs([$io])
			->getMock();
		$this->task->loadTasks();
	}

	/**
	 * @return void
	 */
	public function testProcess() {
		$path = TESTS . 'test_files' . DS;
		$this->task->process($path . 'i18n_before.php');

		$result = $this->task->Stage->source($path . 'i18n_before.php');
		$expected = file_get_contents($path . 'i18n_after.php');
		$this->assertTextEquals($expected, $result);
	}

}
