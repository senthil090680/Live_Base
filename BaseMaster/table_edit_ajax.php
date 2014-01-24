<?php
ob_start();
include('../include/header.php');
if($_POST['id'])
{
$id=mysql_escape_String($_POST['id']);
$sequence_number=mysql_escape_String($_POST['sequence_number']);
$sql = "update customer set sequence_number='$sequence_number' where id='$id'";
mysql_query($sql);
}
?>