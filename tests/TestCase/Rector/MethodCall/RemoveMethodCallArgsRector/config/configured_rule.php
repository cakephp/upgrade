<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake5\RemoveMethodCall\RemoveMethodCall;
use Cake\Upgrade\Rector\Cake5\RemoveMethodCall\RemoveMethodCallRector;
use Cake\Upgrade\Test\TestCase\Rector\MethodCall\RemoveMethodCallArgsRector\Source\SomeModelType;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        new RemoveMethodCall(SomeModelType::class, 'getAttribute'),
    ]);
};
