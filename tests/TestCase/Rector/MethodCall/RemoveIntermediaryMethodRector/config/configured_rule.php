<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\RemoveIntermediaryMethodRector;
use Cake\Upgrade\Rector\ValueObject\RemoveIntermediaryMethod;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/rector/config.php');

    $rectorConfig->ruleWithConfiguration(
        RemoveIntermediaryMethodRector::class,
        [new RemoveIntermediaryMethod('getTableLocator', 'get', 'fetchTable')]
    );
};
