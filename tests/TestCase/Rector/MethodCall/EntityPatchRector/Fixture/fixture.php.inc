<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\EntityPatchRector\Fixture;

use Cake\ORM\Entity;

class MyEntity extends Entity
{
}

class UserController
{
    public function edit()
    {
        $entity = new MyEntity();

        // Should transform: Entity->set with array literal
        $entity->set(['name' => 'Test', 'email' => 'test@example.com']);

        // Should NOT transform: set with variable
        $data = ['name' => 'Test'];
        $entity->set($data);

        // Should NOT transform: set with non-array argument
        $entity->set('name', 'Test');

        return $entity;
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\EntityPatchRector\Fixture;

use Cake\ORM\Entity;

class MyEntity extends Entity
{
}

class UserController
{
    public function edit()
    {
        $entity = new MyEntity();

        // Should transform: Entity->set with array literal
        $entity->patch(['name' => 'Test', 'email' => 'test@example.com']);

        // Should NOT transform: set with variable
        $data = ['name' => 'Test'];
        $entity->set($data);

        // Should NOT transform: set with non-array argument
        $entity->set('name', 'Test');

        return $entity;
    }
}

?>
