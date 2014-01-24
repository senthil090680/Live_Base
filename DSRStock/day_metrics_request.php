<?php
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
error_reporting(E_ALL & ~(E_NOTICE) & ~(E_WARNING)); 
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
EXTRACT($_GET);
if(isset($DateVal) && isset($DSR_Code)) {
	 $where					=	"WHERE DSR_Code='$DSR_Code' AND Date = '$DateVal'";
}

$totallineitems				=	0;
$basket_size				=	0;
$focuslineitems				=	0;
$totalsalevalue				=	0;
$drop_size_rounded			=	0;
$totalsalesinvoice			=	0;
$totalvisit					=	0;

$query_route				=	"SELECT route_id,Date FROM cycle_assignment WHERE DSR_Code='$DSR_Code' AND Date = '$DateVal' AND (flag_status = '1' AND end_flag_status = '0') GROUP BY DSR_Code";										
$result_route				=	mysql_query($query_route) or die(mysql_error());
$row_route					=	mysql_fetch_array($result_route);
$route_id					=	$row_route['route_id'];
$cycle_start_date			=	$row_route['Date'];
$route_code					=	getrouteval($route_id,'route_code','id');

$query_target				=	"SELECT count(id) AS target_visits FROM customer WHERE route = '$route_code' AND DSR_Code = '$DSR_Code'";										
$result_target				=	mysql_query($query_target) or die(mysql_error());
$row_target					=	mysql_fetch_array($result_target);
$target_visits				=	$row_target['target_visits'];

//exit;
$query_totalvisit			=	"SELECT * FROM dsr_metrics $where";			
$res_totalvisit				=	mysql_query($query_totalvisit) or die(mysql_error());
$rowcnt_totalvisit			=	mysql_num_rows($res_totalvisit);
if($rowcnt_totalvisit > 0) {
	$row_totalvisit			=	mysql_fetch_array($res_totalvisit);
	$totalvisit				=	$row_totalvisit['visit_Count'];
	$totalsalesinvoice		=	$row_totalvisit['Invoice_Count'];
	$totallineitems			=	$row_totalvisit['Invoice_Line_Count'];
	$totalsalevalue			=	$row_totalvisit['Total_Sale_Value'];
	$drop_size_rounded		=	$row_totalvisit['Drop_Size_Value'];
	$basket_size			=	$row_totalvisit['Basket_Size_Value'];	
}

$current_date				=	date('Y-m-d');
$coverage					=	round($totalvisit/$target_visits,2);

$pcoverage					=	$totalsalesinvoice/$totalvisit;
//$basket_size				=	$totallineitems/$totalsalesinvoice;

$pcoverage					=	round($pcoverage,3);
//$basket_size				=	round($basket_size,2);

//echo $pcoverage."--".$basket_size;

$query_loadedqty			=	"SELECT Loaded_Qty FROM dailystockloading WHERE DSR_Code='$DSR_Code' AND Date = '$DateVal'";
$res_loadedqty				=	mysql_query($query_loadedqty) or die(mysql_error());

$totalSKUs					=	'';
while($row_loadedqty		=	mysql_fetch_array($res_loadedqty)){
	$totalSKUs			+=	$row_loadedqty['Loaded_Qty'];
}
//echo $totalSKUs;
//exit;
$effectivecoverage			=	$basket_size/$totalSKUs;
//exit;
//exit;
$effectivecoverage_round	=	round($effectivecoverage,3);

//echo round($effectivecoverage,2);
//exit;

$query_trno					=	"SELECT id,Transaction_Number,Transaction_type FROM transaction_hdr WHERE Date = '$DateVal' AND DSR_Code='$DSR_Code'";

$res_trno					=	mysql_query($query_trno)or die(mysql_error());

$rowcnt_trno				=	mysql_num_rows($res_trno);

$focuslinecountval			=	0;
$focusitemszerosold			=	0;
$focusitemszeroall			=	0;
if($rowcnt_trno > 0 ) {
	while($res_trno			=	mysql_fetch_array($res_trno)) {
		$Transaction_Number			=	$res_trno[Transaction_Number];
		$Transaction_type			=	$res_trno[Transaction_type];
		
		// TO find focus items in line items
		$query_focuslineitems		=	"SELECT count(*) AS FOCUSLIST FROM transaction_line WHERE (Focus_Flag = '1' AND DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number') GROUP BY Product_code";
		$res_focuslineitems			=	mysql_query($query_focuslineitems)or die(mysql_error());
		$focuslineitems				=	mysql_num_rows($res_focuslineitems);
		$row_focuslineitems			=	mysql_fetch_array($res_focuslineitems);
		$focuslinecountval			+=	$row_focuslineitems[FOCUSLIST];
		
		// TO find zero stock for only sales
		if($Transaction_type == 2) {
			$query_zerosold			=	"SELECT count(*) AS ZEROSOLD FROM transaction_line WHERE (Focus_Flag = '1' AND DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number' AND Customer_Stock_quantity = 0) GROUP BY Product_code";
			$res_zerosold			=	mysql_query($query_zerosold)or die(mysql_error());
			$rowcnt_zerosold		=	mysql_num_rows($res_zerosold);
			if($rowcnt_zerosold > 0) {
				$row_zerosold				=	mysql_fetch_array($res_zerosold);
				$focusitemszerosold		+=	$row_zerosold[ZEROSOLD];
			}
		}

		// TO find zero stock all count
		$query_zeroall			=	"SELECT count(*) AS ZEROALL FROM transaction_line WHERE (Focus_Flag = '1' AND DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number' AND Customer_Stock_quantity = 0) GROUP BY Product_code";
		$res_zeroall			=	mysql_query($query_zeroall)or die(mysql_error());
		$rowcnt_zeroall		=	mysql_num_rows($res_zeroall);
		if($rowcnt_zeroall > 0) {
			$row_zeroall				=	mysql_fetch_array($res_zeroall);
			$focusitemszeroall			+=	$row_zeroall[ZEROALL];
		}
	}
}

$zero_stock_coverage		=	$focusitemszeroall/$focusitemszerosold;
$zero_stock_coverage_rounded=	round($zero_stock_coverage,3);

$query_focusitemsstock		=	"SELECT id FROM dailystockloading WHERE (focus_Flag ='1') AND Date='$DateVal' AND DSR_Code='$DSR_Code'";
$res_focusitemsstock		=	mysql_query($query_focusitemsstock) or die(mysql_error());

$focusitemsstock			=	mysql_num_rows($res_focusitemsstock);

$focus_coverage				=	$focuslinecountval/$focusitemsstock;
$focus_coverage_rounded		=	round($focus_coverage,3);

//echo $focuslineitems."++++++".$focusitemsstock."=========".$focus_coverage_rounded;

echo $target_visits."~".$totalvisit."~".$coverage."~".$totalsalesinvoice."~".$pcoverage."~".$totallineitems."~".$basket_size."~".$effectivecoverage_round."~".$focuslineitems."~".$focusitemsstock."~".$focus_coverage_rounded."~".$focusitemszerosold."~".$focusitemszeroall."~".$zero_stock_coverage_rounded."~".$totalsalevalue."~".$drop_size_rounded;
exit(0);?>