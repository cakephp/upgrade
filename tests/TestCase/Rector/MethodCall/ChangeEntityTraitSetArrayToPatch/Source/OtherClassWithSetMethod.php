<?php
declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ChangeEntityTraitSetArrayToPatch\Source;

class OtherClassWithSetMethod
{
    public function set(array $arg1): void
    {
    }
}
