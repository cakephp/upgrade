<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Cake\\Upgrade\\Rector\\', __DIR__ . '/../..//src/Rector')
        ->exclude([__DIR__ . '/../../src/Rector/{Rector,ValueObject,Contract}']);
};
