<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

use PHPStan\Type\ObjectType;

final class AddMethodCallArgs
{
    private array $values;

    public function __construct(
        private string $class,
        private string $methodName,
        ...$values
    ) {
        $this->values = $values;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }
}
