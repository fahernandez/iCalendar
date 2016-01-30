<?php
/**
 * ICalendar file path library
 *
 * This class handle path operations as create or delete
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

class Path
{
    /**
     * Create a new path
     * @param  string  $path       absolute path
     * @param  string  $permission permission to be applied to the path
     * @return boolean
     */
    public function create_path($path, $permission = 0777)
    {
        if (is_dir($path)) {
            return true;
        }
        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
        $return = $this->create_path($prev_path, $permission);

        return ($return && is_writable($prev_path))
            ? mkdir($path, $permission, true) && chmod($path, $permission)
            : false;
    }

    /**
     * Delete a file
     * @param  string $path
     * @return booleans
     */
    public function delete_file($path)
    {
        return unlink($path);
    }
}
