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
        $result = $entity->isEmpty('test');
        $entity->set('test', 'value'); // This should stay as is
        $entity->set('test', 'value', ['asOriginal' => 'true']); // This should stay as is
        $entity->set(['test' => 'value']); // This should be changed to patch
        $entity->set(['test' => 'value'], ['asOriginal' => 'true']); // This should be changed to patch

        $table = $this->fetchTable('Articles');
        $expr = $table->find()->newExpr();
    }

    public function findSomething(Query $query, array $options): Query {
        return $query;
    }

    public function routes(): void
    {
        $routes = new RouteBuilder(new RouteCollection(), '/');

        $routes->scope('/api', function ($routes): void {});
        $routes->scope('/api', ['something'], function ($routes): void {});

        $routes->resources('/api', function ($routes): void {});
        $routes->resources('/api', ['something'], function ($routes): void {});

        $routes->prefix('/api', function ($routes): void {});
        $routes->prefix('/api', ['something'], function ($routes): void {});

        $routes->plugin('/api', function ($routes): void {});
        $routes->plugin('/api', ['something'], function ($routes): void {});
    }
}
