<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake4;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Converts Paginator->counter(['format' => ...]) to Paginator->counter(...).
 *
 * @see https://github.com/cakephp/upgrade/issues/143
 */
final class PaginatorCounterFormatRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert Paginator->counter([\'format\' => ...]) to Paginator->counter(...)',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}')]);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$this->Paginator->counter(__('Page {{page}} of {{pages}}'));
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
        if (!$this->isName($node->name, 'counter')) {
            return null;
        }

        if (!$this->isObjectType($node->var, new ObjectType('Cake\View\Helper\PaginatorHelper'))) {
            return null;
        }

        // Must have exactly one argument
        if (count($node->args) !== 1) {
            return null;
        }

        $firstArgNode = $node->args[0];
        if (!$firstArgNode instanceof Arg) {
            return null;
        }

        $firstArg = $firstArgNode->value;

        // Check if the argument is an array
        if (!$firstArg instanceof Array_) {
            return null;
        }

        // Check if the array has exactly one item with key 'format'
        if (count($firstArg->items) !== 1) {
            return null;
        }

        $arrayItem = $firstArg->items[0];

        if (!$arrayItem->key instanceof String_) {
            return null;
        }

        if ($arrayItem->key->value !== 'format') {
            return null;
        }

        // Replace the array argument with just the format value
        $node->args = [new Arg($arrayItem->value)];

        return $node;
    }
}
