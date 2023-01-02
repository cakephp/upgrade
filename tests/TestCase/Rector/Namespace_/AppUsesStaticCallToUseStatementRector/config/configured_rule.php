<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\Namespace_\AppUsesStaticCallToUseStatementRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/rector/config.php');

    $rectorConfig->rule(AppUsesStaticCallToUseStatementRector::class);
};
