<?php
class DatetimeError extends Exception {}


class CharmDate {
    const DAY_SECONDS = 86400;
 
    public $value; // timestamp
    public $year;
    public $month;
    public $days;
    public $hours;
    public $minutes;
    public $seconds;
    public $microseconds;
    public $weekday;
    public $yday;
 
    public function __construct($timestamp) {
        $this->value = $timestamp;
 
        $this->reset_property();
    }
 
    public function __toString() {
        return $this->datetime("%Y-%m-%d %H:%M:%S");
    }
 
    private function reset_property() {
        $info = getdate($this->value);
 
        $this->year = $info['year'];
        $this->month = $info['mon'];
        $this->days = $info['mday'];
        $this->hours = $info['hours'];
        $this->minutes = $info['minutes'];
        $this->seconds = $info['seconds'];
        $this->weekday = $info['wday'] === 0 ? 7 : $info['wday'];
        $this->yday = $info['yday'];
        $this->microseconds = $this->value * 1000;
    }
 
    public static function is_leap_year($year) {
        if(($year / 4 == 0) and ($year / 100) == 0) {
            return true;
        }
 
        if($year / 400 == 0) {
            return true;
        }
 
        return false;
    }
 
    public static function month_max_days($year, $month) {
        if($month == 2) {
            if(self::is_leap_year($year)) {
                return 29;
            }
            return 28;
        }

        $month_days = array(
            31 => [1, 3, 5, 7, 8, 10, 12],
            30 => [4, 6, 9, 11],
        );
        foreach($month_days as $days => $months) {
            if(in_array($month, $months)) {
                return $days;
            }
        }
 
        return 0;
    }
 
    public function datetime($format) {
        return strftime($format, $this->value);
    }
 
    public function beginning_of_date() {
        $time = mktime(0, 0, 0, $this->month, $this->days, $this->year);
        return new CharmDate($time);
    }
 
    public function end_of_date() {
        $time = mktime(23, 59, 59, $this->month, $this->days, $this->year);
        return new CharmDate($time);
    }
 
    public function next_date($days=0) {
        $time = $this->value + self::DAY_SECONDS * $days;
        return new CharmDate($time);
    }
 
    public function next_month($month=0) {
        $year = $this->year;
        $month = $this->month + $month;
        $days = $this->days;
 
        if($month > 12) {
            $year += intval($month / 12);
            $month = $month % 12;
        }

        if($month < 0) {
            $month = abs($month);
            $year = $year - intval($month / 12) - 1;
            $month = 12 - $month % 12;
        }
 
        $days = min($this->days, self::month_max_days($year, $month));
        $time = mktime($this->hours, $this->minutes, $this->seconds,
                       $month, $days, $year);
        return new CharmDate($time);
    }

    public function beginning_of_month() {
        $time = mktime(0, 0, 0, $this->month, 1, $this->year);
        return new CharmDate($time);
    }

    public function end_of_month() {
        $end_date = self::month_max_days($this->year, $this->month);
        $time = mktime(0, 0, 0, $this->month, $end_date, $this->year);
        return new CharmDate($time);
    }

    public function next_week($week=0) {
        $time = $this->value + $week * self::DAY_SECONDS * 7;
        return new CharmDate($time);
    }

    public function beginning_of_week() {
        $time = $this->value - self::DAY_SECONDS * ($this->weekday - 1);
        $obj = new CharmDate($time);
        return $obj->beginning_of_date();
    }

    public function end_of_week() {
        $time = $this->value + self::DAY_SECONDS * (7 - $this->weekday);
        $obj = new CharmDate($time);
        return $obj->end_of_date();
    }
}
?>
