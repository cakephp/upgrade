<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\ORM\Entity;

class SomeTest
{
    public function testRenames(): void
    {
        $entity = new Entity();
        $result = $entity->isEmpty('test');
    }
}
