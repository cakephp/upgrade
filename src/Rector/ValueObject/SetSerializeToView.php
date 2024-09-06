<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

use PHPStan\Type\ObjectType;

final class SetSerializeToView
{
    public function __construct(
        private string $class
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }
}
