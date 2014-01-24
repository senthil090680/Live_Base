<?php
include "../include/config.php";
$query1 = "select user_id,username,email,password,access from  admin where username = '". mysql_real_escape_string($_GET["val"])."' ";
$results=mysql_query($query1);
$cnt=mysql_num_rows($results);
while($rs = mysql_fetch_array($results)) {
echo $rs['username'].'^'.$rs['email'].'^'.$rs['password'].'^'.$rs['access'].'^'.$rs['user_id'].'|';
}

?>
		   
          