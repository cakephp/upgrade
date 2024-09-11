<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SetSerializeToViewBuilderRector extends AbstractRector implements ConfigurableRectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change `$this->set(\'_serialize\', \'result\')` to ' .
                '`$this->viewBuilder()->setOption(\'serialize\', \'result\')`.',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
    $this->set('_serialize', 'result');
    CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
    $this->viewBuilder()->setOption('serialize', 'result');
    CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof MethodCall) {
            return null;
        }

        // Ensure it's the method call we're looking for: $this->set('_serialize', ...)
        if (! $this->isMethodCallMatch($node, 'set', '_serialize')) {
            return null;
        }

        // Create the new method call
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($node->var, 'viewBuilder'),
            'setOption',
            ['serialize', $node->args[1]->value]
        );
    }

    private function isMethodCallMatch(MethodCall $methodCall, string $methodName, string $firstArgumentValue): bool
    {
        // Check if the method is 'set'
        if (! $this->isName($methodCall->name, $methodName)) {
            return false;
        }

        // Check if the first argument is '_serialize'w
        return isset($methodCall->args[0]) && $methodCall->args[0]->value->value === $firstArgumentValue;
    }

    public function configure(array $configuration): void
    {
        // No configuration options
    }
}
