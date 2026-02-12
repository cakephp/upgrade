<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake6;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Transforms binary column definitions for CakePHP 6.0 TYPE_VARBINARY support.
 *
 * In CakePHP 6.0, a new TYPE_VARBINARY was added for variable-length binary columns.
 * The 'fixed' attribute is no longer used - instead use 'binary' for fixed-length
 * and 'varbinary' for variable-length binary columns.
 *
 * @see https://github.com/cakephp/cakephp/pull/19258
 */
final class BinaryColumnToVarbinaryRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change binary column with fixed attribute to use varbinary type',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
// Migration file
$table->addColumn('hash', 'binary', ['length' => 20, 'fixed' => true]);
$table->addColumn('data', 'binary', ['length' => 255]);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
// Migration file
$table->addColumn('hash', 'binary', ['length' => 20]);
$table->addColumn('data', 'varbinary', ['length' => 255]);
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
        return [MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$node->name instanceof Identifier) {
            return null;
        }

        $methodName = $node->name->toString();

        // Handle addColumn() calls in migrations
        if ($methodName === 'addColumn') {
            return $this->refactorAddColumn($node);
        }

        return null;
    }

    /**
     * Refactor addColumn('name', 'binary', [...]) calls
     */
    private function refactorAddColumn(MethodCall $node): ?MethodCall
    {
        $args = $node->args;

        // Need at least 2 arguments: column name and type
        if (count($args) < 2) {
            return null;
        }

        // Check if second argument is 'binary' type
        $typeArg = $args[1];
        if (!$typeArg instanceof Arg) {
            return null;
        }

        if (!$typeArg->value instanceof String_) {
            return null;
        }

        if ($typeArg->value->value !== 'binary') {
            return null;
        }

        // Check third argument (options array) if it exists
        $hasFixed = false;
        $optionsArg = $args[2] ?? null;

        if ($optionsArg instanceof Arg && $optionsArg->value instanceof Array_) {
            $hasFixed = $this->hasFixedTrue($optionsArg->value);

            if ($hasFixed) {
                // Remove 'fixed' => true from the options
                $this->removeFixedFromArray($optionsArg->value);
            }
        }

        // If 'fixed' => true was set, keep as 'binary' (already correct after removing fixed)
        // If 'fixed' was not set, change type to 'varbinary'
        if (!$hasFixed) {
            $typeArg->value = new String_('varbinary');
        }

        return $node;
    }

    /**
     * Check if array contains 'fixed' => true
     */
    private function hasFixedTrue(Array_ $array): bool
    {
        foreach ($array->items as $item) {
            if (!$item instanceof ArrayItem) {
                continue;
            }

            if (
                $item->key instanceof String_ &&
                $item->key->value === 'fixed' &&
                $this->valueResolver->isTrue($item->value)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Remove 'fixed' key from array
     */
    private function removeFixedFromArray(Array_ $array): void
    {
        $array->items = array_values(array_filter(
            $array->items,
            function ($item) {
                if (!$item instanceof ArrayItem) {
                    return true;
                }

                return !($item->key instanceof String_ && $item->key->value === 'fixed');
            },
        ));
    }
}
