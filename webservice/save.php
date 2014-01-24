<?php
include '../include/config.php';
$deviceCode =$_POST['deviceCode'];
$action = $_POST['action'];	
	if($action == "R") {
		$sdate = $_POST['sdate'];
		$edate = $_POST['edate'];
		$query = "update ping_table set ACTION = '". $action . "', START_DATE = '" .$sdate. "' , END_DATE ='". $edate."' where DEVICE_CODE = '". $deviceCode."'";
	}
	else {	
		$query = " update ping_table set ACTION = '" . $action . "' WHERE DEVICE_CODE='" .$deviceCode . "'";
	}	
	mysql_query($query);
	echo "Process Succesfully started ";
?>