<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

use PHPStan\Type\ObjectType;

final class ModalToGetSet
{
    /**
     * @readonly
     */
    private string $getMethod;

    /**
     * @readonly
     */
    private string $setMethod;

    public function __construct(
        private string $type,
        private string $unprefixedMethod,
        ?string $getMethod = null,
        ?string $setMethod = null,
        private int $minimalSetterArgumentCount = 1,
        private ?string $firstArgumentType = null
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
