<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake6\EventManagerOnRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(EventManagerOnRector::class);
};
