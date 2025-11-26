<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\RouteBuilderToCallbackFirstRector;
use Rector\Config\RectorConfig;

# @see https://book.cakephp.org/5/en/appendices/6-0-migration-guide.html
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RouteBuilderToCallbackFirstRector::class);
};
