<?php
/**
 * Kima Error implementation for ICalendar library
 *
 * Error library handler
 *
 * PHP 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author        Steve Vega <https://github.com/svega>
 * @package       Kima
 * @since         0.1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace ICalendar\Util;

use Exception;

/**
 * Handles user trigger errors in the application
 */
class Error
{
    /**
     * Errors
     */
    const ERROR_MISSING_ATTRIBUTE = 'Attribute %s is requiered';
    const ERROR_INVALID_ARGUMENT = 'Attribute %s is invalid for %s, please refer to RFC 5545';
    const ERROR_INVALID_BUCKET = 'Aws S3 bucket %s does not exists';
    const ERROR_INVALID_PROPERTY = 'Undefined property via __get():%s';
    const ERROR_NO_READABLE = "File %s can't be read";
    const ERROR_READABLE = "File %s exist on tmp location, please delete it first.";
    const ERROR_NO_OPENED = "File %s has to be opened first";
    const ERROR_DIR_NO_READABLE = 'Directory %s cannot be access';

    /**
     * Error message format
     */
     const ERROR_FORMAT =
        'ICalendar: %s
        Trigger by %s function %s
        File: %s.
        Args: %s.';

    /**
     * Error levels
     */
    const ERROR = E_USER_ERROR;
    const WARNING = E_USER_WARNING;
    const NOTICE = E_USER_NOTICE;

    /**
     * Error levels list
     */
    private static $error_levels = [
        self::ERROR,
        self::WARNING,
        self::NOTICE
    ];

    /**
     * Error class cannot be instanced directly
     * @codeCoverageIgnore
     */
    private function __contruct()
    {

    }

    /**
     * Sets an application error
     * @param string  $message     the error message
     * @param boolean $is_critical whether is a critical error or not
     */
    public static function set($message, array $parameters = [], $level = null)
    {
        $message = vsprintf($message, $parameters);

        // make sure the error level is valid
        $error_level = in_array($level, self::$error_levels) ? $level : self::ERROR;

        // get the error caller
        $error_caller = self::get_error_caller();

        // sets the error message
        $error_message = sprintf(
            self::ERROR_FORMAT,
            (string) $message,
            $error_caller['class'],
            $error_caller['function'],
            isset($error_caller['file']) ? $error_caller['file'] : '',
            print_r($error_caller['args'], true)
        );

        // send the error
        trigger_error($error_message, $error_level);
    }

    /**
     * Gets the error caller
     * @return array
     */
    private static function get_error_caller()
    {
        $e = new Exception();
        $trace = $e->getTrace();

        return $trace[2];
    }

}
