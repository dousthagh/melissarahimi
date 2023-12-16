<?php 
use Aws\S3\S3Client;

define('AWS_KEY', '99a3f50b-9b55-4a6e-b4a4-6bab09c2d03a');
define('AWS_SECRET_KEY', 'e388d0430cc52d133b2a3cbfccfd06a53509f3a04ffd25a5e56a870a83708bad');
$ENDPOINT = 's3.ir-thr-at1.arvanstorage.ir';

// require the sdk from your composer vendor dir
require __DIR__.'/vendor/autoload.php';

// Instantiate the S3 class and point it at the desired host
$client = new S3Client([
  'region' => '',
  'version' => '2006-03-01',
  'endpoint' => $ENDPOINT,
  'credentials' => [
      'key' => AWS_KEY,
      'secret' => AWS_SECRET_KEY
  ],
  // Set the S3 class to use objects. arvanstorage.ir/bucket
  // instead of bucket.objects. arvanstorage.ir
  'use_path_style_endpoint' => true
]);