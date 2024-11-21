<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSets([
        \Rector\PhpParser\Set\PhpParserSetList::PHP_PARSER_50
    ]);
