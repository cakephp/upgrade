<?php

namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\TestSuite\TestCase;

/**
 * RenameClassesTaskTest
 *
 */
class RenameClassesTaskTest extends TestCase {

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
			'Cake\Upgrade\Shell\Task\RenameClassesTask',
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
	public function testRenameClasses() {
		$this->sut->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$result = $this->sut->process($path . 'RenameClasses.php');
		$this->assertTrue($result);

		$result = $this->sut->Stage->source($path . 'RenameClasses.php');
		$expected = file_get_contents($path . 'RenameClassesAfter.php');
		$this->assertTextEquals($expected, $result);
	}

/**
 * Testing the rename of string class
 *
 * @return void
 */
	public function testRenameStringClass() {
		$this->sut->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$result = $this->sut->process($path . 'RenameString.php');
		$this->assertTrue($result);

		$result = $this->sut->Stage->source($path . 'RenameString.php');
		$expected = file_get_contents($path . 'RenameStringAfter.php');
		$this->assertTextEquals($expected, $result);
	}

}
