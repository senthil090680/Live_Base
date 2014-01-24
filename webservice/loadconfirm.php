<?php
//ini_set("display_errors",true);
//error_reporting(E_ALL);

require_once "../include/config.php";
require_once "../include/ajax_pagination.php";

require_once('lib/nusoap.php');
//$server = new nusoap_client;

$server = new soap_server;

$domain_name			=	"http://".$_SERVER[HTTP_HOST].$_SERVER[PHP_SELF];

$urn_name				=	"urn://".$_SERVER[HTTP_HOST].$_SERVER[PHP_SELF];

$server->configureWSDL('loadconfirm', $domain_name);

$server->register('loadconfirm',        
    array('deviceCode' => 'xsd:string','VehicleCode' => 'xsd:string','SeqNo' => 'xsd:string','status' => 'xsd:string'),     
    array('return' => 'xsd:string'),      
    $domain_name,
	$urn_name,
    'rpc',                               
    'encoded',                          
    'Connectivity for device and base'      
);

function loadconfirm($deviceCode,$VehicleCode,$SeqNo,$status) 	
{
	if($deviceCode == '' || $VehicleCode == '' || $SeqNo == '' || $status == ''){
		return "Some Parameter is Missing";
	}
	if($status == 0) {
		$status = 'no';
		return "Success";
	} elseif($status == 1) {
		$status = 'yes';
		$query ="Insert into dailyloadconfirm values('','" . $deviceCode . "','" . $VehicleCode.  "','" . $SeqNo.  "','" . $status.  "',now())";
        $result = mysql_query($query) or die(mysql_error());
        if($result == true)
            return "Success";
        else
            return "Error";
	}
	
}
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>