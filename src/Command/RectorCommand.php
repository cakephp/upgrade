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
use Symfony\Component\Process\Process;

/**
 * Runs rector rulesets against the provided path.
 */
class RectorCommand extends BaseCommand
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
        if (!file_exists($path)) {
            $io->error("The provided path `{$path}` does not exist.");

            return static::CODE_ERROR;
        }

        $autoload = $args->getOption('autoload');
        if (empty($autoload)) {
            $autoload = $this->detectAutoload($io, $path);
        }
        if ($autoload === null) {
            $io->error('No autoload file could be found. Use the `--autoload` flag to provide a path.');

            return static::CODE_ERROR;
        }
        if (!file_exists($autoload)) {
            $io->error("The autoload file `{$autoload}` does not exist. Use the `--autoload` flag to provide a path.");

            return static::CODE_ERROR;
        }

        $result = $this->runRector($io, $args, $autoload);
        if ($result === false) {
            $io->error('Something went wrong while running rector. Ensure that `php` is on your PATH.');

            return static::CODE_ERROR;
        }
        $io->success('🎉 Upgrade complete! 🎉');

        return static::CODE_SUCCESS;
    }

    /**
     * Run rector as a sub-process.
     *
     * @param \Cake\Console\ConsoleIo $io The io object to output with
     * @param \Cake\Console\Arguments $args The Arguments object
     * @param string $autoload The autoload file path.
     * @return bool
     */
    protected function runRector(ConsoleIo $io, Arguments $args, string $autoload): bool
    {
        $config = ROOT . '/config/rector/' . basename((string)$args->getOption('rules')) . '.php';
        $path = realpath((string)$args->getArgument('path'));

        $cmdPath = ROOT . '/vendor/bin/rector process';
        $command = sprintf(
            '%s %s %s --autoload-file=%s --config=%s %s --clear-cache',
            $cmdPath,
            $args->getOption('dry-run') ? '--dry-run' : '',
            $args->getOption('verbose') ? '-vvvv' : '',
            escapeshellarg($autoload),
            escapeshellarg($config),
            escapeshellarg($path),
        );
        $io->verbose("Running <info>{$command}</info>");

        $io->info('Starting rector at ' . date('Y-m-d H:i:s'));

        $process = Process::fromShellCommandline($command);
        $process->setEnv($_ENV);
        $process->setTimeout(null);
        $process->start();

        foreach ($process as $type => $data) {
            if ($type === Process::OUT) {
                $io->out($data);
            } elseif ($type === Process::ERR) {
                $io->err($data);
            }
        }

        if (!$process->isSuccessful()) {
            $io->error('Something went wrong while running rector.');

            return false;
        }

        $io->info('Rector completed successfully at ' . date('Y-m-d H:i:s'));

        return true;
    }

    /**
     * Find the application autoload file by traversing up the file system
     * looking for a `vendor/autoload.php` file.
     *
     * @param \Cake\Console\ConsoleIo $io The io object
     * @param string $path The path to start scanning from
     * @return string|null The path to a vendor/autoload.php or null
     */
    protected function detectAutoload(ConsoleIo $io, string $path): ?string
    {
        $path = realpath($path);
        $io->verbose("Detecting autoload file for {$path}");
        $segments = explode(DIRECTORY_SEPARATOR, $path);
        while (true) {
            if (count($segments) === 0) {
                break;
            }
            $check = implode(DIRECTORY_SEPARATOR, $segments) . '/vendor/autoload.php';
            $io->verbose("-> Checking {$check}");

            if (file_exists($check)) {
                $io->verbose("-> Found {$check}");

                return $check;
            }
            array_pop($segments);
        }

        return null;
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
                'Apply rector refactoring rules',
                '',
                'Run rector rules against `path`. By default the <info>cakephp50</info> ' .
                'rules are run.',
                '',
                'You will want to run the <info>cakephp50</info> rules multiple times on ' .
                'subdirectories of your application:',
                '',
                '<info>bin/cake upgrade rector ~/app/src</info>',
                '<info>bin/cake upgrade rector ~/app/config</info>',
                '<info>bin/cake upgrade rector ~/app/templates</info>',
                '<info>bin/cake upgrade rector ~/app/tests</info>',
            ])
            ->addArgument('path', [
                'help' => 'The path to the application or plugin.',
                'required' => true,
            ])
            ->addOption('rules', [
                'help' => 'The rector ruleset to run',
                'default' => 'cakephp50',
            ])
            ->addOption('autoload', [
                'help' => 'The path to the application/plugin autoload if one cannot be auto-detected, ' .
                    'or is detected incorrectly.',
            ])
            ->addOption('dry-run', [
                'help' => 'Enable to get a preview of what modifications will be applied.',
                'boolean' => true,
            ]);

        return $parser;
    }
}
