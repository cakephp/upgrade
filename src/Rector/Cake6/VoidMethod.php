<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake6;

use PHPStan\Type\ObjectType;

final class VoidMethod
{
    public function __construct(
        private readonly string $class,
        private readonly string $method,
    ) {
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
