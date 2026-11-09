<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Cake5\AddPropertyTypeDeclarationRector;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AddPropertyTypeDeclarationRector::class, [
        new AddPropertyTypeDeclaration('Cake\Controller\Component', '_defaultConfig', new ArrayType(
            new MixedType(),
            new MixedType(),
        )),
    ]);
};
