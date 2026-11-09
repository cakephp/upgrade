<?php

declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\QueryExpressionComparisonNames\Fixture;

use Cake\Database\Expression\QueryExpression;

/**
 * Custom expressions retain their overrides and parent calls after migration.
 */
class CustomExpression extends QueryExpression
{
    /**
     * Delegate equality to the expression builder.
     */
    public function eq($field, $value, ?string $type = null): static
    {
        return parent::eq($field, $value, $type);
    }

    /**
     * Use inherited comparisons on the custom expression.
     */
    public function published(): static
    {
        return $this->eq('published', true)->gt('views', 1000);
    }
}

-----
<?php

declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\QueryExpressionComparisonNames\Fixture;

use Cake\Database\Expression\QueryExpression;

/**
 * Custom expressions retain their overrides and parent calls after migration.
 */
class CustomExpression extends QueryExpression
{
    /**
     * Delegate equality to the expression builder.
     */
    public function equals($field, $value, ?string $type = null): static
    {
        return parent::equals($field, $value, $type);
    }

    /**
     * Use inherited comparisons on the custom expression.
     */
    public function published(): static
    {
        return $this->equals('published', true)->greaterThan('views', 1000);
    }
}
