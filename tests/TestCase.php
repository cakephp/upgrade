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
use Cake\TestSuite\TestCase as CakeTestCase;

class TestCase extends CakeTestCase
{
    public const APP_PATH = TMP . 'app';

    protected function copyOldApp(string $testName): void
    {
        $className = substr(static::class, strrpos(static::class, '\\') + 1);
        $testPath = TESTS . 'old_apps' . DS . $className . '-' . $testName;

        if (file_exists($testPath)) {
            $appPath = TMP . 'app';

            $fs = new Filesystem();
            $fs->deleteDir($appPath);
            $fs->copyDir($testPath, $appPath);
        }
    }
}
