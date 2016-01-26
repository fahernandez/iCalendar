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
use ICalendar\File\Handler\Aws;
use \ICalendar\Util\Language;

if (!class_exists('Composer\Autoload\ClassLoader', false)) {
    die(
        'You need to set up the project dependencies using the following commands:' . PHP_EOL .
        'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
}


$subscription = (new Subscription(new Aws()))
    ->set_language(Language::SPANISH)
    ->set_prodid('@hulihealth.com')
    ->set_cal_name('Calendario Huli Practice')
    ->set_cal_desc('Calendario Dr Julio Health')
    ->set_relcaid(uniqid())
    ->build();
