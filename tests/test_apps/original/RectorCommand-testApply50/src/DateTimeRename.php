<?php
declare(strict_types=1);

class DateTimeRename {
    public function finders() {
        $date = new \Cake\I18n\FrozenDate();
        $dateTime = new \Cake\I18n\FrozenTime();
    }
}
