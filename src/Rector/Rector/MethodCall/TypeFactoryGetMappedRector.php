<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Transforms TypeFactory::getMap($type) to TypeFactory::getMapped($type)
 *
 * In CakePHP 5.3, calling getMap() with a type argument is deprecated.
 * Use getMapped() instead for single-type lookups.
 *
 * @see https://book.cakephp.org/5/en/appendices/5-3-migration-guide.html
 */
final class TypeFactoryGetMappedRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change TypeFactory::getMap($type) to TypeFactory::getMapped($type)',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Cake\Database\TypeFactory;

$class = TypeFactory::getMap('datetime');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Cake\Database\TypeFactory;

$class = TypeFactory::getMapped('datetime');
CODE_SAMPLE,
                ),
            ],
        );
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof StaticCall) {
            return null;
        }

        // Must be getMap method
        if (!$node->name instanceof Identifier || $node->name->toString() !== 'getMap') {
            return null;
        }

        // Must have at least one argument (the type)
        if (count($node->args) < 1) {
            return null;
        }

        // Check if this is called on TypeFactory
        if (!$node->class instanceof Name) {
            return null;
        }

        $className = $node->class->toString();
        if ($className !== 'Cake\Database\TypeFactory' && $className !== 'TypeFactory') {
            return null;
        }

        // Rename to getMapped
        $node->name = new Identifier('getMapped');

        return $node;
    }
}
