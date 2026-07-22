<?php
declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\DisableHydrationToUnhydratedFindRector\Fixture;

use Cake\ORM\Table;

class MyRepository
{
    public function index(Table $articles)
    {
        $articles->find()->disableHydration();
        $articles->find('all')->disableHydration();
        $articles->find()->where(['id' => 1])->contain(['Users'])->disableHydration();
        $articles->find()->disableHydration()->toArray();
    }
}

?>
-----
<?php
declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\DisableHydrationToUnhydratedFindRector\Fixture;

use Cake\ORM\Table;

class MyRepository
{
    public function index(Table $articles)
    {
        $articles->unhydratedFind();
        $articles->unhydratedFind('all');
        $articles->unhydratedFind()->where(['id' => 1])->contain(['Users']);
        $articles->unhydratedFind()->toArray();
    }
}

?>
