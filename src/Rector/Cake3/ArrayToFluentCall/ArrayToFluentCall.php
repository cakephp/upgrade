<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\Cake3\ArrayToFluentCall;

final class ArrayToFluentCall
{
    /**
     * @param array<string, string> $class
     */
    public function __construct(
        private string $class,
        private array $arrayKeysToFluentCalls,
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
