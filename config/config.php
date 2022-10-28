<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Rector\\CakePHP\\', __DIR__ . '/../src/RectorCakePHP')
        ->exclude([__DIR__ . '/../src/RectorCakePHP/{Rector,ValueObject,Contract}']);
};
