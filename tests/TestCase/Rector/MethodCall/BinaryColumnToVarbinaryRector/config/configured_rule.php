<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake6\BinaryColumnToVarbinaryRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(BinaryColumnToVarbinaryRector::class);
};
