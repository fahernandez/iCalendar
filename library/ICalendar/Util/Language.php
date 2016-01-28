<?php
/**
 * ICalendar language library
 *
 * This class handle languages allowed on ICalendar implementation
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

namespace ICalendar\Util;

class Language
{

    /**
     * Allowed Languages
     */
    const SPANISH = "ES";
    const ENGLISH = "EN";

    /**
     * Array of allowed languages
     */
    private static $allowed_languages = [
        self::SPANISH,
        self::ENGLISH
    ];

    /**
     * Get if a language is valid on the ICalendar implementation
     */
    public static function exists($language)
    {
        return in_array($language, self::$allowed_languages);
    }
}