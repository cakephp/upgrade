<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\NewExprToFuncRector\Fixture;

use Cake\ORM\Query;
use Cake\ORM\Table;

class UsersTable extends Table
{
    public function getStats(Query $query)
    {
        // Aggregate functions that should be transformed
        $count = $query->newExpr()->count();
        $countField = $query->newExpr()->count('id');
        $sum = $query->newExpr()->sum('total');
        $avg = $query->newExpr()->avg('score');
        $min = $query->newExpr()->min('price');
        $max = $query->newExpr()->max('price');

        return compact('count', 'sum', 'avg', 'min', 'max');
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\NewExprToFuncRector\Fixture;

use Cake\ORM\Query;
use Cake\ORM\Table;

class UsersTable extends Table
{
    public function getStats(Query $query)
    {
        // Aggregate functions that should be transformed
        $count = $query->func()->count('*');
        $countField = $query->func()->count('id');
        $sum = $query->func()->sum('total');
        $avg = $query->func()->avg('score');
        $min = $query->func()->min('price');
        $max = $query->func()->max('price');

        return compact('count', 'sum', 'avg', 'min', 'max');
    }
}

?>
