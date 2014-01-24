<?php
set_time_limit(0);
error_reporting(0);
//$tableName = $argv[1]; // tn specifies table name 
$remIp = $_GET['ip'];
$baseIP =$_GET['baseIP'];
$tableName = $_GET['tn'];
$kdCode = $_GET['kdCode'];
$time = $_GET['time'];
$type = $_GET['type'];
$remIp = str_replace("_", "/", $remIp);
//$baseIP = str_replace("_", "/", $baseIP);
//echo $remIp . "  " . $baseIP;
//exit();
 if (!$tableName) {

    echo("Error Message : missing Table Name ");
} {
    //include 'cfg.php';
    include "../include/config.php";



    if (!$time)
        $query = "SELECT * FROM " . $tableName;
    else {
        $editedTime = str_replace("*", " ", $time);
        $time = $editedTime;
        // echo $time;
        $query = "SELECT * FROM " . $tableName . " Where  AUDIT_DATE_TIME  < '" . $time . "'";
    }
    $result = mysql_query($query);
    $data = "";
    while ($row = mysql_fetch_array($result)) {

        for ($i = 0; $i < mysql_num_fields($result); $i++) {
           
            $data .= "'$row[$i]'";
            $data .= ",";
			
        }
        $data .= "\n";
      
    }

    $path = "d2r_functions_UploadtoHost";
    $path = $path . "_" . date("Y-m-d");

    $filePath = str_replace("_", "//", $path);

    if (!is_dir("..//..//" . $filePath)) {
		
        mkdir("..//..//" . $filePath);
    }

    //chmod($filePath,0777);

    $date = new DateTime();


    writeCsv("..//..//" . $filePath . "//" . $tableName . "@" . $date->getTimestamp() . ".csv", $data);

    $data = array($baseIP, $tableName . "@" . $date->getTimestamp(), $path, $kdCode,$type);

    invoke($remIp, "Host/functions/load", $data);
}


function invoke($ip, $path, $data) {
    $url = "http://" . $ip .  $path . ".php?remip=" . $data[0] . "&table=" . $data[1] . "&path=" . $data[2] . "&kdCode=" . $data[3]. "&type=" . $data[4];
	
	//echo $url;exit; 
    $cu = curl_init();
    curl_setopt($cu, CURLOPT_URL, $url);
    curl_exec($cu);
    curl_close($cu);
}

/*
  Function writeCsv :

  fileName = File name to be written.
  records = Records to be written on CSV file.

 */

function writeCsv($fileName, $records) {
    $fp = fopen($fileName, 'w+');
    $write = fputs($fp, $records.",");
    fclose($fp);
}

?>