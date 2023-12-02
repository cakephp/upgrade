<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use Cake\Upgrade\Rector\ValueObject\AddMethodCallArgs;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://book.cakephp.org/5.0/en/appendices/5-0-migration-guide.html
 * @see https://github.com/cakephp/cakephp/commit/77017145961bb697b4256040b947029259f66a9b
 *
 * @see \Cake\Upgrade\Rector\Tests\Rector\MethodCall\AddMethodCallArgsRector\AddMethodCallArgsRectorTest
 */
final class AddMethodCallArgsRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const ADD_METHOD_CALL_ARGS = 'add_method_call_args';

    /**
     * @var \Cake\Upgrade\Rector\ValueObject\AddMethodCallArgs[]
     */
    private array $callsWithAddMethodCallArgs = [];

    public function getRuleDefinition(): RuleDefinition
    {
        $configuration = [
            self::ADD_METHOD_CALL_ARGS => [
                new AddMethodCallArgs('ServerRequest', 'getParam', 'paging', 1, true),
            ],
        ];

        return new RuleDefinition(
            'Adds method call arguments',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
$object = new ServerRequest();
$config = $object->getParam();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$object = new ServerRequest();
$config = $object->getParam('paging', 1, true);
CODE_SAMPLE
                    ,
                    $configuration
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
        foreach ($this->callsWithAddMethodCallArgs as $methodCallRenameWithAddedArgument) {
            if (! $this->isObjectType($node->var, $methodCallRenameWithAddedArgument->getObjectType())) {
                continue;
            }

            if (! $this->isName($node->name, $methodCallRenameWithAddedArgument->getMethodName())) {
                continue;
            }

            $values = $methodCallRenameWithAddedArgument->getValues();
            if ($node->args) {
                $newArgs = [];
                foreach ($node->args as $arg) {
                    $newArgs[] = $arg->value;
                }
                $node->args = $this->nodeFactory->createArgs([...$newArgs, ...$values]);
            } else {
                $node->args = $this->nodeFactory->createArgs($values);
            }

            return $node;
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->callsWithAddMethodCallArgs = $configuration[self::ADD_METHOD_CALL_ARGS] ?? $configuration;
    }
}
