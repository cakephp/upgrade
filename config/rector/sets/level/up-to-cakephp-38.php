<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Set\CakePHPLevelSetList;
use Cake\Upgrade\Rector\Set\CakePHPSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([CakePHPSetList::CAKEPHP_38, CakePHPLevelSetList::UP_TO_CAKEPHP_37]);
};
