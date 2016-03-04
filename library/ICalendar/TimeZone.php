<?php
/**
 * ICalendar time zone file
 *
 * Create a new time zone instance(based RFC 5545)
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

final class TimeZone extends ACalendar
{
    /**
     * VCalendar object template
     */
    const VTEMPLATE = 'VTimeZone.txt';

    /**
     * VTimeZone Opening and closing tag
     */
    const OPENING_TAG = 'BEGIN:VTIMEZONE';
    const CLOSING_TAG = 'END:VTIMEZONE';

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
    const DATETIME_FORMAT = 'Ymd\THis';

    /**
     * VTimeZone time offeset format validator
     */
    const OFFSET_FORMAT = "/^[\-\+]{1}[\d]{4}$/";

    /**
     * This property specifies the text value that uniquely identifies the
     * "VTIMEZONE" calendar * component. (RFC 5545)
     * @var string
     */
    protected $tzid;

    /**
     * This property specifies the offset which is in use prior to this time
     * zone observance. (RFC 5545)
     * @var string
     */
    protected $offset_from;

    /**
     * This property specifies the offset which is in use in this time zone
     * observance.(RFC 5545)
     * @var string
     */
    protected $offset_to;

    /**
     * This property specifies the customary designation for a time zone
     * description(RFC 5545)
     * @var string
     */
    protected $standard_tzname;

    /**
     * This property specifies the customary designation for a time zone
     * description(RFC 5545)
     * @var string
     */
    protected $daylight_tzname;

    /**
     * This property specifies when the calendar component begins(RFC 5545)
     * @var string
     */
    protected $daylight_dtstart;

    /**
     * This property specifies when the calendar component begins(RFC 5545)
     * @var string
     */
    protected $standard_dtstart;

    /**
     * VTimeZone attributes mapping
     * @var array
     *      The key represent the name of the attribute on the class
     *      The value represents an regular expresion to get the value
     *      of the attribute on the vcalendar text object
     */
    protected static $object_attributes = [
        self::TZID => ['/TZID:(\w*[\\|\/]\w*)/', self::REGEX],
        self::STANDARD_DTSTART => ['/DTSTART:(\w*)/', self::REGEX],
        self::OFFSET_FROM => ['/TZOFFSETFROM:([-\d]*)/', self::REGEX],
        self::OFFSET_TO => ['/TZOFFSETTO:([-\d]*)/', self::REGEX],
        self::STANDARD_TZNAME => ['/TZNAME:([\w]*)/', self::REGEX],
        self::DAYLIGHT_DTSTART => ['/DTSTART:(\w*)/', self::REGEX],
        self::DAYLIGHT_TZNAME => ['/TZNAME:([\w]*)/', self::REGEX]
    ];

    /**
     * Create a new Subscription instance
     */
    public function __construct()
    {
        // No value on construct is needed
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
        $this->standard_dtstart = $standard_dtstart->format(self::DATETIME_FORMAT);

        return $this;
    }

    /**
     * Set daylight_dtstart vcalendar paramenter
     * @param DateTime daylight_dtstart
     */
    public function set_daylight_dtstart(DateTime $daylight_dtstart)
    {
        $this->daylight_dtstart = $daylight_dtstart->format(self::DATETIME_FORMAT);

        return $this;
    }

    /**
     * Validate the attributes related to the Vtimezone object
     * @return boolean Set an error in case of any the attributes are missing or
     * true if it was success
     */
    public function validate_attributes()
    {
        return parent::validate_attributes();
    }


}