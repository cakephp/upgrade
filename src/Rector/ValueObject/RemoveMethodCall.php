<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

use PHPStan\Type\ObjectType;

final class RemoveMethodCall
{
    public function __construct(
        private string $class,
        private string $methodName
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }
}
