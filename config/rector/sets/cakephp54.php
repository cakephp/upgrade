<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\DisableHydrationToUnhydratedFindRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

# @see https://book.cakephp.org/5/en/appendices/5-4-migration-guide.html
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Cake\Command\Helper\BannerHelper' => 'Cake\Console\Helper\BannerHelper',
        'Cake\Command\Helper\ProgressHelper' => 'Cake\Console\Helper\ProgressHelper',
        'Cake\Command\Helper\TableHelper' => 'Cake\Console\Helper\TableHelper',
        'Cake\Command\Helper\TreeHelper' => 'Cake\Console\Helper\TreeHelper',
    ]);
    $rectorConfig->rule(DisableHydrationToUnhydratedFindRector::class);
};
