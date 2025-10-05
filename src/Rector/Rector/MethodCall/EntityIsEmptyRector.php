<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use Cake\ORM\Entity;
use PhpParser\Node;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class EntityIsEmptyRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace $entity->isEmpty() with !$entity->hasValue() for \Cake\ORM\Entity descendants',
            [
                new CodeSample(
                    '$entity->isEmpty();',
                    '!$entity->hasValue();',
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

        if (!$this->isName($node->name, 'isEmpty')) {
            return null;
        }

        $objectType = $this->getType($node->var);
        if (!$objectType instanceof ObjectType) {
            return null;
        }

        if (!$objectType->isInstanceOf(Entity::class)->yes()) {
            return null;
        }

        $newMethodCall = new MethodCall(
            $node->var,
            new Identifier('hasValue'),
            $node->args,
        );

        // Replace with !$entity->hasValue($args)
        return new BooleanNot($newMethodCall);
    }
}
