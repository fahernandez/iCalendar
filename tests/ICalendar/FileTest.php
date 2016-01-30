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

class FileTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->root = vfsstream::setup('root');
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
     * @return void
     */
    public function test_save()
    {
        $file = new File();
        $file->delete_file_on_destroy();
        $file_path = $file->save(
            'Hello world',
            '/test.txt',
            vfsstream::url('root')
        );

        $this->assertEquals($file_path, 'vfs://root/test.txt');
    }

}