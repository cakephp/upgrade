<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class ExampleCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        return self::CODE_SUCCESS;
    }
}
