<?php
namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\FixturesTask;
use Cake\Utility\Folder;

/**
 * FixturesTaskTest
 *
 */
class FixturesTest extends TestCase {

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

		$this->sut = $this->getMockBuilder('Cake\Upgrade\Shell\Task\FixturesTask')
						->setMethods([
							'in', 'out', 'hr', 'err', '_shouldProcess',
						])
						->setConstructorArgs([
							$io,
						])->getMock();

		$this->sut->loadTasks();
	}

/**
 * SkeletonTaskTest::testProcess()
 *
 * @return void
 */
	public function testProcess() {
		$this->sut->expects($this->any())
			->method('_shouldProcess')
			->will($this->returnValue(true));

		$path = TESTS . 'test_files' . DS;
		$result = $this->sut->process($path . 'ArticleFixture.php');
		$this->assertTrue($result);

		$result = $this->sut->Stage->source($path . 'ArticleFixture.php');
		$expected = file_get_contents($path . 'ArticleFixtureAfter.php');
		$this->assertTextEquals($expected, $result);
	}

}
