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
     * Temporary file location
     */
    const DEFAULT_TMP_DIRECTORY = "/../../../tmp";
    const FILE_LOCATION_FORMAT = "%s/%s.ics";

    /**
     * Class attributes
     */
    private $file_path;
    private $file_open_mode;
    private $file_handler;
    private $delete_file = false;
    private $tmp_directory;

    /**
     * Create the file instance
     */
    public function __construct()
    {
        $this->tmp_directory = __DIR__ . self::DEFAULT_TMP_DIRECTORY;
    }

    /**
     * Close the file
     * (optional) Destroy the opened file
     */
    public function __destruct()
    {
        $this->close();

        if ($this->delete_file) {
            $this->delete();
        }
    }

    /**
     * Open a file path
     * @return boolean true if the result was correctly opened
     */
    public function open($file_path)
    {
        $this->file_path = $this->get_real_path($file_path);

        $this->file_open_mode = self::R_PLUS;

        if (!is_readable($this->file_path)) {
            Error::set(Error::ERROR_NO_READABLE, [$this->file_path], Error::ERROR);
        }

        return $this->open_handler();
    }


    /**
     * Create a file an save the string content into the file
     * @param  string $content string
     * @param  string $name    string
     * @return string file path where the file can be found
     */
    public function save($content, $name)
    {
        (new Path())->create_path($this->tmp_directory);

        $this->file_path = sprintf(
            self::FILE_LOCATION_FORMAT,
            $this->tmp_directory,
            $name
        );

        $this->file_open_mode = self::W_PLUS;

        if (is_readable($this->file_path)) {
            Error::set(Error::ERROR_READABLE, [$this->file_path], Error::ERROR);
        }

        $this->open_handler();

        $content = iconv(
            'utf-8',
            'utf-8//TRANSLIT//IGNORE',
            $content
        );

        fwrite($this->file_handler, $content);

        $this->file_path = $this->get_real_path($this->file_path);

        return $this->file_path;
    }

    /**
     * Method to indicate than the file must be deleted on destroy method
     * @return $this
     */
    public function delete_file_on_destroy()
    {
        $this->delete_file = true;

        return $this;
    }

    /**
     * Get all the content of the opened file
     * @return string
     */
    public function get_all_content()
    {
        if (!is_resource($this->file_handler)) {
            Error::set(Error::ERROR_NO_OPENED, [$this->file_path], Error::ERROR);
        }

        return fread($this->file_handler, filesize($this->file_path));
    }

    /**
     * Get the text file beetween the opening tag and the closing tag. If no
     * id was specify so the first ocurrence of the object will be return
     * @param  string $opening_tag
     * @param  string $closing_tag
     * @param  string $id          unique vobject id
     * @return string
     */
    public function get_line_content($opening_tag, $closing_tag, $id = null)
    {
        if (!is_resource($this->file_handler)) {
            Error::set(Error::ERROR_NO_OPENED, [$this->file_path], Error::ERROR);
        }

        while (!feof($this->file_handler)) {
            $line = iconv(
                'utf-8',
                'utf-8//TRANSLIT//IGNORE',
                utf8_encode(fgets($this->file_handler))
            );
        }
    }

    /**
     * Get file location
     * @return string location file
     */
    public function get_file_path()
    {
        return $this->file_path;
    }

    /**
     * Get the tmp directory when the files are saved
     * @return string
     */
    public function get_tmp_directory()
    {
        return $this->tmp_directory;
    }

    /**
     * Overrides the default tmp directory on file library
     * @param string $tmp_directory
     */
    public function set_tmp_directory($tmp_directory)
    {
        if (!empty($tmp_directory) && !file_exists($tmp_directory)) {
            Error::set(Error::ERROR_DIR_NO_READABLE, [$tmp_directory], Error::ERROR);
        }

        if (empty($tmp_directory)) {
            return $this;
        }

        $this->tmp_directory = rtrim($tmp_directory, '/');

        return $this;
    }

    /**
     * Close the file handler
     * @param handler $file_handler
     */
    private function close()
    {
        if (is_resource($this->file_handler)) {
            fclose($this->file_handler);
        }
    }

    /**
     * Delete a file on a location
     * @return boolean
     */
    private function delete()
    {
        if (!is_readable($this->file_path)) {
            return false;
        }

        return (new Path())->delete_file($this->file_path);
    }

    /**
     * Open file handler
     * @return boolean true on successfull operation
     */
    private function open_handler()
    {
        $this->file_handler = fopen($this->file_path, $this->file_open_mode);

        return is_resource($this->file_handler);
    }

    /**
     * Get the absolute path related to a file
     * @param  string $file_path
     * @return string file absolute path
     */
    private function get_real_path($file_path)
    {
        return !empty(realpath($file_path))
            ? realpath($file_path)
            : $file_path;
    }
}
