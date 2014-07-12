<?php
class CharmDateTest extends PHPUnit_Framework_TestCase {
    public function test_is_leap_year() {
        $this->assertTrue(CharmDate::is_leap_year(2000));
    }
}
