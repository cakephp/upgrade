<?php

declare(strict_types=1);

use Rector\CakePHP\Set\CakePHPSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../config.php');
    $rectorConfig->sets([CakePHPSetList::CAKEPHP_30, CakePHPSetList::CAKEPHP_34]);
};
