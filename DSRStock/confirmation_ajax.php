<?php

include "../include/config.php";
include "../include/ps_pagination.php";

if($_POST['id'])
{
$id=$_POST['id'];
$sql=mysql_query("select location,route_code from route_master  where route_desc='$id'");
$row=mysql_fetch_array($sql);

	 $data=$row['location'];
	$start=$row['route_code'];
$csql="select sequence_number,Customer_Name,AddressLine1,AddressLine2,lga,City,contactperson,contactnumber from customer where route='$start'";
$cquery=mysql_query($csql);
$i=0;
while($cresult=mysql_fetch_array($cquery))
{

$route['name'][$i]=$cresult;
$i++;

}
$set=array('locations'=>$data,
			'route_code'=>$start);

	$merge=array_merge($set,$route);
	echo json_encode($merge);		
}
