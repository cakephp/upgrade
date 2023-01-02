<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RenameMethodCallBasedOnParameterRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\RenameMethodCallBasedOnParameterRector\Source;

function renameMethodCallBasedOnParameter()
{
    $object = new Source\SomeModelType;

    $config = $object->getParam('paging');
    $object->withParam('paging', 'value');
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\RenameMethodCallBasedOnParameterRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\RenameMethodCallBasedOnParameterRector\Source;

function renameMethodCallBasedOnParameter()
{
    $object = new Source\SomeModelType;

    $config = $object->getAttribute('paging');
    $object->withAttribute('paging', 'value');
}

?>
