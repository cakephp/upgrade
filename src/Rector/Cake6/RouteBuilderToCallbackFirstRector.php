<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake6;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Reorders RouteBuilder scope/prefix/plugin/resources arguments
 * to have callback second and params/options third.
 *
 * @see \Cake\Upgrade\Test\TestCase\Rector\MethodCall\RouteBuilderToCallbackFirstRector\RouteBuilderToCallbackFirstRectorTest
 */
final class RouteBuilderToCallbackFirstRector extends AbstractRector
{
    private const METHODS = ['scope', 'prefix', 'plugin', 'resources'];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Reorder RouteBuilder scope/prefix/plugin/resources arguments ' .
            'to have callback second and params/options third',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$routes->scope('/api', ['prefix' => 'Api'], function ($routes) {
    $routes->resources('Articles');
});
$routes->prefix('admin', [], function ($routes) {
    $routes->connect('/', ['controller' => 'Dashboard']);
});
$routes->resources('Articles', ['only' => 'index']);
$routes->resources('Posts', ['only' => 'index'], function ($routes) {
    $routes->resources('Comments');
});
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$routes->scope('/api', function ($routes) {
    $routes->resources('Articles');
}, ['prefix' => 'Api']);
$routes->prefix('admin', function ($routes) {
    $routes->connect('/', ['controller' => 'Dashboard']);
});
$routes->resources('Articles', null, ['only' => 'index']);
$routes->resources('Posts', function ($routes) {
    $routes->resources('Comments');
}, ['only' => 'index']);
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
        // Check if the object is a RouteBuilder
        if (!$this->isObjectType($node->var, new ObjectType('Cake\Routing\RouteBuilder'))) {
            return null;
        }

        // Check if this is one of our target methods
        if (!$this->isNames($node->name, self::METHODS)) {
            return null;
        }

        $methodName = $this->getName($node->name);
        $argCount = count($node->args);

        // For resources method
        // Old signature: resources(string $name, array $options = [], ?callable $callback = null)
        // New signature: resources(string $name, ?Closure $callback = null, array $options = [])
        if ($methodName === 'resources') {
            // resources('Articles', ['only' => 'index']) -> resources('Articles', null, ['only' => 'index'])
            if ($argCount === 2) {
                $secondArg = $node->args[1];
                // Check if second arg is an array (options)
                if ($secondArg->value instanceof Array_) {
                    // Insert null as second argument, move array to third
                    $node->args = [
                        $node->args[0],
                        new Arg(new ConstFetch(new Name('null'))),
                        $secondArg,
                    ];

                    return $node;
                }
            }

            // resources('Articles', ['only' => 'index'], fn) -> resources('Articles', fn, ['only' => 'index'])
            if ($argCount === 3) {
                $secondArg = $node->args[1];
                $thirdArg = $node->args[2];

                // Check if third arg is closure and second is array
                if ($thirdArg->value instanceof Closure && $secondArg->value instanceof Array_) {
                    // Check if second argument is an empty array - if so, just remove it
                    $isEmptyArray = count($secondArg->value->items) === 0;

                    if ($isEmptyArray) {
                        $node->args = [
                            $node->args[0],
                            $thirdArg,
                        ];
                    } else {
                        // Swap: callback becomes second, options becomes third
                        $node->args[1] = $thirdArg;
                        $node->args[2] = $secondArg;
                    }

                    return $node;
                }
            }

            return null;
        }

        // For scope/prefix/plugin methods
        // Old signature: method(string $path, array|callable $params, ?callable $callback = null)
        // New signature: method(string $path, Closure $callback, array $params = [])

        // Only process if there are exactly 3 arguments
        if ($argCount !== 3) {
            return null;
        }

        $secondArg = $node->args[1];
        $thirdArg = $node->args[2];

        // Check if the third argument is a closure/callable
        // and second argument is array (the old signature)
        if ($thirdArg->value instanceof Closure) {
            // Check if second argument is an empty array - if so, just remove it
            $isEmptyArray = $secondArg->value instanceof Array_ && count($secondArg->value->items) === 0;

            if ($isEmptyArray) {
                // Just use callback as second arg, drop the empty array
                $node->args = [
                    $node->args[0],
                    $thirdArg,
                ];
            } else {
                // Swap: callback becomes second, params becomes third
                $node->args[1] = $thirdArg;
                $node->args[2] = $secondArg;
            }

            return $node;
        }

        return null;
    }
}
