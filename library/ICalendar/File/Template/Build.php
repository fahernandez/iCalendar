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
     * New line field delimiter
     */
    const FIELD_DELIMITER = "\r\n";

    /**
     * String to split lines by end of line
     */
    const SPLIT_LINES_REGEX = '/$\R?^/m';

    /**
     * File handler for the template where the vobject is stored
     */
    private $file_template;

    /**
     * Create a new Subscription instance
     */
    public function __construct($template)
    {
        $this->file_template = new File();
        $this->file_template->open(__DIR__ . '/' .$template);
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

        // Format template according to the requierements of RFC 5545
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
     * Format the template according with the rules defined on RFC 5545
     *
     * Lines of text SHOULD NOT be longer than 75 octets, excluding the line
     * break. Long content lines SHOULD be split into a multiple line
     * representations using a line "folding" technique. That is, a long
     * line can be split between any two characters by inserting a CRLF
     * immediately followed by a single linear white space character (i.e.,
     * SPACE, US-ASCII decimal 32 or HTAB, US-ASCII decimal 9). Any sequence
     * of CRLF followed immediately by a single linear white space character
     * is ignored (i.e., removed) when processing the content type.
     *
     * @param  string $processed_content
     * @return string
     */
    private function format_content($processed_content)
    {
        $separated_content = preg_split(self::SPLIT_LINES_REGEX, $processed_content);
        foreach ($separated_content as $index => $line) {
            $separated_content[$index] = $this->size_75($line);
        }

        return implode($separated_content);
    }

    /**
     * Size a line to 75 chars
     * This library was modified to fullfill the requirements of this particular
     * implementation. But the backbone of this function was build by the author
     * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
     * @since 2.16.2 - 2012-12-18
     * @param string $value
     * @return string
     */
    private function size_75($string)
    {
        $tmp = $string;
        $string = '';
        $cCnt = $x = 0;
        while (true) {
            if (!isset($tmp[$x])) {
                $string .= self::FIELD_DELIMITER;
                break;
            } elseif ((74 <= $cCnt) && ('\\' == $tmp[$x]) && ('n' == $tmp[$x+1])) {
                $string .= self::FIELD_DELIMITER . ' \n';
                $x += 2;
                if (!isset($tmp[$x])) {
                    $string .= self::FIELD_DELIMITER;
                    break;
                }
                $cCnt = 3;
            } elseif (75 <= $cCnt) {
                $string .= self::FIELD_DELIMITER . ' ';
                $cCnt = 1;
            }
            $byte = ord($tmp[$x]);
            $string .= $tmp[$x];
            switch (true) { // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                case (($byte >= 0x20) && ($byte <= 0x7F)):
                    $cCnt += 1;
                    break;
                case (($byte & 0xE0) == 0xC0):
                    if (isset($tmp[$x+1])) {
                        $cCnt += 1;
                        $string .= $tmp[$x+1];
                        $x += 1;
                    }
                    break;
                case (($byte & 0xF0) == 0xE0):
                    if (isset($tmp[$x+2])) {
                        $cCnt += 1;
                        $string .= $tmp[$x+1] . $tmp[$x+2];
                        $x += 2;
                    }
                    break;
                case (($byte & 0xF8) == 0xF0):
                    if (isset($tmp[$x+3])) {
                        $cCnt += 1;
                        $string .= $tmp[$x+1] . $tmp[$x+2] . $tmp[$x+3];
                        $x += 3;
                    }
                    break;
                case (($byte & 0xFC) == 0xF8):
                    if (isset($tmp[$x+4])) {
                        $cCnt += 1;
                        $string .= $tmp[$x+1] . $tmp[$x+2] . $tmp[$x+3] .
                            $tmp[$x+4];
                        $x += 4;
                    }
                    break;
                case (($byte & 0xFE) == 0xFC):
                    if (isset($tmp[$x+5])) {
                        $cCnt += 1;
                        $string .= $tmp[$x+1] . $tmp[$x+2] . $tmp[$x+3] .
                            $tmp[$x+4] . $tmp[$x+5];
                        $x += 5;
                    }
                    break;
                default:
                    break;
            }
            $x += 1;
        }
        return $string;
    }

}