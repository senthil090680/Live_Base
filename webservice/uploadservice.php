<?php

require_once('/lib/nusoap.php');
include '../include/config.php';
$server = new soap_server;

$server->configureWSDL('uploadservice', 'http://182.72.36.182/base/functions/webservice/uploadservice.php');

$server->register('upload', array('deviceCode' => 'xsd:string', 'FileIndex' => 'xsd:string', 'InfoFlag' => 'xsd:string', 'Message' => 'xsd:string'), array('return' => 'xsd:string'), 'http://182.72.36.182/Host/functions/webservice/uploadservice.php', 'urn://182.72.36.182/Host/functions/webservice/uploadservice.php', 'rpc', 'encoded', 'upload to base from device'
);

function upload($deviceCode, $FileIndex, $InfoFlag, $Message) {


//return  $deviceCode. " " . $FileIndex . " " . $InfoFlag . " " .  $Message;

   
    $query = "select * from device_master where device_code='" . $deviceCode . "'";
    $result = mysql_query( $query);
    $count = mysql_num_rows($result);


    if ($count > 0) {
        $query = "select * from ping_table where DEVICE_CODE='" . $deviceCode . "'";
        $result = mysql_query($query);

        while ($data = mysql_fetch_array($result)) {
            $status = $data['STATUS'];
            $action = $data['ACTION'];
        }

        if ($status == "OFFLINE") {
            $errorMessage = "Device Offline . Please sync first before downloading";
            return "DeviceId=" . $deviceCode . "&FileIndex=" . $FileIndex . "&ErrorFlag=2&ErrorMessage=" . $errorMessage;
        }

        if ($action != "U" and $action != "R") {
            $errorMessage = "Upload /Reconsile Option Not avaible. Please sync again";
            return "DeviceId=" . $deviceCode . "&FileIndex=" . $FileIndex . "&ErrorFlag=2&ErrorMessage=" . $errorMessage;
        }








        $infoFlag = "1";

        $query = "insert Into device_transfer_table values('','" . $deviceCode . "','" . $action . "','" . $FileIndex . "',now(),'admin',now(),'admin')";
        mysql_query( $query);




        /*
          process on database table and check for other files .
         */


        $path = "DeviceUpload_Table_" . $deviceCode;
        $path = $path . "_" . date("Y-m-d");

        $filePath = str_replace("_", "\\", $path);

        if (!is_dir("" . $filePath)) {
            mkdir("" . $filePath, 0777, true);
        }
        $date = new DateTime();


         $filename = "" . $filePath . "\\download.txt";

        if ($fp = fopen($filename, 'r')) {
            $tableList = '';
            // keep reading until there's nothing left 
            while ($line = fread($fp, 1024)) {
                $tableList .= $line;
            }
            $tableArray = explode("\n", $tableList);
            foreach ($tableArray as $tableName) {


                $filename = "" . $filePath . "\\" . $tableName;
                $fp = fopen($filename, 'r+');
                $content = "";
                while ($line = fread($fp, 1024)) {
                    $content .= $line;
                }
                fclose($fp);

                $content_lines = explode("\n", $content);



                foreach ($content_lines as $line) {
                    // echo $line;
                    //echo "<br>";
                    $contentDatas = explode(", ", $line);

                    $queryData = "'','";
                    $flag = false;
                    foreach ($contentDatas as $contentData) {
                        if ($flag == false) {
                            $flag = true;
                        } else {
                            $queryData .= $contentData;
                            $queryData .= "','";
                        }
                    }
                    $queryData = substr($queryData, 0, -6);

                     $tableNames=explode("@",$tableName);
                     $tableName = $tableNames[0];

                    $query = "INSERT INTO " . $tableName . " values (" . $queryData . "')";
                    mysql_query($query);
                }
            }
        }









        $query = "update ping_table set ACTION = 'W' where DEVICE_CODE= '" . $deviceCode . "'";
        mysql_query( $query);

        return "DeviceId=" . $deviceCode . "&FileIndex=4&ErrorFlag=1";
    } else {
        $infoFlag = "2";
        $errorMessage = "Invalid Device Code.Please try again";
        return "DeviceId=" . $deviceCode . "&FileIndex=" . $FileIndex . "&ErrorFlag=2&ErrorMessage=" . $errorMessage;
    }
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>