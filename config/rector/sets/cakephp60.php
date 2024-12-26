<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\Rector\String_\RenameStringRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;

# @see https://book.cakephp.org/6/en/appendices/6-0-migration-guide.html
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('Cake\ORM\Entity', 'setAccess', 'setPatchable'),
        new MethodCallRename('Cake\ORM\Entity', 'getAccessible', 'getPatchable'),
        new MethodCallRename('Cake\ORM\Entity', 'isAccessible', 'isPatchable'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenamePropertyRector::class, [
        new RenameProperty('Cake\ORM\Entity', '_accessible', 'patchable'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameStringRector::class, [
        'accessibleFields' => 'patchableFields',
    ]);
};
