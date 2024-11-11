<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\Console\Arguments;

class SomeTest extends TestCase
{
    public function testRenames(): void
    {
        $args = new Arguments([], ['a' => [1, 2]], []);
        $option = $args->getArrayOption('a');
    }
}
