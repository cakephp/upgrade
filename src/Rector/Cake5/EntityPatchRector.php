<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake5;

use Cake\ORM\Entity;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class EntityPatchRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change $entity->set([...]) to $entity->patch([...]) only if first argument is an array literal',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$entity->set(['test' => 'value']);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$entity->patch(['test' => 'value']);
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
        if (!$node instanceof MethodCall) {
            return null;
        }

        // must be $something->set(...)
        if (!$node->name instanceof Identifier || $node->name->toString() !== 'set') {
            return null;
        }

        // must have at least 1 argument
        if ($node->args === [] || !isset($node->args[0])) {
            return null;
        }

        $firstArg = $node->args[0]->value;

        // only change if first argument is an array literal
        if (!$firstArg instanceof Array_) {
            return null;
        }

        $callerType = $this->getType($node->var);
        if (!$callerType instanceof ObjectType) {
            return null;
        }

        if (!$callerType->isInstanceOf(Entity::class)->yes()) {
            return null;
        }

        // change the method name
        $node->name = new Identifier('patch');

        return $node;
    }
}
