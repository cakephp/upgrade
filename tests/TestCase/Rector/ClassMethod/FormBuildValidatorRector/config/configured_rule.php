<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\ClassMethod\FormBuildValidatorRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FormBuildValidatorRector::class);
};
