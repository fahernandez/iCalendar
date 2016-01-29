<?php
/**
 * ICalendar subscription file test
 *
 * Test subscription functionality
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

use ICalendar\Subscription;

class SubscriptionTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test creating a instance of aws s3 file handling
     */
    public function test_create_instance()
    {
        $awss3 = $this->getMockBuilder('ICalendar\File\Location\Handler\AwsS3')
            ->disableOriginalConstructor()
            ->getMock();

        $awss3->method('save')
             ->willReturn('https://s3-us-west-2.amazonaws.com/calendar.dev.subscription/56aaa297a8019.ics');

        $awss3->method('delete')
             ->willReturn(true);

        $this->subscription = new Subscription($awss3);

        $this->assertEquals(get_class($this->subscription), 'ICalendar\Subscription');
    }



}