<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cake\Upgrade\Test\TestCase\Rector\MethodCall\RouteBuilderToCallbackFirstRector\RouteBuilderToCallbackFirstRectorTest
 */
final class RouteBuilderToCallbackFirstRector extends AbstractRector
{
    /**
     * Methods that need argument reordering
     */
    private const METHODS = ['scope', 'prefix', 'plugin', 'resources'];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Reorder RouteBuilder scope/prefix/plugin/resources arguments to have callback second and params/options third',
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
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node->var, new \PHPStan\Type\ObjectType('Cake\Routing\RouteBuilder'))) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if (!in_array($methodName, self::METHODS, true)) {
            return null;
        }

        $args = $node->getArgs();
        $argCount = count($args);

        // Handle resources() special case: 2 args with options array (no callback)
        // resources('Name', ['only' => 'index']) -> resources('Name', null, ['only' => 'index'])
        if ($methodName === 'resources' && $argCount === 2) {
            $secondArg = $args[1]->value;
            if ($secondArg instanceof Array_ && !$this->isEmptyArray($secondArg)) {
                $node->args = [
                    $args[0],
                    new Arg(new ConstFetch(new Name('null'))),
                    $args[1],
                ];

                return $node;
            }
        }

        // Need exactly 3 arguments for swap
        if ($argCount !== 3) {
            return null;
        }

        $secondArg = $args[1]->value;
        $thirdArg = $args[2]->value;

        // Check if second arg is array and third is closure (old style)
        if (!$secondArg instanceof Array_) {
            return null;
        }

        if (!$thirdArg instanceof Closure) {
            return null;
        }

        // If array is empty, just remove it (callback becomes second arg)
        if ($this->isEmptyArray($secondArg)) {
            $node->args = [
                $args[0],
                $args[2],
            ];

            return $node;
        }

        // Swap: callback second, array third
        $node->args = [
            $args[0],
            $args[2],
            $args[1],
        ];

        return $node;
    }

    private function isEmptyArray(Array_ $array): bool
    {
        return $array->items === [];
    }
}
