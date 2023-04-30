<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use Cake\Upgrade\Rector\ValueObject\OptionsArrayToNamedParameters;
use PhpParser\Node\Arg;
use PhpParser\Node\Identifier;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

use function RectorPrefix202304\dump_node;

final class OptionsArrayToNamedParametersRector extends AbstractRector implements ConfigurableRectorInterface
{
    public const OPTIONS_TO_NAMED_PARAMETERS = 'options_to_named_parameters';

    /**
     * @var \Cake\Upgrade\Rector\ValueObject\OptionsArrayToNamedParameters
     */
    private $optionsToNamed = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Converts trailing options arrays into named parameters. Will preserve all other arguments.', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
use Cake\ORM\TableRegistry;

$articles = TableRegistry::get('Articles');

$query = $articles->find('list', ['field' => ['title']]);
$query = $articles->find('all', ['conditions' => ['Articles.title' => $title]]);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Cake\ORM\TableRegistry;

$articles = TableRegistry::get('Articles');

$query = $articles->find('list', field: ['title']]);
$query = $articles->find('all', conditions: ['Articles.title' => $title]);
CODE_SAMPLE
                ,
                [
                    self::OPTIONS_TO_NAMED_PARAMETERS => [
                        new OptionsArrayToNamedParameters('Table', ['find']),
                    ],
                ]
            ),
        ]);
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
    public function configure(array $configuration): void
    {
        $this->optionsToNamed = $configuration[self::OPTIONS_TO_NAMED_PARAMETERS] ?? [];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->optionsToNamed as $optionsToNamed) {
            if (!$this->matchTypeAndMethodName($optionsToNamed, $node)) {
                continue;
            }
            return $this->replaceMethodCall($optionsToNamed, $node);

        }
        return null;
    }

    private function matchtypeAndMethodName(OptionsArrayToNamedParameters $optionsToNamed, MethodCall $methodCall): bool
    {
        if (!$this->isObjectType($methodCall->var, $optionsToNamed->getObjectType())) {
            return false;
        }

        if (in_array($methodCall->name,$optionsToNamed->getMethods(), true)) {
            return false;
        }

        return true;
    }

    private function replaceMethodCall(OptionsArrayToNamedParameters $optionsToNamed, MethodCall $methodCall): ?MethodCall
    {
        $argCount = count($methodCall->args);
        // Only modify method calls that have exactly two arguments.
        // This is important for idempotency.
        if ($argCount !== 2) {
            return null;
        }
        $optionsParam = $methodCall->args[$argCount - 1];
        if (!$optionsParam->value instanceof Array_) {
            return null;
        }
        // Create a copy of the arguments and remove the options array.
        $argNodes = $methodCall->args;
        unset($argNodes[$argCount - 1]);

        foreach ($optionsParam->value->items as $param) {
            $argNodes[] = new Arg($param->value, name: new Identifier($param->key->value));
        }
        $methodCall->args = $argNodes;

        return $methodCall;
    }
}
