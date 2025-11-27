<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassConstFetch;

/**
 * @see https://github.com/cakephp/migrations/releases/5.0.0/
 */
return static function (RectorConfig $rectorConfig): void {

    $constantMap = [
        // Standard types
        'PHINX_TYPE_STRING' => 'TYPE_STRING',
        'PHINX_TYPE_CHAR' => 'TYPE_CHAR',
        'PHINX_TYPE_TEXT' => 'TYPE_TEXT',
        'PHINX_TYPE_INTEGER' => 'TYPE_INTEGER',
        'PHINX_TYPE_TINY_INTEGER' => 'TYPE_TINYINTEGER',
        'PHINX_TYPE_SMALL_INTEGER' => 'TYPE_SMALLINTEGER',
        'PHINX_TYPE_BIG_INTEGER' => 'TYPE_BIGINTEGER',
        'PHINX_TYPE_FLOAT' => 'TYPE_FLOAT',
        'PHINX_TYPE_DECIMAL' => 'TYPE_DECIMAL',
        'PHINX_TYPE_DATETIME' => 'TYPE_DATETIME',
        'PHINX_TYPE_TIMESTAMP' => 'TYPE_TIMESTAMP',
        'PHINX_TYPE_TIME' => 'TYPE_TIME',
        'PHINX_TYPE_DATE' => 'TYPE_DATE',
        'PHINX_TYPE_BINARY' => 'TYPE_BINARY',
        'PHINX_TYPE_BINARYUUID' => 'TYPE_BINARY_UUID',
        'PHINX_TYPE_BOOLEAN' => 'TYPE_BOOLEAN',
        'PHINX_TYPE_JSON' => 'TYPE_JSON',
        'PHINX_TYPE_UUID' => 'TYPE_UUID',
        'PHINX_TYPE_NATIVEUUID' => 'TYPE_NATIVE_UUID',

        // Geospatial types
        'PHINX_TYPE_GEOMETRY' => 'TYPE_GEOMETRY',
        'PHINX_TYPE_POINT' => 'TYPE_POINT',
        'PHINX_TYPE_LINESTRING' => 'TYPE_LINESTRING',
        'PHINX_TYPE_POLYGON' => 'TYPE_POLYGON',

        // Geospatial array constant
        'PHINX_TYPES_GEOSPATIAL' => 'TYPES_GEOSPATIAL',

        // Database-specific types
        'PHINX_TYPE_YEAR' => 'TYPE_YEAR',
        'PHINX_TYPE_CIDR' => 'TYPE_CIDR',
        'PHINX_TYPE_INET' => 'TYPE_INET',
        'PHINX_TYPE_MACADDR' => 'TYPE_MACADDR',
        'PHINX_TYPE_INTERVAL', 'TYPE_INTERVAL',
    ];

    foreach ($constantMap as $oldConstant => $newConstant) {
        $rectorConfig->ruleWithConfiguration(RenameClassConstFetchRector::class, [
            new RenameClassConstFetch('Migrations\Db\Adapter\AdapterInterface', $oldConstant, $newConstant),
        ]);
    }
};
