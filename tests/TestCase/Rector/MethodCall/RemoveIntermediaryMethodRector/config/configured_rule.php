<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake4\RemoveIntermediaryMethod\RemoveIntermediaryMethod;
use Cake\Upgrade\Rector\Cake4\RemoveIntermediaryMethod\RemoveIntermediaryMethodRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(
        RemoveIntermediaryMethodRector::class,
        [new RemoveIntermediaryMethod('getTableLocator', 'get', 'fetchTable')]
    );
};
