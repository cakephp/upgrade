<?php
declare(strict_types=1);

namespace OldApp\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\BaseCommand;


class HelloCommand extends BaseCommand
{
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $io->styles('green', ['background' => 'green']);
    }
}
