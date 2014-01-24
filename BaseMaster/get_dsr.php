<?php
include "../include/config.php";
$sql="select * from dsr where DSRName='". mysql_real_escape_string($_GET["val"])."'";
$results=mysql_query($sql);
$cnt=mysql_num_rows($results);
while($rs = mysql_fetch_array($results)) {

echo $rs['DSR_Code'].'|';
//echo  $rs['DSR_Code'].'^'.$rs['Scheme_code'].'^'.$rs['Effective_from'].'^'.$rs['Effective_to'].'|';
}

?>
		   
          