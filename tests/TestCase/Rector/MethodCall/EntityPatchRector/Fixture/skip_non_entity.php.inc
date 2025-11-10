<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\EntityPatchRector\Fixture;

class SomeOtherClass
{
    public function set(array $data): void
    {
        // Do something
    }
}

class MyController
{
    public function process()
    {
        $object = new SomeOtherClass();

        // Should NOT transform: not an Entity
        $object->set(['key' => 'value']);

        // Should NOT transform: unknown object type
        $someObject->set(['data' => 'test']);

        return $object;
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\EntityPatchRector\Fixture;

class SomeOtherClass
{
    public function set(array $data): void
    {
        // Do something
    }
}

class MyController
{
    public function process()
    {
        $object = new SomeOtherClass();

        // Should NOT transform: not an Entity
        $object->set(['key' => 'value']);

        // Should NOT transform: unknown object type
        $someObject->set(['data' => 'test']);

        return $object;
    }
}

?>
