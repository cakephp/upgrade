<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake5;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Renames Form _execute() to process() if process() doesn't exist.
 *
 * Form::_execute() is deprecated in CakePHP 5.x. The method should be renamed to process()
 * which accepts the same parameters and has the same return type.
 */
final class FormExecuteToProcessRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename Form _execute() to process() if process() doesn\'t exist',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Cake\Form\Form;

class ContactForm extends Form
{
    protected function _execute(array $data, $form): bool
    {
        // Send email or save data
        return true;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Cake\Form\Form;

class ContactForm extends Form
{
    protected function process(array $data, $form): bool
    {
        // Send email or save data
        return true;
    }
}
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
        return [Class_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, new ObjectType('Cake\Form\Form'))) {
            return null;
        }

        // Check if process() already exists
        $hasProcess = false;
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && $this->isName($stmt->name, 'process')) {
                $hasProcess = true;
                break;
            }
        }

        // If process() exists, don't rename _execute()
        if ($hasProcess) {
            return null;
        }

        // Find and rename _execute() method
        $hasChanges = false;
        foreach ($node->stmts as $stmt) {
            if (!$stmt instanceof ClassMethod) {
                continue;
            }

            if (!$this->isName($stmt->name, '_execute')) {
                continue;
            }

            // Rename the method to process()
            $stmt->name = new Identifier('process');

            $hasChanges = true;
            break;
        }

        return $hasChanges ? $node : null;
    }
}
