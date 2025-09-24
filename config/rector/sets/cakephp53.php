<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\EntityIsEmptyRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;

# @see https://book.cakephp.org/5/en/appendices/5-3-migration-guide.html
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('Cake\Database\Query', 'newExpr', 'expr'),
    ]);
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Cake\ORM\Query' => 'Cake\ORM\Query\SelectQuery',
    ]);
    $rectorConfig->rule(EntityIsEmptyRector::class);
};
