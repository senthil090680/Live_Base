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
$params=$kdcode;
if(isset($_GET[srcode]) && $_GET[srcode] !='') {
	$codevalstr		=	implode("','",$srcode);
	if($codevalstr != '') {
		$nextrecval		=	"WHERE DSR_Code IN ('".$codevalstr."') AND frommonth = '$frommonth' AND fromyear = '$fromyear' AND tomonth = '$tomonth' AND toyear = '$toyear'";		
	}
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `sr_incentive` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr			=	mysql_query($qry) or die(mysql_error());
$num_rows				=	mysql_num_rows($results_dsr);
?>




<?php exit(0);?>