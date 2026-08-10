<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Transforms Table::find()->disableHydration() to Table::unhydratedFind().
 *
 * @see https://book.cakephp.org/5/en/appendices/5-4-migration-guide.html
 */
final class DisableHydrationToUnhydratedFindRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change Table::find()->disableHydration() to Table::unhydratedFind()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$articles->find('all')->disableHydration();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$articles->unhydratedFind('all');
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

        if (!$node->name instanceof Identifier || $node->name->toString() !== 'disableHydration') {
            return null;
        }

        if (count($node->args) !== 0) {
            return null;
        }

        $current = $node->var;
        while ($current instanceof MethodCall) {
            if ($current->name instanceof Identifier && $current->name->toString() === 'find') {
                if (!(new ObjectType('Cake\ORM\Table'))->isSuperTypeOf($this->getType($current->var))->yes()) {
                    return null;
                }

                $current->name = new Identifier('unhydratedFind');

                return $node->var;
            }

            $current = $current->var;
        }

        return null;
    }
}
