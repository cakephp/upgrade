<?php
namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\Task\I18nTask;

/**
 * I18nTaskTest
 *
 */
class I18nTaskTest extends TestCase {

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

		$this->sut = $this->getMockBuilder('Cake\Upgrade\Shell\Task\I18nTask')
						->setMethods([
							'in', 'out', 'hr', 'err', '_stop',
						])
						->setConstructorArgs([
							$io,
						])->getMock();

		$this->sut->loadTasks();
	}

	public function testProcess() {
		$path = TESTS . 'test_files' . DS;
		$result = $this->sut->process($path . 'i18n_before.php');
		$this->assertTrue($result);

		$result = $this->sut->Stage->source($path . 'i18n_before.php');
		$expected = file_get_contents($path . 'i18n_after.php');
		$this->assertTextEquals($expected, $result);
	}

}
