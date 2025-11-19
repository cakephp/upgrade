<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\PaginatorCounterFormatRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(PaginatorCounterFormatRector::class);
};
