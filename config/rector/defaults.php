<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;

/**
 * This file is/should be imported by all other rulesets to define
 * default config overwrites which are needed for this app to work properly
 */
return static function (RectorConfig $rectorConfig): void {
    // When processing very large apps rector's default 120 second timeout can be too low
    $rectorConfig->parallel(600);
};
