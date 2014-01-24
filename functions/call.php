<?php
include "../include/config.php";
$query = "select * from kd_information";
$result = mysql_query( $query);
while ($data =mysql_fetch_array($result)) {
    $kd = $data['KD_Code'];
}
//$serverIP = "sfa.fmclgrp.com";
$url = "http://" . $serverIP . "/Host/functions/scheduleUpload.php?kd='" . $kd . "'";
$cu = curl_init();
curl_setopt($cu, CURLOPT_URL, $url);
curl_exec($cu);
curl_close($cu);
?>