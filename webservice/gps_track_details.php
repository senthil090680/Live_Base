<?php
include "../include/config.php";
include "../include/ajax_pagination.php";
$tables[1]		=	"gps_track_details";

$KD_Code = getKDCode();

$file = @fopen("DeviceCode_GPS.txt", "r");

$datavalue		=	file("DeviceCode_GPS.txt");
$linecount		=	count($datavalue);
$linecountval	=	1;

//pre($datavalue);

//pre($file);
//exit;
if ($file) {
    while (($line = fgets($file, 4096)) !== false) {

		$line			=	str_replace("^", "','", $line);

		$dateval		=	date('Y-m-d H:i:s');
		$query = "insert into " . $tables[1] . " values ('','" . $line.$dateval."')";
		//exit;
		$result = mysql_query($query);

		if ($result == true)
			echo $index . "  success";
		else
			echo $index . "  fail";

		echo "<br>";
		$linecountval++;
	}
    fclose($file);
}
?>