<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

# source: https://book.cakephp.org/3.0/en/appendices/3-8-migration-guide.html

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->ruleWithConfiguration(
        RenameMethodRector::class,
        [new MethodCallRename('Cake\ORM\Entity', 'visibleProperties', 'getVisible')]
    );
};
