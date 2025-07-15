<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\EntityIsEmptyRector;
use Rector\Config\RectorConfig;

# @see https://book.cakephp.org/5/en/appendices/5-3-migration-guide.html
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(EntityIsEmptyRector::class);
};
