<?php
date_default_timezone_set('Asia/Shanghai');

class CharmDateTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $now = mktime(0, 54, 33, 7, 14, 2014);
        $this->obj = new CharmDate($now);
    }

    public function test_construct() {
        $this->assertEquals(1405270473, $this->obj->value);
        $this->assertEquals(2014, $this->obj->year);
        $this->assertEquals(7, $this->obj->month);
        $this->assertEquals(14, $this->obj->days);
        $this->assertEquals(0, $this->obj->hours);
        $this->assertEquals(54, $this->obj->minutes);
        $this->assertEquals(33, $this->obj->seconds);
        $this->assertEquals(1, $this->obj->weekday);
        $this->assertEquals(194, $this->obj->yday);
        $this->assertEquals(1405270473000, $this->obj->microseconds);
    }

    public function test_is_leap_year() {
        $this->assertTrue(CharmDate::is_leap_year(2012));
        $this->assertTrue(CharmDate::is_leap_year(2008));
        $this->assertTrue(CharmDate::is_leap_year(2000));
        $this->assertTrue(CharmDate::is_leap_year(1600));

        $this->assertFalse(CharmDate::is_leap_year(1900));
        $this->assertFalse(CharmDate::is_leap_year(1800));
        $this->assertFalse(CharmDate::is_leap_year(2007));
    }

    public function test_max_month_days() {
        $months = array(1, 3, 5, 7, 8, 10, 12);
        foreach($months as $month) {
            $this->assertEquals(31, CharmDate::max_month_days(2014, $month));
        }

        $months = array(4, 6, 9, 11);
        foreach($months as $month) {
            $this->assertEquals(30, CharmDate::max_month_days(2014, $month));
        }

        $this->assertEquals(29, CharmDate::max_month_days(2012, 2));
        $this->assertEquals(28, CharmDate::max_month_days(2014, 2));
    }

    public function test_datetime() {
        $this->assertEquals('2014-07-14', $this->obj->datetime('%Y-%m-%d'));
    }

    public function test_beginning_of_date() {
        $obj = $this->obj->beginning_of_date();
        $this->assertInstanceOf('CharmDate', $obj);

        $fmt = '%Y-%m-%d %H:%M:%S';
        $this->assertEquals('2014-07-14 00:00:00', $obj->datetime($fmt));
    }

    public function test_end_of_date() {
        $obj = $this->obj->end_of_date();
        $this->assertInstanceOf('CharmDate', $obj);

        $fmt = '%Y-%m-%d %H:%M:%S';
        $this->assertEquals('2014-07-14 23:59:59', $obj->datetime($fmt));
    }

    public function test_next_date() {
        $obj = $this->obj->next_date(3);
        $this->assertInstanceOf('CharmDate', $obj);

        $fmt = '%Y-%m-%d %H:%M:%S';
        $this->assertEquals('2014-07-17 00:54:33', $obj->datetime($fmt));

        $obj = $this->obj->next_date(0);
        $this->assertEquals('2014-07-14 00:54:33', $obj->datetime($fmt));

        $obj = $this->obj->next_date(-3);
        $this->assertEquals('2014-07-11 00:54:33', $obj->datetime($fmt));
    }

    public function test_beginning_of_month() {
        $obj = $this->obj->beginning_of_month();
        $this->assertInstanceOf('CharmDate', $obj);

        $fmt = '%Y-%m-%d %H:%M:%S';
        $this->assertEquals('2014-07-01 00:00:00', $obj->datetime($fmt));
    }

    public function test_end_of_month() {
        $obj = $this->obj->end_of_month();
        $this->assertInstanceOf('CharmDate', $obj);

        $fmt = '%Y-%m-%d %H:%M:%S';
        $this->assertEquals('2014-07-31 23:59:59', $obj->datetime($fmt));
    }

    public function test_next_month() {
        $obj = $this->obj->next_month(1);
        $this->assertInstanceOf('CharmDate', $obj);

        $fmt = '%Y-%m-%d %H:%M:%S';
        $this->assertEquals('2014-08-14 00:54:33', $obj->datetime($fmt));

        $obj = $this->obj->next_date(16)->next_month(-5);
        $this->assertEquals('2014-02-28 00:54:33', $obj->datetime($fmt));

        $obj = $this->obj->next_month(32);
        $this->assertEquals('2017-03-14 00:54:33', $obj->datetime($fmt));

        $obj = $this->obj->next_month(-32);
        $this->assertEquals('2011-11-14 00:54:33', $obj->datetime($fmt));
    }

    public function test_beginning_of_week() {
        $obj = $this->obj->beginning_of_week();
        $fmt = '%Y-%m-%d %H:%M:%S';
        $this->assertEquals('2014-07-14 00:00:00', $obj->datetime($fmt));
    }

    public function test_end_of_week() {
        $obj = $this->obj->end_of_week();
        $fmt = '%Y-%m-%d %H:%M:%S';
        $this->assertEquals('2014-07-20 23:59:59', $obj->datetime($fmt));
    }

    public function test_next_week($week=0) {
        $obj = $this->obj->next_week(0);
        $fmt = '%Y-%m-%d %H:%M:%S';
        $this->assertEquals('2014-07-14 00:54:33', $obj->datetime($fmt));

        $obj = $this->obj->next_week(1);
        $this->assertEquals('2014-07-21 00:54:33', $obj->datetime($fmt));

        $obj = $this->obj->next_week(-1);
        $this->assertEquals('2014-07-07 00:54:33', $obj->datetime($fmt));
    }
}
