<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/defaults.php');
    $rectorConfig->sets([PHPUnitSetList::PHPUNIT_80]);
};
