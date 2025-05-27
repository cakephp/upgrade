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

    $staticReturnTypeMap = [
        'Cake\Console\BaseCommand' => ['setName'],
        'Cake\Controller\Controller' => [
            'setName', 'setPlugin', 'enableAutoRender', 'disableAutoRender', 'addViewClasses',
        ],
        'Cake\Core\BasePlugin' => ['enable', 'disable'],
        'Cake\Core\PluginApplicationInterface' => ['addPlugin'],
        'Cake\Core\PluginInterface' => ['enable', 'disable'],
        'Cake\Database\TypedResultInterface' => ['setReturnType'],
        'Cake\Datasource\EntityInterface' => [
            'setHidden', 'setVirtual', 'setDirty', 'setErrors', 'setError', 'setAccess',
            'setPatchable', 'setSource', 'set', 'unset', 'setNew',
        ],
        'Cake\Datasource\InvalidPropertyInterface' => ['setInvalid', 'setInvalidField'],
        'Cake\Datasource\RepositoryInterface' => ['setAlias', 'setRegistryAlias'],
        'Cake\Event\EventInterface' => ['setResult', 'setData'],
        'Cake\Form\Form' => ['setSchema', 'setErrors', 'set', 'setData'],
        'Cake\Http\BaseApplication' => ['addPlugin', 'addOptionalPlugin'],
        'Cake\Mailer\Mailer' => ['setRenderer', 'setViewVars', 'render', 'setProfile', 'restore', 'reset'],
        'Cake\ORM\Behavior\Translate\TranslateStrategyInterface' => ['setLocale'],
        'Cake\ORM\Locator\LocatorInterface' => ['setConfig'],
        'Cake\ORM\Table' => [
            'setTable', 'setAlias', 'setRegistryAlias', 'setConnection', 'setSchema',
            'setPrimaryKey', 'setDisplayField', 'setEntityClass', 'addBehavior',
            'addBehaviors', 'removeBehavior', 'addAssociations',
            'setRequest', 'setResponse', 'setTemplatePath',
            'enableAutoLayout', 'disableAutoLayout', 'setTheme', 'setTemplate',
            'set', 'start', 'append', 'assign', 'reset', 'end', 'extend',
            'loadHelpers', 'setPlugin', 'setElementCache',
        ],
    ];

    // For cakephp/cakephp#18115
    foreach ($staticReturnTypeMap as $className => $methods) {
        foreach ($methods as $method) {
            $rectorConfig->ruleWithConfiguration(AddReturnTypeDeclarationRector::class, [
                new AddReturnTypeDeclaration($className, $method, new SimpleStaticType('')),
            ]);
        }
    }

    // ===== Remove underscores from method names =====

    $map = [
        'Cache' => [
            'Cake\Cache\Cache' => ['_buildEngine'],
            'Cake\Cache\CacheEngine' => ['_key'],
            'Cake\Cache\Engine\FileEngine' => ['_clearDirectory', '_setKey', '_active', '_key'],
            'Cake\Cache\Engine\MemcachedEngine' => ['_setOptions'],
            'Cake\Cache\Engine\RedisEngine' => [
                '_connect', '_connectTransient', '_connectPersistent', '_createRedisInstance',
            ],
        ],
        'Collection' => [
            // _extract can't be easily renamed to extract as it conflicts with the CollectionTrait::extract() method
            'Cake\Collection\ExtractTrait' => [
                '_propertyExtractor', '_simpleExtract', '_createMatcherFilter',
            ],
            'Cake\Collection\Iterator\MapReduce' => ['_execute'],
            'Cake\Collection\Iterator\TreePrinter' => ['_fetchCurrent'],
        ],
        'Command' => [
            'Cake\Command\Helper\TreeHelper' => [
                '_calculateWidths', '_cellWidth', '_rowSeparator', '_render', '_addStyle',
            ],
            'Cake\Command\Helper\TableHelper' => [
                '_calculateWidths', '_cellWidth', '_rowSeparator', '_render', '_addStyle',
            ],
            'Cake\Command\RoutesGenerateCommand' => ['_splitArgs'],
            'Cake\Command\I18nExtractCommand' => [
                '_getPaths', '_addTranslation', '_extract', '_extractTokens', '_parse',
                '_buildFiles', '_store', '_writeFiles', '_writeHeader', '_getStrings',
                '_formatString', '_markerError', '_searchFiles', '_isExtractingApp', '_isPathUsable',
            ],
            'Cake\Command\PluginAssetsTrait' => [
                '_list', '_process', '_remove', '_createDirectory',
                '_createSymlink', '_makeRelativePath', '_copyDirectory',
            ],
        ],
        'Console' => [
            'Cake\Console\HelpFormatter' => ['_generateUsage', '_getMaxLength'],
            'Cake\Console\ConsoleIo' => ['_getInput'],
            // _write can't be renamed to write as it conflicts with the ConsoleOutput::write() method
            'Cake\Console\ConsoleOutput' => ['_replaceTags'],
            'Cake\Console\ConsoleOptionParser' => [
                '_parseLongOption', '_parseShortOption', '_parseOption', '_optionExists', '_parseArg', '_nextToken',
            ],
        ],
        'Controller' => [
            'Cake\Controller\Component\FormProtectionComponent' => ['_getSessionId'],
            'Cake\Controller\Controller' => ['_templatePath'],
        ],
        'Core' => [
            'Cake\Core\App' => ['_classExistsInBase'],
            'Cake\Core\Configure' => ['_getEngine'],
            'Cake\Core\Configure\Engine\IniConfig' => ['_parseNestedValues', '_value'],
            'Cake\Core\ObjectRegistry' => [
                '_checkDuplicate', '_resolveClassName', '_throwMissingClassError', '_create',
            ],
            'Cake\Core\ConventionsTrait' => [
                '_fixtureName', '_entityName', '_modelKey', '_modelNameFromKey',
                '_singularName', '_variableName', '_singularHumanName', '_camelize',
                '_pluralHumanName', '_pluginPath', '_pluginNamespace',
            ],
            'Cake\Core\Configure\FileConfigTrait' => ['_getFilePath'],
            'Cake\Core\InstanceConfigTrait' => ['_configRead', '_configWrite', '_configDelete'],
        ],
    ];

    foreach ($map as $definitions) {
        foreach ($definitions as $className => $methods) {
            foreach ($methods as $method) {
                $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
                    new MethodCallRename($className, $method, substr($method, 1)),
                ]);
            }
        }
    }
};
