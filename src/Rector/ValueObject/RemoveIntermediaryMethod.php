<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

final class RemoveIntermediaryMethod
{
    public function __construct(
        private string $firstMethod,
        private string $secondMethod,
        private string $finalMethod,
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
