<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\RemoveMethodCallRector;
use Cake\Upgrade\Rector\ValueObject\RemoveMethodCall;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

/**
 * @see https://github.com/cakephp/authentication/blob/4.x/docs/en/upgrade-3-to-4.rst
 */
return static function (RectorConfig $rectorConfig): void {
    // URL checker class renames
    // Note: Order matters - StringUrlChecker rename must come first to avoid
    // CakeRouterUrlChecker -> DefaultUrlChecker -> StringUrlChecker chain
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        // Old DefaultUrlChecker renamed to StringUrlChecker
        'Authentication\UrlChecker\DefaultUrlChecker' => 'Authentication\UrlChecker\StringUrlChecker',
        // CakeRouterUrlChecker renamed to DefaultUrlChecker
        'Authentication\UrlChecker\CakeRouterUrlChecker' => 'Authentication\UrlChecker\DefaultUrlChecker',
        // Plugin class renamed
        'Authentication\Plugin' => 'Authentication\AuthenticationPlugin',
    ]);

    // Remove loadIdentifier() method calls from AuthenticationService
    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        new RemoveMethodCall('Authentication\AuthenticationService', 'loadIdentifier'),
        new RemoveMethodCall('Authentication\AuthenticationServiceInterface', 'loadIdentifier'),
    ]);
};
