<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\NewEntityToNewEmptyEntityRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(NewEntityToNewEmptyEntityRector::class);
};
