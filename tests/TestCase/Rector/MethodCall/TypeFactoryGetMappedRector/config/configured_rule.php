<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\TypeFactoryGetMappedRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TypeFactoryGetMappedRector::class);
};
