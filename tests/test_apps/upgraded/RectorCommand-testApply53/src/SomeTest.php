<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

class SomeTest
{
    use LocatorAwareTrait;

    public function testRenames(): void
    {
        $entity = new Entity();
        $result = !$entity->hasValue('test');

        $table = $this->fetchTable('Articles');
        $expr = $table->find()->expr();
    }

    public function findSomething(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery {
        return $query;
    }
}
