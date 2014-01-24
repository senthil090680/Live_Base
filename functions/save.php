<?php
include "../include/config.php";
$process = $_POST['process'];
$option = $_POST['option'];
$frequency = $_POST['frequency'];
$day = $_POST['day'];
$sd = $_POST['sd'];
$st = $_POST['st'];


if ($process == "db")
    $transfername = "Download to Base";

if ($process == "ub")
    $transfername = "Upload To HOST";

if ($frequency != "weekly")
    $day = "";

if ($sd == "")
    $sdate = date('Y-m-d');
else {
    $sdate = new DateTime($sd);
    $sdate = date_format($sdate, 'Y-m-d');
}

if ($st == "")
    $stime = date('H:i:s');

if ($option != "auto") {
    $sd = "";
    $st = "";
    $day = "";
    $frequency = "";
}

// DB Connection
//$query="truncate table data_transfer_configuration";
//mysql_query($con,$query);

if ($process == "ub") {
    $query = "Delete  from data_transfer_configuration where CONFIGURATION_ID = '1'";
    mysql_query($query);
    $query = "Insert into data_transfer_configuration values('1','" . $transfername . "','" . $option . "','" . $frequency . "','" . $day . "','" . $sdate . " " . $stime . "','" . date('Y-m-d H:i:s') . "','admin','" . date('Y-m-d H:i:s') . "','admin')";
    mysql_query($query);
}


if ($option == "auto") {
    createcron($frequency, $day, $sd, $st, $process);
    echo "Succesfully updated. Process will start on  " . $sdate . " " . $stime;
}

if ($option == "ondemand") {
    echo "Succesfully updated . Please use the link specified to process onDemand";
    deleteCron($process);
}



$result = exec("cmd.exe /c auto.bat");

function createcron($frequency, $day, $sd, $st, $process) {
    global $serverName, $userName, $password, $serverPath;

    //echo $frequency;


    if ($frequency == "halfhour")
        $freqCode = " /sc minute /mo 30";
    else
        $freqCode = " /sc " . $frequency;

    if ($day == "")
        $daycode = "";
    else
        $dayCode = " /d " . $day;

    if ($st == "")
        $stCode = "";
    else
        $stCode = " /st " . $st;

    if ($sd == "")
        $sdcode = "";
    else
    {
        $sdate = new DateTime($sd);
    $sdate = date_format($sdate, 'm/d/Y');
    $sdCode = " /sd " . $sdate;
    }

    if ($process == "db") {
        $batFile = "cron.bat";
        $tn = "KD_base_cronDownload";
    } else {
        $batFile = "cronupload.bat";
        $tn = "KD_base_cronUpload";
    }

    $delCode = "schtasks /delete /tn " . $tn . "  /f " . "\n";

    $reCode = 'schtasks /create /s ' . $serverName . ' /RU ' . $userName . ' /RP ' . $password . ' /tn ' . $tn . ' /tr "' . $serverPath . '//vve//functions//' . $batFile . '"' . $freqCode . $dayCode . $stCode . $sdCode;
    $code = $delCode . $reCode;
    $fp = fopen("auto.bat", 'w+');
    $write = fputs($fp, $code);
    fclose($fp);
}

function deleteCron($process) {
    if ($process == "db")
        $tn = "KD_base_cronDownload";
    else
        $tn = "KD_base_cronUpload";



    $delCode = "schtasks /delete /tn " . $tn . " /f " . "\n";
    $fp = fopen("auto.bat", 'w+');
    $write = fputs($fp, $delCode);
    fclose($fp);
}

?>