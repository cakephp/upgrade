<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

use PHPStan\Type\ObjectType;

final class OptionsArrayToNamedParameters
{
    public function __construct(
        private string $class,
        private array $methods = [],
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
    public function getMethod(): string
    {
        return $this->methods[0] ?? '';
    }

    /**
     * @return array<string, string>
     */
    public function getRenames(): array
    {
        if (isset($this->methods['rename'])) {
            return $this->methods['rename'];
        }
        return [];
    }
}
