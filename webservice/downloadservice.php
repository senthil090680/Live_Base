<?php
require_once('/lib/nusoap.php');
include '../include/config.php';
$server = new soap_server;
$server->configureWSDL('downloadservice', 'http://182.72.36.182//Host/functions/webservice/downloadservice.php');
$server->register('download', array('deviceCode' => 'xsd:string', 'FileIndex' => 'xsd:string', 'InfoFlag' => 'xsd:string', 'Message' => 'xsd:string'), array('return' => 'xsd:string'), 'http://172.16.42.220/Host/functions/webservice/downloadservice.php', 'urn://172.16.42.220/Host/functions/webservice/downloadservice.php', 'rpc', 'encoded', 'Download from base to device'
);
function download($deviceCode, $FileIndex, $InfoFlag, $Message) {
//return  $deviceCode. " " . $FileIndex . " " . $InfoFlag . " " .  $Message;    
    $query = "select * from device_master where DEVICE_CODE='" . $deviceCode . "'";
    $result = mysql_query( $query);
    $count = mysql_num_rows();

    if ($count > 0) {
        if ($FileIndex == 5) {
            $query = "update ping_table set STATUS='OFFLINE' where DEVICE_CODE='" . $deviceCode . "'";
            mysql_query( $query);

            $query = "insert into ping_table_log values ('','" . $deviceCode . "','OFFLINE',now(),'admin',now(),'admin')";
            mysql_query( $query);
           return "DeviceId=" . $deviceCode . "&FileIndex=" . $FileIndex ;
        } else {
            $query = "select * from ping_table where DEVICE_CODE='" . $deviceCode . "'";
            $result = mysql_query( $query);

            while ($data = mysql_fetch_array($result)) {
                $status = $data['STATUS'];
                $action = $data['ACTION'];
            }

            if ($status == "OFFLINE") {
                $errorMessage = "Device Offline . Please sync first before downloading";
                return "DeviceId=" . $deviceCode . "&FileIndex=" . $FileIndex . "&ErrorFlag=2&ErrorMessage=" . $errorMessage;
            }

            if ($action != "D") {
                $errorMessage = "Download Option Not avaible. Please sync again";
                return "DeviceId=" . $deviceCode . "&FileIndex=" . $FileIndex . "&ErrorFlag=2&ErrorMessage=" . $errorMessage;
            }

            $infoFlag = "1";

            $query = "insert Into device_transfer_table values('','" . $deviceCode . "','D','" . $FileIndex . "',now(),'admin',now(),'admin')";
            mysql_query( $query);
            /*
              process on database table and check for other files .
             */


            $query = "select * from base_device_transfer";
            $result = mysql_query( $query);
            $lines = "";
            $path = "DeviceUpload_Table_" . $deviceCode;
            $path = $path . "_" . date("Y-m-d");

            $filePath = str_replace("_", "\\", $path);

            if (!is_dir("" . $filePath)) {
                mkdir("" . $filePath, 0777, true);
            }
            $date = new DateTime();


            while ($data = mysql_fetch_array($result)) {


                $tableName = $data['TABLE_NAME'];


                $query = "SELECT * FROM " . $tableName;
                $result_tb = mysql_query( $query);
                $records = "";
                while ($row = mysql_fetch_array($result_tb)) {

                    for ($i = 0; $i < mysql_field_count(); $i++) {
                        $records .= $row[$i];
                        $records .= ", ";
                    }
                    $records .= "\n";
                }

                $filename = "" . $filePath . "\\" . $tableName . "@" . $date->getTimestamp() . ".csv";
                $lines .= $tableName . "@" . $date->getTimestamp() . ".csv" . "\n";
                $fp = fopen($filename, 'w+');
                $write = fputs($fp, $records);
                fclose($fp);
            }

            $filename = $filename = "" . $filePath . "\\download.txt";
            $fp = fopen($filename, 'w+');
            fputs($fp, $lines);
            fclose($fp);



            $query = "update ping_table set ACTION = 'W' where DEVICE_CODE= '" . $deviceCode . "'";
            mysql_query($query);

            return "DeviceId=" . $deviceCode . "&FileIndex=5&ErrorFlag=1";
        }
    } else {
        $infoFlag = "2";
        $errorMessage = "Invalid Device Code.Please try again";
        return "DeviceId=" . $deviceCode . "&FileIndex=" . $FileIndex . "&ErrorFlag=2&ErrorMessage=" . $errorMessage;
    }
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>