<?php
declare(strict_types=1);

namespace TestApp\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class TestCommand extends Command
{
    public function execute(Arguments $args, \Cake\Console\ConsoleIoInterface $io)
    {
        $io->out('Hello World');

        return static::CODE_SUCCESS;
    }
}
