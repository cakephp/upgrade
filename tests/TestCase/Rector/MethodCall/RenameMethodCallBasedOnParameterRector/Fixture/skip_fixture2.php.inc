<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RenameMethodCallBasedOnParameterRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\RenameMethodCallBasedOnParameterRector\Source;

function renameMethodCallBasedOnParameterNoop()
{
    $object = new Source\SomeModelType;

    $config = $object->getParam($value);
    $config = $object->getParam('other');
    $object->withParam('other', 'value');
}
