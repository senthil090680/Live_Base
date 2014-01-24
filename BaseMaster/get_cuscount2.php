<?php
include "../include/config.php";
$sql="select * from  customer  where route='". mysql_real_escape_string($_GET["val"])."'";
$results=mysql_query($sql);
$rs = mysql_fetch_array($results);
$dsr=$rs['DSRName'];
$noofrows	= mysql_num_rows($results);
echo  $dsr.'^'.$noofrows.'|';

?>
		   
          