<?php

class Chronos3 {

    public function datetime() {

        $dateTime = new \Cake\Chronos\Chronos();
        $dateTime->addYears(1);
        $dateTime->subYears(1);
        $dateTime->addYearsWithOverflow(1);
        $dateTime->subYearsWithOverflow(1);
        $dateTime->addMonths(1);
        $dateTime->subMonths(1);
        $dateTime->addMonthsWithOverflow(1);
        $dateTime->subMonthsWithOverflow(1);
        $dateTime->addDays(1);
        $dateTime->subDays(1);
        $dateTime->addWeekdays(1);
        $dateTime->subWeekdays(1);
        $dateTime->addWeeks(1);
        $dateTime->subWeeks(1);

        $dateTime->addHours(1);
        $dateTime->subHours(1);
        $dateTime->addMinutes(1);
        $dateTime->subMinutes(1);
        $dateTime->addSeconds(1);
        $dateTime->subSeconds(1);

    }

}
