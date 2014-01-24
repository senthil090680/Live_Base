<?php
include "../include/config.php";
$sql="select * from  device_master  where device_description='". mysql_real_escape_string($_GET["val"])."'";
$results=mysql_query($sql);
$cnt=mysql_num_rows($results);
while($rs = mysql_fetch_array($results)) {
echo $rs['device_code'].'|';
//echo $rs['kd_category'].'^'.$rs['KD_Code'].'|';
}

?>
		   
          