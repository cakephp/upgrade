<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\BreadcrumbsHelperAddManyRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(BreadcrumbsHelperAddManyRector::class);
};
