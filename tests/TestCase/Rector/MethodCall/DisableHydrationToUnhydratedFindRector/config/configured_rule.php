<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\DisableHydrationToUnhydratedFindRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DisableHydrationToUnhydratedFindRector::class);
};
