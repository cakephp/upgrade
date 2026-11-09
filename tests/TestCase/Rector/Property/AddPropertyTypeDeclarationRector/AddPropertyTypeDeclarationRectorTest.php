<?php
declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\Property\AddPropertyTypeDeclarationRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * Tests property type declarations required by CakePHP.
 */
final class AddPropertyTypeDeclarationRectorTest extends AbstractRectorTestCase
{
    /**
     * Tests property type conversion against a fixture.
     */
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * Provides property declaration fixtures.
     *
     * @return \Iterator<string[]>
     */
    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    /**
     * Returns the property type rule configuration.
     */
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
