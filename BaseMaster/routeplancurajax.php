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
	$nextrecval		=	"WHERE (DSR_Code = '$DSR_Code' AND route = '$routeval')";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `customer` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results		=	mysql_query($qry);
$num_rows		=	mysql_num_rows($results);			


/*$daysval = array();
$num_of_days = date('t');
for($i = 1; $i <= $num_of_days; $i++) {	
    $daysval[ltrim(date("d", strtotime('+'. $i .' days')),0)] = date("D", strtotime('+'. $i .' days'));
}

$list=array();
$num_of_days = date('t');
for($d=1; $d<=$num_of_days; $d++)
{
    $time=mktime(12, 0, 0, date('m'), $d, date('Y'));
    if (date('m', $time)==date('m'))
        $daysval[ltrim(date('d', $time),0)]		=	date('D', $time);
}

$num_of_days = date('t');    
for( $i=1; $i<= $num_of_days; $i++)
 $daysval[ltrim(str_pad($i,2,'0', STR_PAD_LEFT),0)]		=	date('Y') . "-". date('m'). "-". str_pad($i,2,'0', STR_PAD_LEFT); 
*/

//debugerr($daysval);


$customers_val				=	'';
while($row_customers		=	mysql_fetch_array($results)) {
	if($customers_val == ''){
		$custval				=	ucwords(strtolower($row_customers[Customer_Name]));
		$customers_val			.=	$custval;
	} else {
		$custval				=	ucwords(strtolower($row_customers[Customer_Name]));
		$customers_val			.=	"&".$custval;
	}
} // while loop
echo $customers_val;
exit(0);?>