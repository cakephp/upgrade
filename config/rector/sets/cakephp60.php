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
                new AddReturnTypeDeclaration($className, $method, new SimpleStaticType($className)),
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
        'Database' => [
            'Cake\Database\IdentifierQuoter' => [
                '_quoteParts', '_basicQuoter',
                '_quoteJoins', '_quoteSelect',
                '_quoteDelete', '_quoteInsert',
                '_quoteUpdate', '_quoteComparison',
                '_quoteOrderBy', '_quoteIdentifierExpression',
            ],
            'Cake\Database\Query' => [
                '_makeJoin', '_expressionsVisitor', '_conjugate', '_dirty',
            ],

            // Expressions
            'Cake\Database\Expression\BetweenExpression' => ['_bindValue'],
            'Cake\Database\Expression\ComparisonExpression' => [
                '_stringExpression', '_bindValue', '_flattenValue', '_collectExpressions',
            ],
            'Cake\Database\Expression\TupleComparison' => ['_stringifyValues', '_traverseValue'],
            'Cake\Database\Expression\QueryExpression' => [
                '_addConditions', '_parseCondition', '_calculateType',
            ],
            'Cake\Database\Expression\ValuesExpression' => ['_columnNames', '_processExpressions'],

            // Drivers
            'Cake\Database\Driver' => [
                '_expressionTranslators', '_selectQueryTranslator',
                '_transformDistinct', '_deleteQueryTranslator',
                '_updateQueryTranslator', '_removeAliasesFromConditions',
                '_insertQueryTranslator',
            ],
            'Cake\Database\Driver\Postgres' => [
                '_transformIdentifierExpression', '_transformFunctionExpression',
                '_transformStringExpression',
            ],
            'Cake\Database\Driver\Sqlite' => ['_transformFunctionExpression'],
            'Cake\Database\Driver\Sqlserver' => [
                '_pagingSubquery', '_transformFunctionExpression',
            ],
            'Cake\Database\Driver\TupleComparisonTranslatorTrait' => [
                '_transformTupleComparison',
            ],

            // Compilers
            'Cake\Database\QueryCompiler' => [
                '_sqlCompiler', '_buildWithPart', '_buildSelectPart',
                '_buildFromPart', '_buildJoinPart', '_buildWindowPart',
                '_buildSetPart', '_buildSetOperationPart',
                '_buildIntersectPart', '_buildUnionPart', '_buildInsertPart',
                '_buildValuesPart', '_buildUpdatePart', '_buildModifierPart',
                '_stringifyExpressions',
            ],
            'Cake\Database\PostgresCompiler' => ['_buildHavingPart'],
            'Cake\Database\SqlserverCompiler' => ['_buildLimitPart', '_buildHavingPart'],

            // Types
            'Cake\Database\Type\ExpressionTypeCasterTrait' => ['_castToExpression', '_requiresToExpressionCasting'],
            'Cake\Database\Type\FloatType' => ['_parseValue'],
            'Cake\Database\Type\DateType' => ['_parseValue', '_parseLocaleValue'],
            'Cake\Database\Type\DateTimeType' => ['_parseValue', '_parseLocaleValue'],
            'Cake\Database\Type\DecimalType' => ['_parseValue', '_parseLocaleValue'],
            'Cake\Database\Type\TimeType' => ['_parseTimeValue', '_parseLocalTimeValue'],

            // Schema
            'Cake\Database\Schema\TableSchema' => ['_checkForeignKey'],
            'Cake\Database\Schema\SchemaDialect' => [
                '_foreignOnClause', '_convertOnClause', '_convertConstraintColumns',
                '_getTypeSpecificColumnSql', '_applyTypeSpecificColumnConversion',
            ],
            'Cake\Database\Schema\SqliteSchemaDialect' => [
                '_convertColumn', '_defaultValue',
            ],
            'Cake\Database\Schema\SqlserverSchemaDialect' => [
                '_convertColumn', '_defaultValue', '_keySql',
            ],
            'Cake\Database\Schema\MysqlSchemaDialect' => [
                '_convertColumn', '_keySql',
            ],
            'Cake\Database\Schema\PostgresSchemaDialect' => [
                '_convertColumn', '_keySql', '_defaultValue', '_convertConstraint',
            ],
        ],

        'Datasource' => [
            'Cake\Datasource\QueryCacher' => ['_resolveKey', '_resolveCacher'],
            'Cake\Datasource\EntityTrait' => [
                '_accessor', '_nestedErrors', '_readHasErrors', '_readError',
            ],
            'Cake\Datasource\ModelAwareTrait' => ['_setModelClass'],
            'Cake\Datasource\Paging\NumericPaginator' => ['_removeAliases', '_prefix'],
            'Cake\Datasource\RulesChecker' => ['_checkRules', '_addError'],
        ],

        'Error' => [
            'Cake\Error\Renderer\WebExceptionRenderer' => [
                '_getController', '_customMethod', '_method',
                '_message', '_template', '_outputMessage',
                '_outputMessageSafe', '_shutdown',
            ],
            'Cake\Error\Debugger' => ['_highlight'],
        ],

        'Event' => [
            'Cake\Event\Decorator\AbstractDecorator' => ['_call'],
            'Cake\Event\Decorator\ConditionDecorator' => ['_evaluateCondition'],
            'Cake\Event\EventManager' => [
                '_attachSubscriber', '_detachSubscriber', '_callListener',
            ],
        ],

        'Form' => [
            // Can't rename _execute as it conflicts with the Form::execute() method
            'Cake\Form\Form' => ['_buildSchema'],
            'Cake\Form\Schema' => ['_addField'],
        ],

        'Http' => [
            'Cake\Http\CorsBuilder' => ['_normalizeDomains'],
            'Cake\Http\Session' => ['_defaultConfig', '_overwrite', '_hasSession', '_timedOut'],
            // Can't rename _sendRequest as it conflicts with the Client::sendRequest() method
            'Cake\Http\Client' => [
                '_doRequest', '_mergeOptions', '_createRequest', '_typeHeaders',
                '_addAuthentication', '_addProxy', '_createAuth',
            ],

            // Can't rename _is as it conflicts with the ServerRequest::is() method
            'Cake\Http\ServerRequest' => [
                '_setConfig', '_acceptHeaderDetector', '_headerDetector',
                '_paramDetector', '_environmentDetector',
            ],

            'Cake\Http\Response' => [
                '_createStream', '_setContentType', '_setHeader', '_clearHeader',
                '_setStatus', '_setCacheControl', '_getUTCDate', '_fileRange',
            ],

            'Cake\Http\Cookie\Cookie' => ['_setValue', '_flatten', '_expand'],

            // Can't rename _getHeaders as it conflicts with the MessageTrait::getHeaders() method
            // Can't rename _getBody as it conflicts with the MessageTrait::getBody() method
            // Can't rename _getCookies as it conflicts with the Response::getCookies() method
            // Can't rename _getJson as it conflicts with the Response::getJson() method
            // Can't rename _getXml as it conflicts with the Response::getXml() method
            'Cake\Http\Client\Response' => [
                '_decodeGzipBody', '_parseHeaders',
            ],

            'Cake\Http\Client\FormDataPart' => ['_headerParameterToString'],
            'Cake\Http\Client\Auth\Basic' => ['_generateHeader'],
            'Cake\Http\Client\Auth\Digest' => ['_generateHeader', '_getServerInfo'],
            'Cake\Http\Client\Auth\Oauth' => [
                '_plaintext', '_hmacSha1', '_rsaSha1', '_normalizedUrl',
                '_normalizedParams', '_normalizeData', '_buildAuth',
                '_encode',
            ],

            // Can't rename _send as it conflicts with the Stream::send() method
            'Cake\Http\Client\Adapter\Stream' => [
                '_buildContext', '_buildHeaders', '_buildContent', '_buildOptions',
                '_buildSslContext', '_buildResponse', '_open',
            ],

            'Cake\Http\Middleware\CsrfProtectionMiddleware' => [
                '_unsetTokenField', '_verifyToken', '_addTokenCookie',
                '_validateToken', '_createCookie',
            ],
        ],

        'I18n' => [
            // Can't rename _parseDateTime as it conflicts with the DateTime::parseDateTime() method
            'Cake\I18n\DateFormatTrait' => ['_formatObject'],
            'Cake\I18n\Number' => ['_setAttributes'],
            'Cake\I18n\RelativeTimeFormatter' => ['_options', '_diffData'],
            'Cake\I18n\TranslatorRegistry' => ['_getTranslator'],
            'Cake\I18n\Parser\MoFileParser' => ['_readLong'],
            'Cake\I18n\Parser\PoFileParser' => ['_addMessage'],
        ],

        'Log' => [
            'Cake\Log\Engine\FileLog' => ['_getFilename', '_rotateFile'],
            'Cake\Log\Engine\SyslogLog' => ['_open', '_write'],
        ],

        'Mailer' => [
            'Cake\Mailer\TransportFactory' => ['_buildTransport'],
            'Cake\Mailer\Transport\MailTransport' => ['_mail'],
            // Can't rename _connect as it conflicts with the SmtpTransport::connect() method
            // Can't rename _disconnect as it conflicts with the SmtpTransport::disconnect() method
            'Cake\Mailer\Transport\SmtpTransport' => [
                '_bufferResponseLines', '_parseAuthType',
                '_auth', '_authPlain', '_authLogin', '_authXoauth2',
                '_prepareFromCmd', '_prepareRcptCmd', '_prepareFromAddress',
                '_prepareRecipientAddresses', '_prepareMessage',
                '_sendRcpt', '_sendData', '_generateSocket', '_smtpSend',
            ],
        ],

        'Network' => [
            'Cake\Network\Socket' => ['_getStreamSocketClient', '_setSslContext', '_connectionErrorHandler'],
        ],

        'ORM' => [
            'Cake\ORM\AssociationsNormalizerTrait' => ['_normalizeAssociations'],
            'Cake\ORM\AssociationCollection' => ['_saveAssociations', '_save'],
            'Cake\ORM\EagerLoader' => [
                '_reformatContain', '_normalizeContain', '_fixStrategies',
                '_correctStrategy', '_resolveJoins', '_buildAssociationsMap',
                '_collectKeys', '_groupKeys',
            ],
            'Cake\ORM\LazyEagerLoader' => ['_getQuery', '_getPropertyMap', '_injectResults'],
            'Cake\ORM\Marshaller' => [
                '_buildPropertyMap', '_validate', '_prepareDataAndOptions', '_marshalAssociation',
                '_belongsToMany', '_loadAssociatedByIds', '_mergeAssociation', '_mergeBelongsToMany',
                '_mergeJoinData',
            ],
            'Cake\ORM\Table' => [
                // Can't rename _saveMany as it conflicts with the Table::saveMany() method
                // Can't rename _deleteMany as it conflicts with the Table::deleteMany() method
                '_setFieldMatchers', '_executeTransaction', '_transactionCommitted',
                '_processFindOrCreate', '_getFindOrCreateQuery', '_processSave',
                '_onSaveSuccess', '_insert', '_newId', '_update', '_processDelete', '_dynamicFinder',
            ],

            // Behaviors
            'Cake\ORM\Behavior' => ['_resolveMethodAliases', '_reflectionCache'],
            'Cake\ORM\Behavior\TreeBehavior' => [
                // Can't rename _moveUp/_moveDown as it conflicts with the TreeBehavior::moveUp()/moveDown() methods
                // Can't rename _removeFromTree as it conflicts with the TreeBehavior::removeFromTree() method
                '_setChildrenLevel', '_setParent', '_setAsRoot', '_unmarkInternalTree',
                '_removeFromTree', '_getNode', '_recoverTree', '_getMax',
                '_sync', '_scope', '_ensureFields', '_getPrimaryKey',
            ],
            'Cake\ORM\Behavior\CounterCacheBehavior' => [
                '_processAssociations', '_processAssociation', '_shouldUpdateCount', '_getCount',
            ],
            'Cake\ORM\Behavior\TimestampBehavior' => ['_updateField'],

            // Associations
            'Cake\ORM\Association' => [
                '_propertyName', '_options', '_appendNotMatching',
                '_dispatchBeforeFind', '_appendFields', '_formatAssociationResults',
                '_bindNewAssociations', '_joinCondition', '_extractFinder',
            ],
            'Cake\ORM\Association\HasMany' => [
                // Can't rename _unlink as it conflicts with the HasMany::unlink() method
                '_saveTarget', '_unlinkAssociated', '_foreignKeyAcceptsNull',
            ],
            'Cake\ORM\Association\BelongsToMany' => [
                '_generateTargetAssociations', '_generateSourceAssociations',
                '_generateJunctionAssociations', '_saveTarget', '_saveLinks',
                '_appendJunctionJoin', '_diffLinks', '_checkPersistenceStatus',
                '_collectJointEntities', '_junctionAssociationName', '_junctionTableName',
            ],

            // Loaders
            'Cake\ORM\Association\Loader\SelectLoader' => [
                '_defaultOptions', '_buildQuery', '_extractFinder',
                '_assertFieldsPresent', '_addFilteringJoin', '_addFilteringCondition',
                '_createTupleCondition', '_linkField', '_buildSubquery',
                '_subqueryFields', '_buildResultMap', '_resultInjector',
                '_multiKeysInjector',
            ],

            // Locators
            'Cake\ORM\Locator\TableLocator' => ['_getClassName', '_create'],

            // Query
            'Cake\ORM\Query\SelectQuery' => [
                // Can't rename _decorateResults as it conflicts with the DB\SelectQuery::decorateResults() method
                // Can't rename _execute as it conflicts with the DB\Query::execute() method
                '_dirty', '_addAssociationsToTypeMap',
                '_performCount', '_transformQuery',
                '_addDefaultFields', '_addDefaultSelectTypes',
            ],

            // Rules
            'Cake\ORM\RulesChecker' => ['_addLinkConstraintRule', '_addError'],
            'Cake\ORM\Rule\LinkConstraint' => ['_aliasFields', '_buildConditions', '_countLinks'],
            'Cake\ORM\Rule\IsUnique' => ['_alias'],
            'Cake\ORM\Rule\ExistsIn' => ['_fieldsAreNull'],

        ],

        'Utility' => [
            'Cake\Utility\CookieCryptTrait' => [
                '_getCookieEncryptionKey', '_encrypt', '_checkCipher',
                '_decrypt', '_decode', '_implode', '_explode',
            ],
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
