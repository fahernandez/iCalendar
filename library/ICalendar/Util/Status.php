<?php
/**
 * ICalendar status library
 *
 * This class handle Status allowed on ICalendar implementation
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

class Status
{

    /**
     * Allowed status
     */
    const COMPLETED = "CONFIRMED";
    const CANCELLED = "CANCELLED";

    /**
     * Array of allowed statuses
     */
    private static $allowed_statuses = [
        self::COMPLETED,
        self::CANCELLED
    ];

    /**
     * Get if a status is valid on the ICalendar implementation
     */
    public static function exists($status)
    {
        return in_array($status, self::$allowed_statuses);
    }
}