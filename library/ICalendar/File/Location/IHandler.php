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

namespace ICalendar\File\Location;

/**
 * @codeCoverageIgnore
 */
interface IHandler
{

    /**
     * Save a file into a public location. After the path is saved on the public
     * location this file is deleted.
     * @param  string $file_path path where the file will be taken to be copied
     * to its public location
     * @param  string $file content type
     * @return string public location file
     */
    public function save($file_path, $content_type);

    /**
     * Delete a file in a public location
     * @param  string $file_path
     * @return boolean
     */
    public function delete($public_location);

    /**
     * Get a file from a public location and save it on the
     * @param  string $public_location
     * @return string file path where the public file was saved
     */
    public function load($public_location, $file_path_directory);
}