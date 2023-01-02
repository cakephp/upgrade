<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\MethodCall\ModalToGetSetRector;
use Cake\Upgrade\Rector\ValueObject\ModalToGetSet;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\Visibility;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;
use Rector\Transform\Rector\Assign\PropertyFetchToMethodCallRector;
use Rector\Transform\ValueObject\PropertyFetchToMethodCall;
use Rector\Visibility\Rector\ClassMethod\ChangeMethodVisibilityRector;
use Rector\Visibility\ValueObject\ChangeMethodVisibility;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->ruleWithConfiguration(PropertyFetchToMethodCallRector::class, [
        // source: https://book.cakephp.org/3.0/en/appendices/3-4-migration-guide.html
        new PropertyFetchToMethodCall('Cake\Network\Request', 'params', 'getAttribute', null, ['params']),
        new PropertyFetchToMethodCall('Cake\Network\Request', 'data', 'getData'),
        new PropertyFetchToMethodCall('Cake\Network\Request', 'query', 'getQueryParams'),
        new PropertyFetchToMethodCall('Cake\Network\Request', 'cookies', 'getCookie'),
        new PropertyFetchToMethodCall('Cake\Network\Request', 'base', 'getAttribute', null, ['base']),
        new PropertyFetchToMethodCall('Cake\Network\Request', 'webroot', 'getAttribute', null, ['webroot']),
        new PropertyFetchToMethodCall('Cake\Network\Request', 'here', 'getAttribute', null, ['here']),
    ]);

    $rectorConfig->ruleWithConfiguration(
        RenamePropertyRector::class,
        [new RenameProperty('Cake\Network\Request', '_session', 'session')]
    );

    $rectorConfig->ruleWithConfiguration(ModalToGetSetRector::class, [
        new ModalToGetSet('Cake\Core\InstanceConfigTrait', 'config', null, null, 2, 'array'),
        new ModalToGetSet('Cake\Core\StaticConfigTrait', 'config', null, null, 2, 'array'),
        new ModalToGetSet('Cake\Console\ConsoleOptionParser', 'command'),
        new ModalToGetSet('Cake\Console\ConsoleOptionParser', 'description'),
        new ModalToGetSet('Cake\Console\ConsoleOptionParser', 'epilog'),
        new ModalToGetSet('Cake\Database\Connection', 'driver'),
        new ModalToGetSet('Cake\Database\Connection', 'schemaCollection'),
        new ModalToGetSet('Cake\Database\Connection', 'useSavePoints', 'isSavePointsEnabled', 'enableSavePoints'),
        new ModalToGetSet('Cake\Database\Driver', 'autoQuoting', 'isAutoQuotingEnabled', 'enableAutoQuoting'),
        new ModalToGetSet('Cake\Database\Expression\FunctionExpression', 'name'),
        new ModalToGetSet(
            'Cake\Database\Expression\QueryExpression',
            'tieWith',
            'getConjunction',
            'setConjunction'
        ),
        new ModalToGetSet('Cake\Database\Expression\ValuesExpression', 'columns'),
        new ModalToGetSet('Cake\Database\Expression\ValuesExpression', 'values'),
        new ModalToGetSet('Cake\Database\Expression\ValuesExpression', 'query'),
        new ModalToGetSet('Cake\Database\Query', 'connection'),
        new ModalToGetSet('Cake\Database\Query', 'selectTypeMap'),
        new ModalToGetSet(
            'Cake\Database\Query',
            'bufferResults',
            'isBufferedResultsEnabled',
            'enableBufferedResults'
        ),
        new ModalToGetSet('Cake\Database\Schema\CachedCollection', 'cacheMetadata'),
        new ModalToGetSet('Cake\Database\Schema\TableSchema', 'options'),
        new ModalToGetSet('Cake\Database\Schema\TableSchema', 'temporary', 'isTemporary', 'setTemporary'),
        new ModalToGetSet('Cake\Database\TypeMap', 'defaults'),
        new ModalToGetSet('Cake\Database\TypeMap', 'types'),
        new ModalToGetSet('Cake\Database\TypeMapTrait', 'typeMap'),
        new ModalToGetSet('Cake\Database\TypeMapTrait', 'defaultTypes'),
        new ModalToGetSet('Cake\ORM\Association', 'name'),
        new ModalToGetSet('Cake\ORM\Association', 'cascadeCallbacks'),
        new ModalToGetSet('Cake\ORM\Association', 'source'),
        new ModalToGetSet('Cake\ORM\Association', 'target'),
        new ModalToGetSet('Cake\ORM\Association', 'conditions'),
        new ModalToGetSet('Cake\ORM\Association', 'bindingKey'),
        new ModalToGetSet('Cake\ORM\Association', 'foreignKey'),
        new ModalToGetSet('Cake\ORM\Association', 'dependent'),
        new ModalToGetSet('Cake\ORM\Association', 'joinType'),
        new ModalToGetSet('Cake\ORM\Association', 'property'),
        new ModalToGetSet('Cake\ORM\Association', 'strategy'),
        new ModalToGetSet('Cake\ORM\Association', 'finder'),
        new ModalToGetSet('Cake\ORM\Association\BelongsToMany', 'targetForeignKey'),
        new ModalToGetSet('Cake\ORM\Association\BelongsToMany', 'saveStrategy'),
        new ModalToGetSet('Cake\ORM\Association\BelongsToMany', 'conditions'),
        new ModalToGetSet('Cake\ORM\Association\HasMany', 'saveStrategy'),
        new ModalToGetSet('Cake\ORM\Association\HasMany', 'foreignKey'),
        new ModalToGetSet('Cake\ORM\Association\HasMany', 'sort'),
        new ModalToGetSet('Cake\ORM\Association\HasOne', 'foreignKey'),
        new ModalToGetSet('Cake\ORM\EagerLoadable', 'config'),
        new ModalToGetSet('Cake\ORM\EagerLoadable', 'canBeJoined', 'canBeJoined', 'setCanBeJoined'),

        // note: will have to be called after setMatching() to keep the old behavior
        // ref: https://github.com/cakephp/cakephp/blob/4feee5463641e05c068b4d1d31dc5ee882b4240f/src/ORM/EagerLoader.php#L330
        new ModalToGetSet('Cake\ORM\EagerLoadable', 'matching'),
        new ModalToGetSet('Cake\ORM\EagerLoadable', 'autoFields', 'isAutoFieldsEnabled', 'enableAutoFields'),
        new ModalToGetSet('Cake\ORM\Locator\TableLocator', 'config'),
        new ModalToGetSet('Cake\ORM\Query', 'eagerLoader'),
        new ModalToGetSet('Cake\ORM\Query', 'hydrate', 'isHydrationEnabled', 'enableHydration'),
        new ModalToGetSet('Cake\ORM\Query', 'autoFields', 'isAutoFieldsEnabled', 'enableAutoFields'),
        new ModalToGetSet('Cake\ORM\Table', 'table'),
        new ModalToGetSet('Cake\ORM\Table', 'alias'),
        new ModalToGetSet('Cake\ORM\Table', 'registryAlias'),
        new ModalToGetSet('Cake\ORM\Table', 'connection'),
        new ModalToGetSet('Cake\ORM\Table', 'schema'),
        new ModalToGetSet('Cake\ORM\Table', 'primaryKey'),
        new ModalToGetSet('Cake\ORM\Table', 'displayField'),
        new ModalToGetSet('Cake\ORM\Table', 'entityClass'),

        new ModalToGetSet('Cake\Mailer\Email', 'entityClass'),

        new ModalToGetSet('Cake\Mailer\Email', 'from'),
        new ModalToGetSet('Cake\Mailer\Email', 'sender'),
        new ModalToGetSet('Cake\Mailer\Email', 'replyTo'),
        new ModalToGetSet('Cake\Mailer\Email', 'readReceipt'),
        new ModalToGetSet('Cake\Mailer\Email', 'returnPath'),
        new ModalToGetSet('Cake\Mailer\Email', 'to'),
        new ModalToGetSet('Cake\Mailer\Email', 'cc'),
        new ModalToGetSet('Cake\Mailer\Email', 'bcc'),
        new ModalToGetSet('Cake\Mailer\Email', 'charset'),
        new ModalToGetSet('Cake\Mailer\Email', 'headerCharset'),
        new ModalToGetSet('Cake\Mailer\Email', 'emailPattern'),
        new ModalToGetSet('Cake\Mailer\Email', 'subject'),
        // template: have to be changed manually, non A → B change + array case
        new ModalToGetSet('Cake\Mailer\Email', 'viewRender', 'getViewRenderer', 'setViewRenderer'),
        new ModalToGetSet('Cake\Mailer\Email', 'viewVars'),
        new ModalToGetSet('Cake\Mailer\Email', 'theme'),
        new ModalToGetSet('Cake\Mailer\Email', 'helpers'),
        new ModalToGetSet('Cake\Mailer\Email', 'emailFormat'),
        new ModalToGetSet('Cake\Mailer\Email', 'transport'),
        new ModalToGetSet('Cake\Mailer\Email', 'messageId'),
        new ModalToGetSet('Cake\Mailer\Email', 'domain'),
        new ModalToGetSet('Cake\Mailer\Email', 'attachments'),
        new ModalToGetSet('Cake\Mailer\Email', 'configTransport'),
        new ModalToGetSet('Cake\Mailer\Email', 'profile'),
        new ModalToGetSet('Cake\Validation\Validator', 'provider'),
        new ModalToGetSet('Cake\View\StringTemplateTrait', 'templates'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'templatePath'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'layoutPath'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'plugin'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'helpers'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'theme'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'template'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'layout'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'options'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'name'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'className'),
        new ModalToGetSet('Cake\View\ViewBuilder', 'autoLayout', 'isAutoLayoutEnabled', 'enableAutoLayout'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('Cake\Network\Request', 'param', 'getParam'),
        new MethodCallRename('Cake\Network\Request', 'data', 'getData'),
        new MethodCallRename('Cake\Network\Request', 'query', 'getQuery'),
        new MethodCallRename('Cake\Network\Request', 'cookie', 'getCookie'),
        new MethodCallRename('Cake\Network\Request', 'method', 'getMethod'),
        new MethodCallRename('Cake\Network\Request', 'setInput', 'withBody'),
        new MethodCallRename('Cake\Network\Response', 'location', 'withLocation'),
        new MethodCallRename('Cake\Network\Response', 'disableCache', 'withDisabledCache'),
        new MethodCallRename('Cake\Network\Response', 'type', 'withType'),
        new MethodCallRename('Cake\Network\Response', 'charset', 'withCharset'),
        new MethodCallRename('Cake\Network\Response', 'cache', 'withCache'),
        new MethodCallRename('Cake\Network\Response', 'modified', 'withModified'),
        new MethodCallRename('Cake\Network\Response', 'expires', 'withExpires'),
        new MethodCallRename('Cake\Network\Response', 'sharable', 'withSharable'),
        new MethodCallRename('Cake\Network\Response', 'maxAge', 'withMaxAge'),
        new MethodCallRename('Cake\Network\Response', 'vary', 'withVary'),
        new MethodCallRename('Cake\Network\Response', 'etag', 'withEtag'),
        new MethodCallRename('Cake\Network\Response', 'compress', 'withCompression'),
        new MethodCallRename('Cake\Network\Response', 'length', 'withLength'),
        new MethodCallRename('Cake\Network\Response', 'mustRevalidate', 'withMustRevalidate'),
        new MethodCallRename('Cake\Network\Response', 'notModified', 'withNotModified'),
        new MethodCallRename('Cake\Network\Response', 'cookie', 'withCookie'),
        new MethodCallRename('Cake\Network\Response', 'file', 'withFile'),
        new MethodCallRename('Cake\Network\Response', 'download', 'withDownload'),
        # psr-7
        new MethodCallRename('Cake\Network\Response', 'header', 'getHeader'),
        new MethodCallRename('Cake\Network\Response', 'body', 'withBody'),
        new MethodCallRename('Cake\Network\Response', 'statusCode', 'getStatusCode'),
        new MethodCallRename('Cake\Network\Response', 'protocol', 'getProtocolVersion'),
        new MethodCallRename('Cake\Event\Event', 'name', 'getName'),
        new MethodCallRename('Cake\Event\Event', 'subject', 'getSubject'),
        new MethodCallRename('Cake\Event\Event', 'result', 'getResult'),
        new MethodCallRename('Cake\Event\Event', 'data', 'getData'),
        new MethodCallRename('Cake\View\Helper\FormHelper', 'input', 'control'),
        new MethodCallRename('Cake\View\Helper\FormHelper', 'inputs', 'controls'),
        new MethodCallRename('Cake\View\Helper\FormHelper', 'allInputs', 'allControls'),
        new MethodCallRename('Cake\Mailer\Mailer', 'layout', 'setLayout'),
        new MethodCallRename('Cake\Routing\Route\Route', 'parse', 'parseRequest'),
        new MethodCallRename('Cake\Routing\Router', 'parse', 'parseRequest'),
    ]);

    $rectorConfig->ruleWithConfiguration(ChangeMethodVisibilityRector::class, [
        new ChangeMethodVisibility('Cake\Mailer\MailerAwareTrait', 'getMailer', Visibility::PROTECTED),
        new ChangeMethodVisibility('Cake\View\CellTrait', 'cell', Visibility::PROTECTED),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Cake\Database\Schema\Table' => 'Cake\Database\Schema\TableSchema',
    ]);
};
