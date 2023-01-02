<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ModalToGetSetRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\ModalToGetSetRector\Source;

function modalToGetSet3()
{
    $object = new Source\SomeModelType;

    $config = $object->method();
    $config = $object->method('key');
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ModalToGetSetRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\ModalToGetSetRector\Source;

function modalToGetSet3()
{
    $object = new Source\SomeModelType;

    $config = $object->getMethod();
    $config = $object->setMethod('key');
}

?>
