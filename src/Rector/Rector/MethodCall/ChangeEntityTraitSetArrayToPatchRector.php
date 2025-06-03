<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ChangeEntityTraitSetArrayToPatchRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replaces $this->set(array) with $this->patch(array) when the first param is an array',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
$this->set(['key' => 'value']);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$this->patch(['key' => 'value']);
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
        if (! $node instanceof MethodCall) {
            return null;
        }

        // Check the method name is "set"
        if (! $this->isName($node->name, 'set')) {
            return null;
        }

        // Check that the first argument exists and is an array
        if (! isset($node->args[0])) {
            return null;
        }

        $firstArg = $node->args[0]->value;
        if (! $firstArg instanceof Array_) {
            return null;
        }

        // Make sure the method is called on an object that uses EntityTrait
        $callerType = $this->getType($node->var);
        if (! $callerType instanceof ObjectType) {
            return null;
        }

        $classReflection = $callerType->getClassReflection();
        if ($classReflection === null) {
            return null;
        }
        if (! $classReflection->hasTraitUse('Cake\Datasource\EntityTrait')) {
            return null;
        }

        // Rename the method to "patch"
        $node->name = new Identifier('patch');

        return $node;
    }
}
