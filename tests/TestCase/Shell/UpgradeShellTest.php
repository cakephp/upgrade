<?php

namespace Cake\Upgrade\Test\TestCase\Shell;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use Cake\Upgrade\Shell\UpgradeLegacyShell;

/**
 * UpgradeShellTest
 */
class UpgradeShellTest extends TestCase {

	/**
	 * Upgrade shell instance
	 *
	 * @var \Cake\Upgrade\Shell\UpgradeLegacyShell
	 */
	public $shell;

	/**
	 * setUp
	 *
	 * Create a mock for all tests to use
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$io = $this->getMockBuilder(ConsoleIo::class)->getMock();

		$this->shell = $this->getMockBuilder(UpgradeLegacyShell::class)
			->setMethods(['in', 'out', 'hr', 'err', '_stop'])
			->setConstructorArgs([$io])
			->getMock();
		$this->shell->loadTasks();
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

		$this->shell->args = [$repoSrc];

		$files = $this->shell->Stage->files();
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
			'Shell/Task/HelperTrait.php',
			'Shell/Task/ChangeTrait.php',
			'Shell/Task/LocationsTask.php',
			'Shell/Task/I18nTask.php',
			'Shell/Task/SkeletonTask.php',
			'Shell/Task/TemplatesTask.php',
			'Shell/Task/ModelToTableTask.php',
			'Shell/Task/CleanupTask.php',
			'Shell/Task/PrefixedTemplatesTask.php',
			'Shell/Task/CustomTask.php',
			'Shell/Task/LocaleTask.php',
			'Shell/Task/UrlTask.php',
			'Shell/Task/TableToEntityTask.php',
			'Shell/Task/FixtureLoadingTask.php',
			'Shell/Task/FixtureCasingTask.php',
		];

		foreach ($expected as $file) {
			$this->assertTrue(in_array($file, $files, true), 'The files to process should be all files in the src folder - ' . $file);
		}
	}

}
