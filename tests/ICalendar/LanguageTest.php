<?php
/**
 * ICalendar Language library test
 *
 * Test icalendar language functionality
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

use \ICalendar\Util\Language;

class LanguageTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test language exist functionality
     * @covers ICalendar\Util\Language::exists
     * @return void
     */
    public function test_exists()
    {
        $this->assertTrue(Language::exists(Language::SPANISH));
    }
}