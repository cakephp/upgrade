<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\RemoveMethodCallRector;
use Cake\Upgrade\Rector\ValueObject\RemoveMethodCall;
use Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRector\Source\SomeModelType;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/rector/config.php');

    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        new RemoveMethodCall(SomeModelType::class, 'getAttribute'),
    ]);
};
