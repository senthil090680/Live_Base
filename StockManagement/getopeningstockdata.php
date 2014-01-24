<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
if((isset($_GET[TransactionQty]) && $_GET[TransactionQty] !='') && (isset($_GET[pcode]) && $_GET[pcode] !='')) {
	$TransactionType="OpeningStock";
	$TransactionNo="10001";
	$kdcode		=	getKDCode();
	$sql="UPDATE `opening_stock_update` SET `Date`='$DateVal',`StockDateTime`=NOW(),`TransactionType`='$TransactionType',`TransactionNo`='$TransactionNo',`UOM1`='$UOM',`TransactionQty`='$TransactionQty',`BalanceQty`='$TransactionQty',`AddedFirstTime`='D',KD_Code='$kdcode' WHERE Product_code = '$pcode'";
	mysql_query($sql) or die(mysql_error());
	if($sql) {
		echo "success";
	}
} else {
	$nextrecval		=	"";
}
exit(0); ?>