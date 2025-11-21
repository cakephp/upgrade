<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake3\ModalToGetSet\ModalToGetSet;
use Cake\Upgrade\Rector\Cake3\ModalToGetSet\ModalToGetSetRector;
use Cake\Upgrade\Rector\Tests\Rector\MethodCall\ModalToGetSetRector\Source\SomeModelType;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
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
