<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\OptionsArrayToNamedParametersRector;
use Cake\Upgrade\Rector\ValueObject\OptionsArrayToNamedParameters;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;

# @see https://book.cakephp.org/5/en/appendices/5-0-migration-guide.html
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(
        OptionsArrayToNamedParametersRector::class,
        [
            new OptionsArrayToNamedParameters('Cake\ORM\Table', ['find']),
            new OptionsArrayToNamedParameters('Cake\ORM\Query', ['find']),
            new OptionsArrayToNamedParameters('Cake\ORM\Association', ['find']),
            new OptionsArrayToNamedParameters('Cake\ORM\Table', ['get', 'rename' => ['key' => 'cacheKey']]),
        ]
    );

    $arrayType = new ArrayType(new MixedType(), new MixedType());
    $rectorConfig->ruleWithConfiguration(
        AddPropertyTypeDeclarationRector::class,
        [
            new AddPropertyTypeDeclaration('Cake\ORM\Entity', '_hidden', $arrayType),
            new AddPropertyTypeDeclaration('Cake\ORM\Entity', '_accessible', $arrayType),
            new AddPropertyTypeDeclaration('Cake\ORM\Entity', '_virtual', $arrayType),
        ]
    );

};
