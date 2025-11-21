<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\CakePHPSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        CakePHPSetList::CAKEPHP_30,
        CakePHPSetList::CAKEPHP_34,
        CakePHPSetList::CAKEPHP_35,
        CakePHPSetList::CAKEPHP_36,
        CakePHPSetList::CAKEPHP_37,
        CakePHPSetList::CAKEPHP_38,
    ]);
};
