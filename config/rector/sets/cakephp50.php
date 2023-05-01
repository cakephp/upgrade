<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\OptionsArrayToNamedParametersRector;
use Cake\Upgrade\Rector\ValueObject\OptionsArrayToNamedParameters;
use Rector\Config\RectorConfig;

# @see https://book.cakephp.org/5/en/appendices/5-0-migration-guide.html
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(
        OptionsArrayToNamedParametersRector::class,
        [
            new OptionsArrayToNamedParameters('Cake\ORM\Table', ['find']),
            new OptionsArrayToNamedParameters('Cake\ORM\Query', ['find']),
            new OptionsArrayToNamedParameters('Cake\ORM\Association', ['find']),
        ]
    );

};
