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
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Upgrade;

use Cake\Console\CommandCollection;
use Cake\Core\ConsoleApplicationInterface;
use Cake\Upgrade\Command\FileRenameCommand;
use Cake\Upgrade\Command\FileUpgradeCommand;
use Cake\Upgrade\Command\RectorCommand;
use Cake\Upgrade\Command\UpgradeCommand;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application implements ConsoleApplicationInterface
{
    /**
     * Load configuration data.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        require_once dirname(__DIR__) . '/config/bootstrap.php';
    }

    /**
     * Define the console commands for an application.
     *
     * @param \Cake\Console\CommandCollection $commands The CommandCollection to add commands into.
     * @return \Cake\Console\CommandCollection The updated collection.
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        $commands->add('upgrade', UpgradeCommand::class);
        $commands->add('upgrade rename', FileRenameCommand::class);
		$commands->add('upgrade files', FileUpgradeCommand::class);
		$commands->add('upgrade rector', RectorCommand::class);

        return $commands;
    }
}
