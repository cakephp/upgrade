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
 * @since         4.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Upgrade\Test\TestCase\Command;

use Cake\Core\Configure;
use Cake\Upgrade\Test\TestCase;

/**
 * FileRenameCommand test.
 */
class FileRenameCommandTest extends TestCase
{
    public function testTemplates(): void
    {
        $this->setupTestApp(__FUNCTION__);
        Configure::write('App.paths.plugins', TEST_APP . 'plugins');

        $this->exec('upgrade file_rename templates ' . TEST_APP);
        $this->assertTestAppUpgraded();
    }

    public function testLocales(): void
    {
        $this->setupTestApp(__FUNCTION__);
        Configure::write('App.paths.plugins', TEST_APP . 'plugins');

        $this->exec('upgrade file_rename locales ' . TEST_APP);
        $this->assertTestAppUpgraded();
    }
}
