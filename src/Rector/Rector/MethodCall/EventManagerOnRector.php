<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Swaps the 2nd and 3rd arguments of EventManager::on() when called with 3 arguments.
 *
 * @see https://github.com/cakephp/cakephp/issues/19068
 * @see \Cake\Upgrade\Test\TestCase\Rector\MethodCall\EventManagerOnRector\EventManagerOnRectorTest
 */
final class EventManagerOnRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Swaps the 2nd and 3rd arguments of EventManager::on() to match new signature',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$eventManager->on('Model.beforeSave', ['priority' => 90], $callable);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$eventManager->on('Model.beforeSave', $callable, ['priority' => 90]);
CODE_SAMPLE
                ),
            ]
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
        // Check if this is a call to the 'on' method
        if (!$this->isName($node->name, 'on')) {
            return null;
        }

        // Check if the object implements EventManagerInterface
        if (!$this->isObjectType($node->var, new ObjectType('Cake\Event\EventManagerInterface'))) {
            return null;
        }

        // Only process if there are exactly 3 arguments
        if (count($node->args) !== 3) {
            return null;
        }

        // Swap the 2nd and 3rd arguments
        $secondArg = $node->args[1];
        $thirdArg = $node->args[2];
        
        $node->args[1] = $thirdArg;
        $node->args[2] = $secondArg;

        return $node;
    }
}
