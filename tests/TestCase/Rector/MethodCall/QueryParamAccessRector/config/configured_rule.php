<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake5\QueryParamAccessRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(QueryParamAccessRector::class);
};
