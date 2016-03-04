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
     * Validate that the necessary values to build the vcalendar object are set
     * @return boolean Set an error in case of any the attributes are missing or
     * true if it was success
     */
    protected function validate_attributes()
    {
        foreach (static::$object_attributes as $key => $value) {
            if (!isset($this->{$key})) {
                Error::set(Error::ERROR_MISSING_ATTRIBUTE, [$key], Error::ERROR);
            }
        }

        return true;
    }

    /**
     * Load an object based on the file attributes defined for the vcalendar object
     * @param  File   $file file handler of the ics calendar file
     * @return boolean status of the operation
     */
    protected function load_from_file(File $file)
    {
        $input_line = $file->get_block_content(static::OPENING_TAG, static::CLOSING_TAG);

        return $this->digest_content($file, $input_line);
    }

    /**
     * Digest the content related to a vobject
     * @param  File   $file file handler of the ics calendar file
     * @param  string $input_line string to be digested
     * @return boolean
     */
    private function digest_content($file, $input_line) 
    {
        foreach (static::$object_attributes as $property => $process_attributes) {
            list($regex, $type) = $process_attributes;
            // Process fields by regex
            if ($type == self::REGEX) {
                if (preg_match($regex, $input_line, $output_array)) {
                    $this->{$property} = $output_array[1];
                } else {
                    $file->__destruct();
                    Error::set(Error::ERROR_ATTR_NOT_FOUND, [$property, $input_line], Error::ERROR);
                }
            // Process free text fields
            } elseif ($type == self::FREE_TEXT) {
                $string_separated = explode(Build::FIELD_DELIMITER, $input_line);
                list($regex, $type, $final_regex) = $process_attributes;
                $content_line = '';
                $content_found = false;

                // Wrap all the content beetween the $regex and the $final_refex
                foreach ($string_separated as $line) {
                    if (preg_match($regex, $line, $output_array)) {
                        // Remove the beginning tag
                        $content_line .= preg_replace($regex, "", $line);
                        $content_found = true;
                        continue;
                    }

                    if (preg_match($final_regex, $line, $output_array)) {
                        break;
                    }

                    if ($content_found) {
                        // Remove the first white space
                        $content_line .= preg_replace("/^\s/", "", $line);
                    }
                }

                if (empty($content_line)) {
                    $file->__destruct();
                    Error::set(Error::ERROR_ATTR_NOT_FOUND, [$property, $input_line], Error::ERROR);
                } else {
                    $this->{$property} = $content_line;
                }
            } else {
                $file->__destruct();
                Error::set(Error::DIGEST_METHOD_NOT_FOUND, [$type], Error::ERROR);
            }
        }

        return true;
    }

}