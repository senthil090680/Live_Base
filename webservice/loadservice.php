<?php

require_once('/lib/nusoap.php');
include '../include/config.php';
$server = new soap_server;

$server->configureWSDL('loadservice', 'http://182.72.36.182//Host/functions/webservice/loadservice.php');

$server->register('download', array('deviceCode' => 'xsd:string','status' => 'xsd:string'), array('return' => 'xsd:string'), 'http://172.16.42.220/Host/functions/webservice/downloadservice.php', 'urn://172.16.42.220/Host/functions/webservice/downloadservice.php', 'rpc', 'encoded', 'Download from base to device'
);

function download($deviceCode, $status) {


//return  $deviceCode. " " . $FileIndex . " " . $InfoFlag . " " .  $Message;

   
    $query = "select * from device_master where DEVICE_CODE='" . $deviceCode . "'";
    $result = mysql_query( $query);
    $count = mysql_num_rows($result);

    if ($count > 0) {
        
        
        /* DATABASE WORK HERE */
 
        return "DeviceId=" . $deviceCode . "&status=updated";
    } else {
        $errorMessage = "Invalid Device Code.Please try again";
        return "DeviceId=" . $deviceCode .  "&ErrorFlag=2&ErrorMessage=" . $errorMessage;
    }
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>