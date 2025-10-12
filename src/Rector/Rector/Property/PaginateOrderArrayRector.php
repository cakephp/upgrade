<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds 'asc' direction to paginate order arrays when only column name is provided.
 *
 * @see https://github.com/cakephp/upgrade/issues/143
 */
final class PaginateOrderArrayRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add \'asc\' direction to paginate order arrays when only column name is provided',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$this->paginate = [
    'order' => ['Articles.title'],
];
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$this->paginate = [
    'order' => ['Articles.title' => 'asc'],
];
CODE_SAMPLE,
                ),
            ],
        );
    }

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        $expr = $node->expr;
        if (!$expr instanceof Assign) {
            return null;
        }

        /** @var \PhpParser\Node\Expr\Assign $assign */
        $assign = $expr;

        // Check if this is $this->paginate assignment
        if (!$assign->var instanceof PropertyFetch) {
            return null;
        }

        if (!$this->isName($assign->var->name, 'paginate')) {
            return null;
        }

        if (!$this->isObjectType($assign->var->var, new ObjectType('Cake\Controller\Controller'))) {
            return null;
        }

        // Check if the value is an array
        // @phpstan-ignore-next-line property.notFound (PHPParser uses magic properties)
        if (!$assign->value instanceof Array_) {
            return null;
        }

        $assignValue = $assign->value;

        $hasChanges = false;

        // Look for 'order' key in the array
        foreach ($assignValue->items as $item) {
            if (!$item->key instanceof String_) {
                continue;
            }

            if ($item->key->value !== 'order') {
                continue;
            }

            // Check if the order value is an array
            if (!$item->value instanceof Array_) {
                continue;
            }

            // Transform the order array items
            foreach ($item->value->items as $orderItem) {
                // If the item has a key (like ['Column' => 'asc']), skip it
                if ($orderItem->key !== null) {
                    continue;
                }

                // If the item is a simple string value (like ['Column']), convert it
                if ($orderItem->value instanceof String_) {
                    $columnName = $orderItem->value->value;
                    $orderItem->key = new String_($columnName);
                    $orderItem->value = new String_('asc');
                    $hasChanges = true;
                }
            }
        }

        return $hasChanges ? $node : null;
    }
}
