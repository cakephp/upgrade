<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use Cake\Command\Command;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ReflectionProvider;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceCommandArgsIoWithPropertiesRector extends AbstractRector
{
    public function __construct(
        protected BetterNodeFinder $betterNodeFinder,
        protected ReflectionProvider $reflectionProvider,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace `$args` and `$io` parameters in Command classes with `$this->args` and `$this->io`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class TestCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Hello');
        $this->someMethod($args, $io);
    }

    protected function someMethod(Arguments $args, ConsoleIo $io): void
    {
        $someArg = $args->getArgument('some');
        $io->warning('Warn');
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class TestCommand extends Command
{
    public function execute()
    {
        $this->io->out('Hello');
        $this->someMethod();
    }

    protected function someMethod(): void
    {
        $someArg = $this->args->getArgument('some');
        $this->io->warning('Warn');
    }
}
CODE_SAMPLE,
                ),
            ],
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof ClassMethod) {
            return null;
        }

        // Make sure we are in a class
        $scope = ScopeFetcher::fetch($node);
        if (!$scope->isInClass()) {
            return null;
        }
        $class = $scope->getClassReflection();

        // Skip if class doesn't extend Command (you can expand to check parent name)
        $baseCommandClass = $this->reflectionProvider->getClass(Command::class);
        if ($class->getName() === Command::class || $class->isSubclassOfClass($baseCommandClass) === false) {
            return null;
        }

        // Find if params are $args and/or $io
        $argsParam = $this->findParam($node, 'args');
        $ioParam = $this->findParam($node, 'io');

        if (! $argsParam && ! $ioParam) {
            return null;
        }

        // Replace all `$args` and `$io` usages inside the method body
        $this->traverseNodesWithCallable($node->stmts ?? [], function (Node $innerNode) use ($argsParam, $ioParam) {
            // Replace `$args` and `$io` variables
            if ($innerNode instanceof Variable) {
                if ($argsParam && $innerNode->name === 'args') {
                    return new PropertyFetch(new Variable('this'), 'args');
                }

                if ($ioParam && $innerNode->name === 'io') {
                    return new PropertyFetch(new Variable('this'), 'io');
                }
            }

            // Remove `$args` / `$io` from method calls on `$this`
            if (
                $innerNode instanceof MethodCall
                && $innerNode->var instanceof Variable
                && $innerNode->var->name === 'this'
            ) {
                $innerNode->args = array_values(array_filter(
                    $innerNode->args,
                    fn(Node\Arg $arg) => !($arg->value instanceof Variable &&
                        in_array($arg->value->name, ['args', 'io'], true)),
                ));

                return $innerNode;
            }

            return null;
        });

        // Remove the parameters themselves
        $node->params = array_filter($node->params, function (Param $param) {
            return !in_array($this->getName($param), ['args', 'io'], true);
        });

        return $node;
    }

    private function findParam(ClassMethod $method, string $name): ?Param
    {
        foreach ($method->params as $param) {
            if ($this->getName($param) === $name) {
                return $param;
            }
        }

        return null;
    }
}
