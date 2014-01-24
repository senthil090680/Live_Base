<?php
mysql_connect("localhost","sfa","SfaPwfBrA5w");
$query= "SHOW TABLES FROM sfa_vve";
$result= mysql_query($query);
//echo $query;
$return = "";
	while($data = mysql_fetch_array($result))
	{
				$return .= $data[0] . "*"	;
				//echo "wer";
	}
	
	$return = rtrim($return, "*");
	echo $return;

?>