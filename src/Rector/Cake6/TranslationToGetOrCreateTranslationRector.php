<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake6;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Renames translation() to getOrCreateTranslation() for entities using TranslateTrait.
 *
 * In CakePHP 6.0, translation() became a pure getter that returns null if no translation exists.
 * The old create-on-access behavior is now in getOrCreateTranslation().
 *
 * @see https://github.com/cakephp/cakephp/pull/19251
 */
final class TranslationToGetOrCreateTranslationRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename $entity->translation() to $entity->getOrCreateTranslation() to preserve create-on-access behavior',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$article->translation('fra');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$article->getOrCreateTranslation('fra');
CODE_SAMPLE,
                ),
            ],
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
        if (!$node->name instanceof Identifier) {
            return null;
        }

        if ($node->name->toString() !== 'translation') {
            return null;
        }

        if (!$this->isObjectType($node->var, new ObjectType('Cake\ORM\Entity'))) {
            return null;
        }

        $node->name = new Identifier('getOrCreateTranslation');

        return $node;
    }
}
