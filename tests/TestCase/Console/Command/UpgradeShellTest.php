<?php

namespace Cake\Upgrade\Test\TestCase\Console\Command;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Console\Command\UpgradeShell;

/**
 * UpgradeShellTest
 *
 */
class UpgradeShellTest extends TestCase {

/**
 * Upgrade shell instance
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
			'Cake\Upgrade\Console\Command\UpgradeShell',
			['in', 'out', 'hr', 'err', '_stop'],
			[$io]
		);
		$this->sut->loadTasks();
	}

/**
 * Basic test to simulate running on this repo
 *
 * Should return all files in the src directory of this repo
 *
 * @return void
 */
	public function testFiles() {
		$repoSrc = ROOT . DS . 'src';

		$this->sut->args = [$repoSrc];

		$files = $this->sut->Stage->files();
		foreach($files as &$file) {
			$file = str_replace(DS, '/', substr($file, strlen($repoSrc) + 1));
		}

		$expected = [
			'Config/app.php',
			'Config/bootstrap.php',
			'Config/paths.php',
			'Console/Command/UpgradeShell.php',
			'Console/Command/Task/RenameCollectionsTask.php',
			'Console/Command/Task/NamespacesTask.php',
			'Console/Command/Task/StageTask.php',
			'Console/Command/Task/AppUsesTask.php',
			'Console/Command/Task/FixturesTask.php',
			'Console/Command/Task/RenameClassesTask.php',
			'Console/Command/Task/ChangeTrait.php',
			'Console/Command/Task/LocationsTask.php',
			'Console/cake.bat',
			'Console/cake',
			'Console/cake.php'
		];

		sort($files);
		sort($expected);
		$this->assertSame($expected, $files, 'The files to process should be all files in the src folder');
	}

}
