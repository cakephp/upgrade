<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake5;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\StringType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\PHPStan\ScopeFetcher;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\ConfiguredCodeSample;
use Rector\RuleDoc\RuleDefinition;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;

/**
 * Adds property types required by CakePHP to matching declarations.
 */
final class AddPropertyTypeDeclarationRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<\Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration>
     */
    private array $declarations = [];

    /**
     * Creates the rule with its property type mapper.
     */
    public function __construct(private StaticTypeMapper $staticTypeMapper)
    {
    }

    /**
     * Describes the configured property type conversion.
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add property types required by CakePHP 5', [
            new ConfiguredCodeSample(
                'class AppController extends Controller { public $name; }',
                'class AppController extends Controller { public string $name; }',
                [new AddPropertyTypeDeclaration('Cake\Controller\Controller', 'name', new StringType())],
            ),
        ]);
    }

    /**
     * Returns the property nodes handled by this rule.
     *
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    /**
     * Adds a configured type to an untyped, individual property.
     *
     * @param \PhpParser\Node\Stmt\Property $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->type !== null || count($node->props) !== 1) {
            return null;
        }

        $class = ScopeFetcher::fetch($node)->getClassReflection();
        if (!$class instanceof ClassReflection) {
            return null;
        }

        foreach ($this->declarations as $declaration) {
            if (!$class->is($declaration->getClass()) && !$class->hasTraitUse($declaration->getClass())) {
                continue;
            }
            if (!$this->isName($node, $declaration->getPropertyName())) {
                continue;
            }

            $node->type = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                $declaration->getType(),
                TypeKind::PROPERTY,
            );

            return $node;
        }

        return null;
    }

    /**
     * Sets the property types to add for matching classes.
     *
     * @param array<\Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration> $configuration
     */
    public function configure(array $configuration): void
    {
        $this->declarations = $configuration;
    }
}
