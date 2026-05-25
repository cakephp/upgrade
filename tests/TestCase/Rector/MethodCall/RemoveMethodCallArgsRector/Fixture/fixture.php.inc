<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RemoveMethodCallArgsRector\Fixture;

use Cake\Upgrade\Test\TestCase\Rector\MethodCall\RemoveMethodCallArgsRector\Source\SomeModelType;

function removeMethodCallArgs()
{
    $object = new SomeModelType();
    $object->getAttribute('paging');
    $object->setAttribute('paging', []);
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RemoveMethodCallArgsRector\Fixture;

use Cake\Upgrade\Test\TestCase\Rector\MethodCall\RemoveMethodCallArgsRector\Source\SomeModelType;

function removeMethodCallArgs()
{
    $object = new SomeModelType();
    $object->setAttribute('paging', []);
}

?>
