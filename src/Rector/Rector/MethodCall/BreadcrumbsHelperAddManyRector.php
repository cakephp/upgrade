<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Transforms BreadcrumbsHelper::add(array) to addMany() and prepend(array) to prependMany()
 *
 * In CakePHP 5.3, passing an array of crumbs to add() or prepend() is deprecated.
 * Use addMany() or prependMany() instead.
 *
 * @see https://book.cakephp.org/5/en/appendices/5-3-migration-guide.html
 */
final class BreadcrumbsHelperAddManyRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change BreadcrumbsHelper::add(array) to addMany() and prepend(array) to prependMany()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$this->Breadcrumbs->add([
    ['title' => 'Home', 'url' => '/'],
    ['title' => 'Articles', 'url' => '/articles'],
]);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$this->Breadcrumbs->addMany([
    ['title' => 'Home', 'url' => '/'],
    ['title' => 'Articles', 'url' => '/articles'],
]);
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

        // Must be add or prepend method
        if (!$node->name instanceof Identifier) {
            return null;
        }

        $methodName = $node->name->toString();
        if ($methodName !== 'add' && $methodName !== 'prepend') {
            return null;
        }

        // Must have at least one argument
        if (count($node->args) < 1) {
            return null;
        }

        // First argument must be an array
        $firstArg = $node->args[0]->value;
        if (!$firstArg instanceof Array_) {
            return null;
        }

        // Check if the array looks like an array of crumbs (array of arrays)
        // rather than a single crumb with title/url keys
        if (!$this->isArrayOfCrumbs($firstArg)) {
            return null;
        }

        // Check if this is called on BreadcrumbsHelper
        $callerType = $this->getType($node->var);
        if (!$callerType instanceof ObjectType) {
            return null;
        }

        if (!$callerType->isInstanceOf('Cake\View\Helper\BreadcrumbsHelper')->yes()) {
            return null;
        }

        // Rename to addMany or prependMany
        $newMethodName = $methodName === 'add' ? 'addMany' : 'prependMany';
        $node->name = new Identifier($newMethodName);

        return $node;
    }

    /**
     * Determine if an array is an array of crumbs (array of arrays)
     * vs a single crumb (array with title/url keys)
     */
    private function isArrayOfCrumbs(Array_ $array): bool
    {
        // An array of crumbs is typically a numerically indexed array of arrays
        // e.g. [['title' => 'Home'], ['title' => 'Articles']]
        // A single crumb would have string keys like 'title', 'url'
        // e.g. ['title' => 'Home', 'url' => '/']

        if (count($array->items) === 0) {
            return false;
        }

        foreach ($array->items as $item) {
            if (!$item instanceof ArrayItem) {
                continue;
            }

            // If item has a string key like 'title' or 'url', it's a single crumb
            if ($item->key instanceof String_) {
                $keyValue = $item->key->value;
                if ($keyValue === 'title' || $keyValue === 'url' || $keyValue === 'options') {
                    return false;
                }
            }

            // If the item value is an array, it's likely an array of crumbs
            if ($item->value instanceof Array_) {
                return true;
            }
        }

        return false;
    }
}
