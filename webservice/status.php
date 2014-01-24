<?php 
include '../include/config.php';
	$query= "select * from ping_table";
	$result = mysql_query($query);
	$value="";	
	while ($data = mysql_fetch_array($result))
	{
		if($data['STATUS'] == "ONLINE")
			$color="lightgreen";
		else 
			$color = "lightcoral";		
		$value= $value . $data['DEVICE_CODE'] . ":" . $color . ",";
	}
	$value=rtrim($value, ",");
	echo $value;
?>