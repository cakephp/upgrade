<?php
declare(strict_types=1);

namespace Rector\CakePHP\ValueObject;

final class ArrayItemsAndFluentClass
{
    /**
     * @param \PhpParser\Node\Expr\ArrayItem[] $arrayItems
     * @param array<string, \PhpParser\Node\Expr> $fluentCalls
     */
    public function __construct(
        private readonly array $arrayItems,
        private readonly array $fluentCalls
    ) {
    }

    /**
     * @return \PhpParser\Node\Expr\ArrayItem[]
     */
    public function getArrayItems(): array
    {
        return $this->arrayItems;
    }

    /**
     * @return array<string, \PhpParser\Node\Expr>
     */
    public function getFluentCalls(): array
    {
        return $this->fluentCalls;
    }
}
