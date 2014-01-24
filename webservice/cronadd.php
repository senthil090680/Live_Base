<?php
include "../include/config.php";
include "../include/ajax_pagination.php";
echo "hi";
ini_set("display_errors",true);
error_reporting(E_ALL & ~E_NOTICE);
//echo $_SERVER[DOCUMENT_ROOT];
//exit;

$res_checkDeviceCode	= "insert into test (id) values ('5')";
mysql_query($query_checkDeviceCode) or die(mysql_error());
exit;
ini_set("display_errors",true);
error_reporting(E_ALL & ~E_NOTICE);
$current_timestamp	=	date('Y-m-d H:i:s');
$KD_Code			=	"KD001";
$filemode			=	fopen("croncheck.txt","a+");
fwrite($filemode,"\n".$current_timestamp."_".$KD_Code);
fclose;
?>