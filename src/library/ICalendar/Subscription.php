<?php
/**
 * ICalendar subscription file
 *
 * Init a new calendar subscription instance
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

use ICalendar\File\IHandler;
use ICalendar\Util\Error;
use ICalendar\Util\Language;
use ICalendar\File\Template\Build;

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

    /**
     * Handles all related to manage subscription files
     * @var IHandler
     */
    private $file_handler;

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
     * Create a new Subscription instance
     */
    public function __construct(IHandler $file_handler)
    {
        $this->file_handler = $file_handler;
    }

    /**
     * Create a new calendar subscription
     * @return boolean
     */
    public function build()
    {
        $this->validate_calendar_attributes();

        // Construct a vcalendar object based on the templated
        // The attributes must be on the order of the template
        $vcalendar = (new Build(
            Build::VCALENDAR_TEMPLATE
        ))->build([
            self::PRODID => $this->prodid,
            self::LANGUAGE => $this->language,
            self::CAL_NAME => $this->cal_name,
            self::CAL_DESC => $this->cal_desc,
            self::RELCAID => $this->relcaid
        ]);

        return $this;

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
     * Validated that all the attributes needed for the calendar format are set
     * @return void Set an error in case of any the attributes are missing
     */
    private function validate_calendar_attributes()
    {
        if (!isset($this->prodid)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, ['prodid'], Error::ERROR);
        }

        if (!isset($this->cal_name)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, ['cal_name'], Error::ERROR);
        }

        if (!isset($this->cal_desc)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, ['cal_desc'], Error::ERROR);
        }

        if (!isset($this->relcaid)) {
            Error::set(Error::ERROR_MISSING_ATTRIBUTE, ['relcaid'], Error::ERROR);
        }
    }

}