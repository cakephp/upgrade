<?php
declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\QueryExpressionComparisonNames;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * Verify the CakePHP 6 ruleset renames expression comparisons without changing operands.
 */
final class QueryExpressionComparisonNamesTest extends AbstractRectorTestCase
{
    /**
     * Upgrade each expression fixture through the complete CakePHP 6 ruleset.
     *
     * @param string $filePath Input and expected output fixture.
     * @return void
     */
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * Supply supported comparison calls and unrelated-class fixtures.
     *
     * @return \Iterator<array{string}>
     */
    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    /**
     * Use the shipped configuration so these rules are exercised as users run them.
     *
     * @return string
     */
    public function provideConfigFilePath(): string
    {
        return dirname(__DIR__, 5) . '/config/rector/cakephp60.php';
    }
}
