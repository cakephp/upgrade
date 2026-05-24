<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRectorTest\Fixture;

use Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRector\Source\SomeModelType;

function addMethodCallArgs()
{
    $object = new SomeModelType();
    $object->getAttribute('paging');
    $object->getAttribute();
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRectorTest\Fixture;

use Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRector\Source\SomeModelType;

function addMethodCallArgs()
{
    $object = new SomeModelType();
    $object->getAttribute('paging', '2ndArg', 1, true);
    $object->getAttribute('2ndArg', 1, true);
}

?>
