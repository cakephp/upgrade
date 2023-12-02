<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\Namespace_\AppUsesStaticCallToUseStatementRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AppUsesStaticCallToUseStatementRector::class);
};
