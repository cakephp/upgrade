<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\NewEntityToNewEmptyEntityRector\Fixture;

use Cake\ORM\Table;

class ArticlesTable extends Table
{
    public function createNew()
    {
        // This should be changed to newEmptyEntity()
        $entity = $this->newEntity();

        // This should NOT be changed (has arguments)
        $entity2 = $this->newEntity(['title' => 'Test']);

        return $entity;
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\NewEntityToNewEmptyEntityRector\Fixture;

use Cake\ORM\Table;

class ArticlesTable extends Table
{
    public function createNew()
    {
        // This should be changed to newEmptyEntity()
        $entity = $this->newEmptyEntity();

        // This should NOT be changed (has arguments)
        $entity2 = $this->newEntity(['title' => 'Test']);

        return $entity;
    }
}

?>
