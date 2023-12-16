<?php

namespace App\Services\bucket;

use Aws\S3\S3Client;

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

    private function getClient()
    {
        $ENDPOINT = 's3.ir-thr-at1.arvanstorage.ir';

        return new S3Client([
            'region' => '',
            'version' => '2006-03-01',
            'endpoint' => $ENDPOINT,
            'credentials' => [
                'key' => "99a3f50b-9b55-4a6e-b4a4-6bab09c2d03a",
                'secret' => "e388d0430cc52d133b2a3cbfccfd06a53509f3a04ffd25a5e56a870a83708bad"
            ],
            // Set the S3 class to use objects. arvanstorage.ir/bucket
            // instead of bucket.objects. arvanstorage.ir
            'use_path_style_endpoint' => true
        ]);
    }
}
