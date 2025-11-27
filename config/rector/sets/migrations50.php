<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassConstFetch;

/**
 * @see https://github.com/cakephp/migrations/releases/5.0.0/
 */
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassConstFetchRector::class, [
        // Standard types
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_STRING', 'TYPE_STRING'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_CHAR', 'TYPE_CHAR'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_TEXT', 'TYPE_TEXT'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_INTEGER', 'TYPE_INTEGER'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_TINY_INTEGER', 'TYPE_TINYINTEGER'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_SMALL_INTEGER', 'TYPE_SMALLINTEGER'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_BIG_INTEGER', 'TYPE_BIGINTEGER'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_FLOAT', 'TYPE_FLOAT'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_DECIMAL', 'TYPE_DECIMAL'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_DATETIME', 'TYPE_DATETIME'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_TIMESTAMP', 'TYPE_TIMESTAMP'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_TIME', 'TYPE_TIME'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_DATE', 'TYPE_DATE'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_BINARY', 'TYPE_BINARY'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_BINARYUUID', 'TYPE_BINARY_UUID'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_BOOLEAN', 'TYPE_BOOLEAN'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_JSON', 'TYPE_JSON'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_UUID', 'TYPE_UUID'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_NATIVEUUID', 'TYPE_NATIVE_UUID'),

        // Geospatial types
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_GEOMETRY', 'TYPE_GEOMETRY'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_POINT', 'TYPE_POINT'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_LINESTRING', 'TYPE_LINESTRING'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_POLYGON', 'TYPE_POLYGON'),

        // Geospatial array constant
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPES_GEOSPATIAL', 'TYPES_GEOSPATIAL'),

        // Database-specific types
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_YEAR', 'TYPE_YEAR'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_CIDR', 'TYPE_CIDR'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_INET', 'TYPE_INET'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_MACADDR', 'TYPE_MACADDR'),
        new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', 'PHINX_TYPE_INTERVAL', 'TYPE_INTERVAL'),
    ]);
};
