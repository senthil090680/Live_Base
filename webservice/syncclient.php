<?php

require_once('/lib/nusoap.php');
$client = new nusoap_client('http://localhost/host/functions/webservice/syncservice.php', false);

$result = $client->call('ping', array('ping' => '450'));
print_r($result);





?>