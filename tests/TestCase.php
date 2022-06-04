<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Upgrade\Test;

use Cake\Filesystem\Filesystem;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase as CakeTestCase;
use Cake\Utility\Hash;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class TestCase extends CakeTestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * @var string
     */
    protected $testAppDir;

    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner(true);
    }

    protected function setupTestApp(string $testName): void
    {
        $className = substr(static::class, strrpos(static::class, '\\') + 1, -strlen('Test'));
        $this->testAppDir = $className . '-' . $testName;
        $testAppPath = ORIGINAL_APPS . $this->testAppDir;

        if (file_exists($testAppPath)) {
            $fs = new Filesystem();
            $fs->deleteDir(TEST_APP);
            $fs->copyDir($testAppPath, TEST_APP);
        }
    }

    protected function assertTestAppUpgraded(): void
    {
        $appFs = $this->getFsInfo(TEST_APP);
        $upgradedFs = $this->getFsInfo(UPGRADED_APPS . $this->testAppDir);
        $this->assertEquals($upgradedFs['tree'], $appFs['tree'], 'Upgraded test_app does not match `upgraded_apps`');

        foreach ($upgradedFs['files'] as $relativePath) {
            $this->assertFileEquals(UPGRADED_APPS . $this->testAppDir . DS . $relativePath, TEST_APP . $relativePath, $relativePath);
        }
    }

    protected function getFsInfo(string $path): array
    {
        if ($path[-1] !== DS) {
            $path .= DS;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $path,
                RecursiveDirectoryIterator::KEY_AS_PATHNAME |
                RecursiveDirectoryIterator::CURRENT_AS_FILEINFO |
                RecursiveDirectoryIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $tree = [];
        $files = [];
        foreach ($iterator as $filePath => $fileInfo) {
            $relativePath = substr($filePath, strlen($path));
            if ($fileInfo->isDir()) {
                $tree[$relativePath] = [];
            } elseif ($fileInfo->isFile() && $fileInfo->getFileName() !== 'empty') {
                $tree[$relativePath] = $fileInfo->getFileName();
                $files[] = $relativePath;
            }
        }

        return ['tree' => Hash::expand($tree, DS), 'files' => $files];
    }
}
