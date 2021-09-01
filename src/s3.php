<?php

/**
 * This requires the AWS PHP SDK https://github.com/aws/aws-sdk-php.
 */

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Initilise S3 client.
try{
  $s3Client = new S3Client([
    'version'     => "version",
    'region'      => "region",
    'credentials' => [
      'key' => "credential_key",
      'secret' => "$credential_secret",
    ],
  ]);
} catch (S3Exception $e) {
    return $e->getMessage();
}

/**
 * Upload the file to S3 bucket.
 *
 * @param string $file_path The file to be moved to S3.
 */
function upload_to_s3($file_path){
  global $s3Client;
  try{
    $result = $s3Client->putObject([
      'Bucket'     => "bucket_name",
      'Key'        => basename($file_path),
      'SourceFile' => $file_path,
    ]);
  } catch (S3Exception $e) {
    throw new Exception($e->getMessage());
  }
  return TRUE;
}

