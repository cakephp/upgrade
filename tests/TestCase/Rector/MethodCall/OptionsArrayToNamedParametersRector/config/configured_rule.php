<?php

declare(strict_types=1);

use Cake\Upgrade\Rector\Cake5\OptionsArrayToNamedParameters\OptionsArrayToNamedParameters;
use Cake\Upgrade\Rector\Cake5\OptionsArrayToNamedParameters\OptionsArrayToNamedParametersRector;
use Cake\Upgrade\Rector\Tests\Rector\MethodCall\OptionsArrayToNamedParametersRector\Source\ConfigurableClass;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(OptionsArrayToNamedParametersRector::class, [
        new OptionsArrayToNamedParameters(ConfigurableClass::class, ['find']),
        new OptionsArrayToNamedParameters(ConfigurableClass::class, [
            'get', 'rename' => ['key' => 'cacheKey'],
        ]),
    ]);
};
