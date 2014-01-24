<?php

include "../include/config.php";
include "../include/ps_pagination.php";
if($_POST['id'])
{
$id=$_POST['id'];
echo $sql=mysql_query("select location from route_master  where route_desc='$id'");
while($row=mysql_fetch_array($sql))
{
	$data=$row['location'];
	echo '<option value="'.$data.'">'.$data.'</option>';

}
}
?>