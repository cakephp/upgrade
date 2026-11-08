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

final class AddPropertyTypeDeclarationRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<\Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration>
     */
    private array $declarations = [];

    public function __construct(private StaticTypeMapper $staticTypeMapper)
    {
    }

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
        if ($node->type !== null) {
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
     * @param array<\Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration> $configuration
     */
    public function configure(array $configuration): void
    {
        $this->declarations = $configuration;
    }
}
