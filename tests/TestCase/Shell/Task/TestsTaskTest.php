<?php
namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\TestsTask;

/**
 * TestsTaskTest
 *
 */
class TestsTaskTest extends TestCase {

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

		$io = $this->getMockBuilder('Cake\Console\ConsoleIo')
				->disableOriginalConstructor()
				->getMock();

		$this->sut = $this->getMockBuilder('Cake\Upgrade\Shell\Task\TestsTask')
						->setMethods([
							'in', 'out', 'hr', 'err', '_stop',
						])
						->setConstructorArgs([
							$io,
						])->getMock();

		$this->sut->loadTasks();
	}

/**
 * TestsTaskTest::testProcess()
 *
 * @return void
 */
	public function testProcess() {
		$path = TESTS . 'test_files' . DS;
		$result = $this->sut->process($path . 'tests_before.php');
		$this->assertTrue($result);

		$result = $this->sut->Stage->source($path . 'tests_before.php');
		$expected = file_get_contents($path . 'tests_after.php');
		$this->assertTextEquals($expected, $result);
	}

}
