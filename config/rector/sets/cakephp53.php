<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\EntityIsEmptyRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

# @see https://book.cakephp.org/5/en/appendices/5-3-migration-guide.html
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('Cake\Database\Query', 'newExpr', 'expr'),
    ]);
    $rectorConfig->rule(EntityIsEmptyRector::class);
};
