<?php
require_once('/lib/nusoap.php');
$server = new soap_server;
include '../include/config.php';
$server->configureWSDL('syncService', 'http://182.72.36.182/base/webservice/syncservice.php');

$server->register('sync',        
    array('deviceCode' => 'xsd:string'),     
    array('return' => 'xsd:string'),      
    'http://182.72.36.182/base/webservice/syncservice.php',                   
    'urn://182.72.36.182/base/webservice/syncservice.php',              
    'rpc',                               
    'encoded',                          
    'Connectivity for device and base'      
);


function sync($deviceCode) {
	$query = "select * from device_master where device_code='" . $deviceCode . "'";
	$result= mysql_query($query);
	$count=mysql_num_rows($result);
	if($count > 0) {
		$statusCode=1;
		$message="status=".$statusCode;			
		$query = "Select * from ping_table where DEVICE_CODE='" .$deviceCode . "'";
		$result = mysql_query($query);				
			while($data = mysql_fetch_array($result))
			{
				$transferType=$data['ACTION'];	
				$startDate = $data['START_DATE'];
				$endDate = $data['END_DATE'];
				$status=$data['STATUS'];
			}		
		if($status == "OFFLINE")
		{
			$query ="update ping_table set STATUS='ONLINE' where DEVICE_CODE='" .$deviceCode . "'";
			mysql_query($query);		
			$query = "insert into ping_table_log values ('','" .$deviceCode . "','ONLINE',now(),'admin',now(),'admin')";
			mysql_query($query);
		}
		$message .= "&";
		$message .= "TransferType=" . $transferType;
		if($transferType=="R")
		{
			$message .= "&";
			$message .= "startdate=" .$startDate;
			$message .= "&";
			$message .= "enddate=" .$endDate;
		}
		return $message;		
	}
	else {
		$statusCode=-1;
		return "status=".$statusCode;
	}
}


$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

   ?>