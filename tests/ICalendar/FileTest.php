<?php
/**
 * ICalendar file library test
 *
 * Test file functionality
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

use \ICalendar\Util\File;
use \org\bovigo\vfs\vfsStream;
use \org\bovigo\vfs\vfsStreamWrapper;
use \ICalendar\File\Template\Build;

class FileTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->root = vfsstream::setup('root');

        $this->content_test = "BEGIN:VCALENDAR" . Build::FIELD_DELIMITER .
            "VERSION:2.0" . Build::FIELD_DELIMITER .
            "PRODID:-//@hulihealth.com//NONSGML v1.0//ES" . Build::FIELD_DELIMITER .
            "CALSCALE:GREGORIAN" . Build::FIELD_DELIMITER .
            "METHOD:PUBLISH" . Build::FIELD_DELIMITER .
            "X-WR-CALNAME;LANGUAGE=ES:Calendario Huli Practice" . Build::FIELD_DELIMITER .
            "X-WR-CALDESC;LANGUAGE=ES:Lorem itsum Lorem itsum" . Build::FIELD_DELIMITER .
            "X-WR-RELCALID;LANGUAGE=ES:1232131231321" . Build::FIELD_DELIMITER .
            "X-WR-TIMEZONE;LANGUAGE=ES:America/Costa_Rica" . Build::FIELD_DELIMITER .
            "X-DTSTAMP;TYPE=DATE-TIME:20000101T000000" . Build::FIELD_DELIMITER .
            "X-END=TRUE" . Build::FIELD_DELIMITER .
            "END:VCALENDAR";
    }

    /**
     * Testing opening a file
     * @covers ICalendar\Util\File::__construct
     * @covers ICalendar\Util\File::__destruct
     * @covers ICalendar\Util\File::open
     * @covers ICalendar\Util\File::close
     * @covers ICalendar\Util\File::open_handler
     * @covers ICalendar\Util\File::get_all_content
     * @covers ICalendar\Util\File::get_real_path
     * @return void
     */
    public function test_open()
    {
        $file = new File();
        vfsstream::newFile('example.txt')
            ->withContent('Hello world')
            ->at(vfsStreamWrapper::getRoot());
        $this->assertFileExists(vfsstream::url('root/example.txt'));
        $file->open(vfsstream::url('root/example.txt'));
        $content = $file->get_all_content();
        $this->assertEquals($content, 'Hello world');
    }

    /**
     * Test saving a new document
     * @covers ICalendar\Util\File::__construct
     * @covers ICalendar\Util\File::__destruct
     * @covers ICalendar\Util\File::save
     * @covers ICalendar\Util\File::delete_file_on_destroy
     * @covers ICalendar\Util\File::close
     * @covers ICalendar\Util\File::delete
     * @covers ICalendar\Util\File::open_handler
     * @covers ICalendar\Util\File::get_real_path
     * @covers ICalendar\Util\File::set_tmp_directory
     * @covers ICalendar\Util\File::get_file_path
     * @covers ICalendar\Util\File::get_tmp_directory
     * @return void
     */
    public function test_save()
    {
        $file = new File();
        $file->delete_file_on_destroy()
            ->set_tmp_directory(vfsstream::url('root'));

        $file_path = $file->save(
            'Hello world',
            'test'
        );

        $this->assertEquals(
            $file->get_file_path(),
            $file->get_tmp_directory() . '/test.ics'
        );
    }

    /**
     * Test getting a bloke of content from a file
     * @covers ICalendar\Util\File::get_block_content
     * @return void
     */
    public function test_block_content()
    {
        vfsstream::newFile('56aaa297a8019.ics')
            ->withContent($this->content_test)
            ->at(vfsStreamWrapper::getRoot());

        $file = new File();
        $file->delete_file_on_destroy()
            ->set_tmp_directory(vfsstream::url('root'));

        $file->open('vfs://root/56aaa297a8019.ics');

        $this->assertTrue($file->get_block_content('BEGIN:VCALENDAR', 'END:VCALENDAR') == $this->content_test);
    }

}