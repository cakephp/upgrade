<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

use PHPStan\Type\ObjectType;

final class FactoryMethod
{
    public function __construct(
        private string $type,
        private string $method,
        private string $newClass,
        private int $position
    ) {
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->type);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getNewClass(): string
    {
        return $this->newClass;
    }
}
