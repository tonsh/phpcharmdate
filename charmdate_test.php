<?php
class CharmDateTest extends PHPUnit_Framework_TestCase {
    public function test_is_leap_year() {
        $this->assertTrue(CharmDate::is_leap_year(2012));
        $this->assertTrue(CharmDate::is_leap_year(2008));
        $this->assertTrue(CharmDate::is_leap_year(2000));
        $this->assertTrue(CharmDate::is_leap_year(1600));

        $this->assertFalse(CharmDate::is_leap_year(1900));
        $this->assertFalse(CharmDate::is_leap_year(1800));
        $this->assertFalse(CharmDate::is_leap_year(2007));
    }
}
