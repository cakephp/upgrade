<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake6;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Expression;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes variable assignments from method calls that now return void.
 *
 * @see https://github.com/cakephp/cakephp/pull/19220
 * @see https://github.com/cakephp/cakephp/pull/19243
 */
final class RemoveAssignmentFromVoidMethodRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<\Cake\Upgrade\Rector\Cake6\VoidMethod>
     */
    private array $voidMethods = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove variable assignment from void method calls',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
$result = $request->allowMethod(['POST']);
$result = Configure::load('app');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$request->allowMethod(['POST']);
Configure::load('app');
CODE_SAMPLE
                    ,
                    [
                        new VoidMethod('Cake\Http\ServerRequest', 'allowMethod'),
                        new VoidMethod('Cake\Core\Configure', 'load'),
                    ],
                ),
            ],
        );
    }

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$node->expr instanceof Assign) {
            return null;
        }

        $assign = $node->expr;

        // Handle instance method calls
        if ($assign->expr instanceof MethodCall) {
            $methodCall = $assign->expr;
            foreach ($this->voidMethods as $voidMethod) {
                if (!$this->isObjectType($methodCall->var, $voidMethod->getObjectType())) {
                    continue;
                }
                if (!$this->isName($methodCall->name, $voidMethod->getMethod())) {
                    continue;
                }

                return new Expression($methodCall);
            }
        }

        // Handle static method calls
        if ($assign->expr instanceof StaticCall) {
            $staticCall = $assign->expr;
            foreach ($this->voidMethods as $voidMethod) {
                if (!$this->isName($staticCall->class, $voidMethod->getClass())) {
                    continue;
                }
                if (!$this->isName($staticCall->name, $voidMethod->getMethod())) {
                    continue;
                }

                return new Expression($staticCall);
            }
        }

        return null;
    }

    /**
     * @param list<mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        $this->voidMethods = $configuration;
    }
}
