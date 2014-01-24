<?php
//ini_set("display_errors",true);
//error_reporting(E_ALL);

require_once "../include/config.php";
require_once "../include/ajax_pagination.php";

require_once('lib/nusoap.php');

//$server = new soap_server;

$domain_name		=	"http://sfa.fmclgrp.com/RetailKd/Base/d2r/webservice/loadconfirm.php";

$server				=	new nusoap_client($domain_name);

$error				=	$server->getError();

if($error) {
	echo $error;
}
$result		=	$server->call('loadconfirm', array('deviceCode'=>'DEV001','VehicleCode'=>'VEH001','SeqNo'=>'2','status'=>'1'));

pre($result);
?>