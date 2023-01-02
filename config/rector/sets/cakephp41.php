<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\ModalToGetSetRector;
use Cake\Upgrade\Rector\ValueObject\ModalToGetSet;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Cake\Routing\Exception\RedirectException' => 'Cake\Http\Exception\RedirectException',
        'Cake\Database\Expression\Comparison' => 'Cake\Database\Expression\ComparisonExpression',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('Cake\Database\Schema\TableSchema', 'getPrimary', 'getPrimaryKey'),
        new MethodCallRename('Cake\Database\Type\DateTimeType', 'setTimezone', 'setDatabaseTimezone'),
        new MethodCallRename('Cake\Database\Expression\QueryExpression', 'or_', 'or'),
        new MethodCallRename('Cake\Database\Expression\QueryExpression', 'and_', 'and'),
        new MethodCallRename('Cake\View\Form\ContextInterface', 'primaryKey', 'getPrimaryKey'),
        new MethodCallRename(
            'Cake\Http\Middleware\CsrfProtectionMiddleware',
            'whitelistCallback',
            'skipCheckCallback'
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(ModalToGetSetRector::class, [new ModalToGetSet('Cake\Form\Form', 'schema')]);
};
