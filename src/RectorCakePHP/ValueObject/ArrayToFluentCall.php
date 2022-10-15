<?php

declare(strict_types=1);

namespace Rector\CakePHP\ValueObject;

final class ArrayToFluentCall
{
    /**
     * @param array<string, string> $arrayKeysToFluentCalls
     */
    public function __construct(
        private readonly string $class,
        private readonly array $arrayKeysToFluentCalls
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
