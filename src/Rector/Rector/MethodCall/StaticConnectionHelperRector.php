<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use Cake\TestSuite\ConnectionHelper;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class StaticConnectionHelperRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Transform ConnectionHelper instance method calls to static calls', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$connectionHelper = new ConnectionHelper();
$connectionHelper->runWithoutConstraints($connection, function ($connection) {
    $connection->execute('SELECT * FROM table');
});
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
ConnectionHelper::runWithoutConstraints($connection, function ($connection) {
    $connection->execute('SELECT * FROM table');
});
CODE_SAMPLE,
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Expression::class, MethodCall::class];
    }

    public function refactor(Node $node): int|Node|null
    {
        if ($node instanceof Expression) {
            if (
                $node->expr instanceof Assign &&
                $node->expr->expr instanceof New_ &&
                $this->isName($node->expr->expr->class, ConnectionHelper::class)
            ) {
                return NodeVisitor::REMOVE_NODE;
            }

            return null;
        }

        // Ensure the node is a method call on the ConnectionHelper instance
        if (! $this->isObjectType($node->var, new ObjectType(ConnectionHelper::class))) {
            return null;
        }

        // Replace with a static method call
        return new StaticCall(
            new Node\Name\FullyQualified(ConnectionHelper::class),
            $node->name,
            $node->args,
        );
    }
}
