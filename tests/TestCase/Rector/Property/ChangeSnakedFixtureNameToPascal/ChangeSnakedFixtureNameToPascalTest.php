<?php
declare(strict_types=1);

namespace Cake\Upgrade\Test\TestCase\Rector\Property\ChangeSnakedFixtureNameToPascal;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ChangeSnakedFixtureNameToPascalTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
