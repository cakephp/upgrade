<?php

declare(strict_types=1);

namespace Rector\CakePHP\ValueObject;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayItem;

final class ArrayItemsAndFluentClass
{
    /**
     * @param ArrayItem[] $arrayItems
     * @param array<string, Expr> $fluentCalls
     */
    public function __construct(
        private readonly array $arrayItems,
        private readonly array $fluentCalls
    ) {
    }

    /**
     * @return ArrayItem[]
     */
    public function getArrayItems(): array
    {
        return $this->arrayItems;
    }

    /**
     * @return array<string, Expr>
     */
    public function getFluentCalls(): array
    {
        return $this->fluentCalls;
    }
}
