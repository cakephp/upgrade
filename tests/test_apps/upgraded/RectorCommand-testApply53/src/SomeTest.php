<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\Routing\RouteBuilder;
use Cake\Routing\RouteCollection;

class SomeTest
{
    use LocatorAwareTrait;

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
    }

    public function findSomething(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery {
        return $query;
    }

    public function routes(): void
    {
        $routes = new RouteBuilder(new RouteCollection(), '/');

        $routes->scope('/api', params: [], callback: function ($routes): void {});
        $routes->scope('/api', params: ['something'], callback: function ($routes): void {});

        $routes->resources('/api', callback: function ($routes): void {});
        $routes->resources('/api', options: ['something'], callback: function ($routes): void {});

        $routes->prefix('/api', callback: function ($routes): void {});
        $routes->prefix('/api', params: ['something'], callback: function ($routes): void {});

        $routes->plugin('/api', callback: function ($routes): void {});
        $routes->plugin('/api', options: ['something'], callback: function ($routes): void {});
    }
}
