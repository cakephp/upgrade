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
 * Renames Table->newEntity() to Table->newEmptyEntity() when called with no arguments.
 *
 * @see https://github.com/cakephp/upgrade/issues/143
 */
final class NewEntityToNewEmptyEntityRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename Table->newEntity() to Table->newEmptyEntity() when called with no arguments',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$entity = $this->Articles->newEntity();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$entity = $this->Articles->newEmptyEntity();
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
        if (!$this->isName($node->name, 'newEntity')) {
            return null;
        }

        if (!$this->isObjectType($node->var, new ObjectType('Cake\ORM\Table'))) {
            return null;
        }

        // Only rename if there are no arguments
        if (count($node->args) > 0) {
            return null;
        }

        $node->name = new Identifier('newEmptyEntity');

        return $node;
    }
}
