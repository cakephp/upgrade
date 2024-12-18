<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

/**
 * @see https://github.com/cakephp/migrations/releases/4.5.0/
 */
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Migrations\AbstractMigration' => 'Migrations\BaseMigration',
        'Migrations\AbstractSeed' => 'Migrations\BaseSeed',
    ]);
};
