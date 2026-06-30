<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\CakePHPSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/defaults.php');
    $rectorConfig->sets([CakePHPSetList::CAKEPHP_44]);
};
