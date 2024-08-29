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
            }

            return $params;
        };

        $io->out('<info>Applying cakephp50 Rector rules</info>');
        $this->mapCommand($io, $paths, fn ($directory) => $this->executeCommand(
            RectorCommand::class,
            $withDryRun(['--rules', 'cakephp50', $directory]),
            $io
        ));

        $io->out('<info>Applying cakephp51 Rector rules</info>');
        $this->mapCommand($io, $paths, fn ($directory) => $this->executeCommand(
            RectorCommand::class,
            $withDryRun(['--rules', 'cakephp51', $directory]),
            $io
        ));

        $io->out('Next upgrade your <info>composer.json</info>.');
        $version = '5.0';
        $io->out("Run <info>composer requires -W 'cakephp/cakephp:^{$version}'</info>.");

        return static::CODE_SUCCESS;
    }

    /**
     * Map a command over a list of paths.
     *
     * Useful for invoking sub-commands like rector.
     *
     * @param \Cake\Console\ConsoleIo $io The io
     * @param array $paths List of path strings to enumerate
     * @param callable $fn The function to invoke for each directory
     * @return void
     */
    protected function mapCommand(ConsoleIo $io, array $paths, callable $fn): void
    {
        foreach ($paths as $directory) {
            if (!is_dir($directory)) {
                $io->warning("{$directory} does not exist, skipping.");
                continue;
            }
            $fn($directory);
        }
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
                '<question>Upgrade tool for CakePHP 5.x</question>',
                '',
                'Runs all of the sub commands on an application/plugin. The <info>path</info> ' .
                'argument should be the application or plugin root directory.',
                '',
                'You can also run each command individually on specific directories if you want more control.',
                '',
                '<info>Sub-Commands</info>',
                '',
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
