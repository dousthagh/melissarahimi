<?php

namespace App\Services\bucket;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class BucketService
{
    public function execute()
    {
        $listResponse = $this->getClient()->listBuckets();
        $buckets = $listResponse['Buckets'];
        foreach ($buckets as $bucket) {
            echo $bucket['Name'] . "\t" . $bucket['CreationDate'] . "\n";
        }
    }

    function createBucket($bucketName)
    {
        try {
            $result = $this->getClient()->createBucket([
                'ACL' => 'private|public-read',
                'Bucket' => $bucketName,
            ]);
            return 'The bucket\'s location is: ' .
                $result['Location'] . '. ' .
                'The bucket\'s effective URI is: ' .
                $result['@metadata']['effectiveUri'];
        } catch (AwsException $e) {
            dd($e);
            return 'Error: ' . $e->getAwsErrorMessage();
        }
    }

    function getFile($key)
    {
        $result = $this->getClient()->getObject([
            'Bucket' => $this->getBucketName(),
            'Key' => $key
        ]);
        $type = $this->getMimeType($key);
        // $headers = [
        //     'Content-Type' => "image/*", // Set the appropriate image type
        //     'Content-Disposition' => 'inline; filename="' . $key . '"', // Display in the browser
        // ];
        //  return Response::make($result['Body']->getContents(), 200, $headers);

        $file = $result['Body'];
        $size = $result['ContentLength'];
        $response = response($file, 200);
        $response->header("Content-Type", $type);
        $response->header("Content-Disposition", "inline; filename=$type");
        $response->header("Content-Length", $size);
        return $response;
    }

    private function getMimeType($key)
    {
        $array = explode('.', $key);
        $type = $array[count($array) - 1];
        
        switch (strtolower($type)) {
            case "png":
            case "jpg":
            case "jpeg":
                return "image/png";
            case "mp4":
                return "video/mp4";
            default:
                return "video/mov";
        }
    }



    function uploadPartOfFile($file, $key, $tmp_name = null)
    {
        ini_set('max_execution_time', -1);
        if ($tmp_name  != null)
            $source = $tmp_name;
        else
            $source = $file['tmp_name'];
        $client = $this->getClient();
        $mimType = mime_content_type($source);
        try {
            $uploader = $client->createMultipartUpload([
                'Bucket' => $this->getBucketName(),
                'Key' => $key,
            ]);
            $uploadId = $uploader['UploadId'];

            $partNumber = 1;
            $partSize = 5 * 1024 * 1024; // Set your desired part size (5MB in this example)

            $fileHandle = fopen($source, 'r');
            while (!feof($fileHandle)) {
                $part = fread($fileHandle, $partSize);
                $uploadPartResult = $client->uploadPart([
                    'Bucket' => $this->getBucketName(),
                    'Key' => $key,
                    'UploadId' => $uploadId,
                    'PartNumber' => $partNumber,
                    'Body' => $part,
                    'ContentType' => mime_content_type($source)
                ]);
                $parts[] = [
                    'ETag' => $uploadPartResult['ETag'],
                    'PartNumber' => $partNumber,
                ];
                $partNumber++;
            }
            fclose($fileHandle);

            // Complete the multipart upload
            $completeResult = $client->completeMultipartUpload([
                'Bucket' => $this->getBucketName(),
                'Key' => $key,
                'UploadId' => $uploadId,
                'MultipartUpload' => ['Parts' => $parts],
            ]);
            return true;
        } catch (AwsException $e) {
            return false;
        }
    }


    function getParts($bucket, $key)
    {
        try {
            $result = $this->getClient()->listParts([
                'Bucket' => $bucket, // REQUIRED
                'ExpectedBucketOwner' => '<string>',
                'Key' => $key, // REQUIRED
                'MaxParts' => 0,
                'PartNumberMarker' => 0,
                'RequestPayer' => 'requester',
                'UploadId' => '<string>', // REQUIRED
            ]);
            var_dump($result);
        } catch (AwsException $e) {
            // Display error message
            echo $e->getMessage();
            echo "\n";
        }
    }


    public function Delete($key)
    {
        $object = $this->getClient()->deleteObject([
            'Bucket' => $this->getBucketName(),
            'Key' => $key
        ]);
    }

    private function getClient()
    {
        $ENDPOINT = 'https://s3.ir-thr-at1.arvanstorage.com';

        return new S3Client([
            'region' => env("AWS_DEFAULT_REGION"),
            'version' => '2006-03-01',
            'endpoint' => $ENDPOINT,
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env("AWS_SECRET_ACCESS_KEY")
            ],
            'use_path_style_endpoint' => env("AWS_USE_PATH_STYLE_ENDPOINT")
        ]);
    }
    private function getBucketName()
    {
        return env("AWS_BUCKET");
    }
}
