<?php
declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\ConditionOperatorsToPredicatesRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * Verifies condition operator migrations and deliberately unchanged queries.
 */
final class ConditionOperatorsToPredicatesRectorTest extends AbstractRectorTestCase
{
    /**
     * Checks one before/after migration fixture.
     *
     * @param string $filePath The fixture to migrate.
     */
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * Provides migration fixtures.
     *
     * @return \Iterator<string[]>
     */
    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    /**
     * Returns the isolated predicate migration configuration.
     *
     * @return string
     */
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
