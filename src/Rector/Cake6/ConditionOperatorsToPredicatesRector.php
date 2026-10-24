<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake6;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\UseItem;
use PhpParser\NodeFinder;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\FileNode;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces operator-bearing condition keys with CakePHP 6 predicate functions.
 */
final class ConditionOperatorsToPredicatesRector extends AbstractRector
{
    /**
     * The file currently being transformed, including imports added by this rule.
     *
     * @var \Rector\PhpParser\Node\FileNode
     */
    private FileNode $currentFile;

    /**
     * Dedicated functions for the operators understood by condition arrays.
     *
     * @var array<string, string>
     */
    private const HELPERS = [
        '=' => 'equals', '!=' => 'notEquals', '<>' => 'notEquals',
        '>' => 'greaterThan', '>=' => 'greaterThanOrEquals', '<' => 'lessThan', '<=' => 'lessThanOrEquals',
        'IN' => 'in', 'NOT IN' => 'notIn', 'LIKE' => 'like', 'NOT LIKE' => 'notLike',
        'IS' => 'equals', 'IS NOT' => 'notEquals',
        'IS DISTINCT FROM' => 'isDistinctFrom', 'IS NOT DISTINCT FROM' => 'isNotDistinctFrom',
    ];

    /**
     * Creates the AST finder used to inspect local variable references.
     *
     * @param \PhpParser\NodeFinder $finder The node finder.
     */
    public function __construct(private readonly NodeFinder $finder = new NodeFinder())
    {
    }

    /**
     * Describes the condition migration and its generated code.
     *
     * @return \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace condition operators with predicate functions', [
            new CodeSample(
                '$query->where([\'id >\' => $minimum]);',
                <<<'CODE'
use function Cake\Database\Expression\greaterThan;

$query->where(['id' => greaterThan($minimum)]);
CODE,
            ),
        ]);
    }

    /**
     * Lists nodes containing typed condition consumers or local condition variables.
     *
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [FileNode::class];
    }

    /**
     * Converts only arrays proven to be used as CakePHP conditions.
     *
     * @param \PhpParser\Node $node The node to migrate.
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FileNode) {
            return null;
        }
        $this->currentFile = $node;
        $changed = false;
        foreach ($this->finder->find($node->stmts, static fn(Node $n): bool => $n instanceof ClassMethod || $n instanceof Function_) as $scope) {
            if ($scope instanceof ClassMethod || $scope instanceof Function_) {
                $changed = $this->convertScope($scope) || $changed;
            }
        }
        $references = [];
        foreach ($this->finder->find($node->stmts, static fn(Node $n): bool => $n instanceof MethodCall || $n instanceof New_) as $call) {
            $changed = $this->convertCall($call, $references) || $changed;
        }

        return $changed ? $node : null;
    }

    /**
     * Converts inline conditions and local arrays used exclusively as condition arguments.
     *
     * @param \PhpParser\Node\Stmt\ClassMethod|\PhpParser\Node\Stmt\Function_ $node The local scope.
     * @return bool Whether code changed.
     */
    private function convertScope(ClassMethod|Function_ $node): bool
    {
        $references = [];
        $changed = false;
        foreach ($this->finder->find($node->stmts ?? [], static fn(Node $n): bool => $n instanceof MethodCall || $n instanceof New_) as $call) {
            $changed = $this->convertCall($call, $references) || $changed;
        }
        foreach ($references as $name => $uses) {
            $variables = $this->finder->find($node->stmts ?? [], static fn(Node $n): bool => $n instanceof Variable && $n->name === $name);
            $assignments = $this->finder->find($node->stmts ?? [], static fn(Node $n): bool => $n instanceof Assign && $n->var instanceof Variable && $n->var->name === $name);
            if (count($assignments) !== 1 || count($variables) !== count($uses) + 1) {
                continue;
            }
            $assignment = $assignments[0];
            if ($assignment instanceof Assign && $assignment->expr instanceof Array_) {
                $changed = $this->convertConditions($assignment->expr) || $changed;
            }
        }

        return $changed;
    }

    /**
     * Finds condition arguments on supported CakePHP objects.
     *
     * @param \PhpParser\Node $node The call or construction to examine.
     * @param array<string, array<int, true>> $references Proven condition variable reads.
     * @return bool Whether code changed.
     */
    private function convertCall(Node $node, array &$references): bool
    {
        if ($node instanceof New_) {
            if (!$this->isName($node->class, 'Cake\Database\Expression\QueryExpression')) {
                return false;
            }

            $changed = false;
            foreach ($node->args as $position => $arg) {
                if ($arg instanceof Arg && (($arg->name === null && $position === 0) || $arg->name?->toString() === 'conditions')) {
                    $changed = $this->convertArgument($arg, $references) || $changed;
                }
            }

            return $changed;
        }
        if (!$node instanceof MethodCall || !$node->name instanceof Identifier || $node->isFirstClassCallable()) {
            return false;
        }

        $method = strtolower($node->name->toString());
        $positions = [];
        $options = [];
        if ($this->isObjectType($node->var, new ObjectType('Cake\Database\Connection'))) {
            $positions = match ($method) {
                'update' => [2],
                'delete' => [1],
                default => [],
            };
        }
        if ($this->isObjectType($node->var, new ObjectType('Cake\Database\Query'))) {
            if (in_array($method, ['where', 'andwhere', 'having', 'andhaving', 'expr', 'newexpr'], true)) {
                $positions = [0];
            } elseif (in_array($method, ['innerjoin', 'leftjoin', 'rightjoin'], true)) {
                $positions = [1];
            } elseif ($method === 'join') {
                $options = [0];
            }
        }
        if (
            $this->isObjectType($node->var, new ObjectType('Cake\Database\Expression\QueryExpression'))
            && !$this->isObjectType($node->var, new ObjectType('Cake\Database\Expression\FunctionExpression'))
            && !$this->isObjectType($node->var, new ObjectType('Cake\Database\Expression\OrderByExpression')) && in_array($method, ['add', 'and', 'or', 'not'], true)
        ) {
            $positions = [0];
        }
        foreach (['CaseStatementExpression', 'WhenThenExpression', 'AggregateExpression'] as $class) {
            if ($this->isObjectType($node->var, new ObjectType('Cake\Database\Expression\\' . $class)) && in_array($method, ['when', 'filter'], true)) {
                $positions = [0];
            }
        }
        foreach (['Cake\ORM\Table', 'Cake\ORM\Association'] as $class) {
            if (!$this->isObjectType($node->var, new ObjectType($class))) {
                continue;
            }
            if (in_array($method, ['exists', 'deleteall', 'setconditions'], true)) {
                $positions = [0];
            } elseif ($method === 'updateall') {
                $positions = [1];
            } elseif (in_array($method, ['find', 'findall', 'findlist', 'findthreaded', 'hasmany', 'hasone', 'belongsto', 'belongstomany'], true)) {
                $options = [1];
            }
        }

        $changed = false;
        foreach ($node->args as $position => $arg) {
            if (!$arg instanceof Arg) {
                continue;
            }
            $name = $arg->name?->toString();
            if (($name === null && in_array($position, $positions, true)) || ($positions !== [] && in_array($name, ['conditions', 'rawExpression'], true))) {
                $changed = $this->convertArgument($arg, $references) || $changed;
            } elseif (($name === null && in_array($position, $options, true)) || ($options !== [] && in_array($name, ['options', 'tables'], true))) {
                if ($arg->value instanceof Array_) {
                    $changed = $this->convertOptions($arg->value, $references) || $changed;
                }
            } elseif ($options !== [] && $name === 'conditions') {
                $changed = $this->convertArgument($arg, $references) || $changed;
            }
        }

        return $changed;
    }

    /**
     * Converts conditions inside join or association options without touching other data.
     *
     * @param \PhpParser\Node\Expr\Array_ $array The options array.
     * @param array<string, array<int, true>> $references Proven condition variable reads.
     * @return bool Whether code changed.
     */
    private function convertOptions(Array_ $array, array &$references): bool
    {
        $changed = false;
        foreach ($array->items as $item) {
            if ($item->key instanceof String_ && strtolower($item->key->value) === 'conditions') {
                $changed = $this->convertArgument(new Arg($item->value), $references) || $changed;
            } elseif ($item->value instanceof Array_) {
                $changed = $this->convertOptions($item->value, $references) || $changed;
            }
        }

        return $changed;
    }

    /**
     * Converts an inline array, callback result, or records a local condition variable.
     *
     * @param \PhpParser\Node\Arg|null $arg The condition argument.
     * @param array<string, array<int, true>> $references Proven condition variable reads.
     * @return bool Whether code changed.
     */
    private function convertArgument(?Arg $arg, array &$references): bool
    {
        if ($arg === null) {
            return false;
        }
        $value = $arg->value;
        if ($value instanceof Array_) {
            return $this->convertConditions($value);
        }
        if ($value instanceof Variable && is_string($value->name)) {
            $references[$value->name][spl_object_id($value)] = true;
        }
        if ($value instanceof ArrowFunction) {
            return $this->convertArgument(new Arg($value->expr), $references);
        }
        if ($value instanceof Closure) {
            $changed = false;
            foreach ($value->stmts as $statement) {
                if ($statement instanceof Return_ && $statement->expr !== null) {
                    $changed = $this->convertArgument(new Arg($statement->expr), $references) || $changed;
                }
            }

            return $changed;
        }

        return false;
    }

    /**
     * Converts condition keys while preserving boolean grouping and field collisions.
     *
     * @param \PhpParser\Node\Expr\Array_ $array The condition array.
     * @return bool Whether code changed.
     */
    private function convertConditions(Array_ $array): bool
    {
        $changed = false;
        $seen = [];
        foreach ($array->items as $item) {
            if (($item->unpack || ($item->key !== null && !$item->key instanceof String_ && !$item->key instanceof Int_))) {
                return false;
            }
            if ($item->key instanceof String_) {
                if (isset($seen[$item->key->value])) {
                    return false;
                }
                $seen[$item->key->value] = true;
            }
        }
        $fields = [];
        $parsed = [];
        foreach ($array->items as $index => $item) {
            if (($item->key === null || $item->key instanceof Int_ || ($item->key instanceof String_ && in_array(strtolower($item->key->value), ['and', 'or', 'xor', 'not'], true))) && $item->value instanceof Array_) {
                $changed = $this->convertConditions($item->value) || $changed;
                continue;
            }
            if (!$item->key instanceof String_) {
                continue;
            }
            $parts = $this->parseKey($item->key->value);
            $field = $parts[0] ?? $item->key->value;
            $fields[$field] = ($fields[$field] ?? 0) + 1;
            if ($parts !== null) {
                $parsed[$index] = $parts;
            }
        }
        foreach ($parsed as $index => [$field, $operator]) {
            $item = $array->items[$index];
            $helper = self::HELPERS[$operator] ?? 'comparison';
            $args = [new Arg($item->value)];
            if ($helper === 'comparison') {
                array_unshift($args, new Arg(new String_($operator)));
            }
            $item->value = new FuncCall($this->predicateFunction($helper, $array), $args);
            $item->key = new String_($field);
            if ($fields[$field] > 1) {
                $array->items[$index] = new ArrayItem(new Array_([$item]), null);
            }
            $changed = true;
        }

        return $changed;
    }

    /**
     * Imports a predicate function without changing existing function resolution.
     *
     * @param string $helper The predicate function to import.
     * @param \PhpParser\Node\Expr\Array_ $array The condition array's namespace context.
     * @return \PhpParser\Node\Name The imported function name or alias.
     */
    private function predicateFunction(string $helper, Array_ $array): Name
    {
        $fileNode = $this->currentFile;
        $namespace = ScopeFetcher::fetch($array)->getNamespace();
        $statements = &$fileNode->stmts;
        foreach ($fileNode->stmts as $statement) {
            if ($statement instanceof Namespace_ && $statement->name?->toString() === $namespace) {
                $statements = &$statement->stmts;
                break;
            }
        }

        $target = 'Cake\Database\Expression\\' . $helper;
        $occupied = [];
        foreach ($statements as $statement) {
            if (!$statement instanceof Use_ && !$statement instanceof GroupUse) {
                continue;
            }
            foreach ($statement->uses as $use) {
                if (($use->type ?: $statement->type) !== Use_::TYPE_FUNCTION) {
                    continue;
                }
                $name = ($statement instanceof GroupUse ? $statement->prefix->toString() . '\\' : '')
                    . $use->name->toString();
                if (strcasecmp($name, $target) === 0) {
                    return new Name($use->getAlias()->toString());
                }
                $occupied[strtolower($use->getAlias()->toString())] = true;
            }
        }
        foreach ($this->finder->findInstanceOf($statements, Function_::class) as $function) {
            $occupied[strtolower($function->name->toString())] = true;
        }
        foreach ($this->finder->findInstanceOf($statements, FuncCall::class) as $call) {
            if ($call->name instanceof Name && $call->name->isUnqualified()) {
                $occupied[strtolower($call->name->toString())] = true;
            }
        }

        $alias = $helper;
        if (isset($occupied[strtolower($alias)])) {
            $alias = 'cake' . ucfirst($helper);
            for ($suffix = 2; isset($occupied[strtolower($alias)]); $suffix++) {
                $alias = 'cake' . ucfirst($helper) . $suffix;
            }
        }
        $this->addPredicateImport($statements, $helper, $alias);

        return new Name($alias);
    }

    /**
     * Groups predicate imports while keeping generated declarations within the coding standard's line length.
     *
     * @param array<\PhpParser\Node\Stmt> $statements The namespace statements.
     * @param string $helper The predicate function to import.
     * @param string $alias The collision-free local name.
     * @return void
     */
    private function addPredicateImport(array &$statements, string $helper, string $alias): void
    {
        $prefix = 'Cake\Database\Expression';
        $members = [new UseItem(new Name($helper), $alias === $helper ? null : new Identifier($alias))];
        $remaining = [];
        $comments = [];
        foreach ($statements as $statement) {
            if (
                $statement instanceof GroupUse && $statement->type === Use_::TYPE_FUNCTION
                && $statement->prefix->toString() === $prefix
            ) {
                $members = [...$members, ...$statement->uses];
                $comments = [...$comments, ...$statement->getComments()];
            } elseif (
                $statement instanceof Use_ && $statement->type === Use_::TYPE_FUNCTION
                && count($statement->uses) === 1
                && str_starts_with($statement->uses[0]->name->toString(), $prefix . '\\')
                && !str_contains(substr($statement->uses[0]->name->toString(), strlen($prefix) + 1), '\\')
            ) {
                $use = $statement->uses[0];
                $members[] = new UseItem(new Name($use->name->getLast()), $use->alias);
                $comments = [...$comments, ...$statement->getComments()];
            } else {
                $remaining[] = $statement;
            }
        }
        usort($members, static fn(UseItem $a, UseItem $b): int => strcasecmp($a->name->toString(), $b->name->toString()));
        $chunks = [];
        $chunk = [];
        $length = strlen('use function ' . $prefix . '\{};');
        foreach ($members as $member) {
            $memberLength = strlen($member->name->toString())
                + ($member->alias === null ? 0 : strlen(' as ' . $member->alias->toString())) + 2;
            if ($chunk !== [] && $length + $memberLength > 120) {
                $chunks[] = $chunk;
                $chunk = [];
                $length = strlen('use function ' . $prefix . '\{};');
            }
            $chunk[] = $member;
            $length += $memberLength;
        }
        $chunks[] = $chunk;
        $imports = [];
        foreach ($chunks as $chunk) {
            $imports[] = count($chunk) === 1
                ? new Use_([new UseItem(new Name($prefix . '\\' . $chunk[0]->name->toString()), $chunk[0]->alias)], Use_::TYPE_FUNCTION)
                : new GroupUse(new Name($prefix), $chunk, Use_::TYPE_FUNCTION);
        }
        $imports[0]->setAttribute('comments', $comments);
        $position = 0;
        foreach ($remaining as $index => $statement) {
            if ($statement instanceof Declare_ || $statement instanceof Use_ || $statement instanceof GroupUse) {
                $position = $index + 1;
            }
            if (
                ($statement instanceof Use_ && $statement->type === Use_::TYPE_FUNCTION
                    && strcasecmp($statement->uses[0]->name->toString(), $prefix . '\\') > 0)
                || ($statement instanceof GroupUse && $statement->type === Use_::TYPE_FUNCTION
                    && strcasecmp($statement->prefix->toString(), $prefix) > 0)
            ) {
                $position = $index;
                break;
            }
        }
        array_splice($remaining, $position, 0, $imports);
        $statements = $remaining;
    }

    /**
     * Splits a static legacy key using the original parser's multi-word operator rules.
     *
     * @param string $key The legacy condition key.
     * @return array{string, string}|null The field and operator, when present.
     */
    private function parseKey(string $key): ?array
    {
        $key = trim($key);
        $isOperators = 'IS(?:\\s+NOT)?(?:\\s+DISTINCT\\s+FROM)?';
        $wordOperators = '(?:NOT\\s+)?SIMILAR\\s+TO|NOT\\s+\\w+';
        $operatorPattern = '/^(.*?)\\s+(' . $isOperators . '|' . $wordOperators . '|[^\\s]+)$/i';
        if (!preg_match($operatorPattern, $key, $matches)) {
            return null;
        }
        $field = trim($matches[1]);
        $operator = strtoupper((string)preg_replace('/\\s+/', ' ', $matches[2]));
        if ($field === '' || str_ends_with($key, ')') || str_ends_with($key, "'") || str_ends_with($key, '"')) {
            return null;
        }

        if (
            !isset(self::HELPERS[$operator])
            && !preg_match('/^[!<>=~@#%^&|?+*\\\\\\/-]+$/', $operator)
            && !in_array($operator, ['ILIKE', 'NOT ILIKE', 'REGEXP', 'NOT REGEXP', 'RLIKE', 'NOT RLIKE', 'GLOB', 'MATCH', 'SIMILAR TO', 'NOT SIMILAR TO', 'OVERLAPS'], true)
        ) {
            return null;
        }

        return [$field, $operator];
    }
}
