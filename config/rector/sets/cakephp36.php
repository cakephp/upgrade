<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\Assign\PropertyFetchToMethodCallRector;
use Rector\Transform\ValueObject\PropertyFetchToMethodCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    # source: https://book.cakephp.org/3.0/en/appendices/3-6-migration-guide.html
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('Cake\ORM\Table', 'association', 'getAssociation'),
        new MethodCallRename('Cake\Validation\ValidationSet', 'isPresenceRequired', 'requirePresence'),
        new MethodCallRename('Cake\Validation\ValidationSet', 'isEmptyAllowed', 'allowEmpty'),
    ]);

    $rectorConfig->ruleWithConfiguration(PropertyFetchToMethodCallRector::class, [
        new PropertyFetchToMethodCall('Cake\Controller\Controller', 'name', 'getName', 'setName'),
        new PropertyFetchToMethodCall('Cake\Controller\Controller', 'plugin', 'getPlugin', 'setPlugin'),
        new PropertyFetchToMethodCall('Cake\Form\Form', 'validator', 'getValidator', 'setValidator'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Cake\Cache\Engine\ApcEngine' => 'Cake\Cache\Engine\ApcuEngine',
        'Cake\Network\Exception\BadRequestException' => 'Cake\Http\Exception\BadRequestException',
        'Cake\Network\Exception\ConflictException' => 'Cake\Http\Exception\ConflictException',
        'Cake\Network\Exception\ForbiddenException' => 'Cake\Http\Exception\ForbiddenException',
        'Cake\Network\Exception\GoneException' => 'Cake\Http\Exception\GoneException',
        'Cake\Network\Exception\HttpException' => 'Cake\Http\Exception\HttpException',
        'Cake\Network\Exception\InternalErrorException' => 'Cake\Http\Exception\InternalErrorException',
        'Cake\Network\Exception\InvalidCsrfTokenException' => 'Cake\Http\Exception\InvalidCsrfTokenException',
        'Cake\Network\Exception\MethodNotAllowedException' => 'Cake\Http\Exception\MethodNotAllowedException',
        'Cake\Network\Exception\NotAcceptableException' => 'Cake\Http\Exception\NotAcceptableException',
        'Cake\Network\Exception\NotFoundException' => 'Cake\Http\Exception\NotFoundException',
        'Cake\Network\Exception\NotImplementedException' => 'Cake\Http\Exception\NotImplementedException',
        'Cake\Network\Exception\ServiceUnavailableException' => 'Cake\Http\Exception\ServiceUnavailableException',
        'Cake\Network\Exception\UnauthorizedException' => 'Cake\Http\Exception\UnauthorizedException',
        'Cake\Network\Exception\UnavailableForLegalReasonsException'
            => 'Cake\Http\Exception\UnavailableForLegalReasonsException',
        'Cake\Network\Session' => 'Cake\Http\Session',
        'Cake\Network\Session\DatabaseSession' => 'Cake\Http\Session\DatabaseSession',
        'Cake\Network\Session\CacheSession' => 'Cake\Http\Session\CacheSession',
        'Cake\Network\CorsBuilder' => 'Cake\Http\CorsBuilder',
        'Cake\View\Widget\WidgetRegistry' => 'Cake\View\Widget\WidgetLocator',
    ]);
};
