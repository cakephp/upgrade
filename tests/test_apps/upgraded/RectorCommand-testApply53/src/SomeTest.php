<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\Database\TypeFactory;
use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\View\Helper\BreadcrumbsHelper;

class SomeTest
{
    use LocatorAwareTrait;

    private BreadcrumbsHelper $Breadcrumbs;

    public function testRenames(): void
    {
        $entity = new Entity();
        $result = !$entity->hasValue('test');
        $entity->set('test', 'value'); // This should stay as is
        $entity->set('test', 'value', ['asOriginal' => 'true']); // This should stay as is
        $entity->patch(['test' => 'value']); // This should be changed to patch
        $entity->patch(['test' => 'value'], ['asOriginal' => 'true']); // This should be changed to patch

        $table = $this->fetchTable('Articles');
        $expr = $table->find()->expr();

        // BreadcrumbsHelper::add(array) should be changed to addMany()
        $this->Breadcrumbs->addMany([
            ['title' => 'Home', 'url' => '/'],
        ]);
        // Single crumb should stay as is
        $this->Breadcrumbs->add('Articles', '/articles');

        // TypeFactory::getMap($type) should be changed to getMapped($type)
        $class = TypeFactory::getMapped('datetime');
        $allTypes = TypeFactory::getMap(); // This should stay as is
    }

    public function findSomething(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery {
        return $query;
    }
}
