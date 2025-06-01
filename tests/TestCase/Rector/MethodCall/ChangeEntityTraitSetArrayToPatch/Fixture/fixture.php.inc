<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRectorTest\Fixture;

use Cake\ORM\Entity;
use Cake\Upgrade\Test\TestCase\Rector\MethodCall\ChangeEntityTraitSetArrayToPatch\Source\OtherClassWithSetMethod;

function mymethod()
{
    $object = new Entity();
    // This should be changed to patch
    $object->set(['paging' => 'test']);
    // This should not be changed
    $object->set('paging', 'test');

    $otherObject = new OtherClassWithSetMethod();
    // This should not be changed as well
    $otherObject->set(['paging' => 'test']);
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRectorTest\Fixture;

use Cake\ORM\Entity;
use Cake\Upgrade\Test\TestCase\Rector\MethodCall\ChangeEntityTraitSetArrayToPatch\Source\OtherClassWithSetMethod;

function mymethod()
{
    $object = new Entity();
    // This should be changed to patch
    $object->patch(['paging' => 'test']);
    // This should not be changed
    $object->set('paging', 'test');

    $otherObject = new OtherClassWithSetMethod();
    // This should not be changed as well
    $otherObject->set(['paging' => 'test']);
}

?>
