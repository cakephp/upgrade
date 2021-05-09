<?php
declare(strict_types=1);

use Rector\CakePHP\Set\CakePHPSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(CakePHPSetList::CAKEPHP_41);
};
