<?php
declare(strict_types=1);

namespace Cake\Upgrade\Command;

use Cake\Console\Arguments;
use Cake\Console\BaseCommand;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use SplFileInfo;

class LinterCommand extends BaseCommand
{
    protected static array $defaultDirectories = [
        'src/',
        'config/',
        'templates/',
        'tests/',
    ];

    /**
     * The name of this command.
     *
     * @var string
     */
    protected string $name = 'linter';

    /**
     * Get the command description.
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Check PHP files using PHP linter.';
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $dirs = [];
        foreach (static::$defaultDirectories as $dir) {
            $dirs[] = '`' . $dir . '`';
        }

        $help = 'The path (or comma separated paths) to the file or directory to check.';
        $help .= ' If not provided, defaults to checking common directories ' . implode(', ', $dirs) . '.';

        return parent::buildOptionParser($parser)
            ->addArgument('path', [
                'help' => $help,
                'default' => null,
                'required' => false,
            ])
            ->setDescription(static::getDescription());
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int The exit code
     */
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $directories = $args->getArgument('path') ?: static::$defaultDirectories;
        if (is_string($directories) && str_contains($directories, ',')) {
            $directories = explode(',', $directories);
        }

        $result = static::CODE_SUCCESS;
        foreach ((array)$directories as $directory) {
            if (!file_exists($directory)) {
                $io->warning('Not exists: ' . $directory . ' - skipping.');

                continue;
            }

            $io->out('Checking ' . (is_file($directory) ? 'file' : 'directory') . ': ' . $directory);
            if (is_file($directory)) {
                $phpFiles = [
                    new SplFileInfo($directory),
                ];
            } else {
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
                $phpFiles = new RegexIterator($files, '/\.php$/');
            }

            $fileCount = iterator_count($phpFiles);
            $io->helper('Progress')->init(['total' => $fileCount]);
            $io->out('', 0);

            /** @var \SplFileInfo $file */
            foreach ($phpFiles as $file) {
                $io->verbose('Checking ' . $file->getPathname());

                exec('php -l ' . escapeshellarg($file->getPathname()), $output, $returnVar);
                if ($returnVar !== 0) {
                    $io->err('Error in ' . $file->getPathname() . ': ' . implode("\n", $output));
                    $result = self::CODE_ERROR;

                    continue;
                }

                $io->helper('Progress')->increment();
                $io->helper('Progress')->draw();
            }

            $io->out('');
        }

        if ($result === self::CODE_SUCCESS) {
            $io->success('All files are valid.');
        } else {
            $io->error('Some files have errors. Please check the output above.');
        }

        return $result;
    }
}
