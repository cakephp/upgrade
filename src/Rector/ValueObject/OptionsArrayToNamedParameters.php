<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

use PHPStan\Type\ObjectType;

final class OptionsArrayToNamedParameters
{
    public function __construct(
        private string $class,
        private array $methods
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

    /**
     * @return array<string, string>
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}
