<?php
declare(strict_types=1);

namespace Rector\CakePHP\ValueObject;

use PHPStan\Type\ObjectType;

final class RenameMethodCallBasedOnParameter
{
    public function __construct(
        private readonly string $oldClass,
        private readonly string $oldMethod,
        private readonly string $parameterName,
        private readonly string $newMethod
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
