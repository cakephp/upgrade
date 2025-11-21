<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake5\NewExprToFuncRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(NewExprToFuncRector::class);
};
