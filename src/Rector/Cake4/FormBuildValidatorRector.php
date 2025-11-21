<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake4;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Renames Form buildValidator() to validationDefault() if validationDefault() doesn't exist.
 *
 * @see https://github.com/cakephp/upgrade/issues/143
 */
final class FormBuildValidatorRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename Form buildValidator() to validationDefault() if validationDefault() doesn\'t exist',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Cake\Form\Form;
use Cake\Event\Event;
use Cake\Validation\Validator;

class ContactForm extends Form
{
    public function buildValidator(Event $event, Validator $validator, $name)
    {
        return $validator;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Cake\Form\Form;
use Cake\Event\Event;
use Cake\Validation\Validator;

class ContactForm extends Form
{
    public function validationDefault(Validator $validator): Validator
    {
        return $validator;
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

        // Check if validationDefault() already exists
        $hasValidationDefault = false;
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && $this->isName($stmt->name, 'validationDefault')) {
                $hasValidationDefault = true;
                break;
            }
        }

        // If validationDefault() exists, don't rename buildValidator()
        if ($hasValidationDefault) {
            return null;
        }

        // Find and rename buildValidator() method
        $hasChanges = false;
        foreach ($node->stmts as $stmt) {
            if (!$stmt instanceof ClassMethod) {
                continue;
            }

            if (!$this->isName($stmt->name, 'buildValidator')) {
                continue;
            }

            // Rename the method
            $stmt->name = new Identifier('validationDefault');

            // Update the signature: keep only the Validator parameter
            $validatorParam = null;
            foreach ($stmt->params as $param) {
                $isFullyQualified = $param->type instanceof FullyQualified &&
                    $param->type->toString() === 'Cake\Validation\Validator';
                if ($isFullyQualified) {
                    $validatorParam = $param;
                    break;
                }
                // Also check for short name
                if ($param->type instanceof Identifier && $param->type->name === 'Validator') {
                    $validatorParam = $param;
                    break;
                }
            }

            if ($validatorParam !== null) {
                $stmt->params = [$validatorParam];
            }

            // Add return type
            $stmt->returnType = new Identifier('Validator');

            $hasChanges = true;
            break;
        }

        return $hasChanges ? $node : null;
    }
}
