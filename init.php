<?php
/**
 * ICalendar library init file
 *
 * Load the autoloader and instantiate ICalendar
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

// Find and initialize Composer
$files = array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/vendor/autoload.php',
);

foreach ($files as $file) {
    if (file_exists($file)) {
        require_once $file;
        break;
    }
}

use ICalendar\Subscription;
use ICalendar\TimeZone;
use ICalendar\File\Location\Handler\AwsS3;
use \ICalendar\Util\Language;

if (!class_exists('Composer\Autoload\ClassLoader', false)) {
    die(
        'You need to set up the project dependencies using the following commands:' . PHP_EOL .
        'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
}

$time_zone = (new TimeZone())
    ->set_tzid('America/Costa_Rica')
    ->set_standard_dtstart(new DateTime())
    ->set_offset_from('-0600')
    ->set_offset_to('-0600')
    ->set_standard_tzname('DST')
    ->set_daylight_dtstart(new DateTime())
    ->set_daylight_tzname('DLT');

$aws = new AwsS3(array(
        // Bootstrap the configuration file with AWS specific features
        'includes' => array('_aws'),
        'credentials' => array(
            'key'    => 'AKIAICNATEG4LN2GIHUQ',
            'secret' => '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'
        ),
        'region' => 'us-west-2'
    ), 'calendar.dev.subscription');

$subscription = (new Subscription($aws))
    ->set_language(Language::SPANISH)
    ->set_prodid('@hulihealth.com')
    ->set_cal_name('Calendario Huli Practice')
    ->set_cal_desc('Lorem itsum Lorem itsum Lorem itsum Lorem itsum Lorem itsum Lorem itsum Lorem itsum Lorem itsum Lorem itsum Lorem itsum Lorem itsum Lorem itsum Lorem itsum')
    ->set_relcaid(uniqid())
    ->set_time_zone($time_zone)
    ->create();
