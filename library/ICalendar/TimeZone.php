<?php
/**
 * ICalendar time zone file
 *
 * Create a new time zone instance(based RFC 2445)
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

namespace ICalendar;

use ICalendar\Util\Error;
use ICalendar\File\Template\Build;
use DateTime;

class TimeZone
{
    /**
     * Calendar time zone parameters
     */
    const TZID = 'tzid';
    const STANDARD_DTSTART = 'standard_dtstart';
    const OFFSET_FROM = 'offset_from';
    const OFFSET_TO = 'offset_to';
    const STANDARD_TZNAME = 'standard_tzname';
    const DAYLIGHT_DTSTART = 'daylight_dtstart';
    const DAYLIGHT_TZNAME = 'daylight_tzname';

    /**
     * VTimeZone dtstart attribute date time format
     */
    const DTSTART_FORMAT = 'Ymd\THis';

    /**
     * VTimeZone time offeset format validator
     */
    const OFFSET_FORMAT = "/^[\-\+]{1}[\d]{4}$/";

    /**
     * This property specifies the text value that uniquely identifies the
     * "VTIMEZONE" calendar * component. (RFC 2445)
     * @var string
     */
    private $tzid;

    /**
     * This property specifies the offset which is in use prior to this time
     * zone observance. (RFC 2445)
     * @var string
     */
    private $offset_from;

    /**
     * This property specifies the offset which is in use in this time zone
     * observance.(RFC 2445)
     * @var string
     */
    private $offset_to;

    /**
     * This property specifies the customary designation for a time zone
     * description(RFC 2445)
     * @var string
     */
    private $standard_tzname;

    /**
     * This property specifies the customary designation for a time zone
     * description(RFC 2445)
     * @var string
     */
    private $daylight_tzname;

    /**
     * This property specifies when the calendar component begins(RFC 2445)
     * @var string
     */
    private $daylight_dtstart;

    /**
     * This property specifies when the calendar component begins(RFC 2445)
     * @var string
     */
    private $standard_dtstart;

    /**
     * Create a new Subscription instance
     */
    public function __construct()
    {
        // No value on construct is needed
    }

    /**
     * Build a new calendar vtimezone string
     * @return vtimezone formatted string
     */
    public function build()
    {
        $this->validate_time_zone_attributes();

        // Construct a vTimezone object based on the templated
        return (new Build(
            Build::VTIMEZONE_TEMPLATE
        ))->build([
            self::TZID => $this->tzid,
            self::STANDARD_DTSTART => $this->standard_dtstart,
            self::OFFSET_FROM => $this->offset_from,
            self::OFFSET_TO => $this->offset_to,
            self::STANDARD_TZNAME => $this->standard_tzname,
            self::DAYLIGHT_DTSTART => $this->daylight_dtstart,
            self::DAYLIGHT_TZNAME => $this->daylight_tzname
        ]);
    }

    /**
     * Set tzid vcalendar paramenter
     * @param string tzid
     */
    public function set_tzid($tzid)
    {
        $this->tzid = $tzid;

        return $this;
    }

    /**
     * Set offset_from vcalendar paramenter
     * @param string offset_from
     */
    public function set_offset_from($offset_from)
    {
        preg_match(self::OFFSET_FORMAT, $offset_from, $output_array);
        if (empty($output_array)) {
            Error::set(
                Error::ERROR_INVALID_ARGUMENT,
                [$offset_from, self::OFFSET_FROM],
                Error::ERROR
            );
        }

        $this->offset_from = $offset_from;

        return $this;
    }

    /**
     * Set offset_to vcalendar paramenter
     * @param string offset_to
     */
    public function set_offset_to($offset_to)
    {
        preg_match(self::OFFSET_FORMAT, $offset_to, $output_array);
        if (empty($output_array)) {
            Error::set(
                Error::ERROR_INVALID_ARGUMENT,
                [$offset_to, self::OFFSET_TO],
                Error::ERROR
            );
        }
        $this->offset_to = $offset_to;

        return $this;
    }

    /**
     * Set standard_tzname vcalendar paramenter
     * @param string standard_tzname
     */
    public function set_standard_tzname($standard_tzname)
    {
        $this->standard_tzname = $standard_tzname;

        return $this;
    }

    /**
     * Set daylight_tzname vcalendar paramenter
     * @param string daylight_tzname
     */
    public function set_daylight_tzname($daylight_tzname)
    {
        $this->daylight_tzname = $daylight_tzname;

        return $this;
    }

    /**
     * Set standard_dtstart vcalendar paramenter
     * @param DateTime standard_dtstart
     */
    public function set_standard_dtstart(DateTime $standard_dtstart)
    {
        $this->standard_dtstart = $standard_dtstart->format(self::DTSTART_FORMAT);

        return $this;
    }

    /**
     * Set daylight_dtstart vcalendar paramenter
     * @param DateTime daylight_dtstart
     */
    public function set_daylight_dtstart(DateTime $daylight_dtstart)
    {
        $this->daylight_dtstart = $daylight_dtstart->format(self::DTSTART_FORMAT);

        return $this;
    }

    /**
     * Validate the attributes related to the Vtimezone object
     * @return void
     */
    private function validate_time_zone_attributes()
    {
        if (!isset($this->tzid)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::TZID], Error::ERROR);
        }

        if (!isset($this->standard_dtstart)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::STANDARD_DTSTART], Error::ERROR);
        }

        if (!isset($this->offset_from)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::OFFSET_FROM], Error::ERROR);
        }

        if (!isset($this->offset_to)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::OFFSET_TO], Error::ERROR);
        }

        if (!isset($this->standard_tzname)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::STANDARD_TZNAME], Error::ERROR);
        }
        if (!isset($this->daylight_dtstart)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::DAYLIGHT_DTSTART], Error::ERROR);
        }

        if (!isset($this->daylight_tzname)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::DAYLIGHT_TZNAME], Error::ERROR);
        }
    }


}