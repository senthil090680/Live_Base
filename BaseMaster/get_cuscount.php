<?php
include "../include/config.php";
$sql="select * from  customer  where route='". mysql_real_escape_string($_GET["val"])."'";
$results=mysql_query($sql);
$rs = mysql_fetch_array($results);
$dsr=$rs['DSRName'];
$route=$rs['route'];
$rou = "select * from route_master where route_code = '$route'";
$result=mysql_query($rou);
$row = mysql_fetch_array($result);
$routename=$row['route_desc'];

$noofrows	= mysql_num_rows($results);
echo  $routename.'^'.$dsr.'^'.$noofrows.'|';

?>
		   
          