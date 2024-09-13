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
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;
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
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class, Assign::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Assign) {
            if ($node->expr instanceof New_ && $this->isName($node->expr->class, 'ConnectionHelper')) {
                // Remove the instantiation statement
                $parent = $node->getAttribute(AttributeKey::PARENT_NODE);
                if ($parent instanceof Expression) {
                    $this->removeNode($parent);

                    return null;
                }
            }
        }

        // Ensure the node is a method call on the ConnectionHelper instance
        if (! $this->isObjectType($node->var, new ObjectType(ConnectionHelper::class))) {
            return null;
        }

        // Replace with a static method call
        return new StaticCall(
            new Node\Name\FullyQualified(ConnectionHelper::class),
            $node->name,
            $node->args
        );
    }
}
