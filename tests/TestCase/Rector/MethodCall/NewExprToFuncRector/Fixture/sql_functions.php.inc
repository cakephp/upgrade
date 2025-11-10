<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\NewExprToFuncRector\Fixture;

use Cake\Database\Query;

class ReportGenerator
{
    public function buildQuery(Query $query)
    {
        // Date functions
        $now = $query->newExpr()->now();
        $diff = $query->newExpr()->dateDiff('created', 'modified');
        $extract = $query->newExpr()->extract('year', 'created');

        // Other SQL functions
        $concat = $query->newExpr()->concat(['first_name', 'last_name']);
        $coalesce = $query->newExpr()->coalesce(['email', 'backup_email']);
        $rand = $query->newExpr()->rand();

        return $query;
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\NewExprToFuncRector\Fixture;

use Cake\Database\Query;

class ReportGenerator
{
    public function buildQuery(Query $query)
    {
        // Date functions
        $now = $query->func()->now();
        $diff = $query->func()->dateDiff('created', 'modified');
        $extract = $query->func()->extract('year', 'created');

        // Other SQL functions
        $concat = $query->func()->concat(['first_name', 'last_name']);
        $coalesce = $query->func()->coalesce(['email', 'backup_email']);
        $rand = $query->func()->rand();

        return $query;
    }
}

?>
