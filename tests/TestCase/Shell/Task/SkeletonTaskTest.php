<?php
namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\SkeletonTask;
use Cake\Utility\Folder;

/**
 * SkeletonTaskTest
 *
 */
class SkeletonTaskTest extends TestCase {

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
			'Cake\Upgrade\Shell\Task\SkeletonTask',
			['in', 'out', 'hr', 'err', '_stop'],
			[$io]
		);
		$this->sut->loadTasks();
	}

/**
 * Teardown
 *
 * @return void
 */
	public function tearDown() {
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

		$result = $this->sut->process($path . 'composer.json');
		$this->assertTrue($result);

		$this->assertTrue(file_exists($path . 'logs' . DS . 'empty'));
		$this->assertTrue(file_exists($path . 'tests' . DS . 'bootstrap.php'));
	}

}
