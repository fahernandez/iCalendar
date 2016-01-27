<?php
/**
 * ICalendar subscription file
 *
 * Init a new calendar subscription instance(based RFC 2445)
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

class Subscription
{
    /**
     * Calendar subscription parameters
     */
    const PRODID = 'prodid';
    const LANGUAGE = 'language';
    const CAL_NAME = 'cal_name';
    const CAL_DESC = 'cal_desc';
    const RELCAID = 'relcaid';
    const TIME_ZONE = 'time_zone';

    /**
     * Saves/load files to its public location
     * @var IHandler
     */
    private $file_location_handler;

    /**
     * This property specifies the identifier for the product that created the
     * iCalendar object(RFC 2445)
     * @var string
     */
    private $prodid;

    /**
     * To specify the language for text values in a property or property
     * parameter (RFC 2445)
     * @var string
     */
    private $language;

    /**
     * To specify the name of the calendar
     * @var string
     */
    private $cal_name;

    /**
     * To specify a description for the calendar
     * @var string
     */
    private $cal_desc;

    /**
     * To specify unique name for the calendar
     * @var string
     */
    private $relcaid;

    /**
     * Time Zone object for the subscription
     * @var VTimeZone
     */
    private $time_zone;

    /**
     * Path location to the vcalendar .ics file
     * @var string
     */
    private $vcalendar_file_location;

    /**
     * Create a new Subscription instance
     */
    public function __construct(IHandler $file_location_handler)
    {
        $this->file_location_handler = $file_location_handler;
    }

    /**
     * Build a new vcalendar subscription string
     * @return vcalendar formatted string
     */
    public function build()
    {
        $this->validate_calendar_attributes();

        // Construct a vcalendar object based on the templated
        return (new Build(
            Build::VCALENDAR_TEMPLATE
        ))->build([
            self::PRODID => $this->prodid,
            self::LANGUAGE => $this->language,
            self::CAL_NAME => $this->cal_name,
            self::CAL_DESC => $this->cal_desc,
            self::RELCAID => $this->relcaid
        ]);
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

        $this->vcalendar_file_location = (new File())
            ->save($vcalendar, $this->relcaid);

        $this->file_location_handler->save($this->vcalendar_file_location);
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
                [$language, 'language'],
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
     * Validated that all the attributes needed for the calendar format are set
     * @return void Set an error in case of any the attributes are missing
     */
    private function validate_calendar_attributes()
    {
        if (!isset($this->prodid)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::PRODID], Error::ERROR);
        }

        if (!isset($this->cal_name)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::CAL_NAME], Error::ERROR);
        }

        if (!isset($this->cal_desc)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::CAL_DESC], Error::ERROR);
        }

        if (!isset($this->relcaid)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::RELCAID], Error::ERROR);
        }

        if (!isset($this->time_zone)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, [self::TIME_ZONE], Error::ERROR);
        }
    }

    /**
     * Insert the vtimezone into the vcalendar string
     * The vtimezone string will be inserted at the end of the vcalendar string
     * @param  string $vcalendar formatted vcalendar string
     * @param  string $vtimezone formatted vtimezone string
     * @return string vtimezone inserted into the vcalendar object
     */
    private function insert_time_zone($vcalendar, $vtimezone)
    {
        $separated_content = preg_split(Build::SPLIT_LINES_REGEX, $vcalendar);
        $end_vcalendar = $separated_content[count($separated_content)-1];
        $separated_content[count($separated_content)-1] = $vtimezone;
        array_push($separated_content, $end_vcalendar);

        return implode($separated_content);
    }

}