<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
if(isset($DSR_Code) && $DSR_Code !='') {

	$curdate			=	date('Y-m-d');
	$nextrecval			=	"WHERE (flag_status = 'no' OR flag_status = 'No' OR flag_status = 'NO' OR flag_status = 'yes' OR flag_status = 'Yes' OR flag_status = 'YES') AND dsr_code = '$DSR_Code' AND Date = '$curdate'";

} else {
	$nextrecval			=	"";
}
$where					=	"$nextrecval";

if(isset($nextrecval) && $nextrecval !='')
{
	$qry_checkcyc		=	"SELECT device_name,route_name,vehicle FROM `cycle_assignment` $where";
}
else
{ 
	echo "Invalid Access";
	exit;
}
$res_checkcyc			=	mysql_query($qry_checkcyc) or die(mysql_error());
$nor_checkcyc			=	mysql_num_rows($res_checkcyc);

if($nor_checkcyc > 0){

$row_checkcyc			=	mysql_fetch_array($res_checkcyc);
$device_name			=	$row_checkcyc['device_name'];
$route_name				=	$row_checkcyc['route_name'];
$vehicle				=	$row_checkcyc['vehicle'];

$qry_devcode			=	"SELECT device_code FROM `device_master` WHERE device_description = '$device_name'";
$res_devcode			=	mysql_query($qry_devcode) or die(mysql_error());	
$row_devcode			=	mysql_fetch_array($res_devcode);
$devcode				=	$row_devcode['device_code'];

$qry_vehcode			=	"SELECT vehicle_code FROM `vehicle_master` WHERE vehicle_desc = '$vehicle'";
$res_vehcode			=	mysql_query($qry_vehcode) or die(mysql_error());	
$row_vehcode			=	mysql_fetch_array($res_vehcode);
$vehcode				=	$row_vehcode['vehicle_code'];

$qry_rotcode			=	"SELECT route_code FROM `route_master` WHERE route_desc = '$route_name'";
$res_rotcode			=	mysql_query($qry_rotcode) or die(mysql_error());	
$row_rotcode			=	mysql_fetch_array($res_rotcode);
$rotcode				=	$row_rotcode['route_code'];

echo $device_name."~".$vehicle."~".$route_name."~".$devcode."~".$vehcode."~".$rotcode;
} else {
	echo "ASSNO";
}
exit(0);?>