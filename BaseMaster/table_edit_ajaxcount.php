<?php
ob_start();
include('../include/header.php');
if($_POST['id'])
{
$id=mysql_escape_String($_POST['id']);
$sequence_number=mysql_escape_String($_POST['sequence_number']);
$Customer_Name=mysql_escape_String($_POST['Customer_Name']);
$sql = "update customer_count set sequence_number='$sequence_number' where Customer_Name='$Customer_Name'";
mysql_query($sql);
}
?>