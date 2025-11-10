<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Transforms $query->newExpr()->aggregate() to $query->func()->aggregate()
 *
 * newExpr() was used to create expression builders, but when followed by aggregate
 * or SQL function calls, it should use func() instead which is the function builder.
 *
 * Handles common FunctionsBuilder methods like:
 * - Aggregates: count(), sum(), avg(), min(), max(), rowNumber(), lag(), lead()
 * - Date functions: dateDiff(), datePart(), extract(), dateAdd(), now()
 * - Other functions: concat(), coalesce(), cast(), rand()
 */
final class NewExprToFuncRector extends AbstractRector
{
    /**
     * List of FunctionsBuilder methods that should trigger the transformation
     */
    private const FUNC_BUILDER_METHODS = [
        // Aggregate functions
        'count',
        'sum',
        'avg',
        'min',
        'max',
        'rowNumber',
        'lag',
        'lead',
        // Date/time functions
        'dateDiff',
        'datePart',
        'extract',
        'dateAdd',
        'now',
        'weekday',
        'dayOfWeek',
        // Other SQL functions
        'concat',
        'coalesce',
        'cast',
        'rand',
        'jsonValue',
        'aggregate',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change $query->newExpr()->funcMethod() to $query->func()->funcMethod() for FunctionsBuilder methods',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$query->newExpr()->count();
$query->newExpr()->sum('total');
$query->newExpr()->avg('score');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$query->func()->count('*');
$query->func()->sum('total');
$query->func()->avg('score');
CODE_SAMPLE,
                ),
            ],
        );
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof MethodCall) {
            return null;
        }

        // Check if this is a FunctionsBuilder method call
        if (!$node->name instanceof Identifier) {
            return null;
        }

        $methodName = $node->name->toString();
        if (!in_array($methodName, self::FUNC_BUILDER_METHODS, true)) {
            return null;
        }

        // Check if the var is also a MethodCall (chained call)
        if (!$node->var instanceof MethodCall) {
            return null;
        }

        $innerMethodCall = $node->var;

        // Check if the inner call is ->newExpr()
        if (!$innerMethodCall->name instanceof Identifier || $innerMethodCall->name->toString() !== 'newExpr') {
            return null;
        }

        // Check if the caller is a Query object (Cake\Database\Query or similar)
        $callerType = $this->getType($innerMethodCall->var);
        if (
            !$callerType->isInstanceOf('Cake\Database\Query')->yes() &&
            !$callerType->isInstanceOf('Cake\ORM\Query')->yes()
        ) {
            return null;
        }

        // Change newExpr to func
        $innerMethodCall->name = new Identifier('func');

        // Add '*' argument to count() if it doesn't have arguments
        if ($methodName === 'count' && empty($node->args)) {
            $node->args = [new Arg(new String_('*'))];
        }

        return $node;
    }
}
