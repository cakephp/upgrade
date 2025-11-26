<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RouteBuilderToCallbackFirstRector\Fixture;

class SomeOtherClass
{
    public function scope(string $path, array $options, callable $callback): void
    {
    }

    public function prefix(string $name, array $options, callable $callback): void
    {
    }
}

class SomeController
{
    public function test(): void
    {
        // Should NOT transform: not a RouteBuilder
        $other = new SomeOtherClass();
        $other->scope('/path', ['key' => 'value'], function () {
        });
        $other->prefix('admin', [], function () {
        });
    }
}

?>
