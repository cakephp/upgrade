<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\NewExprToFuncRector\Fixture;

use Cake\Database\Query;

class QueryBuilder
{
    public function buildConditions(Query $query)
    {
        // Should NOT transform: newExpr() with expression methods (not func methods)
        $expr1 = $query->newExpr()->eq('status', 1);
        $expr2 = $query->newExpr()->gt('price', 100);
        $expr3 = $query->newExpr()->and(['status' => 1, 'active' => true]);

        // Should NOT transform: standalone newExpr() call
        $expr4 = $query->newExpr();

        // Should NOT transform: non-Query object
        $someObject->newExpr()->count();

        return $query;
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\NewExprToFuncRector\Fixture;

use Cake\Database\Query;

class QueryBuilder
{
    public function buildConditions(Query $query)
    {
        // Should NOT transform: newExpr() with expression methods (not func methods)
        $expr1 = $query->newExpr()->eq('status', 1);
        $expr2 = $query->newExpr()->gt('price', 100);
        $expr3 = $query->newExpr()->and(['status' => 1, 'active' => true]);

        // Should NOT transform: standalone newExpr() call
        $expr4 = $query->newExpr();

        // Should NOT transform: non-Query object
        $someObject->newExpr()->count();

        return $query;
    }
}

?>
