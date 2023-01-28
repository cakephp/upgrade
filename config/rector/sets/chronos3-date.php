<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Transform\Rector\MethodCall\MethodCallToAnotherMethodCallWithArgumentsRector;
use Rector\Transform\ValueObject\MethodCallToAnotherMethodCallWithArguments;

/**
 * @see https://github.com/cakephp/chronos/blob/2.next/docs/en/2-4-upgrade-guide.rst
 * @see https://github.com/cakephp/chronos/blob/3.x/docs/en/3-x-migration-guide.rst
 */
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Cake\Chronos\Date' => 'Cake\Chronos\ChronosDate',
        'Cake\Chronos\MutableDate' => 'Cake\Chronos\ChronosDate',
    ]);

    $mutationMethods = [
        'addYear' => 'addYears',
        'subYear' => 'subYears',
        'addYearWithOverflow' => 'addYearsWithOverflow',
        'subYearWithOverflow' => 'subYearsWithOverflow',
        'addMonth' => 'addMonths',
        'subMonth' => 'subMonths',
        'addMonthWithOverflow' => 'addMonthsWithOverflow',
        'subMonthWithOverflow' => 'subMonthsWithOverflow',
        'addDay' => 'addDays',
        'subDay' => 'subDays',
        'addWeekday' => 'addWeekdays',
        'subWeekday' => 'subWeekdays',
        'addWeek' => 'addWeeks',
        'subWeek' => 'subWeeks',
    ];

    $renameMethods = [];

    foreach ($mutationMethods as $oldMethod => $newMethod) {
        $renameMethods[] = new MethodCallToAnotherMethodCallWithArguments(
            'Cake\Chronos\ChronosDate',
            $oldMethod,
            $newMethod,
            [1],
        );
    }

    $rectorConfig->ruleWithConfiguration(
        MethodCallToAnotherMethodCallWithArgumentsRector::class,
        $renameMethods
    );
};
