<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
//require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
if(isset($_GET[DSR_Code]) && $_GET[DSR_Code] !='') {
	$nextrecval		=	"WHERE (DSR_Code = '$DSR_Code')";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `routemasterplan` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results		=	mysql_query($qry);
$num_rows		=	mysql_num_rows($results);			
$KD_Code		=	getKDCode();
if($num_rows > 0) {
	$query_masplan	=	"UPDATE `routemasterplan` SET route_mon = '$route_mon', route_tue = '$route_tue', route_wed = '$route_wed', route_thu = '$route_thu', route_fri = '$route_fri', route_sat = '$route_sat',updatedatetime=NOW() WHERE DSR_Code = '$DSR_Code'";
	$res_masplan	=	mysql_query($query_masplan) or die(mysql_error());
	if($res_masplan) {
		echo 'update';
	}
} else {
	$query_masplan	=	"INSERT INTO `routemasterplan` SET DSR_Code = '$DSR_Code', KD_Code = '$KD_Code', route_mon = '$route_mon', route_tue = '$route_tue', route_wed = '$route_wed', route_thu = '$route_thu', route_fri = '$route_fri', route_sat = '$route_sat',insertdatetime=NOW()";
	$res_masplan	=	mysql_query($query_masplan) or die(mysql_error());
	if($res_masplan) {
		echo 'insert';
	}
}
exit(0);?>