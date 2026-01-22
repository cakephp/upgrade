<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\TypeFactoryGetMappedRector\Fixture;

use Cake\Database\TypeFactory;

class SomeClass
{
    public function someMethod()
    {
        // Should NOT transform: getMap without argument (returns full map)
        $map = TypeFactory::getMap();

        return $map;
    }
}

?>
