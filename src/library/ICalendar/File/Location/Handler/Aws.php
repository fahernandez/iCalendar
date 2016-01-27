<?php
/**
 * ICalendar aws file handler implementation
 *
 * This class handle files to be saved and retrieved to an from AWS
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

namespace ICalendar\File\Location\Handler;

use ICalendar\File\Location\IHandler;

class Aws implements IHandler
{

    /**
     * Save a file into a S3 location
     * @return boolean
     */
    public function save($file_path)
    {

    }
}