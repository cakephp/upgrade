<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRectorTest\Fixture;

use Cake\ORM\Entity;

function mymethod()
{
    $object = new Entity();
    // This should be changed to patch
    $object->set(['paging' => 'test']);
    // This should not be changed
    $object->set('paging', 'test');
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRectorTest\Fixture;

use Cake\ORM\Entity;

function mymethod()
{
    $object = new Entity();
    // This should be changed to patch
    $object->patch(['paging' => 'test']);
    // This should not be changed
    $object->set('paging', 'test');
}

?>
