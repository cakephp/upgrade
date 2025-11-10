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
 * Transforms $query->newExpr()->count() to $query->func()->count('*')
 *
 * newExpr() was used to create expression builders, but when followed by count(),
 * it should use func() instead which is the aggregate function builder.
 */
final class NewExprToFuncRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change $query->newExpr()->count() to $query->func()->count(\'*\')',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$query->newExpr()->count();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$query->func()->count('*');
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

        // Check if this is a ->count() call
        if (!$node->name instanceof Identifier || $node->name->toString() !== 'count') {
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
        if (empty($node->args)) {
            $node->args = [new Arg(new String_('*'))];
        }

        return $node;
    }
}
