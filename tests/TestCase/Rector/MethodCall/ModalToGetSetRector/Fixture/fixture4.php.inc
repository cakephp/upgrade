<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ModalToGetSetRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\ModalToGetSetRector\Source;

function modalToGetSet4()
{
    $object = new Source\SomeModelType;

    $object->makeEntity();
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ModalToGetSetRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\ModalToGetSetRector\Source;

function modalToGetSet4()
{
    $object = new Source\SomeModelType;

    $object->createEntity();
}

?>
