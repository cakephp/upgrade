<?php

namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\TestSuite\TestCase;

/**
 * AppUsesTaskTest
 *
 */
class AppUsesTaskTest extends TestCase {

/**
 * Task instance
 *
 * @var mixed
 */
	public $sut;

/**
 * setUp
 *
 * Create a mock for all tests to use
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$io = $this->getMock('Cake\Console\ConsoleIo', [], [], '', false);

		$this->sut = $this->getMock(
			'Cake\Upgrade\Shell\Task\AppUsesTask',
			['in', 'out', 'hr', 'err', '_shouldProcess'],
			[$io]
		);
		$this->sut->loadTasks();
	}

/**
 * Testing the rename of string class
 *
 * @return void
 */
	public function testAppUses() {
		$this->sut->expects($this->any())
			->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$result = $this->sut->process($path . 'AppUses.php');
		$this->assertTrue($result);

		$result = $this->sut->Stage->source($path . 'AppUses.php');
		$expected = file_get_contents($path . 'AppUsesAfter.php');
		$this->assertTextEquals($expected, $result);
	}

}
