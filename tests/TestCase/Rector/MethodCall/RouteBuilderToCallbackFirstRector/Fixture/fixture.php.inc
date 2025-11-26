<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RouteBuilderToCallbackFirstRector\Fixture;

use Cake\Routing\RouteBuilder;

class RoutesConfig
{
    public function __invoke(RouteBuilder $routes): void
    {
        // scope with params - should swap
        $routes->scope('/api', ['prefix' => 'Api'], function (RouteBuilder $routes): void {
            $routes->connect('/articles', ['controller' => 'Articles']);
        });

        // scope with empty array - should remove empty array
        $routes->scope('/admin', [], function (RouteBuilder $routes): void {
            $routes->connect('/dashboard', ['controller' => 'Dashboard']);
        });

        // prefix with params - should swap
        $routes->prefix('admin', ['_namePrefix' => 'admin:'], function (RouteBuilder $routes): void {
            $routes->connect('/users', ['controller' => 'Users']);
        });

        // prefix with empty array - should remove
        $routes->prefix('api', [], function (RouteBuilder $routes): void {
            $routes->connect('/status', ['controller' => 'Status']);
        });

        // plugin with params - should swap
        $routes->plugin('Blog', ['path' => '/blog'], function (RouteBuilder $routes): void {
            $routes->connect('/posts', ['controller' => 'Posts']);
        });

        // plugin with empty array - should remove
        $routes->plugin('Forum', [], function (RouteBuilder $routes): void {
            $routes->connect('/threads', ['controller' => 'Threads']);
        });

        // resources with options only (no callback) - should insert null
        $routes->resources('Articles', ['only' => 'index']);

        // resources with callback and options - should swap
        $routes->resources('Comments', ['only' => ['index', 'view']], function (RouteBuilder $routes): void {
            $routes->resources('Replies');
        });

        // resources with empty options and callback - should remove empty array
        $routes->resources('Tags', [], function (RouteBuilder $routes): void {
            $routes->connect('/popular', ['action' => 'popular']);
        });

        // Should NOT transform: already new style (callback second)
        $routes->scope('/new', function (RouteBuilder $routes): void {
            $routes->connect('/test', ['controller' => 'Test']);
        });

        // Should NOT transform: resources with just name
        $routes->resources('Users');
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RouteBuilderToCallbackFirstRector\Fixture;

use Cake\Routing\RouteBuilder;

class RoutesConfig
{
    public function __invoke(RouteBuilder $routes): void
    {
        // scope with params - should swap
        $routes->scope('/api', function (RouteBuilder $routes): void {
            $routes->connect('/articles', ['controller' => 'Articles']);
        }, ['prefix' => 'Api']);

        // scope with empty array - should remove empty array
        $routes->scope('/admin', function (RouteBuilder $routes): void {
            $routes->connect('/dashboard', ['controller' => 'Dashboard']);
        });

        // prefix with params - should swap
        $routes->prefix('admin', function (RouteBuilder $routes): void {
            $routes->connect('/users', ['controller' => 'Users']);
        }, ['_namePrefix' => 'admin:']);

        // prefix with empty array - should remove
        $routes->prefix('api', function (RouteBuilder $routes): void {
            $routes->connect('/status', ['controller' => 'Status']);
        });

        // plugin with params - should swap
        $routes->plugin('Blog', function (RouteBuilder $routes): void {
            $routes->connect('/posts', ['controller' => 'Posts']);
        }, ['path' => '/blog']);

        // plugin with empty array - should remove
        $routes->plugin('Forum', function (RouteBuilder $routes): void {
            $routes->connect('/threads', ['controller' => 'Threads']);
        });

        // resources with options only (no callback) - should insert null
        $routes->resources('Articles', null, ['only' => 'index']);

        // resources with callback and options - should swap
        $routes->resources('Comments', function (RouteBuilder $routes): void {
            $routes->resources('Replies');
        }, ['only' => ['index', 'view']]);

        // resources with empty options and callback - should remove empty array
        $routes->resources('Tags', function (RouteBuilder $routes): void {
            $routes->connect('/popular', ['action' => 'popular']);
        });

        // Should NOT transform: already new style (callback second)
        $routes->scope('/new', function (RouteBuilder $routes): void {
            $routes->connect('/test', ['controller' => 'Test']);
        });

        // Should NOT transform: resources with just name
        $routes->resources('Users');
    }
}

?>
