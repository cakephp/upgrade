<?php
declare(strict_types=1);

namespace Rector\CakePHP\ValueObject;

use PHPStan\Type\ObjectType;

final class FactoryMethod
{
    public function __construct(
        private readonly string $type,
        private readonly string $method,
        private readonly string $newClass,
        private readonly int $position
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
