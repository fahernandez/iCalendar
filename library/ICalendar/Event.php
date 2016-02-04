<?php
/**
 * ICalendar event file
 *
 * Create a new event instance(based RFC 5545)
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
use ICalendar\Util\Status;
use ICalendar\File\Template\Build;
use ICalendar\TimeZone as VTimeZone;
use DateTime;

final class Event extends ACalendar
{

    /**
     * VCalendar object template
     */
    const VTEMPLATE = 'VEvent.txt';


    /**
     * VEvent attributes object
     */
    const UID = 'uid';
    const DTSTAMP = 'dtstamp';
    const CREATED = 'created';
    const LANGUAGE = 'language';
    const DESCRIPTION = 'description';
    const TZID = 'tzid';
    const DTSTART = 'dtstart';
    const DTEND = 'dtend';
    const LAST_MODIFIED = 'last_modified';
    const LOCATION = 'location';
    const SUMMARY = 'summary';
    const STATUS = 'status';

    /**
     * This property defines the persistent, globally unique
     * identifier for the calendar component (based RFC 5545)
     * @var string
     */
    protected $uid;

    /**
     * This property specifies the date and time that
     * the instance of the iCalendar object was created (based RFC 5545)
     * @var string
     */
    protected $dtstamp;

    /**
     * To specify the language for text values in a property or
     * property parameter (based RFC 5545)
     * @var string
     */
    protected $language;

    /**
     * This property specifies the date and time that the calendar
     * information was created by the calendar user agent in the
     * calendar store (based RFC 5545)
     * @var string
     */
    protected $created;

    /**
     * This property specifies the text value that uniquely
     * identifies the "VTIMEZONE" calendar component in the scope of an
     * iCalendar object (based RFC 5545)
     * @var string
     */
    protected $tzid;

    /**
     * This property specifies the date and time that the
     * information associated with the calendar component was last
     * revised in the calendar store. (based RFC 5545)
     * @var string
     */
    protected $last_modified;

    /**
     * Set the event id. This value join to the calendar prodid will
     * be the event uid
     * @var int
     */
    protected $id;

    /**
     * This property provides a more complete description of the
     * calendar component than that provided by the "SUMMARY" property (based RFC 5545)
     * @var string
     */
    protected $description;


    /**
     * This property specifies when the calendar component begins. (based RFC 5545)
     * @var string
     */
    protected $dtstart;

    /**
     * This property specifies the date and time that a calendar
     * component ends. (based RFC 5545)
     * @var string
     */
    protected $dtend;

    /**
     * This property defines the intended venue for the activity
     * defined by a calendar component. (based RFC 5545)
     * @var string
     */
    protected $location;

    /**
     * This property defines a short summary or subject for the
     * calendar component. (based RFC 5545)
     * @var string
     */
    protected $summary;

    /**
     * This property defines the overall status or confirmation
     * for the calendar component. (based RFC 5545)
     * @var string
     */
    protected $status;

    /**
     * VEvent attributes mapping
     * @var array
     *      The key represent the name of the attribute on the class
     *      The value represents an regular expresion to get the value
     *      of the attribute on the vcalendar text object
     */
    protected static $object_attributes = [
        self::UID => 'UID',
        self::DTSTAMP => 'DTSTAMP',
        self::CREATED => 'CREATED',
        self::LANGUAGE => 'LANGUAGE',
        self::DESCRIPTION => 'DESCRIPTION',
        self::TZID => 'TZID',
        self::DTSTART => 'DTSTART',
        self::DTEND => 'DTEND',
        self::LAST_MODIFIED => 'LAST-MODIFIED',
        self::LOCATION => 'LOCATION',
        self::SUMMARY => 'SUMMARY',
        self::STATUS => 'STATUS'
    ];

    /**
     * Create a new Subscription instance
     */
    public function __construct()
    {
        // No value on construct is needed
    }

    /**
     * Set the event id
     * @param string created
     */
    public function set_id($id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Set the event description
     * @param string description
     */
    public function set_description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the time when the event begins
     * @param DateTime dtstart
     */
    public function set_dtstart(DateTime $dtstart)
    {
        $this->dtstart = $dtstart->format(VTimeZone::DATETIME_FORMAT);

        return $this;
    }

    /**
     * Set the time when the event finishes
     * @param DateTime dtend
     */
    public function set_dtend(DateTime $dtend)
    {
        $this->dtend = $dtend->format(VTimeZone::DATETIME_FORMAT);

        return $this;
    }

    /**
     * Set the location of the event
     * @param string location
     */
    public function set_location($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Set the event summary
     * @param string summary
     */
    public function set_summary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Set the event status
     * @param string status
     */
    public function set_status($status)
    {
        if (!Status::exists($status)) {
            Error::set(
                Error::ERROR_INVALID_ARGUMENT,
                [$status, self::STATUS],
                Error::ERROR
            );
        }

        $this->status = $status;

        return $this;
    }


    /**
     * Validate the attributes related to the VEvent object
     * @return void
     */
    protected function validate_attributes()
    {
        parent::validate_attributes();
    }


}