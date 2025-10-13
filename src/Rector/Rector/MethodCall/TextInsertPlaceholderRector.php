<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replace :placeholder with {placeholder} in Text::insert() calls
 * for CakePHP 6.0, unless 'before' or 'after' options are explicitly set
 */
final class TextInsertPlaceholderRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace :placeholder with {placeholder} in Text::insert() calls',
            [
                new CodeSample(
                    'Text::insert(\'Hello :name\', [\'name\' => \'World\']);',
                    'Text::insert(\'Hello {name}\', [\'name\' => \'World\']);',
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

        if (!$this->isName($node->class, 'Cake\Utility\Text')) {
            return null;
        }

        if (!$this->isName($node->name, 'insert')) {
            return null;
        }

        // Need at least 1 argument (the template string)
        if (count($node->args) === 0) {
            return null;
        }

        $templateArg = $node->args[0];
        if (!$templateArg->value instanceof String_) {
            return null;
        }

        // Check if 'before' or 'after' options are set in the third argument
        if (count($node->args) >= 3) {
            $optionsArg = $node->args[2];
            if ($optionsArg->value instanceof Array_) {
                if ($this->hasBeforeOrAfterOption($optionsArg->value)) {
                    // Skip transformation if custom before/after are set
                    return null;
                }
            }
        }

        // Transform the template string
        $originalTemplate = $templateArg->value->value;
        $transformedTemplate = $this->transformPlaceholders($originalTemplate);

        // If no change, return null
        if ($originalTemplate === $transformedTemplate) {
            return null;
        }

        // Create new String_ node with transformed template
        $node->args[0] = new Arg(new String_($transformedTemplate));

        return $node;
    }

    /**
     * Check if the options array has 'before' or 'after' keys
     */
    private function hasBeforeOrAfterOption(Array_ $array): bool
    {
        foreach ($array->items as $item) {
            if ($item instanceof ArrayItem && $item->key instanceof String_) {
                if (in_array($item->key->value, ['before', 'after'], true)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Transform :placeholder to {placeholder}
     * Matches :word (word characters: letters, numbers, underscore)
     */
    private function transformPlaceholders(string $template): string
    {
        // Replace :word with {word}
        // Match : followed by word characters (letters, digits, underscore)
        return preg_replace('/:(\w+)/', '{$1}', $template);
    }
}
