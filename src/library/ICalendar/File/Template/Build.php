<?php
/**
 * ICalendar template build library
 *
 * Build the ical template object according to the attributes passed.
 * Returs the object formatted as string
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

namespace ICalendar\File\Template;

use ICalendar\Util\File;

class Build
{

    /**
     * Subscription template
     */
    const VCALENDAR_TEMPLATE = '/VCalendar.txt';

    /**
     * File handler for the template where the vobject is stored
     */
    private $file_template;

    /**
     * Create a new Subscription instance
     */
    public function __construct($template)
    {
        $this->file_template = new File(__DIR__ . $template);
        $this->file_template->open();
    }

    /**
     * Build the template using the attributes passed
     * @param  array  $attributes
     * @return string template formatted
     */
    public function build(array $attributes)
    {
        // Get template content
        $content = $this->file_template->get_all_content();

        // Overrides parameters on the template
        $processed_content = $this->process_content($content, $attributes);

        // Format template according to the requierements of RFC 2445
        return $this->format_content($processed_content);
    }

    /**
     * Overrides parameters on the template content
     * @param  string $content
     * @param  array $attributes
     * @return string
     */
    private function process_content($content, $attributes)
    {
        foreach ($attributes as $name => $value) {
            $content = preg_replace("/\{$name\}/", $value, $content);
        }

        return $content;
    }

    /**
     * Format the template according with the rules defined on RFC 2445
     * @param  string $processed_content
     * @return string
     */
    private function format_content($processed_content)
    {

    }

}