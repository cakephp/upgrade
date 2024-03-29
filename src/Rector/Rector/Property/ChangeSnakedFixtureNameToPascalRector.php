<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\Property;

use Cake\Utility\Inflector;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cake\Upgrade\Rector\Tests\Rector\Property\ChangeSnakedFixtureNameToPascal\ChangeSnakedFixtureNameToPascalTest
 *
 * @see https://book.cakephp.org/3.0/en/appendices/3-7-migration-guide.html
 */
final class ChangeSnakedFixtureNameToPascalRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Changes $fixtures style from snake_case to PascalCase.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeTest
{
    protected $fixtures = [
        'app.posts',
        'app.users',
        'some_plugin.posts/special_posts',
        'app.Messages',
        'plugin.Data.Languages',
    ];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeTest
{
    protected $fixtures = [
        'app.Posts',
        'app.Users',
        'some_plugin.Posts/SpecialPosts',
        'app.Messages',
        'plugin.Data.Languages',
    ];
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Property $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'fixtures')) {
            return null;
        }

        foreach ($node->props as $prop) {
            $this->refactorPropertyWithArrayDefault($prop);
        }

        return $node;
    }

    private function refactorPropertyWithArrayDefault(PropertyProperty $propertyProperty): void
    {
        if (! $propertyProperty->default instanceof Array_) {
            return;
        }

        $array = $propertyProperty->default;
        foreach ($array->items as $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                continue;
            }

            $itemValue = $arrayItem->value;
            if (! $itemValue instanceof String_) {
                continue;
            }

            $this->renameFixtureName($itemValue);
        }
    }

    private function renameFixtureName(String_ $string): void
    {
        [$prefix, $table] = explode('.', $string->value, 2);

        $tableParts = explode('/', $table);

        $pascalCaseTableParts = array_map(
            function (string $token): string {
                return Inflector::camelize($token);
            },
            $tableParts
        );

        $table = implode('/', $pascalCaseTableParts);

        $string->value = sprintf('%s.%s', $prefix, $table);
    }
}
