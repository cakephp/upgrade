<?php
declare(strict_types=1);

namespace TestApp\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class TestCommand extends Command
{
    public function execute()
    {
        $this->io->out('Hello World');
        $this->someMethod();
        return static::CODE_SUCCESS;
    }

    protected function someMethod(): void
    {
        $someArg = $this->args->getArgument('some');
        $this->io->warning('Warning');
    }
}
