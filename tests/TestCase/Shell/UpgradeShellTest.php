<?php
namespace Cake\Upgrade\Test\TestCase\Shell;

use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\UpgradeShell;

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
			'Cake\Upgrade\Shell\UpgradeShell',
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
		foreach ($files as &$file) {
			$file = str_replace(DS, '/', substr($file, strlen($repoSrc) + 1));
		}

		$expected = [
			'Shell/UpgradeShell.php',
			'Shell/Task/BaseTask.php',
			'Shell/Task/RenameCollectionsTask.php',
			'Shell/Task/NamespacesTask.php',
			'Shell/Task/StageTask.php',
			'Shell/Task/TestsTask.php',
			'Shell/Task/AppUsesTask.php',
			'Shell/Task/FixturesTask.php',
			'Shell/Task/RenameClassesTask.php',
			'Shell/Task/MethodNamesTask.php',
			'Shell/Task/MethodSignaturesTask.php',
			'Shell/Task/ChangeTrait.php',
			'Shell/Task/LocationsTask.php',
			'Shell/Task/I18nTask.php',
			'Shell/Task/SkeletonTask.php',
			'Shell/Task/TemplatesTask.php',
			'Shell/Task/ModelToTableTask.php',
			'Shell/Task/CleanupTask.php',
			'Shell/Task/PrefixedTemplatesTask.php',
			'Shell/Task/CustomTask.php',
		];

		sort($files);
		sort($expected);
		$this->assertSame($expected, $files, 'The files to process should be all files in the src folder');
	}

}
