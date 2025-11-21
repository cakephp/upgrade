<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake3\ChangeSnakedFixtureNameToPascalRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ChangeSnakedFixtureNameToPascalRector::class);
};
