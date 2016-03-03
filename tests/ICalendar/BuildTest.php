<?php
/**
 * ICalendar build template file test
 *
 * Test build functionality
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

use \ICalendar\File\Template\Build;
use \ICalendar\Subscription;
use \ICalendar\TimeZone;

class BuildTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test buil a vcalendar template
     * @covers ICalendar\File\Template\Build::__construct
     * @covers ICalendar\File\Template\Build::build
     * @covers ICalendar\File\Template\Build::process_content
     * @covers ICalendar\File\Template\Build::format_content
     * @covers ICalendar\File\Template\Build::size_75
     */
    public function test_build_vcalendar()
    {
        $build = new Build(Subscription::VTEMPLATE);
        $generated_content = $build->build(array(
            Subscription::PRODID => '@testtest',
            Subscription::LANGUAGE => 'EN',
            Subscription::CAL_NAME => 'Calendar Test',
            Subscription::CAL_DESC => 'Calendar Test description',
            Subscription::RELCAID => '12312313',
            Subscription::TZID => 'America/Costa_Rica',
            Subscription::X_DTSTAMP => '20160128T232158'
        ));
        $content_test = "BEGIN:VCALENDAR" . Build::FIELD_DELIMITER .
            "VERSION:2.0" . Build::FIELD_DELIMITER .
            "PRODID:-//@testtest//NONSGML v1.0//EN" . Build::FIELD_DELIMITER .
            "CALSCALE:GREGORIAN" . Build::FIELD_DELIMITER .
            "METHOD:PUBLISH" . Build::FIELD_DELIMITER .
            "X-WR-CALNAME;LANGUAGE=EN:Calendar Test" . Build::FIELD_DELIMITER .
            "X-WR-CALDESC;LANGUAGE=EN:Calendar Test description" . Build::FIELD_DELIMITER .
            "X-WR-RELCALID;LANGUAGE=EN:12312313" . Build::FIELD_DELIMITER .
            "X-WR-TIMEZONE;LANGUAGE=EN:America/Costa_Rica" . Build::FIELD_DELIMITER .
            "X-DTSTAMP;TYPE=DATE-TIME:20160128T232158" . Build::FIELD_DELIMITER .
            "X-END=TRUE" . Build::FIELD_DELIMITER .
            "END:VCALENDAR";

        $this->assertEquals($generated_content, $content_test);
    }

     /**
     * Test buil a vtimezone template
     * @covers ICalendar\File\Template\Build::__construct
     * @covers ICalendar\File\Template\Build::build
     * @covers ICalendar\File\Template\Build::process_content
     * @covers ICalendar\File\Template\Build::format_content
     * @covers ICalendar\File\Template\Build::size_75
     */
    public function test_build_vtimezone()
    {
        $build = new Build(TimeZone::VTEMPLATE);
        $generated_content = $build->build(array(
            TimeZone::TZID => "America\Costa_Rica",
            TimeZone::STANDARD_DTSTART => '20160128T232158',
            TimeZone::OFFSET_FROM => '-0500',
            TimeZone::OFFSET_TO => '-0600',
            TimeZone::STANDARD_TZNAME => 'DLS',
            TimeZone::DAYLIGHT_DTSTART => '20160128T232159',
            TimeZone::DAYLIGHT_TZNAME => 'DES'
        ));

        $content_test = "BEGIN:VTIMEZONE" . Build::FIELD_DELIMITER .
            "TZID:America\Costa_Rica" . Build::FIELD_DELIMITER .
            "BEGIN:STANDARD" . Build::FIELD_DELIMITER .
            "DTSTART:20160128T232158" . Build::FIELD_DELIMITER .
            "TZOFFSETFROM:-0500" . Build::FIELD_DELIMITER .
            "TZOFFSETTO:-0600" . Build::FIELD_DELIMITER .
            "TZNAME:DLS" . Build::FIELD_DELIMITER .
            "END:STANDARD" . Build::FIELD_DELIMITER .
            "BEGIN:DAYLIGHT" . Build::FIELD_DELIMITER .
            "DTSTART:20160128T232159" . Build::FIELD_DELIMITER .
            "TZOFFSETFROM:-0600" . Build::FIELD_DELIMITER .
            "TZOFFSETTO:-0500" . Build::FIELD_DELIMITER .
            "TZNAME:DES" . Build::FIELD_DELIMITER .
            "END:DAYLIGHT" . Build::FIELD_DELIMITER .
            "END:VTIMEZONE";

        $this->assertEquals($generated_content, $content_test);
    }

}