<?php
/**
 * ICalendar error library test
 *
 * Test error functionality
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

use \ICalendar\Util\Error;

class ErrorTest extends PHPUnit_Framework_TestCase
{

    private $errors;

    protected function setUp()
    {
        $this->errors = array();
        set_error_handler(array($this, "errorHandler"));
    }

    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $this->errors[] = compact("errno", "errstr", "errfile", "errline", "errcontext");
    }

    public function assertError($errstr, $errno)
    {

        foreach ($this->errors as $error) {
            if ($error["errstr"] === $errstr
                && $error["errno"] === $errno) {
                return;
            }
        }
        $this->fail(
            "Error with level " .
            $errno . " and message '" .
            $errstr . "' not found in ",
            var_export($this->errors, TRUE)
        );
    }

    /**
     * Test error set funcitionality
     * @covers ICalendar\Util\Error::set
     * @covers ICalendar\Util\Error::get_error_caller
     * @return [type] [description]
     */
    public function test_set_error()
    {
        Error::set('Hello %s', ['World'], Error::ERROR);
        $this->assertError("ICalendar: Hello World
        Trigger by ErrorTest function test_set_error
        File: .
        Args: Array
(
)
.", E_USER_ERROR);
    }

}