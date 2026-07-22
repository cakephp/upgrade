<?php
declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\DisableHydrationToUnhydratedFindRector\Fixture;

class NotATable
{
    public function find()
    {
        return $this;
    }

    public function disableHydration()
    {
        return $this;
    }
}

class MyRepository
{
    public function index(NotATable $repository)
    {
        $repository->find()->disableHydration();
    }
}

?>
