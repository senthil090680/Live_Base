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
if(isset($_GET[prodcode]) && $_GET[prodcode] !='') {
	$nextrecval		=	"WHERE (id = '$prodcode')";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `product` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$res_Brand							=	mysql_query($qry);
$num_Brand							=	mysql_num_rows($res_Brand);

if($num_Brand > 0 ) {
	$row_Brand						=	mysql_fetch_array($res_Brand);
	$Brand							=	$row_Brand[brand];
}

$qry_BrandName						=	"SELECT * FROM `brand` WHERE id = '$Brand'";
$res_BrandName						=	mysql_query($qry_BrandName);
$num_BrandName						=	mysql_num_rows($res_BrandName);

if($num_BrandName > 0) {
	$row_BrandName					=	mysql_fetch_array($res_BrandName);
	$BrandName						=	ucwords(strtolower($row_BrandName[brand]));
}

echo $Brand."~".$BrandName;
exit(0);?>