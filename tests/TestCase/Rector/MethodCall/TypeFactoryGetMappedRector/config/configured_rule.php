<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake5\TypeFactoryGetMappedRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TypeFactoryGetMappedRector::class);
};
