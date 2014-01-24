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
$KD_Code		=	getKDCode();

if($copyval == 1) {
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
	$row			=	mysql_fetch_array($results);

	$route_mon		=	$row[route_mon];
	$route_tue		=	$row[route_tue];
	$route_wed		=	$row[route_wed];
	$route_thu		=	$row[route_thu];
	$route_fri		=	$row[route_fri];
	$route_sat		=	$row[route_sat];
	
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

	$query_monthplan	=	"INSERT INTO `routemonthplan` SET $insertvalues,DSR_Code = '$DSR_Code', KD_Code = '$KD_Code',copiedfrom='master',route_mon='$route_mon',route_tue='$route_tue',route_wed='$route_wed',route_thu='$route_thu',route_fri='$route_fri',route_sat='$route_sat',routemonth='$routemonth',routeyear='$routeyear',insertdatetime=NOW()";
	//exit;
	$res_monthplan	=	mysql_query($query_monthplan) or die(mysql_error());
	if($res_monthplan) {
		echo mysql_insert_id();
	}
} else {
	$routeyear		=	date('Y');
	$routemonth		=	ltrim(date('m'),0);
	$copiedval		=	$copyval."-".$routeyear;
	$query_copy		=	"SELECT * from routemonthplan WHERE routemonth = '$copyval' AND routeyear = '$routeyear' AND DSR_Code = '$DSR_Code'";
	$res_copy		=	mysql_query($query_copy) or die(mysql_error());
	$rowcnt_copy		=	mysql_num_rows($res_copy);
	if($rowcnt_copy > 0) {
		$query_tobecopied	=	"INSERT INTO routemonthplan (day1,day2,day3,day4,day5,day6,day7,day8,day9,day10,day11,day12,day13,day14,day15,day16,day17,day18,day19,day20,day21,day22,day23,day24,day25,day26,day27,day28,day29,day30,day31,route_mon,route_tue,route_wed,route_thu,route_fri,route_sat,copiedfrom,KD_Code,DSR_Code,routemonth,routeyear,insertdatetime) SELECT day1,day2,day3,day4,day5,day6,day7,day8,day9,day10,day11,day12,day13,day14,day15,day16,day17,day18,day19,day20,day21,day22,day23,day24,day25,day26,day27,day28,day29,day30,day31,route_mon,route_tue,route_wed,route_thu,route_fri,route_sat,'$copiedval','$KD_Code','$DSR_Code','$routemonth','$routeyear',NOW() FROM routemonthplan WHERE routemonth = '$copyval' AND routeyear = '$routeyear' AND DSR_Code = '$DSR_Code'";
		//echo $query_tobecopied;
		//exit;
		$res_tobecopied		=	mysql_query($query_tobecopied) or die(mysql_error());

		if($res_tobecopied) {
			echo mysql_insert_id();
		}
	}
}
exit(0); ?>