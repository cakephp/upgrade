<?php

declare(strict_types=1);

use Rector\CakePHP\Rector\MethodCall\ArrayToFluentCallRector;

use Rector\CakePHP\Tests\Rector\MethodCall\ArrayToFluentCallRector\Source\ConfigurableClass;
use Rector\CakePHP\Tests\Rector\MethodCall\ArrayToFluentCallRector\Source\FactoryClass;
use Rector\CakePHP\ValueObject\ArrayToFluentCall;
use Rector\CakePHP\ValueObject\FactoryMethod;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../config/config.php');

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
