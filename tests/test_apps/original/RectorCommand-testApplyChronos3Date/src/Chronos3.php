<?php

class Chronos3 {

    public function date() {

        $date = new \Cake\Chronos\Date();
        $date->addYear();
        $date->subYear();
        $date->addYearWithOverflow();
        $date->subYearWithOverflow();
        $date->addMonth();
        $date->subMonth();
        $date->addMonthWithOverflow();
        $date->subMonthWithOverflow();
        $date->addDay();
        $date->subDay();
        $date->addWeekday();
        $date->subWeekday();
        $date->addWeek();
        $date->subWeek();
        $date->addWeek()->subWeek();

        $date = new \Cake\Chronos\MutableDate();
        $date->addYear();
        $date->subYear();
        $date->addYearWithOverflow();
        $date->subYearWithOverflow();
        $date->addMonth();
        $date->subMonth();
        $date->addMonthWithOverflow();
        $date->subMonthWithOverflow();
        $date->addDay();
        $date->subDay();
        $date->addWeekday();
        $date->subWeekday();
        $date->addWeek();
        $date->subWeek();
        $date->addWeek()->subWeek();

    }

}
