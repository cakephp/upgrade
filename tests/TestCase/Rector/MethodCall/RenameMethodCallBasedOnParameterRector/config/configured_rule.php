<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\RenameMethodCallBasedOnParameterRector;
use Cake\Upgrade\Rector\Tests\Rector\MethodCall\RenameMethodCallBasedOnParameterRector\Source\SomeModelType;
use Cake\Upgrade\Rector\ValueObject\RenameMethodCallBasedOnParameter;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/rector/config.php');

    $rectorConfig->ruleWithConfiguration(RenameMethodCallBasedOnParameterRector::class, [
        new RenameMethodCallBasedOnParameter(SomeModelType::class, 'getParam', 'paging', 'getAttribute'),
        new RenameMethodCallBasedOnParameter(SomeModelType::class, 'withParam', 'paging', 'withAttribute'),
    ]);
};
