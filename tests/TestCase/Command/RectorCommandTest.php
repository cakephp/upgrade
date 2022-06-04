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

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Upgrade\Test\TestCase;

/**
 * RectorCommand test.
 */
class RectorCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * @var string
     */
    protected $appDir;

    /**
     * setup method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->useCommandRunner(true);
        $this->configApplication('\Cake\Upgrade\Application', []);
    }

    /**
     * @return void
     */
    public function testInvalidAppDir()
    {
        $this->exec('upgrade rector --dry-run ./something/invalid');

        $this->assertExitError();
        $this->assertErrorContains('`./something/invalid` does not exist.');
    }

    /**
     * @return void
     */
    public function testApplyAppDir()
    {
        $this->setupTestApp(__FUNCTION__);
        $this->exec('upgrade rector --dry-run ' . TEST_APP);

        $this->assertExitSuccess();
        $this->assertOutputContains('HelloCommand.php');
        $this->assertOutputContains('begin diff');
        $this->assertOutputContains('Rector applied successfully');
    }
}
