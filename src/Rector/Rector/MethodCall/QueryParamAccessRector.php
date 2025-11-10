<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Transforms $request->getParam('?') to $request->getQueryParams()
 *
 * In CakePHP 6.0, the getParam('?') shortcut for accessing query parameters
 * is removed. Instead, use getQueryParams() directly.
 *
 * @see https://book.cakephp.org/6/en/appendices/6-0-migration-guide.html
 */
final class QueryParamAccessRector extends AbstractRector
{
    public function __construct(
        private ValueResolver $valueResolver,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change $request->getParam(\'?\') to $request->getQueryParams()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$queryParams = $request->getParam('?');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$queryParams = $request->getQueryParams();
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

        // Must be ->getParam() call
        if (!$node->name instanceof Identifier || $node->name->toString() !== 'getParam') {
            return null;
        }

        // Must have at least one argument
        if (count($node->args) < 1) {
            return null;
        }

        // First argument must be the string '?'
        $firstArg = $node->args[0]->value;
        if (!$this->valueResolver->isValue($firstArg, '?')) {
            return null;
        }

        // Check if this is called on a Request object
        $callerType = $this->getType($node->var);
        if (!$callerType instanceof ObjectType) {
            return null;
        }

        if (
            !$callerType->isInstanceOf('Cake\Http\ServerRequest')->yes() &&
            !$callerType->isInstanceOf('Psr\Http\Message\ServerRequestInterface')->yes()
        ) {
            return null;
        }

        // Transform to getQueryParams() with no arguments
        $node->name = new Identifier('getQueryParams');
        $node->args = [];

        return $node;
    }
}
