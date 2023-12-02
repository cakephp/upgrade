<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;

# @see https://book.cakephp.org/4/en/appendices/4-5-migration-guide.html
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Cake\Datasource\Paging\Paginator' => 'Cake\Datasource\Paging\NumericPaginator',
        'Cake\TestSuite\ContainerStubTrait' => 'Cake\Core\TestSuite\ContainerStubTrait',
        'Cake\TestSuite\HttpClientTrait' =>  'Cake\Http\TestSuite\HttpClientTrait',
        'Cake\Cache\InvalidArgumentException' => 'Cake\Cache\Exception\InvalidArgumentException',
    ]);

    $rectorConfig->ruleWithConfiguration(
        RenameMethodRector::class,
        [
            new MethodCallRename('Cake\View\View', 'loadHelper', 'addHelper'),
            new MethodCallRename('Cake\Validation\Validator', 'isArray', 'array'),
        ]
    );

};
