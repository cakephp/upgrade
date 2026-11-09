<?php

declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\QueryExpressionComparisonNames\Fixture;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query\SelectQuery;

/**
 * Build chained comparisons with positional and named arguments.
 */
function comparisons(QueryExpression $expression, SelectQuery $query): void
{
    $expression->eq('id', 1, 'integer')
        ->notEq('status', 'deleted')
        ->gt('views', 1000)
        ->gte('rating', 4)
        ->lt('price', 50)
        ->lte('stock', 10);
    $expression->eq(value: 2, field: 'id', type: 'integer');
    $query->where(fn (QueryExpression $exp) => $exp->gt('views', 1000));
}

/**
 * Migrate a nullable expression and a first-class method callable.
 */
function nullableComparison(?QueryExpression $expression): void
{
    $expression?->gte('views', 1000);
    $compare = $expression === null ? null : $expression->lt(...);
}

-----
<?php

declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\QueryExpressionComparisonNames\Fixture;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query\SelectQuery;

/**
 * Build chained comparisons with positional and named arguments.
 */
function comparisons(QueryExpression $expression, SelectQuery $query): void
{
    $expression->equals('id', 1, 'integer')
        ->notEquals('status', 'deleted')
        ->greaterThan('views', 1000)
        ->greaterThanOrEquals('rating', 4)
        ->lessThan('price', 50)
        ->lessThanOrEquals('stock', 10);
    $expression->equals(value: 2, field: 'id', type: 'integer');
    $query->where(fn (QueryExpression $exp) => $exp->greaterThan('views', 1000));
}

/**
 * Migrate a nullable expression and a first-class method callable.
 */
function nullableComparison(?QueryExpression $expression): void
{
    $expression?->greaterThanOrEquals('views', 1000);
    $compare = $expression === null ? null : $expression->lessThan(...);
}
