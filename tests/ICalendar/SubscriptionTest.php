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
use ICalendar\TimeZone;
use \ICalendar\File\Template\Build;
use \ICalendar\Util\Language;
use \org\bovigo\vfs\vfsStream;
use \org\bovigo\vfs\vfsStreamWrapper;

class SubscriptionTest extends PHPUnit_Framework_TestCase
{

    private $awss3;
    private $time_zone;


    /**
     * Create a instance of aws s3 file handling
     */
    public function setup()
    {
        $this->root = vfsstream::setup('root');

        $this->awss3 = $this->getMockBuilder('ICalendar\File\Location\Handler\AwsS3')
            ->disableOriginalConstructor()
            ->getMock();

        $this->awss3->method('save')
             ->willReturn('https://s3-us-west-2.amazonaws.com/calendar.dev.subscription/56aaa297a8019.ics');

        $this->awss3->method('delete')
             ->willReturn(true);

        $this->awss3->method('load')
             ->willReturn('vfs://root/56aaa297a8019.ics');

        $this->time_zone = (new TimeZone())
            ->set_tzid('America/Costa_Rica')
            ->set_standard_dtstart(new DateTime('2000-01-01'))
            ->set_offset_from('-0500')
            ->set_offset_to('-0600')
            ->set_standard_tzname('DST')
            ->set_daylight_dtstart(new DateTime('2000-01-01'))
            ->set_daylight_tzname('DLT');
    }

    /**
     * Testing create a instance of calendar subscription
     * @covers ICalendar\Subscription::__construct
     * @return void
     */
    public function test_create_instance_aws()
    {
        $subscription = new Subscription($this->awss3);
        $this->assertEquals(get_class($subscription), 'ICalendar\Subscription');
    }

    /**
     * Test buid a subscription
     * @depends test_create_instance_aws
     * @covers ICalendar\Subscription::build
     * @covers ICalendar\Subscription::set_prodid
     * @covers ICalendar\Subscription::set_language
     * @covers ICalendar\Subscription::set_cal_name
     * @covers ICalendar\Subscription::set_cal_desc
     * @covers ICalendar\Subscription::set_relcaid
     * @covers ICalendar\Subscription::set_time_zone
     * @covers ICalendar\Subscription::set_x_dtstamp
     * @covers ICalendar\Subscription::validate_attributes
     * @return void
     */
    public function test_build_aws()
    {
        $subscription = new Subscription($this->awss3);

        $generated_content = $subscription
            ->set_language(Language::SPANISH)
            ->set_prodid('@hulihealth.com')
            ->set_cal_name('Calendario Huli Practice')
            ->set_cal_desc('Lorem itsum Lorem itsum')
            ->set_relcaid('1232131231321')
            ->set_time_zone($this->time_zone)
            ->set_x_dtstamp(new DateTime('2000-01-01'))
            ->build();

        $content_test = "BEGIN:VCALENDAR" . Build::FIELD_DELIMITER .
            "VERSION:2.0" . Build::FIELD_DELIMITER .
            "PRODID:-//@hulihealth.com//NONSGML v1.0//ES" . Build::FIELD_DELIMITER .
            "CALSCALE:GREGORIAN" . Build::FIELD_DELIMITER .
            "METHOD:PUBLISH" . Build::FIELD_DELIMITER .
            "X-WR-CALNAME;LANGUAGE=ES:Calendario Huli Practice" . Build::FIELD_DELIMITER .
            "X-WR-CALDESC;LANGUAGE=ES:Lorem itsum Lorem itsum" . Build::FIELD_DELIMITER .
            "X-WR-RELCALID;LANGUAGE=ES:1232131231321" . Build::FIELD_DELIMITER .
            "X-WR-TIMEZONE;LANGUAGE=ES:America/Costa_Rica" . Build::FIELD_DELIMITER .
            "X-DTSTAMP;TYPE=DATE-TIME:20000101T000000" . Build::FIELD_DELIMITER .
            "END:VCALENDAR"  . Build::FIELD_DELIMITER;

        $this->assertEquals($generated_content, $content_test);

        return $generated_content;
    }

    /**
     * Test Create a new calendar subscription
     * @depends test_create_instance_aws
     * @covers ICalendar\Subscription::create
     * @covers ICalendar\Subscription::load
     * @covers ICalendar\Subscription::insert_time_zone
     * @covers ICalendar\Subscription::set_tmp_directory
     * @return void
     */
    public function test_create_aws()
    {
        $subscription = new Subscription($this->awss3);

        vfsstream::newFile('56aaa297a8019.ics')
            ->at(vfsStreamWrapper::getRoot());

        $subscription
            ->set_language(Language::SPANISH)
            ->set_prodid('@hulihealth.com')
            ->set_cal_name('Calendario Huli Practice')
            ->set_cal_desc('Lorem itsum Lorem itsum')
            ->set_relcaid('1232131231321')
            ->set_tmp_directory(vfsstream::url('root'))
            ->set_time_zone($this->time_zone)
            ->create();

        $this->assertEquals(
            $subscription->public_location,
            "https://s3-us-west-2.amazonaws.com/calendar.dev.subscription/56aaa297a8019.ics"
        );
    }


}