<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRectorTest\Fixture;

use Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRector\Source\SomeModelType;

function addMethodCallArgs()
{
    $object = new SomeModelType();
    $object->getAttribute('paging');
    $object->setAttribute('paging', []);
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRectorTest\Fixture;

use Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRector\Source\SomeModelType;

function addMethodCallArgs()
{
    $object = new SomeModelType();
    $object->setAttribute('paging', []);
}

?>
