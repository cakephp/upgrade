<?php

declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class TableRegistryLocatorRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor `TableRegistry::get()` to `TableRegistry::getTableLocator()->get()`', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
TableRegistry::get('something');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
TableRegistry::getTableLocator()->get('something');
CODE_SAMPLE
            )
        ]);
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if(! $node instanceof StaticCall) {
            return null;
        }

        // Ensure it's a static call we're looking for: TableRegistry::get(...)
        if (! $this->isStaticCallMatch($node, 'Cake\ORM\TableRegistry', 'get')) {
            return null;
        }

        // Create new static call TableRegistry::getTableLocator()->get(...)
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall('Cake\ORM\TableRegistry', 'getTableLocator'),
            'get',
            $node->args
        );
    }

    private function isStaticCallMatch(StaticCall $staticCall, string $className, string $methodName): bool
    {
        // Check if the static call is `TableRegistry::get`
        return $this->isName($staticCall->class, $className) && $this->isName($staticCall->name, $methodName);
    }
}
