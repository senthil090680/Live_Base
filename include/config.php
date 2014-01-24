<?php
$mysql_hostname = "localhost";
$mysql_user = "sfa";
$mysql_password = "SfaPwfBrA5w";
$mysql_database = "sfa_d2r";
$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) 
or die("Opps some thing went wrong");
mysql_select_db($mysql_database, $bd) or die("Opps some thing went wrong");

function getKDCode() {
	$sel_kdcode="select KD_Code from kd_information";
	$res_kdcode=mysql_query($sel_kdcode) or die(mysql_error());
	if(mysql_num_rows($res_kdcode) > 0) {
		$row_kdcode=mysql_fetch_array($res_kdcode);
		$kdcode	=	$row_kdcode['KD_Code'];
	} else {
		echo "No KD Code"; exit;
	}
	return $kdcode;
}
//$timezone = "Asia/Calcutta";
$timezone = "Africa/Lagos";
if(function_exists('date_default_timezone_set')) 
date_default_timezone_set($timezone);
?>