<?php

declare (strict_types = 1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Removing\Rector\FuncCall\RemoveFuncCallRector\RemoveFuncCallRectorTest
 */
final class RemoveMethodCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const REMOVE_METHOD_CALL_ARGS = 'remove_method_call_args';

    /**
     * @var \Cake\Upgrade\Rector\ValueObject\RemoveMethodCall[]
     */
    private $callsWithRemoveMethodCallArgs = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove method call', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$obj = new SomeClass();
$obj->methodCall1();
$obj->methodCall2();
CODE_SAMPLE, <<<'CODE_SAMPLE'
$obj = new SomeClass();
$obj->methodCall2();
CODE_SAMPLE, ['SomeClass', 'methodCall1']
            )
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?int
    {
        if (! $node->expr instanceof MethodCall) {
            return null;
        }

        foreach ($this->callsWithRemoveMethodCallArgs as $removedFunction) {
            if (! $this->isObjectType($node->expr->var, $removedFunction->getObjectType())) {
                continue;
            }

            if (! $this->isName($node->expr->name, $removedFunction->getMethodName())) {
                continue;
            }

            return NodeTraverser::REMOVE_NODE;
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->callsWithRemoveMethodCallArgs = $configuration[self::REMOVE_METHOD_CALL_ARGS] ?? $configuration;
    }
}
