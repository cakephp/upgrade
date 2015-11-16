<?php

namespace Cake\Upgrade\Test\TestCase\Shell\Task;

use Cake\TestSuite\TestCase;

/**
 * I18nTaskTest
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

		$io = $this->getMock('Cake\Console\ConsoleIo', [], [], '', false);

		$this->sut = $this->getMock(
			'Cake\Upgrade\Shell\Task\I18nTask',
			['in', 'out', 'hr', 'err', '_stop'],
			[$io]
		);
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
