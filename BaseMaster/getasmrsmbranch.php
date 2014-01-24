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
	$qry="SELECT * FROM `dsr` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_ASM					=	mysql_query($qry);
$num_ASM						=	mysql_num_rows($results_ASM);

if($num_ASM > 0 ) {
	$row_ASM					=	mysql_fetch_array($results_ASM);
	$ASM						=	$row_ASM[ASM];
}

$qry_RSM						=	"SELECT * FROM `asm_sp` WHERE id = '$ASM'";
$results_RSM					=	mysql_query($qry_RSM);
$num_RSM						=	mysql_num_rows($results_RSM);

if($num_RSM > 0) {
	$row_RSM					=	mysql_fetch_array($results_RSM);
	$RSM						=	$row_RSM[RSM];
	$ASMName					=	ucwords(strtolower($row_RSM[DSRName]));

	$qry_RSMName				=	"SELECT * FROM `rsm_sp` WHERE id = '$RSM'";
	$res_RSMName				=	mysql_query($qry_RSMName);
	$num_RSMName				=	mysql_num_rows($res_RSMName);

	if($num_RSMName > 0) {
		$row_RSMName			=	mysql_fetch_array($res_RSMName);
		$RSMName				=	ucwords(strtolower($row_RSMName[DSRName]));
		$branch_id				=	$row_RSMName[branch_id];

		$qry_branchName				=	"SELECT * FROM `branch` WHERE id = '$branch_id'";
		$res_branchName				=	mysql_query($qry_branchName);
		$num_branchName				=	mysql_num_rows($res_branchName);
		if($num_branchName > 0) {
			$row_branchName			=	mysql_fetch_array($res_branchName);
			$branchName				=	ucwords(strtolower($row_branchName[branch]));
		}
	}
}

echo $ASM."~".$ASMName."~".$RSM."~".$RSMName."~".$branchName;
exit(0);?>