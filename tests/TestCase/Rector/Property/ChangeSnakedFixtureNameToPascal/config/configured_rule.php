<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\Property\ChangeSnakedFixtureNameToPascalRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/rector/config.php');

    $rectorConfig->rule(ChangeSnakedFixtureNameToPascalRector::class);
};
