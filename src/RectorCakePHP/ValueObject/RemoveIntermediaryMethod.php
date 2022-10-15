<?php

declare(strict_types=1);

namespace Rector\CakePHP\ValueObject;

final class RemoveIntermediaryMethod
{
    public function __construct(
        private readonly string $firstMethod,
        private readonly string $secondMethod,
        private readonly string $finalMethod,
    ) {
    }

    public function getFirstMethod(): string
    {
        return $this->firstMethod;
    }

    public function getSecondMethod(): string
    {
        return $this->secondMethod;
    }

    public function getFinalMethod(): string
    {
        return $this->finalMethod;
    }
}
