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
namespace Cake\Upgrade\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Entry point into the upgrade process
 */
class UpgradeCommand extends Command
{
    /**
     * Execute.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $path = (string)$args->getArgument('path');

        $io->out('<info>Moving templates</info>');
        $this->executeCommand(FileRenameCommand::class, ['templates', '--path', $path], $io);

        // $io->out('<info>Moving locale files</info>');
        // $this->executeCommand(FileRenameCommand::class, ['locales', '--path', $path], $io);

        return static::CODE_SUCCESS;
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to build
     * @return \Cake\Console\ConsoleOptionParser
     */
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->setDescription([
                'Upgrade tool for CakePHP 4.0',
                '',
                'Runs all of the sub commands. You can also run each command individually.',
                '',
                'Sub-Commands',
                '------------',
                '',
                '- file_rename - Rename template and locale files',
            ])
            ->addArgument('path', [
                'help' => 'The path to the application or plugin.',
                'required' => true,
            ])
            ->addOption('plugin', [
                'help' => 'Indicate that path is a plugin.',
                'boolean' => true,
            ])
            ->addOption('dry-run', [
                'help' => 'Dry run.',
                'boolean' => true,
            ]);

        return $parser;
    }
}
