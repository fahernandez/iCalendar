<?php
/**
 * ICalendar aws file handler implementation
 *
 * This class handle files to be saved and retrieved to an from AWS
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

namespace ICalendar\File\Location\Handler;

use ICalendar\File\Location\IHandler;
use ICalendar\Util\Error;
use \Aws\S3\S3Client;
use \Aws\S3\Exception\S3Exception;

class AwsS3 implements IHandler
{

    /**
     * S3 client instance
     * @var S3Client
     */
    private $s3_client;

    /**
     * S3 bucket where the subscription will be saved
     * @var string
     */
    private $bucket;

    /**
     * Create a new aws s3 instance
     */
    public function __construct(array $configuration, $bucket)
    {
        $this->s3_client = S3Client::factory($configuration);
        $this->bucket = $bucket;

        if (!$this->s3_client->doesBucketExist($this->bucket)) {
            Error::set(Error::ERROR_INVALID_BUCKET, [$this->bucket], Error::ERROR);
        }
    }

    /**
     * Save a file into a S3 location
     * @return string public file location
     */
    public function save($file_path, $content_type)
    {
        try {
            // Upload data.
            $result = $this->s3_client->putObject(array(
                'Bucket'       => $this->bucket,
                'Key'          => basename($file_path),
                'SourceFile'   => $file_path,
                'ContentType'  => $content_type,
                'StorageClass' => 'STANDARD',
                'ACL'          => 'public-read',
            ));
        } catch (S3Exception $error) {
            Error::set($error->getMessage(), [], Error::ERROR);
        }

        return $result->get('ObjectURL');
    }

    /**
     * Delete a file from a bucket
     * @return boolean
     */
    public function delete($public_location)
    {
        try {
            // Delete file.
            $result = $this->s3_client->deleteObject(array(
                'Bucket' => $this->bucket,
                'Key'    => basename($public_location)
            ));
        } catch (S3Exception $error) {
            Error::set($error->getMessage(), [], Error::ERROR);
        }

        return true;
    }

    /**
     * Load a file aws into a location where it can be handled
     * @param  string $public_location
     * @param  string $file_path_directory
     * @return string
     */
    public function load($public_location, $file_path_directory)
    {

    }

}