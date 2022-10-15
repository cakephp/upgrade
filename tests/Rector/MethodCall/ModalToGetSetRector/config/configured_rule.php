<?php

declare(strict_types=1);

use Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector;

use Rector\CakePHP\Tests\Rector\MethodCall\ModalToGetSetRector\Source\SomeModelType;
use Rector\CakePHP\ValueObject\ModalToGetSet;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../config/config.php');

    $rectorConfig->ruleWithConfiguration(ModalToGetSetRector::class, [

        new ModalToGetSet(SomeModelType::class, 'config', null, null, 2, 'array'),
        new ModalToGetSet(
            SomeModelType::class,
            'customMethod',
            'customMethodGetName',
            'customMethodSetName',
            2,
            'array'
        ),
        new ModalToGetSet(SomeModelType::class, 'makeEntity', 'createEntity', 'generateEntity'),
        new ModalToGetSet(SomeModelType::class, 'method'),

    ]);
};
