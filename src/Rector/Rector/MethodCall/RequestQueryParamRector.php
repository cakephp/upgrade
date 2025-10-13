<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replace $request->getParam('?') with $request->getQueryParams()
 * for ServerRequest instances in CakePHP 6.0
 */
final class RequestQueryParamRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace $request->getParam(\'?\') with $request->getQueryParams()',
            [
                new CodeSample(
                    '$queryParams = $request->getParam(\'?\');',
                    '$queryParams = $request->getQueryParams();',
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

        if (!$this->isName($node->name, 'getParam')) {
            return null;
        }

        // Check if this is a ServerRequest method call
        $objectType = $this->getType($node->var);
        if (!$objectType instanceof ObjectType) {
            return null;
        }

        if (!$objectType->isInstanceOf('Cake\Http\ServerRequest')->yes()) {
            return null;
        }

        // Check if the first argument is the string '?'
        if (count($node->args) === 0) {
            return null;
        }

        $firstArg = $node->args[0]->value;
        if (!$firstArg instanceof String_) {
            return null;
        }

        if ($firstArg->value !== '?') {
            return null;
        }

        // Replace with getQueryParams() call with no arguments
        return new MethodCall(
            $node->var,
            new Identifier('getQueryParams'),
        );
    }
}
