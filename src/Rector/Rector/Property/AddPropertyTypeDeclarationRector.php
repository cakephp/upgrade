<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddPropertyTypeDeclarationRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<\Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration>
     */
    private array $propertyTypes = [];

    public function __construct(private StaticTypeMapper $staticTypeMapper)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add configured types to CakePHP properties.', [
            new CodeSample(
                'class MyComponent extends \\Cake\\Controller\\Component { protected $components = []; }',
                'class MyComponent extends \\Cake\\Controller\\Component { protected array $components = []; }',
            ),
        ]);
    }

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanges = false;
        foreach ($this->propertyTypes as $propertyType) {
            if (!$this->isObjectType($node, new ObjectType($propertyType->getClass()))) {
                continue;
            }

            $property = $node->getProperty($propertyType->getPropertyName());
            if ($property === null || $property->type !== null || count($property->props) !== 1) {
                continue;
            }

            $type = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                $propertyType->getType(),
                TypeKind::PROPERTY,
            );
            if ($type === null) {
                continue;
            }

            $property->type = $type;
            $hasChanges = true;
        }

        return $hasChanges ? $node : null;
    }

    /**
     * @param array<\Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration> $configuration
     */
    public function configure(array $configuration): void
    {
        $this->propertyTypes = $configuration;
    }
}
