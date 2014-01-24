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
	$routemonth			=	ltrim(date('m'),0);
	$routeyear			=	date('Y');
	$curdate			=	ltrim(date('d'),0);
	$nextrecval			=	"WHERE (DSR_Code = '$DSR_Code' AND routemonth = '$routemonth' AND $routeyear = '$routeyear')";	
} else {
	$nextrecval			=	"";
}
$where					=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry				=	"SELECT * FROM `routemonthplan` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results				=	mysql_query($qry);
$num_rows				=	mysql_num_rows($results);			
$KD_Code				=	getKDCode();
if($num_rows > 0) {

	$list=array();
	$routemonth		=	ltrim(date('m'),0);
	$routeyear		=	date('Y');
	$num_of_days = date('t');
	for($d=1; $d<=$num_of_days; $d++)
	{
		$time=mktime(12, 0, 0, date('m'), $d, date('Y'));
		if (date('m', $time)==date('m'))
			$daysval[ltrim(date('d', $time),0)]		=	date('D', $time);
	}
	$insertvalues				=	'';
	$k							=	1;
	foreach($daysval as $daykey=>$dayvalue) {
		$lowerdayvalue	=	strtolower($dayvalue);		
		if($daykey >= $curdate) {
			if($lowerdayvalue == 'mon') {			
				if($insertvalues == '') {
					$insertvalues		.=	"day".$k. "= '".$route_mon."'";
				} else {
					$insertvalues		.=	",day".$k. "= '".$route_mon."'";
				}
			} elseif($lowerdayvalue == 'tue') {
				if($insertvalues == '') {
					$insertvalues		.=	"day".$k. "= '".$route_tue."'";
				} else {
					$insertvalues		.=	",day".$k. "= '".$route_tue."'";
				}
			} elseif($lowerdayvalue == 'wed') {
				if($insertvalues == '') {
					$insertvalues		.=	"day".$k. "= '".$route_wed."'";
				} else {
					$insertvalues		.=	",day".$k. "= '".$route_wed."'";
				}
			} elseif($lowerdayvalue == 'thu') {
				if($insertvalues == '') {
					$insertvalues		.=	"day".$k. "= '".$route_thu."'";
				} else {
					$insertvalues		.=	",day".$k. "= '".$route_thu."'";
				}
			} elseif($lowerdayvalue == 'fri') {
				if($insertvalues == '') {
					$insertvalues		.=	"day".$k. "= '".$route_fri."'";
				} else {
					$insertvalues		.=	",day".$k. "= '".$route_fri."'";
				}
			} elseif($lowerdayvalue == 'sat') {
				if($insertvalues == '') {
					$insertvalues		.=	"day".$k. "= '".$route_sat."'";
				} else {
					$insertvalues		.=	",day".$k. "= '".$route_sat."'";
				}
			}
		}
		$k++;
	}

	$query_masplan	=	"UPDATE `routemonthplan` SET $insertvalues,route_mon = '$route_mon', route_tue = '$route_tue', route_wed = '$route_wed', route_thu = '$route_thu', route_fri = '$route_fri', route_sat = '$route_sat',updatedatetime=NOW() WHERE DSR_Code = '$DSR_Code' AND routemonth = '$routemonth' AND $routeyear = '$routeyear'";
	$res_masplan	=	mysql_query($query_masplan) or die(mysql_error());
	if($res_masplan) {
		echo 'update';
	}
} else {

	$list=array();
	$routemonth		=	ltrim(date('m'),0);
	$routeyear		=	date('Y');
	$num_of_days = date('t');
	for($d=1; $d<=$num_of_days; $d++)
	{
		$time=mktime(12, 0, 0, date('m'), $d, date('Y'));
		if (date('m', $time)==date('m'))
			$daysval[ltrim(date('d', $time),0)]		=	date('D', $time);
	}
	$insertvalues				=	'';
	$k							=	1;
	foreach($daysval as $daykey=>$dayvalue) {
		$lowerdayvalue	=	strtolower($dayvalue);
		if($lowerdayvalue == 'mon') {
			if($insertvalues == '') {
				$insertvalues		.=	"day".$k. "= '".$route_mon."'";
			} else {
				$insertvalues		.=	",day".$k. "= '".$route_mon."'";
			}
		} elseif($lowerdayvalue == 'tue') {
			if($insertvalues == '') {
				$insertvalues		.=	"day".$k. "= '".$route_tue."'";
			} else {
				$insertvalues		.=	",day".$k. "= '".$route_tue."'";
			}
		} elseif($lowerdayvalue == 'wed') {
			if($insertvalues == '') {
				$insertvalues		.=	"day".$k. "= '".$route_wed."'";
			} else {
				$insertvalues		.=	",day".$k. "= '".$route_wed."'";
			}
		} elseif($lowerdayvalue == 'thu') {
			if($insertvalues == '') {
				$insertvalues		.=	"day".$k. "= '".$route_thu."'";
			} else {
				$insertvalues		.=	",day".$k. "= '".$route_thu."'";
			}
		} elseif($lowerdayvalue == 'fri') {
			if($insertvalues == '') {
				$insertvalues		.=	"day".$k. "= '".$route_fri."'";
			} else {
				$insertvalues		.=	",day".$k. "= '".$route_fri."'";
			}
		} elseif($lowerdayvalue == 'sat') {
			if($insertvalues == '') {
				$insertvalues		.=	"day".$k. "= '".$route_sat."'";
			} else {
				$insertvalues		.=	",day".$k. "= '".$route_sat."'";
			}
		}
		$k++;
	}

	$query_monthplan	=	"INSERT INTO `routemonthplan` SET $insertvalues,DSR_Code = '$DSR_Code', KD_Code = '$KD_Code',copiedfrom='manual',route_mon='$route_mon',route_tue='$route_tue',route_wed='$route_wed',route_thu='$route_thu',route_fri='$route_fri',route_sat='$route_sat',routemonth='$routemonth',routeyear='$routeyear',insertdatetime=NOW()";
	$res_monthplan	=	mysql_query($query_monthplan) or die(mysql_error());
	if($res_monthplan) {
		echo 'insert';
	}
}
exit(0);?>