<?php
/**
 * ICalendar abstract class file
 *
 * Define all common methods on calendar handler objectes
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
use ICalendar\Util\File;

/**
 * @codeCoverageIgnore
 */
abstract class ACalendar
{

    /**
     * Available methods to parse the vcalendar content text
     */
    const REGEX = 'REGEX';
    const FREE_TEXT = 'FREE_TEXT';

     /**
     * Build a new calendar object string
     * @return vcalendar formatted string
     */
    public function build()
    {
        $this->validate_attributes();

        $attributes = [];
        foreach (static::$object_attributes as $key => $value) {
            $attributes[$key] = $this->{$key};
        }

        // Construct a vcalendar object based on the templated
        return (new Build(
            static::VTEMPLATE
        ))->build(
            $attributes
        );
    }

     /**
     * Get any class attribute
     * @param  string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        if (!isset($this->{$attribute})) {
            Error::set(Error::ERROR_INVALID_PROPERTY, [$attribute], Error::ERROR);
        }
        return $this->{$attribute};
    }

    /**
     *Validate that the necessary values to build the vcalendar object are set
     * @return void
     */
    protected function validate_attributes()
    {
        foreach (static::$object_attributes as $key => $value) {
            if (!isset($this->{$key})) {
                Error::set(Error::ERROR_MISSING_ATTRIBUTE, [$key], Error::ERROR);
            }
        }
    }

    /**
     * Load an object based on the file attributes defined for the vcalendar object
     * @param  File   $file file handler of the ics calendar file
     * @return boolean status of the operation
     */
    protected function load_from_file(File $file)
    {
        $string = $file->get_line_content(static::OPENING_TAG, static::CLOSING_TAG);
        return true;
    }

}