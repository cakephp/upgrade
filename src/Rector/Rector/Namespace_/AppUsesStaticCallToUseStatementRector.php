<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Rector\Namespace_;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;
use PHPStan\Type\ObjectType;
use Cake\Upgrade\Rector\ShortClassNameResolver;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://github.com/cakephp/upgrade/blob/756410c8b7d5aff9daec3fa1fe750a3858d422ac/src/Shell/Task/AppUsesTask.php
 * @see https://github.com/cakephp/upgrade/search?q=uses&unscoped_q=uses
 *
 * @see \Cake\Upgrade\Rector\Tests\Rector\Namespace_\AppUsesStaticCallToUseStatementRector\AppUsesStaticCallToUseStatementRectorTest
 */
final class AppUsesStaticCallToUseStatementRector extends AbstractRector
{
    public function __construct(
        private ShortClassNameResolver $shortClassNameResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change App::uses() to use imports', [
            new CodeSample(
                <<<'CODE_SAMPLE'
App::uses('NotificationListener', 'Event');

CakeEventManager::instance()->attach(new NotificationListener());
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Event\NotificationListener;

CakeEventManager::instance()->attach(new NotificationListener());
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [FileWithoutNamespace::class, Namespace_::class];
    }

    /**
     * @param \Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace|\PhpParser\Node\Stmt\Namespace_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $appUsesStaticCalls = $this->collectAppUseStaticCalls($node);
        if ($appUsesStaticCalls === []) {
            return null;
        }

        $this->nodeRemover->removeNodes($appUsesStaticCalls);

        $names = $this->resolveNamesFromStaticCalls($appUsesStaticCalls);
        $uses = $this->nodeFactory->createUsesFromNames($names);

        if ($node instanceof Namespace_) {
            $node->stmts = array_merge($uses, $node->stmts);

            return $node;
        }

        return $this->refactorFile($node, $uses);
    }

    /**
     * @return \PhpParser\Node\Expr\StaticCall[]
     */
    private function collectAppUseStaticCalls(Node $node): array
    {
        /** @var \PhpParser\Node\Expr\StaticCall[] $appUsesStaticCalls */
        $appUsesStaticCalls = $this->betterNodeFinder->find($node, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            $callerType = $this->nodeTypeResolver->getType($node->class);
            if (! $callerType->isSuperTypeOf(new ObjectType('App'))->yes()) {
                return false;
            }

            return $this->isName($node->name, 'uses');
        });

        return $appUsesStaticCalls;
    }

    /**
     * @param \PhpParser\Node\Expr\StaticCall[] $staticCalls
     * @return string[]
     */
    private function resolveNamesFromStaticCalls(array $staticCalls): array
    {
        $names = [];
        foreach ($staticCalls as $staticCall) {
            $names[] = $this->createFullyQualifiedNameFromAppUsesStaticCall($staticCall);
        }

        return $names;
    }

    /**
     * @param \PhpParser\Node\Stmt\Use_[] $fileWithoutNamespace
     */
    private function refactorFile(FileWithoutNamespace $fileWithoutNamespace, array $uses): ?FileWithoutNamespace
    {
        $hasNamespace = $this->betterNodeFinder->findFirstInstanceOf($fileWithoutNamespace, Namespace_::class);
        // already handled above
        if ($hasNamespace !== null) {
            return null;
        }

        $hasDeclare = $this->betterNodeFinder->findFirstInstanceOf($fileWithoutNamespace, Declare_::class);
        if ($hasDeclare !== null) {
            return $this->refactorFileWithDeclare($fileWithoutNamespace, $uses);
        }

        $fileWithoutNamespace->stmts = array_merge($uses, $fileWithoutNamespace->stmts);

        return $fileWithoutNamespace;
    }

    private function createFullyQualifiedNameFromAppUsesStaticCall(StaticCall $staticCall): string
    {
        /** @var string $shortClassName */
        $shortClassName = $this->valueResolver->getValue($staticCall->args[0]->value);

        /** @var string $namespaceName */
        $namespaceName = $this->valueResolver->getValue($staticCall->args[1]->value);

        return $this->shortClassNameResolver->resolveShortClassName(
            $namespaceName,
            $shortClassName
        );
    }

    /**
     * @param \PhpParser\Node\Stmt\Use_[] $fileWithoutNamespace
     */
    private function refactorFileWithDeclare(
        FileWithoutNamespace $fileWithoutNamespace,
        array $uses
    ): FileWithoutNamespace {
        $newStmts = [];
        foreach ($fileWithoutNamespace->stmts as $stmt) {
            $newStmts[] = $stmt;

            if ($stmt instanceof Declare_) {
                foreach ($uses as $use) {
                    $newStmts[] = $use;
                }

                continue;
            }
        }

        return new FileWithoutNamespace($newStmts);
    }
}
