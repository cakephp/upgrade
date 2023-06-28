<?php
declare (strict_types=1);

namespace Cake\Upgrade\Rector\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;

/**
 * Utils for chain of MethodCall Node:
 * "$this->methodCall()->chainedMethodCall()"
 *
 * Taken from https://github.com/rectorphp/rector/blob/d8da002b107c9b64d464bb48101290d4d078df4b/packages/Defluent/NodeAnalyzer/FluentChainMethodCallNodeAnalyzer.php
 * https://github.com/rectorphp/rector/blob/main/LICENSE
 */
final class FluentChainMethodCallNodeAnalyzer
{
    /**
     * @api doctrine
     */
    public function resolveRootMethodCall(MethodCall $methodCall) : ?MethodCall
    {
        $callerNode = $methodCall->var;
        while ($callerNode instanceof MethodCall && $callerNode->var instanceof MethodCall) {
            $callerNode = $callerNode->var;
        }
        if ($callerNode instanceof MethodCall) {
            return $callerNode;
        }
        return null;
    }
    /**
     * @return \PhpParser\Node\Expr|\PhpParser\Node\Name
     */
    public function resolveRootExpr(MethodCall $methodCall)
    {
        $callerNode = $methodCall->var;
        while ($callerNode instanceof MethodCall || $callerNode instanceof StaticCall) {
            $callerNode = $callerNode instanceof StaticCall ? $callerNode->class : $callerNode->var;
        }
        return $callerNode;
    }
}
