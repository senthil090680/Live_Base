<?php
$TransactionType				=	'DSR Return';
$Transaction_number_Opening		=	'DSRETURN10001';
$Transaction_number_Opening		=	'DSRETURN10001';
$TodayDate						=	date('Y-m-d');


$sel_dsrtransno		=	"SELECT Transaction_number FROM `dsr_return` ORDER BY id DESC";
$res_dsrtransno			=	mysql_query($sel_dsrtransno) or die(mysql_error());	
$rowcnt_dsrtransno			=	mysql_num_rows($res_dsrtransno);
if($rowcnt_dsrtransno > 0){
	$row_dsrtransno		=	mysql_fetch_array($res_dsrtransno);
	$dsrtransno			=	$row_dsrtransno['Transaction_number'];
} else {
	$dsrtransno			=	rand(1,99);
}

$dsr_idval									=	getdsrval($DSR_Code[1],'id','DSR_Code');
$dsr_nameval								=	getdsrval($DSR_Code[1],'DSRName','DSR_Code');

$sel="select id,BalanceQty from opening_stock_update where Product_code ='$Product_code' AND KD_Code = '$KD_Code' ORDER BY id desc";
$sel_query=mysql_query($sel) or die(mysql_error());
if(mysql_num_rows($sel_query) > 0) {
$row_qty=mysql_fetch_array($sel_query);
	$open_id	=	$row_qty[id];
	$updated_qty	=	$row_qty[BalanceQty] + $KD_returned_qty;
	$sql_opening	=	"INSERT INTO opening_stock_update SET `Date`='$TodayDate',`StockDateTime`=NOW(),`TransactionType`='$TransactionType',`TransactionNo`='$Transaction_number',`UOM1`='$uom',`TransactionQty`='$KD_returned_qty',`BalanceQty`='$updated_qty',`AddedFirstTime`='Y',`Product_code`='$pcode',`KD_Code`='$KD_Code'";					
	mysql_query($sql_opening) or die(mysql_error());
	$last_inserted_id	=	mysql_insert_id();
}

$dsrtransnoadded							=	$dsrtransno + 1;

if($k == $prodcnt) {
	$ins_val	.=	"('$dsr_idval','$dsr_nameval','$supplier_name','$dsrtransnoadded','$TodayDate','$Product_code','$UOM','$KD_returned_qty')";
} else {
	$ins_val	.=	"('$dsr_idval','$dsr_nameval','$supplier_name','$dsrtransnoadded','$TodayDate','$Product_code','$UOM','$KD_returned_qty'),";
}

$sql_dsrreturn="INSERT INTO `dsr_return` (`dsr_id`,`DSRName`,`supplier_name`,`Transaction_number`,`Date`,`Product_code`,`UOM`,`quantity`) VALUES $ins_val";
mysql_query($sql_dsrreturn) or die(mysql_error());




?>