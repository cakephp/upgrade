<?php
declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ArrayToFluentCallRector\Source;

class ConfigurableClass
{
    public function setName(string $name): self
    {
        return $this;
    }

    public function setSize(int $size): self
    {
        return $this;
    }

    public function doSomething(): void
    {
    }
}
