<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\Console\Arguments;
use Cake\ORM\Entity;

class SomeTest extends TestCase
{
    public function testRenames(): void
    {
        $args = new Arguments([], ['a' => [1, 2]], []);
        $option = $args->getArrayOption('a');

        $entity = new Entity();
        // This should be changed to patch
        $entity->patch(['paging' => 'test']);
        // This should not be changed
        $entity->set('paging', 'test');
    }
}
