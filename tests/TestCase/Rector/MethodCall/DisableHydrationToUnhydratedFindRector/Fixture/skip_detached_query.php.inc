<?php
declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\DisableHydrationToUnhydratedFindRector\Fixture;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;

class DetachedQuery
{
    public function index(Table $articles, SelectQuery $query)
    {
        // The find() call is not part of this chain, so there is nothing to rename.
        $query->disableHydration();

        // Associations do not have an unhydratedFind() counterpart.
        $articles->getAssociation('Users')->find()->disableHydration();
    }
}

?>
