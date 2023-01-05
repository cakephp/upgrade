<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

final class ArrayToFluentCall
{
    /**
     * @param array<string, string> $arrayKeysToFluentCalls
     */
    public function __construct(
        private string $class,
        private array $arrayKeysToFluentCalls
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return array<string, string>
     */
    public function getArrayKeysToFluentCalls(): array
    {
        return $this->arrayKeysToFluentCalls;
    }
}
