<?php

declare(strict_types=1);

use Cake\Upgrade\Rector\Cake5\StaticConnectionHelperRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(StaticConnectionHelperRector::class);
};
