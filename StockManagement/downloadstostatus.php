<?php
session_start();
ob_start();
error_reporting(0);
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
error_reporting(0);
extract($_REQUEST);

//$severnIdexcel		=	stripslashes($severnIdexcel);

//$fromDate				=	'2013-11-07';

//$toDate				=	'2013-11-07';

$query_allprod			=	"SELECT prod.UOM1,prod.Product_description1,prod.Product_code FROM `product` prod UNION
SELECT cp.UOM1,cp.Product_description1,cp.Product_code FROM `customertype_product` cp";

$result_allprod			=	mysql_query($query_allprod) or die(mysql_error());
$num_rows				=	mysql_num_rows($result_allprod);
$qry					=	$query_allprod;
while($row_allprod		=	mysql_fetch_array($result_allprod)) {
	$allprods[]			=	$row_allprod[Product_code];
}

$results_dsr=mysql_query($qry) or die(mysql_error());
$num_rows= mysql_num_rows($results_dsr);

if($sortorder == "")
{
	$orderby	=	"ORDER BY Product_code";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.=" $orderby ";  //need to uncomment
//echo $qry;
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());

$addlColumns    =   array('Product Code','Product Name','UOM','Quantity','Price','Value');

$data[]			=	$addlColumns;

if(!empty($num_rows)){
	$OpeningStock			=		0;
	$c=0;$cc=1;$y=0;
	$OpeningStock = 0;
	while($fetch = mysql_fetch_array($results_dsr)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$Product_codeval			=		$fetch['Product_code'];
		$pname						=		$fetch['Product_description1'];
		$puom						=		$fetch['UOM1'];

		$qry_closing			=	"select BalanceQty from opening_stock_update where Product_code = '$Product_codeval' AND (Date BETWEEN '$fromDate' AND '$toDate' OR Date < '$fromDate') ORDER BY id DESC"; 
		$res_closing			=	mysql_query($qry_closing) or die(mysql_error());
		if(mysql_num_rows($res_closing) > 0) {
			$row_closing		=	mysql_fetch_array($res_closing);
			$closingstock		=	$row_closing[BalanceQty];
		}
		
		//exit;
		//$todayDate					=	date('Y-m-d');
		$qry_openingid			=	"select id from opening_stock_update where Product_code = '$Product_codeval' AND (Date BETWEEN '$fromDate' AND '$toDate') ORDER BY id ASC"; 
		$res_openingid			=	mysql_query($qry_openingid) or die(mysql_error());
		if(mysql_num_rows($res_openingid) > 0) {
			$row_openingid		=	mysql_fetch_array($res_openingid);
			$OpeningStockid		=	$row_openingid[id];
			$qry_opening			=	"select BalanceQty from opening_stock_update where Product_code = '$Product_codeval' AND (id < '$OpeningStockid') ORDER BY id DESC"; 
			$res_opening			=	mysql_query($qry_opening) or die(mysql_error());
			if(mysql_num_rows($res_opening) > 0) {
				$row_opening		=	mysql_fetch_array($res_opening);
				$OpeningStock		=	$row_opening[BalanceQty];
			} else {
				$qry_openingval			=	"select BalanceQty from opening_stock_update where Product_code = '$Product_codeval' AND (id = '$OpeningStockid') ORDER BY id DESC"; 
				$res_openingval			=	mysql_query($qry_openingval) or die(mysql_error());
				if(mysql_num_rows($res_openingval) > 0) {
					$row_openingval		=	mysql_fetch_array($res_openingval);
					$OpeningStock		=	$row_openingval[BalanceQty];
				}
			}
		} else {
			$qry_openingid			=	"select id AS ID from opening_stock_update where Product_code = '$Product_codeval' AND (Date < '$fromDate') ORDER BY id DESC"; 
			$res_openingid			=	mysql_query($qry_openingid) or die(mysql_error());
			if(mysql_num_rows($res_openingid) > 0) {
				$row_openingid		=	mysql_fetch_array($res_openingid);
				$OpeningStockid		=	$row_openingid[ID];
				
				$qry_openingval			=	"select BalanceQty from opening_stock_update where Product_code = '$Product_codeval' AND (id = '$OpeningStockid') ORDER BY id DESC"; 
				$res_openingval			=	mysql_query($qry_openingval) or die(mysql_error());
				if(mysql_num_rows($res_openingval) > 0) {
					$row_openingval		=	mysql_fetch_array($res_openingval);
					$OpeningStock		=	$row_openingval[BalanceQty];
				}
			}
		}

		if($OpeningStock == '') { 
			$OpeningStock		=	0;
			$closingstock		=	0;
		}

		//$OpeningStock					=	$fetch['BalanceQty'];

		$sel_receipt	=	"SELECT sum(quantity) as recqty FROM `stock_receipts` WHERE (Product_code = '$Product_codeval') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_receipt	=	mysql_query($sel_receipt)  or die(mysql_error());
		$row_receipt		=	mysql_fetch_array($results_receipt);

		$sel_issue	=	"SELECT sum(issued_quantity) as issqty FROM `stock_issue` WHERE (Product_code = '$Product_codeval') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_issue	=	mysql_query($sel_issue)  or die(mysql_error());
		$row_issue		=	mysql_fetch_array($results_issue);

		//echo intval($row_receipt[recqty])."ydh";
		$sel_dsreturn	=	"SELECT sum(quantity) as dsrqty FROM `dsr_return` WHERE (Product_code = '$Product_codeval') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_dsreturn	=	mysql_query($sel_dsreturn)  or die(mysql_error());
		$row_dsreturn		=	mysql_fetch_array($results_dsreturn);

		$sel_cusreturn	=	"SELECT sum(quantity) as cusqty FROM `customer_return` WHERE (Product_code = '$Product_codeval') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_cusreturn	=	mysql_query($sel_cusreturn)  or die(mysql_error());
		$row_cusreturn		=	mysql_fetch_array($results_cusreturn);

		$sel_adjust	=	"SELECT sum(quantity) as adjqty FROM `stock_adjustment` WHERE (Product_code = '$Product_codeval') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_adjust	=	mysql_query($sel_adjust)  or die(mysql_error());
		$row_adjust		=	mysql_fetch_array($results_adjust);
		//echo $OpeningStock;		
			
		//echo $fetch['BalanceQty'];
				
		$sel_priceval			=	"SELECT Price FROM `price_master` WHERE Product_code = '$Product_codeval'"; 
		$results_priceval		=	mysql_query($sel_priceval)  or die(mysql_error());
		$row_priceval			=	mysql_fetch_array($results_priceval);
		$priceval				=	$row_priceval['Price'];
		
		$c++; $cc++; $y++; 
		
	
		$row[]			=	$Product_codeval;
		$row[]			=	$pname;
		$row[]			=	$puom;
		$row[]			=	$closingstock;
		$row[]			=	$priceval;
		$row[]			=	($priceval * $closingstock);
		$data[]			=	$row;
		
		$row			=	'';	
		$OpeningStock	=	0;
		$closingstock	=	0;
		$priceval		=	0;
	}
}


/*
$querySelect		=	"select severnId,orderRefNum,serialNum,taqNum,customer,project,poNum,yearSupplied,purchasingOffice,consultant,endUser,finalDest,industry,globalSector,originalManf,unival,valveModel,valveSize,endCorn,valveType,rating,bodyMaterial,bodyType,trimType,trimChar,trimMaterial,leakageClass,ratedCv,balanced,application,designTemp,designPressure,fluid,actuatorType,actuatorSize,handWheel,strokeLength,airFail,positioner,convertor,airSet,solenoidValve,lockValue,limitSwitch,quickExhaust,airReceiver,booster,others,signalValue,specialNotes,history,costPrice,currency,sellingPrice,insertedDate,updatedDate,valveInfo,itemNumber,extensionPart,min_allow_press,min_allow_temp,airsetOption1,positionerOption2,solenoidValveOption3,limitSwitchOption1,others1,others2,others3,others4,others5,others6,others7,originalManufacturer".$addlColumns." from ".TABLE_SEVERN." where serialNum in ('".$severnIdexcel."')";

//$querySelect		=	"select * from ".TABLE_SEVERN." where serialNum in ('".$severnIdexcel."')";

//exit(0);

$result = mysql_query($querySelect);
$lastresult = array();
while($row = mysql_fetch_assoc($result)) {
	$data[] = $row;
}
*/

//print_r($data);

//exit;

/*$data = array( 
array("firstname" => "Mary", "lastname" => "Johnson", "age" => 25), 
array("firstname" => "Amanda", "lastname" => "Miller", "age" => 18), 
array("firstname" => "James", "lastname" => "Brown", "age" => 31), 
array("firstname" => "Patricia", "lastname" => "Williams", "age" => 7), 
array("firstname" => "Michael", "lastname" => "Davis", "age" => 43), 
array("firstname" => "Sarah", "lastname" => "Miller", "age" => 24), 
array("firstname" => "Patrick", "lastname" => "Miller", "age" => 27) ); */

function cleanData(&$str) { 
	$str = preg_replace("/\t/", "\\t", $str); 
	$str = preg_replace("/\r?\n/", "\\n", $str); 
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 

# filename for download $filename = "website_data_" . date('Ymd') . ".xls"; 

$filename = "excelformat";
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".xls");
header( "Content-disposition: attachment; filename=".$filename.".xls");
$flag = false; 
foreach($data as $row) { 
	if(!$flag) { 
		# display field/column names as first row 
		//echo implode("\t", array_keys($row)) . "\r\n"; $flag = true; 
	} 
	array_walk($row, 'cleanData'); 
	echo implode("\t", array_values($row)) . "\r\n";
} 
exit;
?>