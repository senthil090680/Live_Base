<?php
//ini_set("display_errors",true);
//error_reporting(E_ALL);

require_once "../include/config.php";
require_once "../include/ajax_pagination.php";

require_once('lib/nusoap.php');
//$server = new nusoap_client;

$server = new soap_server;

$domain_name			=	"http://".$_SERVER[HTTP_HOST].$_SERVER[PHP_SELF];

$urn_name			=	"urn://".$_SERVER[HTTP_HOST].$_SERVER[PHP_SELF];

$server->configureWSDL('loaddatetime', $domain_name);

$server->register('loaddatetime',        
    array('dateId' => 'xsd:string'),     
    array('return' => 'xsd:string'),      
    $domain_name,
	$urn_name,
    'rpc',                               
    'encoded',                          
    'Checking for Date and Time'      
);

function loaddatetime($dateId) 	
{
	$dateIdVal	=	date('dmYHis');
	return $dateIdVal;
}
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>