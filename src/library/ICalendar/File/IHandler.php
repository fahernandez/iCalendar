<?php
/**
 * ICalendar file handler contract definition
 *
 * Defines methods that all subscription file handler have to implement to handle
 * different subscription methods
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

namespace ICalendar\File;

interface IHandler
{

    /**
     * Save a file into a location
     * @param  string $file_name name of the file to be save
     * @return boolean
     */
    public function save_file();
}