<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RouteBuilderToCallbackFirstRector\Fixture;

use Cake\Routing\RouteBuilder;

class Fixture
{
    public function run(RouteBuilder $routes)
    {
        // Should be transformed - scope with params and callback (3 args)
        $routes->scope('/api', ['prefix' => 'Api'], function ($routes) {
            $routes->resources('Articles');
        });

        // Should be transformed - prefix with empty array and callback
        $routes->prefix('admin', [], function ($routes) {
            $routes->connect('/', ['controller' => 'Dashboard']);
        });

        // Should be transformed - plugin with params
        $routes->plugin('Blog', ['path' => '/blog'], function ($routes) {
            $routes->resources('Posts');
        });

        // Should be transformed - resources with only options (no callback)
        $routes->resources('Articles', ['only' => 'index']);

        // Should be transformed - resources with options and callback (3 args)
        $routes->resources('Posts', ['only' => ['index', 'view']], function ($routes) {
            $routes->resources('Comments');
        });

        // Should be transformed - resources with empty options and callback
        $routes->resources('Tags', [], function ($routes) {
            $routes->connect('/popular', ['action' => 'popular']);
        });

        // Should NOT be transformed - scope with only callback (2 args, new style)
        $routes->scope('/v2', function ($routes) {
            $routes->resources('Users');
        });

        // Should NOT be transformed - prefix with only callback
        $routes->prefix('api', function ($routes) {
            $routes->connect('/', []);
        });

        // Should NOT be transformed - resources with callback (3 args, but callback is second)
        $routes->resources('Comments', function ($routes) {
            $routes->resources('Replies');
        });
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RouteBuilderToCallbackFirstRector\Fixture;

use Cake\Routing\RouteBuilder;

class Fixture
{
    public function run(RouteBuilder $routes)
    {
        // Should be transformed - scope with params and callback (3 args)
        $routes->scope('/api', function ($routes) {
            $routes->resources('Articles');
        }, ['prefix' => 'Api']);

        // Should be transformed - prefix with empty array and callback
        $routes->prefix('admin', function ($routes) {
            $routes->connect('/', ['controller' => 'Dashboard']);
        });

        // Should be transformed - plugin with params
        $routes->plugin('Blog', function ($routes) {
            $routes->resources('Posts');
        }, ['path' => '/blog']);

        // Should be transformed - resources with only options (no callback)
        $routes->resources('Articles', null, ['only' => 'index']);

        // Should be transformed - resources with options and callback (3 args)
        $routes->resources('Posts', function ($routes) {
            $routes->resources('Comments');
        }, ['only' => ['index', 'view']]);

        // Should be transformed - resources with empty options and callback
        $routes->resources('Tags', function ($routes) {
            $routes->connect('/popular', ['action' => 'popular']);
        });

        // Should NOT be transformed - scope with only callback (2 args, new style)
        $routes->scope('/v2', function ($routes) {
            $routes->resources('Users');
        });

        // Should NOT be transformed - prefix with only callback
        $routes->prefix('api', function ($routes) {
            $routes->connect('/', []);
        });

        // Should NOT be transformed - resources with callback (3 args, but callback is second)
        $routes->resources('Comments', function ($routes) {
            $routes->resources('Replies');
        });
    }
}

?>
