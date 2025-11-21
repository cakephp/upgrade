<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake5\AddMethodCallArgs\AddMethodCallArgs;
use Cake\Upgrade\Rector\Cake5\AddMethodCallArgs\AddMethodCallArgsRector;
use Cake\Upgrade\Test\TestCase\Rector\MethodCall\AddMethodCallArgsRector\Source\SomeModelType;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AddMethodCallArgsRector::class, [
        new AddMethodCallArgs(SomeModelType::class, 'getAttribute', '2ndArg', 1, true),
    ]);
};
