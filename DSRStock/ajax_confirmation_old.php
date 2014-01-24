<?php
include "../include/config.php";
include "../include/ps_pagination.php";
if($_POST['id'])
{
$id=$_POST['id'];
$sql=mysql_query("select DSR_code from dsr  where DSRName='$id'");
$row=mysql_fetch_array($sql);

echo $data=$row['DSR_code'];


}
?>