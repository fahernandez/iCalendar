<?php
/**
 * ICalendar VTimezone file test
 *
 * Test VTimezone functionality
 *
 * PHP 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author        Fabian Hernandez <fabian.hernandez@hulihealth.com>
 * @copyright     Copyright 2016, Fabian Hernandez <fabian.hernandez@hulihealth.com>
 * @link          https://github.com/fahernandez/iCalendar
 * @package       ICalendar
 * @since         0.1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

use ICalendar\TimeZone;
use \ICalendar\File\Template\Build;

class TimeZoneTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test build a new time zone object
     * @covers ICalendar\TimeZone::__construct
     * @covers ICalendar\TimeZone::build
     * @covers ICalendar\TimeZone::set_tzid
     * @covers ICalendar\TimeZone::set_offset_from
     * @covers ICalendar\TimeZone::set_offset_to
     * @covers ICalendar\TimeZone::set_standard_tzname
     * @covers ICalendar\TimeZone::set_daylight_tzname
     * @covers ICalendar\TimeZone::set_standard_dtstart
     * @covers ICalendar\TimeZone::set_daylight_dtstart
     * @covers ICalendar\TimeZone::validate_attributes
     */
    public function test_build_time_zone()
    {
        $time_zone = (new TimeZone())
            ->set_tzid('America/Costa_Rica')
            ->set_standard_dtstart(new DateTime('2000-01-01'))
            ->set_offset_from('-0500')
            ->set_offset_to('-0600')
            ->set_standard_tzname('DST')
            ->set_daylight_dtstart(new DateTime('2000-01-01'))
            ->set_daylight_tzname('DLT');

        $content_test = "BEGIN:VTIMEZONE" . Build::FIELD_DELIMITER .
            "TZID:America/Costa_Rica" . Build::FIELD_DELIMITER .
            "BEGIN:STANDARD" . Build::FIELD_DELIMITER .
            "DTSTART:20000101T000000" . Build::FIELD_DELIMITER .
            "TZOFFSETFROM:-0500" . Build::FIELD_DELIMITER .
            "TZOFFSETTO:-0600" . Build::FIELD_DELIMITER .
            "TZNAME:DST" . Build::FIELD_DELIMITER .
            "END:STANDARD" . Build::FIELD_DELIMITER .
            "BEGIN:DAYLIGHT" . Build::FIELD_DELIMITER .
            "DTSTART:20000101T000000" . Build::FIELD_DELIMITER .
            "TZOFFSETFROM:-0600" . Build::FIELD_DELIMITER .
            "TZOFFSETTO:-0500" . Build::FIELD_DELIMITER .
            "TZNAME:DLT" . Build::FIELD_DELIMITER .
            "END:DAYLIGHT" . Build::FIELD_DELIMITER .
            "END:VTIMEZONE";

        $generated_content = $time_zone->build();

        $this->assertEquals($generated_content, $content_test);
    }



}