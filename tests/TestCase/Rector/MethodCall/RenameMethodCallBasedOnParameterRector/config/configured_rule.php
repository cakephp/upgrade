<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake4\RenameMethodCallBasedOnParameter\RenameMethodCallBasedOnParameter;
use Cake\Upgrade\Rector\Cake4\RenameMethodCallBasedOnParameter\RenameMethodCallBasedOnParameterRector;
use Cake\Upgrade\Rector\Tests\Rector\MethodCall\RenameMethodCallBasedOnParameterRector\Source\SomeModelType;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameMethodCallBasedOnParameterRector::class, [
        new RenameMethodCallBasedOnParameter(SomeModelType::class, 'getParam', 'paging', 'getAttribute'),
        new RenameMethodCallBasedOnParameter(SomeModelType::class, 'withParam', 'paging', 'withAttribute'),
    ]);
};
