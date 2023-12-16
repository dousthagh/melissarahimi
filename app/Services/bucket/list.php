<?php
require('client.php');

$listResponse = $client->listBuckets();
$buckets = $listResponse['Buckets'];
foreach ($buckets as $bucket) {
  echo $bucket['Name'] . "\t" . $bucket['CreationDate'] . "\n";
}



