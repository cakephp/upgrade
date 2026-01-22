<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\Database\TypeFactory;
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
        $entity->set('test', 'value'); // This should stay as is
        $entity->set('test', 'value', ['asOriginal' => 'true']); // This should stay as is
        $entity->set(['test' => 'value']); // This should be changed to patch
        $entity->set(['test' => 'value'], ['asOriginal' => 'true']); // This should be changed to patch

        $table = $this->fetchTable('Articles');
        $expr = $table->find()->newExpr();

        // TypeFactory::getMap($type) should be changed to getMapped($type)
        $class = TypeFactory::getMap('datetime');
        $allTypes = TypeFactory::getMap(); // This should stay as is
    }

    public function findSomething(Query $query, array $options): Query {
        return $query;
    }
}
