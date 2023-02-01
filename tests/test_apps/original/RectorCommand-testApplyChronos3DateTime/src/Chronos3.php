<?php

class Chronos3 {

    public function datetime() {

        $dateTime = new \Cake\Chronos\MutableDateTime();
        $dateTime->addYear();
        $dateTime->subYear();
        $dateTime->addYearWithOverflow();
        $dateTime->subYearWithOverflow();
        $dateTime->addMonth();
        $dateTime->subMonth();
        $dateTime->addMonthWithOverflow();
        $dateTime->subMonthWithOverflow();
        $dateTime->addDay();
        $dateTime->subDay();
        $dateTime->addWeekday();
        $dateTime->subWeekday();
        $dateTime->addWeek();
        $dateTime->subWeek();

        $dateTime->addHour();
        $dateTime->subHour();
        $dateTime->addMinute();
        $dateTime->subMinute();
        $dateTime->addSecond();
        $dateTime->subSecond();

    }

}
