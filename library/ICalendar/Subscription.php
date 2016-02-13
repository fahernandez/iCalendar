<?php
/**
 * ICalendar subscription file
 *
 * Init a new calendar subscription instance(based RFC 5545)
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

use ICalendar\File\Location\IHandler;
use ICalendar\Util\Error;
use ICalendar\Util\File;
use ICalendar\Util\Language;
use ICalendar\File\Template\Build;
use ICalendar\TimeZone as VTimeZone;
use DateTime;

final class Subscription extends ACalendar
{

    /**
     * VCalendar object template
     */
    const VTEMPLATE = 'VCalendar.txt';

    /**
     * VCalendar Opening and closing tag
     */
    const OPENING_TAG = 'BEGIN:VCALENDAR';
    const CLOSING_TAG = 'X-END=TRUE';

    /**
     * Calendar subscription parameters
     */
    const PRODID = 'prodid';
    const LANGUAGE = 'language';
    const CAL_NAME = 'cal_name';
    const CAL_DESC = 'cal_desc';
    const RELCAID = 'relcaid';
    const TIME_ZONE = 'time_zone';
    const TZID = 'tzid';
    const X_DTSTAMP = 'x_dtstamp';

    /**
     * Saves/load files to its public location
     * @var IHandler
     */
    protected $file_location_handler;

    /**
     * Location where tmp location files will be saved
     */
    protected $tmp_directory;

    /**
     * This property specifies the identifier for the product that created the
     * iCalendar object(RFC 5545)
     * @var string
     */
    protected $prodid;

    /**
     * To specify the language for text values in a property or property
     * parameter (RFC 5545)
     * @var string
     */
    protected $language;

    /**
     * To specify the name of the calendar
     * @var string
     */
    protected $cal_name;

    /**
     * To specify a description for the calendar
     * @var string
     */
    protected $cal_desc;

    /**
     * To specify unique name for the calendar
     * @var string
     */
    protected $relcaid;

    /**
     * Time Zone object for the subscription
     * @var VTimeZone
     */
    protected $time_zone;

    /**
     * Public location where the file where saved
     * @var string
     */
    protected $public_location;

    /**
     * File object handler of the loaded ics calendar file
     * @var File
     */
    protected $ics_file;

    /**
     * Custom icalendar value to save when the calendar was created
     * @var string formatted datetime
     */
    protected $x_dtstamp;

    /**
     * This property specifies the text value that uniquely identifies the
     * "VTIMEZONE" calendar * component. (RFC 5545)
     * @var string
     */
    protected $tzid;

    /**
     * VCalendar attributes mapping
     * @var array
     *      The key represent the name of the attribute on the class
     *      The value represents an regular expresion to get the value
     *      of the attribute on the vcalendar text object
     */
    protected static $object_attributes = [
        self::PRODID => ["^PRODID:-//([@\w;\-\.\,\!\#\$\%\~\'\"]*)", self::REGEX],
        self::LANGUAGE => ["^X-WR-CALNAME;LANGUAGE=([\w]*)", self::REGEX],
        self::CAL_NAME => ["X-WR-CALNAME", self::FREE_TEXT],
        self::CAL_DESC => ["X-WR-CALDESC", self::FREE_TEXT],
        self::RELCAID => ["^X-WR-RELCALID;LANGUAGE=\w*:(\w*)", self::REGEX],
        self::TZID => ["^X-WR-TIMEZONE;LANGUAGE=\w*:(\w*[\/|\\]\w*)", self::REGEX],
        self::X_DTSTAMP => ["^X-DTSTAMP;TYPE=DATE-TIME:(\w*)", self::REGEX]
    ];

    /**
     * Create a new Subscription instance
     * @codeCoverageIgnore
     * @param IHandler $file_location_handler
     */
    public function __construct(IHandler $file_location_handler)
    {
        $this->file_location_handler = $file_location_handler;
    }

    /**
     * Create a new calendar subscription
     * @return void
     */
    public function create()
    {
        $vcalendar = $this->build();
        $vtimezone = $this->time_zone->build();

        $vcalendar = $this->insert_time_zone($vcalendar, $vtimezone);

        $file = new file();
        $file->set_tmp_directory($this->tmp_directory)
            ->delete_file_on_destroy()
            ->save($vcalendar, $this->relcaid);

        $this->public_location = $this->file_location_handler->save($file);

        return $this->public_location;
    }

    /**
     * Load a file from its public location to edit it
     * @param  string $public_location
     * @return boolean true if the file was correctly loaded and the subscription
     * and time zone was correctly loaded from the string
     */
    public function load($public_location)
    {
        $this->public_location = $public_location;

        $this->ics_file = new file();
        $this->ics_file->set_tmp_directory($this->tmp_directory)
            ->delete_file_on_destroy();

        $file_path = $this->file_location_handler->load(
            $this->public_location,
            $this->ics_file->get_tmp_directory()
        );

        $this->time_zone = new VTimeZone();

        // Open the file and load the subscription and timezone object using
        // the attributes defined on static::object_attributes
        return $this->ics_file->open($file_path) &&
            $this->time_zone->load_from_file($this->ics_file) &&
            $this->load_from_file($this->ics_file);
    }

    /**
     * Delete a calendar subscription
     * @param  string $public_location location where the file can be found
     * @return boolean
     */
    public function delete($public_location)
    {
        return $this->file_location_handler->delete($public_location);
    }

    /**
     * Set prodid parameter
     * @param string $prodid
     */
    public function set_prodid($prodid)
    {
        $this->prodid = $prodid;

        return $this;
    }

    /**
     * Set vcalendar language
     * @param string $language
     */
    public function set_language($language)
    {
        if (!Language::exists($language)) {
            Error::set(
                Error::ERROR_INVALID_ARGUMENT,
                [$language, self::LANGUAGE],
                Error::ERROR
            );
        }

        $this->language = $language;

        return $this;
    }

    /**
     * Set calendar name
     * @param string $cal_name
     */
    public function set_cal_name($cal_name)
    {
        $this->cal_name = $cal_name;

        return $this;
    }

    /**
     * Set calendar description
     * @param string $cal_desc
     */
    public function set_cal_desc($cal_desc)
    {
        $this->cal_desc = $cal_desc;

        return $this;
    }

    /**
     * Set calendar unique id value
     * @param string $relcaid
     */
    public function set_relcaid($relcaid)
    {
        $this->relcaid = $relcaid;

        return $this;
    }

    /**
     * Set calendar time zone id value
     * @param string $relcaid
     */
    public function set_time_zone(VTimeZone $time_zone)
    {
        $this->time_zone = $time_zone;

        return $this;
    }

    /**
     * Change the default location of the temporary created files
     * On this location will be saved(and then deleted) the subscription files
     * @param string $tmp_directory
     */
    public function set_tmp_directory($tmp_directory)
    {
        $this->tmp_directory = $tmp_directory;

        return $this;
    }

    /**
     * Date time when the calendar file was created
     * @param DateTime $x_dtstamp
     */
    public function set_x_dtstamp(DateTime $x_dtstamp)
    {
        $this->x_dtstamp = $x_dtstamp->format(VTimeZone::DATETIME_FORMAT);

        return $this;
    }

    /**
     * Insert the vtimezone into the vcalendar string
     * The vtimezone string will be inserted at the end of the vcalendar string
     * @param  string $vcalendar formatted vcalendar string
     * @param  string $vtimezone formatted vtimezone string
     * @return string vtimezone inserted into the vcalendar object
     */
    public function insert_time_zone($vcalendar, $vtimezone)
    {
        $separated_content = preg_split(Build::SPLIT_LINES_REGEX, $vcalendar);
        $end_vcalendar = $separated_content[count($separated_content)-1];
        $separated_content[count($separated_content)-1] = $vtimezone;
        array_push($separated_content, $end_vcalendar);

        return implode($separated_content);
    }

    /**
     * Validated that all the attributes needed for the calendar format are set
     * @return void Set an error in case of any the attributes are missing
     */
    protected function validate_attributes()
    {
        if (!isset($this->x_dtstamp)) {
            $this->x_dtstamp = (new DateTime())->format(VTimeZone::DATETIME_FORMAT);
        }

        if (isset($this->time_zone)) {
            $this->tzid = $this->time_zone->tzid;
        }

        parent::validate_attributes();
    }

}