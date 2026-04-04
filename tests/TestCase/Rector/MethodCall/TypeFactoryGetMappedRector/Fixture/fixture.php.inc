<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\TypeFactoryGetMappedRector\Fixture;

use Cake\Database\TypeFactory;

class SomeClass
{
    public function someMethod()
    {
        // Should transform: getMap with type argument
        $class = TypeFactory::getMap('datetime');

        // Should also transform with variable
        $type = 'string';
        $class = TypeFactory::getMap($type);

        return $class;
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\TypeFactoryGetMappedRector\Fixture;

use Cake\Database\TypeFactory;

class SomeClass
{
    public function someMethod()
    {
        // Should transform: getMap with type argument
        $class = TypeFactory::getMapped('datetime');

        // Should also transform with variable
        $type = 'string';
        $class = TypeFactory::getMapped($type);

        return $class;
    }
}

?>
