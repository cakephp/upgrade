<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use Cake\Routing\RouteBuilder;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RouteBuilderCleanupRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<string, string[]>
     * e.g. ['scope' => ['path', 'options', 'callback']]
     */
    private array $methods = [];

    public function configure(array $configuration): void
    {
        $this->methods = $configuration['methods'] ?? [];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Normalize RouteBuilder calls to always use named arguments based on configuration', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$routes->scope('/api', function (RouteBuilder $routes): void {});
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
$routes->scope(path: '/api', options: [], callback: function (RouteBuilder $routes): void {});
CODE_SAMPLE,
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof MethodCall) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if (! isset($this->methods[$methodName])) {
            return null;
        }

        // Must be called on a Cake\Routing\RouteBuilder
        $callerType = $this->getType($node->var);
        if (! (new ObjectType(RouteBuilder::class))->isSuperTypeOf($callerType)->yes()) {
            return null;
        }

        $argNames = $this->methods[$methodName];
        $args = $node->args;

        $pathValue = $args[0]->value ?? null;
        $optionsValue = null;
        $callbackValue = null;

        // Handle case where 2nd param is callable or array
        if (isset($args[1])) {
            if ($this->isCallableNode($args[1]->value)) {
                // Case: scope('/api', fn() => null)
                $callbackValue = $args[1]->value;
            } else {
                // Case: scope('/api', [], fn() => null)
                $optionsValue = $args[1]->value;
                $callbackValue = $args[2]->value ?? null;
            }
        }

        if ($callbackValue === null) {
            // no callable = no change
            return null;
        }

        $newArgs = [];

        // always add first argument (path/name) without a named argument
        if (isset($argNames[0]) && $pathValue !== null) {
            $newArgs[] = new Arg(
                $pathValue,
                false,
                false,
                [],
            );
        }

        // only add options if it existed in original call
        if (isset($argNames[1]) && $optionsValue !== null) {
            $newArgs[] = new Arg(
                $optionsValue,
                false,
                false,
                [],
                new Identifier($argNames[1]),
            );
        } elseif ($methodName === 'scope') {
            // scope() must always have options
            $newArgs[] = new Arg(
                $optionsValue ?? $this->nodeFactory->createArray([]),
                false,
                false,
                [],
                new Identifier($argNames[1]),
            );
        }

        // always add callback if present
        if (isset($argNames[2])) {
            $newArgs[] = new Arg(
                $callbackValue,
                false,
                false,
                [],
                new Identifier($argNames[2]),
            );
        }

        $node->args = $newArgs;

        return $node;
    }

    private function isCallableNode(Node $node): bool
    {
        return $node instanceof Closure
            || $node instanceof ArrowFunction
            || $node instanceof FuncCall;
    }
}
