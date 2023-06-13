<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\MethodCall\AddMethodCallArgsRector;
use Cake\Upgrade\Rector\ValueObject\AddMethodCallArgs;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;

/**
 * @see https://github.com/cakephp/chronos/blob/2.next/docs/en/2-4-upgrade-guide.rst
 * @see https://github.com/cakephp/chronos/blob/3.x/docs/en/3-x-migration-guide.rst
 */
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        // Date
        'Cake\Chronos\Date' => 'Cake\Chronos\ChronosDate',
        'Cake\Chronos\MutableDate' => 'Cake\Chronos\ChronosDate',

        // DateTime
        'Cake\Chronos\MutableDateTime' => 'Cake\Chronos\Chronos',
    ]);

    $dateTimeMutationMethods = [
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

        // Time specific methods
        'addHour' => 'addHours',
        'subHour' => 'subHours',
        'addMinute' => 'addMinutes',
        'subMinute' => 'subMinutes',
        'addSecond' => 'addSeconds',
        'subSecond' => 'subSeconds',
    ];

    $renameMethods = $addMethodCallArgs = [];

    foreach ($dateTimeMutationMethods as $oldMethod => $newMethod) {
        $renameMethods[] = new MethodCallRename('Cake\Chronos\Chronos', $oldMethod, $newMethod);
        $addMethodCallArgs[] = new AddMethodCallArgs('Cake\Chronos\Chronos', $newMethod, 1);
    }

    $dateMutationMethods = [
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

    foreach ($dateMutationMethods as $oldMethod => $newMethod) {
        $renameMethods[] = new MethodCallRename('Cake\Chronos\ChronosDate', $oldMethod, $newMethod);
        $addMethodCallArgs[] = new AddMethodCallArgs('Cake\Chronos\ChronosDate', $newMethod, 1);
    }

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, $renameMethods);
    $rectorConfig->ruleWithConfiguration(AddMethodCallArgsRector::class, $addMethodCallArgs);
};
