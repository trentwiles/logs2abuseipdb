#!/usr/bin/php

<?php

require 'vendor/autoload.php';

exec('grep -E -o "([0-9]{1,3}[\.]){3}[0-9]{1,3}" auth.log > output.txt && cp auth.log auth.log.back && rm -rf auth.log && touch auth.log');

$handle = fopen("output.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $ip = fgets($handle);
        $client = new GuzzleHttp\Client([
  'base_uri' => 'https://api.abuseipdb.com/api/v2/'
]);

$response = $client->request('POST', 'report', [
    'query' => [
        'ip' => $ip,
        'categories' => '18,22',
        'comment' => 'SSH Bruteforce'
    ],
    'headers' => [
        'Accept' => 'application/json',
        'Key' => 'YOUR_KEY_GOES_HERE'
  ],
]);

$output = $response->getBody();
// Store response as a PHP object.
$ipDetails = json_decode($output, true);
echo "line one done...";
    }

    fclose($handle);
} else {
    echo "got an error opening the file...";
}
exec("sudo service rsyslog restart");
