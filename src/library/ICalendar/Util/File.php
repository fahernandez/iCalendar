<?php
/**
 * ICalendar file handler library
 *
 * This class handle file operations
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

use \ICalendar\Util\Error;

class File
{
    /**
     * Specifies the type of access you require to the stream.
     */
    const R = 'r' ;
    const R_PLUS = 'r+';
    const W = 'w';
    const W_PLUS = 'w+';
    const A = 'a';
    const A_PLUS = 'a+';
    const X = 'x';
    const X_PLUS = 'x+';
    const C = 'c';
    const C_PLUS = 'c+';

    /**
     * Errors
     */
    const ERROR_NO_READABLE = "File %s can't be read";
    const ERROR_READABLE = "File %s exist on tmp location, please delete it first.";
    const ERROR_NO_OPENED = "File %s has to be opened first";

    /**
     * Temporary file location
     */
    const TEMP_FILE_LOCATION = "%s/../../../tmp/%s.ics";

    /**
     * Class attributes
     */
    private $file_path;
    private $file_open_mode;
    private $file_handler;

    /**
     * Create the file instance
     * @param string $file_path
     * @param string $file_open_mode file open mode
     */
    public function __construct()
    {
        // No construct implementation needed
    }

    /**
     * Close the file
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Open a file path
     * @return File handler
     */
    public function open($file_path)
    {
        $this->file_path = realpath($file_path);
        $this->file_open_mode = self::R_PLUS;

        if (!is_readable($this->file_path)) {
            Error::set(self::ERROR_NO_READABLE, [$this->file_path], Error::ERROR);
        }

        $this->open_handler();
    }


    /**
     * Create a file an save the string content into the file
     * @param  string $content string
     * @param  string $name    string
     * @return string file path where the file can be found
     */
    public function save($content, $name)
    {
        $this->file_path = sprintf(self::TEMP_FILE_LOCATION, __DIR__, $name);
        $this->file_open_mode = self::W_PLUS;

        if (is_readable($this->file_path)) {
            Error::set(self::ERROR_READABLE, [$this->file_path], Error::ERROR);
        }

        $this->open_handler();

        fwrite($this->file_handler, $content);
        $this->file_path = realpath($this->file_path);

        return $this->file_path;
    }

    /**
     * Close the file handler
     * @param handler $file_handler
     */
    public function close()
    {
        if (is_resource($this->file_handler)) {
            fclose($this->file_handler);
        }
    }

    /**
     * Get all the content of the opened file
     * @return string
     */
    public function get_all_content()
    {
        if (empty($this->file_handler)) {
            Error::set(self::ERROR_NO_OPENED, [$this->file_path], Error::ERROR);
        }

        return fread($this->file_handler, filesize($this->file_path));
    }

    /**
     * Open file handler
     */
    private function open_handler()
    {
        $this->file_handler = fopen($this->file_path, $this->file_open_mode);
    }
}
