<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\OptionsArrayToNamedParametersRector;
use Cake\Upgrade\Rector\ValueObject\OptionsArrayToNamedParameters;
use PHPStan\Type\ArrayType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\UnionType;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;

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

    $intNull = new UnionType([new IntegerType(), new NullType()]);
    $rectorConfig->ruleWithConfiguration(AddReturnTypeDeclaration::class, [
        new AddReturnTypeDeclaration('Cake\Console\Command', 'execute', $intNull),
    ]);

    $arrayType = new ArrayType(new MixedType(), new MixedType());
    $rectorConfig->ruleWithConfiguration(
        AddPropertyTypeDeclarationRector::class,
        [
            new AddPropertyTypeDeclaration('Cake\ORM\Entity', '_hidden', $arrayType),
            new AddPropertyTypeDeclaration('Cake\ORM\Entity', '_accessible', $arrayType),
            new AddPropertyTypeDeclaration('Cake\ORM\Entity', '_virtual', $arrayType),
        ]
    );

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Cake\I18n\FrozenDate' => 'Cake\I18n\Date',
        'Cake\I18n\FrozenTime' => 'Cake\I18n\DateTime',
    ]);
};
