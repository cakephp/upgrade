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
use Cake\Console\BaseCommand;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Entry point into the upgrade process
 */
class UpgradeCommand extends BaseCommand
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
        $path = realpath($path);
        $paths = [
            'src' => $path . '/src',
            'tests' => $path . '/tests',
            'config' => $path . '/config',
            'tempates' => $path . '/templates',
        ];
        $withDryRun = function (array $params) use ($args): array {
            if ($args->getOption('dry-run')) {
                array_unshift($params, '--dry-run');

                return $params;
            }

            return $params;
        };

        $io->out('<info>Moving templates</info>');
        $this->executeCommand(FileRenameCommand::class, $withDryRun(['templates', $path]), $io);

        $io->out('<info>Moving locale files</info>');
        $this->executeCommand(FileRenameCommand::class, $withDryRun(['locales', $path]), $io);

        $io->out('<info>Applying cakephp40 Rector rules</info>');
        foreach ($paths as $directory) {
            if (!is_dir($directory)) {
                $io->warning("{$directory} does not exist, skipping.");
                continue;
            }
            $this->executeCommand(RectorCommand::class, $withDryRun(['--rules', 'cakephp40', $directory]), $io);
        }
        $io->out('<info>Applying phpunit80 Rector rules</info>');
        $this->executeCommand(RectorCommand::class, $withDryRun(['--rules', 'phpunit80', $paths['tests']]), $io);

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
                '<question>Upgrade tool for CakePHP 4.0</question>',
                '',
                'Runs all of the sub commands on an application/plugin. The <info>path</info> ' .
                'argument should be the application or plugin root directory.',
                '',
                'You can also run each command individually on specific directories if you want more control.',
                '',
                '<info>Sub-Commands</info>',
                '',
                '- file_rename  Rename template and locale files',
                '- rector       Apply rector rules for phpunit80 and cakephp40',
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
