<?php

declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\QueryExpressionComparisonNames\Fixture;

/**
 * Unrelated APIs keep their own short method names.
 */
class OtherExpression
{
    /**
     * Compare an unrelated value.
     */
    public function eq(string $field, mixed $value): self
    {
        return $this;
    }
}

/**
 * Leave unrelated and untyped receivers unchanged.
 */
function unrelated(OtherExpression $expression, $unknown): void
{
    $expression->eq('value', 1);
    $unknown->gt('value', 2);
}
