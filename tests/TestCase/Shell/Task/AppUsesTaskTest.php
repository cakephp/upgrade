<?php

namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\TestSuite\TestCase;

/**
 * AppUsesTaskTest
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
 * Testing the `use` transformation.
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

/**
 * Testing the implicit `use` adding.
 *
 * @return void
 */
	public function testAppUsesImplicit() {
		$this->sut->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$result = $this->sut->process($path . 'AppUsesImplicit.php');
		$this->assertTrue($result);

		$result = $this->sut->Stage->source($path . 'AppUsesImplicit.php');
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
		$this->sut->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$result = $this->sut->process($path . 'AppUsesImplicitTestCase.php');
		$this->assertTrue($result);

		$result = $this->sut->Stage->source($path . 'AppUsesImplicitTestCase.php');
		$expected = file_get_contents($path . 'AppUsesImplicitTestCaseAfter.php');
		$this->assertTextEquals($expected, $result);
	}

}
