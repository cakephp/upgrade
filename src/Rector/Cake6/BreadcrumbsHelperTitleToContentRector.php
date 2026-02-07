<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake6;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Transforms BreadcrumbsHelper 'title' array keys and named parameters to 'content'
 *
 * In CakePHP 6.0, the 'title' key/parameter was renamed to 'content' for clarity.
 *
 * @see https://github.com/cakephp/cakephp/pull/18334
 */
final class BreadcrumbsHelperTitleToContentRector extends AbstractRector
{
    /**
     * Methods where we should rename 'title' to 'content' in array keys
     */
    private const METHODS_WITH_TITLE_ARRAY = [
        'add',
        'prepend',
        'addMany',
        'prependMany',
    ];

    /**
     * Methods where first parameter name changed from matchingTitle to matchingContent
     */
    private const METHODS_WITH_MATCHING_TITLE = [
        'insertBefore',
        'insertAfter',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change BreadcrumbsHelper "title" array key and parameter to "content"',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$this->Breadcrumbs->add(['title' => 'Home', 'url' => '/']);
$this->Breadcrumbs->addMany([
    ['title' => 'Home', 'url' => '/'],
    ['title' => 'Articles', 'url' => '/articles'],
]);
$this->Breadcrumbs->insertBefore('Home', 'Dashboard', '/dashboard');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$this->Breadcrumbs->add(['content' => 'Home', 'url' => '/']);
$this->Breadcrumbs->addMany([
    ['content' => 'Home', 'url' => '/'],
    ['content' => 'Articles', 'url' => '/articles'],
]);
$this->Breadcrumbs->insertBefore('Home', 'Dashboard', '/dashboard');
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

        // Check if this is called on BreadcrumbsHelper
        if (!$this->isObjectType($node->var, new ObjectType('Cake\View\Helper\BreadcrumbsHelper'))) {
            return null;
        }

        $methodName = $node->name->toString();
        $hasChanges = false;

        // Handle methods where array keys need 'title' → 'content' rename
        if (in_array($methodName, self::METHODS_WITH_TITLE_ARRAY, true)) {
            $hasChanges = $this->renameArrayKeys($node) || $hasChanges;
            $hasChanges = $this->renameNamedParameter($node, 'title', 'content') || $hasChanges;
        }

        // Handle insertBefore/insertAfter: matchingTitle → matchingContent named param
        if (in_array($methodName, self::METHODS_WITH_MATCHING_TITLE, true)) {
            $hasChanges = $this->renameNamedParameter($node, 'matchingTitle', 'matchingContent') || $hasChanges;
            $hasChanges = $this->renameNamedParameter($node, 'title', 'content') || $hasChanges;
        }

        // Handle insertAt: title → content named param
        if ($methodName === 'insertAt') {
            $hasChanges = $this->renameNamedParameter($node, 'title', 'content') || $hasChanges;
        }

        return $hasChanges ? $node : null;
    }

    /**
     * Rename 'title' keys to 'content' in array arguments
     */
    private function renameArrayKeys(MethodCall $node): bool
    {
        $hasChanges = false;

        foreach ($node->args as $arg) {
            if (!$arg instanceof Arg) {
                continue;
            }

            if ($arg->value instanceof Array_) {
                $hasChanges = $this->processArray($arg->value) || $hasChanges;
            }
        }

        return $hasChanges;
    }

    /**
     * Process an array, renaming 'title' keys to 'content'
     */
    private function processArray(Array_ $array): bool
    {
        $hasChanges = false;

        foreach ($array->items as $item) {
            if (!$item instanceof ArrayItem) {
                continue;
            }

            // Rename 'title' key to 'content'
            if ($item->key instanceof String_ && $item->key->value === 'title') {
                $item->key = new String_('content');
                $hasChanges = true;
            }

            // Recursively process nested arrays (for addMany/prependMany)
            if ($item->value instanceof Array_) {
                $hasChanges = $this->processArray($item->value) || $hasChanges;
            }
        }

        return $hasChanges;
    }

    /**
     * Rename a named parameter
     */
    private function renameNamedParameter(MethodCall $node, string $oldName, string $newName): bool
    {
        foreach ($node->args as $arg) {
            if (!$arg instanceof Arg) {
                continue;
            }

            if ($arg->name instanceof Identifier && $arg->name->toString() === $oldName) {
                $arg->name = new Identifier($newName);

                return true;
            }
        }

        return false;
    }
}
