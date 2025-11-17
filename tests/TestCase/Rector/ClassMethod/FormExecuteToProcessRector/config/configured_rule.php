<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake5\FormExecuteToProcessRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FormExecuteToProcessRector::class);
};
