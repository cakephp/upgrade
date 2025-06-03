<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\ChangeEntityTraitSetArrayToPatchRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ChangeEntityTraitSetArrayToPatchRector::class);
};
