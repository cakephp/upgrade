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
        $path = rtrim((string)$args->getArgument('path'), DIRECTORY_SEPARATOR);
        $pathParts = explode(DIRECTORY_SEPARATOR, $path);
        $srcPath = $testsPath = $path;
        if ($pathParts[-1] !== 'src') {
            $srcPath .= DIRECTORY_SEPARATOR . 'src';
        }
        if ($pathParts[-1] !== 'tests') {
            $testsPath .= DIRECTORY_SEPARATOR . 'tests';
        }

        $io->out('<info>Moving templates</info>');
        $this->executeCommand(FileRenameCommand::class, ['templates', $path], $io);

        $io->out('<info>Moving locale files</info>');
        $this->executeCommand(FileRenameCommand::class, ['locales', $path], $io);

        $io->out('<info>Applying cakephp40 Rector rules</info>');
        $this->executeCommand(RectorCommand::class, ['--rules', 'cakephp40', $srcPath], $io);
        $this->executeCommand(RectorCommand::class, ['--rules', 'cakephp40', $testsPath], $io);

        $io->out('<info>Applying phpunit80 Rector rules</info>');
        $this->executeCommand(RectorCommand::class, ['--rules', 'phpunit80', $testsPath], $io);

        $io->out('Next upgrade your <info>composer.json</info>.');

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
                '<info>Sub-Commands</info>',
                '',
                '- file_rename - Rename template and locale files',
                '- rector      - Apply rector refactoring rules for phpunit80 and cakephp40',
            ])
            ->addArgument('path', [
                'help' => 'The path to the application or plugin.',
                'required' => true,
            ])
            ->addOption('dry-run', [
                'help' => 'Dry run.',
                'boolean' => true,
            ]);

        return $parser;
    }
}
