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
        $result = $entity->isEmpty('test');

        $table = $this->fetchTable('Articles');
        $expr = $table->find()->newExpr();
    }

    public function findSomething(Query $query, array $options): Query {
        return $query;
    }
}
