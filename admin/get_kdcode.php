<?php
include "../include/config.php";
$sql="select * from  kd  where KD_Name='". mysql_real_escape_string($_GET["val"])."'";
$results=mysql_query($sql);
$cnt=mysql_num_rows($results);
while($rs = mysql_fetch_array($results)) {
//echo $rs['Status_kd_product'].'|';
echo $rs['KD_Name'].'^'.$rs['KD_Code'].'^'.$rs['Address_Line_1'].'^'.$rs['Address_Line_2'].'^'.$rs['Address_Line_3'].'^'.$rs['City'].'^'.$rs['Pin'].'^'.$rs['Contact_Person'].'^'.$rs['Contact_Number'].'^'.$rs['Email_ID'].'^'.$rs['kd_category'].'|';}

?>
		   
          