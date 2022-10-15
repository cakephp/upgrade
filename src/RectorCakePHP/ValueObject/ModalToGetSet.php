<?php

declare(strict_types=1);

namespace Rector\CakePHP\ValueObject;

use PHPStan\Type\ObjectType;

final class ModalToGetSet
{
    private readonly string $getMethod;

    private readonly string $setMethod;

    public function __construct(
        private readonly string $type,
        private readonly string $unprefixedMethod,
        ?string $getMethod = null,
        ?string $setMethod = null,
        private readonly int $minimalSetterArgumentCount = 1,
        private readonly ?string $firstArgumentType = null
    ) {
        $this->getMethod = $getMethod ?? 'get' . ucfirst($unprefixedMethod);
        $this->setMethod = $setMethod ?? 'set' . ucfirst($unprefixedMethod);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->type);
    }

    public function getUnprefixedMethod(): string
    {
        return $this->unprefixedMethod;
    }

    public function getGetMethod(): string
    {
        return $this->getMethod;
    }

    public function getSetMethod(): string
    {
        return $this->setMethod;
    }

    public function getMinimalSetterArgumentCount(): int
    {
        return $this->minimalSetterArgumentCount;
    }

    public function getFirstArgumentType(): ?string
    {
        return $this->firstArgumentType;
    }
}
