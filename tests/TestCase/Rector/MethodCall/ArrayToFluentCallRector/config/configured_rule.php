<?php

declare(strict_types=1);

use Cake\Upgrade\Rector\Cake3\ArrayToFluentCall\ArrayToFluentCall;
use Cake\Upgrade\Rector\Cake3\ArrayToFluentCall\ArrayToFluentCallRector;
use Cake\Upgrade\Rector\Cake3\ArrayToFluentCall\FactoryMethod;
use Cake\Upgrade\Rector\Tests\Rector\MethodCall\ArrayToFluentCallRector\Source\ConfigurableClass;
use Cake\Upgrade\Rector\Tests\Rector\MethodCall\ArrayToFluentCallRector\Source\FactoryClass;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ArrayToFluentCallRector::class, [
        ArrayToFluentCallRector::ARRAYS_TO_FLUENT_CALLS => [
            new ArrayToFluentCall(ConfigurableClass::class, [
                'name' => 'setName',
                'size' => 'setSize',
            ]),
        ],
        ArrayToFluentCallRector::FACTORY_METHODS => [
            new FactoryMethod(FactoryClass::class, 'buildClass', ConfigurableClass::class, 2),
        ],
    ]);
};
