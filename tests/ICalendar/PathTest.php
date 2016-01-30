<?php
/**
 * ICalendar path library test
 *
 * Test path functionality
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

use \ICalendar\Util\Path;
use \org\bovigo\vfs\vfsStream;
use \org\bovigo\vfs\vfsStreamWrapper;

class PathTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->root = vfsstream::setup('root');
    }

    /**
     * Test create a new path
     * @covers \ICalendar\Util\Path::create_path
     * @return string location of the created path
     */
    public function test_create_path()
    {
        vfsstream::newDirectory('test')
            ->at(vfsStreamWrapper::getRoot());
        $path = vfsstream::url('root/test');
        $created_path = $path . '/deleted';

        $this->assertTrue((new Path())->create_path($created_path));

        return  $created_path;
    }

    /**
     * Test delete a file path
     * @param  string $path
     * @depends test_create_path
     * @covers \ICalendar\Util\Path::delete_file
     * @return void
     */
    public function test_delete_file($path)
    {
        vfsstream::newFile('example.txt')
            ->withContent('Hello world')
            ->at(vfsStreamWrapper::getRoot());

        $this->assertTrue((new Path())
            ->delete_file(vfsstream::url('root/example.txt')));
    }
}