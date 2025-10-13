<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\String_;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Rename 'multicheckboxTitle' to 'multicheckboxLabel' in FormHelper templates
 * for CakePHP 6.0
 */
final class RenameFormHelperTemplateKeyRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename \'multicheckboxTitle\' to \'multicheckboxLabel\' in FormHelper template configurations',
            [
                new CodeSample(
                    <<<'CODE'
$this->Form->setTemplates([
    'multicheckboxTitle' => '<legend>{{text}}</legend>',
]);
CODE
                    ,
                    <<<'CODE'
$this->Form->setTemplates([
    'multicheckboxLabel' => '<legend>{{text}}</legend>',
]);
CODE,
                ),
            ],
        );
    }

    public function getNodeTypes(): array
    {
        return [ArrayItem::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof ArrayItem) {
            return null;
        }

        // Only process items with a key
        if ($node->key === null) {
            return null;
        }

        // Check if the key is a string with value 'multicheckboxTitle'
        if (!$node->key instanceof String_) {
            return null;
        }

        if ($node->key->value !== 'multicheckboxTitle') {
            return null;
        }

        // Replace the key
        $node->key = new String_('multicheckboxLabel');

        return $node;
    }
}
