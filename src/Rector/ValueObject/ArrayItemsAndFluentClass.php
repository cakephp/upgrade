<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector\ValueObject;

final class ArrayItemsAndFluentClass
{
    /**
     * @param \PhpParser\Node\Expr\ArrayItem[] $arrayItems
     * @param array<string, \PhpParser\Node\Expr> $fluentCalls
     */
    public function __construct(
        private array $arrayItems,
        private array $fluentCalls
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
