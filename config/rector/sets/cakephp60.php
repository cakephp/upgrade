<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\Rector\String_\RenameStringRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;
use Rector\StaticTypeMapper\ValueObject\Type\SimpleStaticType;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;

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

    // For cakephp/cakephp#18115
    $rectorConfig->ruleWithConfiguration(AddReturnTypeDeclarationRector::class, [
        new AddReturnTypeDeclaration('Cake\Console\BaseCommand', 'setName', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Controller\Controller', 'setName', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Controller\Controller', 'setPlugin', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Controller\Controller', 'enableAutoRender', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Controller\Controller', 'disableAutoRender', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Controller\Controller', 'addViewClasses', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Core\BasePlugin', 'enable', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Core\BasePlugin', 'disable', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Core\PluginApplicationInterface', 'addPlugin', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Core\PluginInterface', 'disable', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Core\PluginInterface', 'enable', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Core\PluginInterface', 'enable', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Database\TypedResultInterface', 'setReturnType', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'setHidden', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'setVirtual', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'setDirty', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'setErrors', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'setError', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'setAccess', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'setPatchable', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'setSource', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'set', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'unset', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\EntityInterface', 'setNew', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\InvalidPropertyInterface', 'setInvalid', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\InvalidPropertyInterface', 'setInvalidField', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\RepositoryInterface', 'setAlias', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Datasource\RepositoryInterface', 'setRegistryAlias', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Event\EventInterface', 'setResult', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Event\EventInterface', 'setData', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Form\Form', 'setSchema', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Form\Form', 'setErrors', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Form\Form', 'set', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Form\Form', 'setData', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Http\BaseApplication', 'addPlugin', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Http\BaseApplication', 'addOptionalPlugin', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Mailer\Mailer', 'setRenderer', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Mailer\Mailer', 'setViewVars', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Mailer\Mailer', 'render', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Mailer\Mailer', 'setProfile', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Mailer\Mailer', 'restore', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\Mailer\Mailer', 'reset', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Behavior\Translate\TranslateStrategyInterface', 'setLocale', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Locator\LocatorInterface', 'setConfig', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'setTable', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'setAlias', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'setRegistryAlias', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'setConnection', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'setSchema', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'setPrimaryKey', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'setDisplayField', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'setEntityClass', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'addBehavior', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'addBehaviors', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'removeBehavior', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\ORM\Table', 'addAssociations', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'setRequest', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'setResponse', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'setTemplatePath', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'enableAutoLayout', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'disableAutoLayout', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'setTheme', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'setTemplate', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'set', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'start', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'append', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'assign', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'reset', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'end', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'extend', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'loadHelpers', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'setPlugin', new SimpleStaticType('')),
        new AddReturnTypeDeclaration('Cake\View\View', 'setElementCache', new SimpleStaticType('')),
    ]);
};
