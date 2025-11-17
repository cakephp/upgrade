<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRectorTest\Fixture;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class TestCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Hello World');
        $this->someMethod($args, $io);
        return static::CODE_SUCCESS;
    }

    protected function someMethod(Arguments $args, ConsoleIo $io): void
    {
        $someArg = $args->getArgument('some');
        $io->warning('Warning');
    }
}
?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRectorTest\Fixture;

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
?>
