<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ArrayToFluentCallRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\ArrayToFluentCallRector\Source;

function arrayToFluentCall()
{
    $factory = new Source\FactoryClass();

    $factory->buildClass('foo');

    $factory->buildClass('foo', []);

    $factory->buildClass('foo', ['baz' => 1]);
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ArrayToFluentCallRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\ArrayToFluentCallRector\Source;

function arrayToFluentCall()
{
    $factory = new Source\FactoryClass();

    $factory->buildClass('foo');

    $factory->buildClass('foo');

    $factory->buildClass('foo', ['baz' => 1]);
}

?>
