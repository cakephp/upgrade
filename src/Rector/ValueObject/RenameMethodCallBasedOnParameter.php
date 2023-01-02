<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

use PHPStan\Type\ObjectType;

final class RenameMethodCallBasedOnParameter
{
    public function __construct(
        private string $oldClass,
        private string $oldMethod,
        private string $parameterName,
        private string $newMethod
    ) {
    }

    public function getOldMethod(): string
    {
        return $this->oldMethod;
    }

    public function getParameterName(): string
    {
        return $this->parameterName;
    }

    public function getNewMethod(): string
    {
        return $this->newMethod;
    }

    public function getOldObjectType(): ObjectType
    {
        return new ObjectType($this->oldClass);
    }
}
