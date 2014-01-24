<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
//require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_REQUEST);
//debugerr($_REQUEST);
//exit;
$params		=	$kdcode;
$where		=	"";
$complete_query		=	'';
$focuscheck_query	=	'';
$target_query		=	'';
if(isset($_REQUEST[propmonths]) && $_REQUEST[propmonths] !='') {
	
	$datecolvalue	=	$propyears."-".$propmonths;
	/*$datecol		=	"(Date >= '".$fromdatevalue."' AND Date <= '".$todatevalue."')";*/
	$datecolfocus	=	"(LEFT(Date,10) LIKE '".$datecolvalue."' AND LEFT(Date,10) LIKE '".$datecolvalue."')";

	$datecol		=	"Date LIKE '".$datecolvalue."%'";
	
	if($asmcode	==	'') {
		$asmcodecol		=	'';
		$wherefordsr	=	'';
	} elseif($asmcode	!=	'') {
		$asmcodestr		=	implode("','",$asmcode);
		$asmcodecol		=	"ASM IN ('".$asmcodestr."')";
		$asmcodecolval	=	"DSR_Code IN ('".$asmcodestr."')";
		$wherefordsr	=	'WHERE';
	}

	if($rsmcode	==	'') {
		$rsmcodecol		=	'';
	} elseif($rsmcode	!=	'') {
		$rsmcodestr		=	implode("','",$rsmcode);
		$rsmcodecol		=	"RSM IN ('".$rsmcodestr."')";
	}
	
	if($srcode	==	'') {
		$DSR_Codestr		=	'';
	} elseif($srcode	!=	'') {
		//$DSR_Codestr		=	implode("','",$srcode);
		$DSR_Codestr		=	$srcode;
		//$srcodecol		=	"DSR_Code IN ('".$srcodestr."')";
	}

	//echo $Custypestr;
	//exit;
	$finalSearchInfo					=	'';
	
	if($focuscheck_query	==	'') {
		if($DSR_Codestr	==	'') {
			$focuscheck_query		.=	"";
		} else {
			$focuscheck_query		.=	" WHERE DSR_Code IN ('".$DSR_Codestr."')";
		}
	} else if($focuscheck_query	!=	'') {
		if($DSR_Codestr	==	'') {
			$focuscheck_query		.=	"";
		} else {
			$focuscheck_query		.=	" AND DSR_Code IN ('".$DSR_Codestr."')";
		}
	}

	if($complete_query	==	'') {
		if($DSR_Codestr	==	'') {
			$complete_query		.=	"";
		} else {
			$complete_query		.=	" WHERE DSR_Code IN ('".$DSR_Codestr."')";
		}
	} else if($complete_query	!=	'') {
		if($DSR_Codestr	==	'') {
			$complete_query		.=	"";
		} else {
			$complete_query		.=	" AND DSR_Code IN ('".$DSR_Codestr."')";
		}
	}

	if($Custypestr	==	'') {
		$custype_query		.=	"";
	} else {
		$custype_query		.=	" WHERE customer_type IN ('".$Custypestr."')";
	}

	$propmonthstrim		=	ltrim($propmonths,0);
	if($target_query	==	'') {		
		$target_query		.=	" WHERE monthval = '$propmonthstrim' AND yearval = '$propyears'";
	} else if($target_query	!=	'') {
		$target_query		.=	" AND monthval = '$propmonthstrim' AND yearval = '$propyears'";
	}

	if($complete_query	==	'') {
		$complete_query			.=	" WHERE $datecol";
		//$complete_query		.=	" WHERE $datecol";
	} else if($complete_query	!=	'') {
		$complete_query			.=	" AND $datecol";
		//$complete_query		.=	" AND $datecol";
	}

	if($focuscheck_query	==	'') {
		$focuscheck_query			.=	" WHERE $datecolfocus";
		//$complete_query		.=	" WHERE $datecol";
	} else if($focuscheck_query	!=	'') {
		$focuscheck_query			.=	" AND $datecolfocus";
		//$complete_query		.=	" AND $datecol";
	}
					
	$query_trans									=   "SELECT KD_Code,DSR_Code,Date,SUM(visit_Count) AS ACTVISCNT,SUM(Invoice_Count) AS ACTINVCNT,SUM(effective_count) AS EFFCNT,SUM(productive_count) AS PROCNT,SUM(Invoice_Line_Count) ACTLINCNT,SUM(Total_Sale_Value) AS ACTSALTOT,SUM(Drop_Size_Value) AS ACTDROP,SUM(Basket_Size_Value) AS ACTBAS FROM dsr_metrics $complete_query ORDER BY Date";
	
	//$query_trans									=   "SELECT KD_Code,DSR_Code,Date,Customer_Code,Check_In_time,Check_Out_time FROM customer_visit_tracking $complete_query";
	//echo $query_trans;
	//exit;
	$res_trans										=   mysql_query($query_trans);

	while($row_trans								=   mysql_fetch_assoc($res_trans)) {
		$customer_track[]							=	$row_trans;
		$kdcode_trans[]								=	$row_trans["KD_Code"];
		$dsrcode_trans[]							=	$row_trans["DSR_Code"];		
	}

	//echo count($transInfo)."jungle";
	$kdcode_trans		=	array_unique($kdcode_trans);
	$kdcode_Total		=	implode("','",$kdcode_trans);

	$dsrcode_trans		=	array_unique($dsrcode_trans);
	$dsrcode_Total		=	implode("','",$dsrcode_trans);
	
	//exit;
	//pre($transInfo);
	//exit;
	$finalSearchInfo			=	$customer_track;
	//pre($finalSearchInfo);
	//exit;
	
	$query_dsrmetrics	=   "SELECT DSR_Code,Date,Check_In_time,Check_Out_time,Customer_Code FROM customer_visit_tracking WHERE $datecol ORDER BY Date,Check_In_time";
	//echo $query_dsrmetrics;
	//exit;
	$res_dsrmetrics									=   mysql_query($query_dsrmetrics);
	while($row_dsrmetrics							=   mysql_fetch_assoc($res_dsrmetrics)) {
		$checktimeInfo[]							=	$row_dsrmetrics;
		$cuscode_trans[]							=	$row_trans["Customer_Code"];
	}

	$cuscode_trans		=	array_unique($cuscode_trans);
	$cuscode_Total		=	implode("','",$cuscode_trans);

	//pre($checktimeInfo);
	//exit;
	$p								=	1;
	$checkarrcnt					=	count($checktimeInfo);
	$combinedatedsr					=	'';
	$checkdatedsr					=	'';
	foreach($checktimeInfo AS $checktime) {		
		//$timestartdate									=	$checktime["Date"];
		$timestartdateval								=	explode(' ',$checktime["Date"]);
		$timestartdate									=	$timestartdateval[0];

		//echo $checktime["Customer_Code"]."<br>";
		$checkRoute_Code								=	getdbval($checktime["Customer_Code"],'route','customer_code','customer');
		//echo $checkRoute_Code."<br>";
		$checkCustCount									=	findCustomerCount($checkRoute_Code,$checktime["DSR_Code"]);
		//echo $checkCustCount."<br>";
		$timeCheck_In_time								=	$checktime["Check_In_time"];
		$timeCheck_Out_time								=	$checktime["Check_Out_time"];
		$timestartdsr									=	$checktime["DSR_Code"];
		$checkdatedsr									=	$timestartdate.$timestartdsr;
		$checkintimeval									=	$timestartdate." ".$timeCheck_In_time;
		$checkouttimeval								=	$timestartdate." ".$timeCheck_Out_time;

		//echo $checkRoute_Code."<br>";
		//echo $checkdatedsr."<br>"; 
		if($p != 1) {			
			if($checkdatedsr  != $combinedatedsr) {				
				//echo $outtime[$combinedatedsr]. "==". $intime[$checkdatedsr]."<br>"; 
				$timecal[$combinedatedsr][DSR_CODE]		=	$timestartdsr;
				$timecal[$combinedatedsr][DSRDATE]		=	$combinedatedsr;
				$timecal[$combinedatedsr][FIRSTIN]		=	$firsttime[$combinedatedsr];
				$timecal[$combinedatedsr][LASTOUT]		=	$lasttime[$combinedatedsr];
				$timecal[$combinedatedsr][CUSTCOUNT]	=	$checkCustCounts[$combinedatedsr];
				$timecal[$combinedatedsr][TOTAL]		=	ceil(($outtime[$combinedatedsr]	-	$intime[$combinedatedsr])/3600);
				$intime[$checkdatedsr]					=	strtotime($checkintimeval);
				$firsttime[$checkdatedsr]				=	$timeCheck_In_time;
			}
		}
		if($checkarrcnt != $p) {			
			//echo $outtime[$combinedatedsr]."<br>"; 
			$combinedatedsr								=	$timestartdate.$timestartdsr;
			//echo $combinedatedsr."<br>";
			//echo $outtime[$combinedatedsr]."<br>"; 
			$outtime[$checkdatedsr]						=	strtotime($checkouttimeval);
			$lasttime[$checkdatedsr]					=	$timeCheck_Out_time;
			$checkCustCounts[$checkdatedsr]				=	$checkCustCount;
		} else {
			//echo $p."<br>";
			//echo $combinedatedsr."<br>";
			//echo $checkdatedsr."<br>";
			$combinedatedsr								=	$timestartdate.$timestartdsr;
			$outtime[$checkdatedsr]						=	strtotime($checkouttimeval);
			$lasttime[$checkdatedsr]					=	$timeCheck_Out_time;
			$checkCustCounts[$checkdatedsr]				=	$checkCustCount;
		}
		if($p	==	1) {
			//echo $combinedatedsr."<br>";
			$intime[$combinedatedsr]					=	strtotime($checkintimeval);
			//echo $intime[$combinedatedsr]."<br>";
			$firsttime[$combinedatedsr]					=	$timeCheck_In_time;
			//echo $firsttime[$combinedatedsr]."<br>";
		}
		$p++;
	}
	//echo $outtime[$combinedatedsr]. "===". $intime[$combinedatedsr]."<br>";
	//echo $outtime[$combinedatedsr]. "===". $intime[$combinedatedsr]."<br>";
	//echo $combinedatedsr."<br>";
	$timecal[$combinedatedsr][DSR_CODE]		=	$timestartdsr;
	$timecal[$combinedatedsr][TOTAL]		=	ceil(($outtime[$checkdatedsr]	-	$intime[$combinedatedsr])/3600);
	$timecal[$combinedatedsr][FIRSTIN]		=	$firsttime[$combinedatedsr];
	$timecal[$combinedatedsr][LASTOUT]		=	$lasttime[$checkdatedsr];
	$timecal[$combinedatedsr][CUSTCOUNT]	=	$checkCustCounts[$checkdatedsr];
	$timecal[$combinedatedsr][DSRDATE]		=	$checkdatedsr;
	
	//pre($timecal);
	//exit;

	
	$i=0;

	//pre($finalSearchInfo);
	foreach($timecal AS $key=>$value) {
		$DSRCode											=	$value[DSR_CODE];
		$check[$DSRCode]									=	$DSRCode;

		//echo $check[$DSRCode] ." == ". $checkagain[$DSRCode]."<br>";

		if(($check[$DSRCode] == $checkagain[$DSRCode]) && ($check[$DSRCode] != '' &&  $checkagain[$DSRCode] != '')) {			
			$CUSTCOUNT[$DSRCode]								+=	$value[CUSTCOUNT];
			$TOTAL[$DSRCode]									+=	$value[TOTAL];

			//echo $CUSTCOUNT[$DSRCode]."==". $DSRCode. "<br>";
			//echo $TOTAL[$DSRCode]."==". $DSRCode. "<br>";

			//echo $gettingi[$DSRCode]-1;
			//echo $Sold_Qty[$DSRCode];
			$finalTimeInfo[$value["DSR_CODE"]]["CUSTCOUNT"]			=   $CUSTCOUNT[$DSRCode];
			$finalTimeInfo[$value["DSR_CODE"]]["TOTAL"]				=   $TOTAL[$DSRCode];
		} else {
			$finalTimeInfo[$value["DSR_CODE"]]["DSR_CODE"]					=   $value["DSR_CODE"];
			$finalTimeInfo[$value["DSR_CODE"]]["TOTAL"]						=   $value["TOTAL"];
			$finalTimeInfo[$value["DSR_CODE"]]["FIRSTIN"]					=   $value["FIRSTIN"];
			$finalTimeInfo[$value["DSR_CODE"]]["LASTOUT"]					=   $value["LASTOUT"];
			$finalTimeInfo[$value["DSR_CODE"]]["CUSTCOUNT"]					=   $value["CUSTCOUNT"];
			$finalTimeInfo[$value["DSR_CODE"]]["DSRDATE"]					=   $value["DSRDATE"];
			$CUSTCOUNT[$DSRCode]							+=	$value[CUSTCOUNT];
			$TOTAL[$DSRCode]								+=	$value[TOTAL];
			$gettingi[$DSRCode]								=	$i;
			$i++;
			$checkagain[$DSRCode]		=	$check[$DSRCode];
		}
		
	}

	//pre($Sold_Qty);
	//pre($VALUE_NAIRA);
	//pre($gettingi);
	//pre($finalTimeInfo);
	$timecal			=	$finalTimeInfo;
	//pre($timecal);
	//exit;


//KD_Code,DSR_Code,Date,SUM(visit_Count) AS ACTVISCNT,SUM(Invoice_Count) AS ACTINVCNT,SUM(effective_count) AS EFFCNT,SUM(productive_count) AS PROCNT,SUM(Invoice_Line_Count) ACTLINCNT,SUM(Total_Sale_Value) AS ACTSALTOT,SUM(Drop_Size_Value) AS ACTDROP,SUM(Basket_Size_Value) AS ACTBAS

	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_checkin){
		$valDSRCHC									=	$val_checkin["DSR_Code"];
		//echo $timecal[$val_checkin["DSR_Code"]]["DSR_CODE"] . "===". $valDSRCHC."<br>";
		if($timecal[$val_checkin["DSR_Code"]]["DSR_CODE"] == $valDSRCHC) { 
			$finalcheckinInfo[$i]["DSRCode"]							=   $val_checkin["DSR_Code"];
			$finalcheckinInfo[$i]["ACTVISCNT"]							=   $val_checkin["ACTVISCNT"];
			$finalcheckinInfo[$i]["ACTINVCNT"]							=   $val_checkin["ACTINVCNT"];
			$finalcheckinInfo[$i]["EFFCNT"]								=   $val_checkin["EFFCNT"];
			$finalcheckinInfo[$i]["PROCNT"]								=   $val_checkin["PROCNT"];
			$finalcheckinInfo[$i]["ACTLINCNT"]							=   $val_checkin["ACTLINCNT"];
			$finalcheckinInfo[$i]["ACTSALTOT"]							=   $val_checkin["ACTSALTOT"];
			$finalcheckinInfo[$i]["ACTDROP"]							=   $val_checkin["ACTDROP"];
			$finalcheckinInfo[$i]["ACTBAS"]								=   $val_checkin["ACTBAS"];
			$finalcheckinInfo[$i]["TOTCUS"]								=   $timecal[$val_checkin["DSR_Code"]]["CUSTCOUNT"];
			$finalcheckinInfo[$i]["TOTALHRS"]							=   $timecal[$val_checkin["DSR_Code"]]["TOTAL"];
			$i++;
		}
		$k++;
	}

	$finalSearchInfo          =   $finalcheckinInfo;
	//pre($finalSearchInfo);
	//exit;
	
	$query_transhdr													=   "SELECT id,Customer_code,Transaction_Number,Date,Time,transaction_Reference_Number FROM transaction_hdr $complete_query AND Transaction_type IN ('2','3','4')";
	//echo $query_transhdr;
	//exit;
	$res_transhdr													=   mysql_query($query_transhdr);
	$transno_transhdr												=	array();
	while($row_transhdr												=   mysql_fetch_assoc($res_transhdr)) {		
		$Transaction_Number											=	$row_transhdr[Transaction_Number];
		$query_returnline											=   "SELECT id,Transaction_Number FROM transaction_return_line WHERE Transaction_Number = '$Transaction_Number'";
		$res_returnline												=   mysql_query($query_returnline);
		$rowcnt_returnline											=   mysql_num_rows($res_returnline);

		if($rowcnt_returnline == 0) {
			$Transaction_Number_sales							=   $row_transhdr[Transaction_Number];
			if($row_transhdr[transaction_Reference_Number] !='' && $row_transhdr[transaction_Reference_Number] != '0') {
				$transaction_Reference_Number_cancel[]			=   $row_transhdr[transaction_Reference_Number];
				$transno_cancel_number[]						=   $row_transhdr[Transaction_Number];
			}
			$transhdr_result[]									=   $row_transhdr;
			$transhdrInfo[$row_transhdr[Transaction_Number]]	=   $row_transhdr;
			$transno_transhdr[]									=   $row_transhdr[Transaction_Number];
		}
	}
	 
	//pre($transno_transhdr);	
	//pre($transaction_Reference_Number_cancel);
	//pre($transno_cancel_number);
	
	foreach($transaction_Reference_Number_cancel AS $REFVALE){
		//echo $REFVALE		=	trim($REFVALE);
		//pre($transno_transhdr);
		//echo $arraysearchval		=	array_search($REFVALE,$transno_transhdr);
		//echo $REFVALE."++".pre($transno_transhdr)."<br>";
		if(array_search($REFVALE,$transno_transhdr) !== false) {
			//echo $REFVAL;
			$arraysearchval		=	array_search($REFVALE,$transno_transhdr);
			//echo $arraysearchval;
			unset($transno_transhdr[$arraysearchval]);
		} else {
			//echo $arraysearchval		=	array_search($REFVAL,$transno_transhdr);
			//echo "notin";
		}
	}

	//pre($transno_transhdr);
	//exit;
	//pre($transno_cancel_number);
	foreach($transno_cancel_number AS $REFVALUE){
		if(array_search($REFVALUE,$transno_transhdr) !== false) {
			$arraysearchval		=	array_search($REFVALUE,$transno_transhdr);
			unset($transno_transhdr[$arraysearchval]);
		}
	}

	//pre($transno_transhdr);
	
	//exit;
	$transno_transhdr		=	array_unique($transno_transhdr);
	$transno_Total			=	implode("','",$transno_transhdr);

	//pre($transno_transhdr);                                                        
	//exit;

	$finalAllProdInfo					=	$transhdr_result;
	//pre($finalAllProdInfo);
	//echo $transno_Total;
	//exit;


	$query_trans										=   "SELECT KD_Code,DSR_Code,Product_code,SUM(Sold_Quantity) AS SALQTY FROM transaction_line WHERE Transaction_Number IN ('".$transno_Total."') $prodcodecol GROUP BY Product_code ORDER BY Product_code";
	//$query_trans										=   "SELECT KD_Code,DSR_Code,Product_code,Transaction_Number,Sold_quantity AS SOLQTY FROM transaction_line WHERE POSM_Flag = '1' AND Transaction_Number IN ('".$transno_Total."') $prodcodecol ORDER BY Product_code";
	//echo $query_trans;
	//exit;
	$res_trans											=   mysql_query($query_trans);

	while($row_trans									=   mysql_fetch_assoc($res_trans)) {
		//$transAllInfo[$row_trans["Transaction_Number"]]=	$row_trans;
		$product_id										=	getdbval($row_trans["Product_code"],'id','Product_code','product');
		$transTotalProdInfo[$row_trans["DSR_Code"].$product_id]				=	$row_trans;
		$transTotalProdInfo[$row_trans["DSR_Code"].$product_id][Product_id]	=	$product_id;
		$transProdInfo[$row_trans["DSR_Code"].$product_id]					=	$product_id;
	}
	//pre($transTotalProdInfo);
	//exit;

	foreach($transTotalProdInfo AS $prodval) {
		$prodvalue[]	=	getdbval($prodval[Product_code],'id','Product_code','product');
		//pre($prodval);
	}

	//pre($prodvalue);
	//exit;

	$tofindtgt_prod				=	array_unique($prodvalue);
	$tofindtgt_Total			=	implode("','",$tofindtgt_prod);
	
	$trimmedmonths				=	ltrim($propmonths,0);
	$query_tgtval								=   "SELECT KD_Code,DSR_Code,Product_id,target_units,target_naira,targetFlag FROM sr_incentive WHERE Product_id IN ('".$tofindtgt_Total."') AND DSR_Code = '$srcode' AND monthval = '$trimmedmonths' AND yearval = '$propyears' ORDER BY Product_id";
	//echo $query_tgtval;
	//exit;
	$res_tgtval									=   mysql_query($query_tgtval);

	while($row_tgtval							=   mysql_fetch_assoc($res_tgtval)) {
		//$transAllInfo[$row_trans["Transaction_Number"]]=	$row_trans;
		$transTotalProdTgtInfo[$row_tgtval["DSR_Code"].$row_tgtval["Product_id"]]			=	$row_tgtval;
	}
	//pre($transTotalProdTgtInfo);
	//exit;

	$i=0;
	foreach($transTotalProdInfo AS $val_tgt_prod) {
		//pre($val_tgt_prod);
		//echo $transTotalProdTgtInfo[$val_tgt_prod["DSR_Code"].$val_tgt_prod["Product_id"]][Product_id] ."===".	$val_tgt_prod[Product_id]."<br>";
		if($transTotalProdTgtInfo[$val_tgt_prod["DSR_Code"].$val_tgt_prod["Product_id"]]["Product_id"] == $val_tgt_prod[Product_id]) {		
			$finaltgtarray[$i]["DSRCode"] 							=   $val_tgt_prod["DSR_Code"];
			$finaltgtarray[$i]["Product_code"] 						=   $val_tgt_prod["Product_code"];
			$finaltgtarray[$i]["Product_id"] 						=   $val_tgt_prod["Product_id"];
			$finaltgtarray[$i]["SALQTY"] 							=   $val_tgt_prod["SALQTY"];
			$finaltgtarray[$i]["TGT_UNITS"] 						=   $transTotalProdTgtInfo[$val_tgt_prod["DSR_Code"].$val_tgt_prod["Product_id"]]["target_units"];
			$finaltgtarray[$i]["TGT_NAIRA"] 						=   $transTotalProdTgtInfo[$val_tgt_prod["DSR_Code"].$val_tgt_prod["Product_id"]]["target_naira"];

			$finaltgtarray[$i]["TOTAL_TGT_NAIRA"] 					+=   $finaltgtarray[$i]["TGT_UNITS"] * $finaltgtarray[$i]["TGT_NAIRA"];
			$finaltgtarray[$i]["TOTAL_ACT_NAIRA"] 					+=   $finaltgtarray[$i]["SALQTY"] * $finaltgtarray[$i]["TGT_NAIRA"];
			$i++;
		}
	}
	
	$transTotalProdInfo			=	$finaltgtarray;
	//pre($transTotalProdInfo);
	//exit;

	$totalTgtCost = multi_array_sum($transTotalProdInfo, 'TOTAL_TGT_NAIRA');
	$totalActNaira = multi_array_sum($transTotalProdInfo, 'TOTAL_ACT_NAIRA');
	$totalSalesValue = multi_array_sum($transTotalProdInfo, 'SALQTY');
	
	/*echo "<br>".$totalTgtCost;
	echo "<br>".$totalActNaira;
	echo "<br>".$totalSalesValue."<br>";
	exit;
	*/


	/*foreach($transTotalProdInfo AS $prodval) {
		$prodvalue[]	=	$prodval[Product_code];
		//pre($prodval);
	}

	$tofindtgt_prod				=	array_unique($prodvalue);
	$tofindtgt_Total			=	implode("','",$tofindtgt_prod);*/

	$i=0;
	foreach($finalSearchInfo as $val_merge){
		$finaltgtmergeInfo[$i]["DSRCode"]							=   $val_merge["DSRCode"];
		$finaltgtmergeInfo[$i]["ACTVISCNT"]							=   $val_merge["ACTVISCNT"];
		$finaltgtmergeInfo[$i]["ACTINVCNT"]							=   $val_merge["ACTINVCNT"];
		$finaltgtmergeInfo[$i]["EFFCNT"]							=   $val_merge["EFFCNT"];
		$finaltgtmergeInfo[$i]["PROCNT"]							=   $val_merge["PROCNT"];
		$finaltgtmergeInfo[$i]["ACTLINCNT"]							=   $val_merge["ACTLINCNT"];
		$finaltgtmergeInfo[$i]["ACTSALTOT"]							=   $val_merge["ACTSALTOT"];
		$finaltgtmergeInfo[$i]["ACTDROP"]							=   $val_merge["ACTDROP"];
		$finaltgtmergeInfo[$i]["ACTBAS"]							=   $val_merge["ACTBAS"];
		$finaltgtmergeInfo[$i]["TOTCUS"]							=   $val_merge["TOTCUS"];
		$finaltgtmergeInfo[$i]["TOTALHRS"]							=   $val_merge["TOTALHRS"];
		$finaltgtmergeInfo[$i]["TOTAL_TGT_NAIRA"]					=   $totalTgtCost;
		$finaltgtmergeInfo[$i]["TOTAL_ACT_NAIRA"]					=   $totalActNaira;
		$finaltgtmergeInfo[$i]["SALQTY"]							=   $totalSalesValue;
		$i++;
	}

	$finalSearchInfo			=	$finaltgtmergeInfo;
	//pre($finalSearchInfo);
	//exit;




	/*$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_tot){
		//echo $transNoInfo[$val_tot["DSRCode"]][DSR_Code] ."===".	$val_tot[DSRCode]."<br>";
		if($transNoInfo[$val_tot["DSRCode"]]["DSR_Code"] == $val_tot["DSRCode"]) {
			$finaltotInfo[$i]["DSRCode"]							=   $val_tot["DSRCode"];
			$finaltotInfo[$i]["ACTVISCNT"]							=   $val_tot["ACTVISCNT"];
			$finaltotInfo[$i]["ACTINVCNT"]							=   $val_tot["ACTINVCNT"];
			$finaltotInfo[$i]["EFFCNT"]								=   $val_tot["EFFCNT"];
			$finaltotInfo[$i]["PROCNT"]								=   $val_tot["PROCNT"];
			$finaltotInfo[$i]["ACTLINCNT"]							=   $val_tot["ACTLINCNT"];
			$finaltotInfo[$i]["ACTSALTOT"]							=   $val_tot["ACTSALTOT"];
			$finaltotInfo[$i]["ACTDROP"]							=   $val_tot["ACTDROP"];
			$finaltotInfo[$i]["ACTBAS"]								=   $val_tot["ACTBAS"];
			$finaltotInfo[$i]["TOTCUS"]								=   $val_tot["TOTCUS"];
			$finaltotInfo[$i]["TOTALHRS"]							=   $val_tot["TOTALHRS"];
			$finaltotInfo[$i]["TOTQTY"]								=   $transNoInfo[$val_tot["DSRCode"]]["SOLQTY"];
			$finaltotInfo[$i]["TOTSAL"]								=   $transNoInfo[$val_tot["DSRCode"]]["SALVAL"];
			$i++;
		//pre($val_transno);
		}
	}

	$finalSearchInfo				=		$finaltotInfo;
	//pre($finalSearchInfo);
	//exit;

	*/

	$query_focus										=   "SELECT KD_Code,DSR_Code,Product_code,Transaction_Number,SUM(Focus_Flag) AS TOTFOCLIN, COUNT(DISTINCT(Product_code)) AS TOTFOCPRO FROM transaction_line WHERE Transaction_Number IN ('".$transno_Total."') $prodcodecol AND Focus_Flag = 1 ORDER BY Product_code";
	//echo $query_focus;
	//exit;
	//$query_focus										=   "SELECT KD_Code,DSR_Code,Product_code,Transaction_Number,Sold_quantity AS SOLQTY FROM transaction_line WHERE POSM_Flag = '1' AND Transaction_Number IN ('".$transno_Total."') $prodcodecol ORDER BY Product_code";
	//echo $query_focus;
	//exit;
	$res_focus											=   mysql_query($query_focus);
	
	while($row_focus									=   mysql_fetch_assoc($res_focus)) {
		$transFocusInfo[$row_focus["DSR_Code"]]			=	$row_focus;
	}
	 
	//pre($transFocusInfo);
	//exit;

	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_focus){
		//echo $transFocusInfo[$val_focus["DSRCode"]][DSR_Code] ."===".	$val_focus[DSRCode]."<br>";
		if($transFocusInfo[$val_focus["DSRCode"]]["DSR_Code"] == $val_focus["DSRCode"]) {
			$finalfocusInfo[$i]["DSRCode"]								=   $val_focus["DSRCode"];
			$finalfocusInfo[$i]["ACTVISCNT"]							=   $val_focus["ACTVISCNT"];
			$finalfocusInfo[$i]["ACTINVCNT"]							=   $val_focus["ACTINVCNT"];
			$finalfocusInfo[$i]["EFFCNT"]								=   $val_focus["EFFCNT"];
			$finalfocusInfo[$i]["PROCNT"]								=   $val_focus["PROCNT"];
			$finalfocusInfo[$i]["ACTLINCNT"]							=   $val_focus["ACTLINCNT"];
			$finalfocusInfo[$i]["ACTSALTOT"]							=   $val_focus["ACTSALTOT"];
			$finalfocusInfo[$i]["ACTDROP"]								=   $val_focus["ACTDROP"];
			$finalfocusInfo[$i]["ACTBAS"]								=   $val_focus["ACTBAS"];
			$finalfocusInfo[$i]["TOTCUS"]								=   $val_focus["TOTCUS"];
			$finalfocusInfo[$i]["TOTALHRS"]								=   $val_focus["TOTALHRS"];
			$finalfocusInfo[$i]["TOTFOCLIN"]							=   $transFocusInfo[$val_focus["DSRCode"]]["TOTFOCLIN"];
			$finalfocusInfo[$i]["TOTFOCPRO"]							=   $transFocusInfo[$val_focus["DSRCode"]]["TOTFOCPRO"];
			$finalfocusInfo[$i]["TOTAL_TGT_NAIRA"]						=   $val_focus[TOTAL_TGT_NAIRA];
			$finalfocusInfo[$i]["TOTAL_ACT_NAIRA"]						=   $val_focus[TOTAL_ACT_NAIRA];
			$finalfocusInfo[$i]["SALQTY"]								=   $val_focus[SALQTY];
			$i++;
		//pre($val_transno);
		}
	}

	$finalSearchInfo				=		$finalfocusInfo;
	//pre($finalSearchInfo);
	//exit;

	//$query_focusact										=   "SELECT DSR_Code,SUM(replace(focus_Flag,'Yes','1')) AS FOCUS_ACT FROM dailystockloading $complete_query AND (focus_Flag ='Yes' OR focus_Flag ='yes' OR focus_Flag = 'YES')";
	$query_focusact										=   "SELECT DSR_Code,SUM(focus_Flag) AS FOCUS_ACT FROM dailystockloading $complete_query AND (focus_Flag ='1')";
	//echo $query_focusact;
	//exit;
	$res_focusact										=   mysql_query($query_focusact);
	while($row_focusact									=   mysql_fetch_assoc($res_focusact)) {
		$focusactInfo[$row_focusact[DSR_Code]]			=	$row_focusact;
	}

	//pre($focusactInfo);
	//exit;
	
	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_focusact){
		//echo $focusactInfo[$val_focusact["DSRCode"]][DSR_Code] ."===".	$val_focusact[DSRCode]."<br>";
		if($focusactInfo[$val_focusact["DSRCode"]]["DSR_Code"] == $val_focusact["DSRCode"]) {
			$finalfocusInfo[$i]["DSRCode"]								=   $val_focusact["DSRCode"];
			$finalfocusInfo[$i]["ACTVISCNT"]							=   $val_focusact["ACTVISCNT"];
			$finalfocusInfo[$i]["ACTINVCNT"]							=   $val_focusact["ACTINVCNT"];
			$finalfocusInfo[$i]["EFFCNT"]								=   $val_focusact["EFFCNT"];
			$finalfocusInfo[$i]["PROCNT"]								=   $val_focusact["PROCNT"];
			$finalfocusInfo[$i]["ACTLINCNT"]							=   $val_focusact["ACTLINCNT"];
			$finalfocusInfo[$i]["ACTSALTOT"]							=   $val_focusact["ACTSALTOT"];
			$finalfocusInfo[$i]["ACTDROP"]								=   $val_focusact["ACTDROP"];
			$finalfocusInfo[$i]["ACTBAS"]								=   $val_focusact["ACTBAS"];
			$finalfocusInfo[$i]["TOTCUS"]								=   $val_focusact["TOTCUS"];
			$finalfocusInfo[$i]["TOTALHRS"]								=   $val_focusact["TOTALHRS"];
			$finalfocusInfo[$i]["TOTFOCLIN"]							=   $val_focusact["TOTFOCLIN"];
			$finalfocusInfo[$i]["TOTFOCPRO"]							=   $val_focusact["TOTFOCPRO"];
			$finalfocusInfo[$i]["FOCUS_ACT"]							=   $focusactInfo[$val_focusact["DSRCode"]]["FOCUS_ACT"];
			$finalfocusInfo[$i]["TOTAL_TGT_NAIRA"]						=   $val_focusact[TOTAL_TGT_NAIRA];
			$finalfocusInfo[$i]["TOTAL_ACT_NAIRA"]						=   $val_focusact[TOTAL_ACT_NAIRA];
			$finalfocusInfo[$i]["ACH_PER"]								=   ceil(($finalfocusInfo[$i]["TOTAL_ACT_NAIRA"]/$finalfocusInfo[$i]["TOTAL_TGT_NAIRA"])*(100));
			$finalfocusInfo[$i]["SALQTY"]								=   $val_focusact[SALQTY];

			$finalfocusInfo[$i]["EFF_COV"]								=   ceil($finalSearchInfo[$i]["PROCNT"]/$finalSearchInfo[$i]["ACTVISCNT"]);
			$finalfocusInfo[$i]["PRO_COV"]								=   ceil($finalSearchInfo[$i]["ACTINVCNT"]/$finalSearchInfo[$i]["ACTVISCNT"]);
			$finalfocusInfo[$i]["FOCUS_COV"]							=   ceil($finalSearchInfo[$i]["TOTFOCLIN"]/$finalfocusInfo[$i]["FOCUS_ACT"]);
			$finalfocusInfo[$i]["FOCUS_EFF"]							=   ceil(($finalSearchInfo[$i]["TOTFOCLIN"]/$finalfocusInfo[$i]["FOCUS_ACT"])*(100));
			$i++;
		} else {
			$finalfocusInfo[$i]["DSRCode"]								=   $val_focusact["DSRCode"];
			$finalfocusInfo[$i]["ACTVISCNT"]							=   $val_focusact["ACTVISCNT"];
			$finalfocusInfo[$i]["ACTINVCNT"]							=   $val_focusact["ACTINVCNT"];
			$finalfocusInfo[$i]["EFFCNT"]								=   $val_focusact["EFFCNT"];
			$finalfocusInfo[$i]["PROCNT"]								=   $val_focusact["PROCNT"];
			$finalfocusInfo[$i]["ACTLINCNT"]							=   $val_focusact["ACTLINCNT"];
			$finalfocusInfo[$i]["ACTSALTOT"]							=   $val_focusact["ACTSALTOT"];
			$finalfocusInfo[$i]["ACTDROP"]								=   $val_focusact["ACTDROP"];
			$finalfocusInfo[$i]["ACTBAS"]								=   $val_focusact["ACTBAS"];
			$finalfocusInfo[$i]["TOTCUS"]								=   $val_focusact["TOTCUS"];
			$finalfocusInfo[$i]["TOTALHRS"]								=   $val_focusact["TOTALHRS"];
			$finalfocusInfo[$i]["TOTFOCLIN"]							=   $val_focusact["TOTFOCLIN"];
			$finalfocusInfo[$i]["TOTFOCPRO"]							=   $val_focusact["TOTFOCPRO"];
			$finalfocusInfo[$i]["FOCUS_ACT"]							=   0;
			$finalfocusInfo[$i]["TOTAL_TGT_NAIRA"]						=   $val_focusact[TOTAL_TGT_NAIRA];
			$finalfocusInfo[$i]["TOTAL_ACT_NAIRA"]						=   $val_focusact[TOTAL_ACT_NAIRA];
			$finalfocusInfo[$i]["ACH_PER"]								=   ceil(($finalfocusInfo[$i]["TOTAL_ACT_NAIRA"]/$finalfocusInfo[$i]["TOTAL_TGT_NAIRA"])*(100));
			$finalfocusInfo[$i]["SALQTY"]								=   $val_focusact[SALQTY];

			$finalfocusInfo[$i]["EFF_COV"]								=   ceil($finalSearchInfo[$i]["PROCNT"]/$finalSearchInfo[$i]["ACTVISCNT"]);
			$finalfocusInfo[$i]["PRO_COV"]								=   ceil($finalSearchInfo[$i]["ACTINVCNT"]/$finalSearchInfo[$i]["ACTVISCNT"]);	
			$finalfocusInfo[$i]["FOCUS_COV"]							=   0;
			$finalfocusInfo[$i]["FOCUS_EFF"]							=   0;
			$i++;
		}
	}

	$finalSearchInfo													=		$finalfocusInfo;
	//pre($finalSearchInfo);
	//exit;

	$query_tgtsetval													=   "SELECT KD_Code,SR_Code,coverage_percent,effective_percent,productive_percent,cov_visit,prod_visit,eff_visit,prod_status,eff_status FROM coverage_target_setting WHERE SR_Code = '$srcode' AND monthval = '$trimmedmonths' AND yearval = '$propyears'";
	//echo $query_tgtsetval;
	//exit;
	$res_tgtsetval														=   mysql_query($query_tgtsetval);

	while($row_tgtsetval												=   mysql_fetch_assoc($res_tgtsetval)) {
		$transTotalProdTgtSetInfo[$row_tgtsetval["SR_Code"]]			=	$row_tgtsetval;
	}
	//pre($transTotalProdTgtSetInfo);
	//exit;

	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_tgtset){
		//echo $transTotalProdTgtSetInfo[$val_tgtset["DSRCode"]][SR_Code] ."===".	$val_tgtset[DSRCode]."<br>";
		if($transTotalProdTgtSetInfo[$val_tgtset["DSRCode"]]["SR_Code"] == $val_tgtset["DSRCode"]) {
			$finaltgtsetInfo[$i]["DSRCode"]								=   $val_tgtset["DSRCode"];
			$finaltgtsetInfo[$i]["ACTVISCNT"]							=   $val_tgtset["ACTVISCNT"];
			$finaltgtsetInfo[$i]["ACTINVCNT"]							=   $val_tgtset["ACTINVCNT"];
			$finaltgtsetInfo[$i]["EFFCNT"]								=   $val_tgtset["EFFCNT"];
			$finaltgtsetInfo[$i]["PROCNT"]								=   $val_tgtset["PROCNT"];
			$finaltgtsetInfo[$i]["ACTLINCNT"]							=   $val_tgtset["ACTLINCNT"];
			$finaltgtsetInfo[$i]["ACTSALTOT"]							=   $val_tgtset["ACTSALTOT"];
			$finaltgtsetInfo[$i]["ACTDROP"]								=   $val_tgtset["ACTDROP"];
			$finaltgtsetInfo[$i]["ACTBAS"]								=   $val_tgtset["ACTBAS"];
			$finaltgtsetInfo[$i]["TOTCUS"]								=   $val_tgtset["TOTCUS"];
			$finaltgtsetInfo[$i]["TOTALHRS"]							=   $val_tgtset["TOTALHRS"];
			$finaltgtsetInfo[$i]["TOTFOCLIN"]							=   $val_tgtset["TOTFOCLIN"];
			$finaltgtsetInfo[$i]["TOTFOCPRO"]							=   $val_tgtset["TOTFOCPRO"];
			$finaltgtsetInfo[$i]["FOCUS_ACT"]							=   $val_tgtset["FOCUS_ACT"];
			$finaltgtsetInfo[$i]["TOTAL_TGT_NAIRA"]						=   $val_tgtset[TOTAL_TGT_NAIRA];
			$finaltgtsetInfo[$i]["TOTAL_ACT_NAIRA"]						=   $val_tgtset[TOTAL_ACT_NAIRA];
			$finaltgtsetInfo[$i]["ACH_PER"]								=   $val_tgtset[ACH_PER];
			$finaltgtsetInfo[$i]["SALQTY"]								=   $val_tgtset[SALQTY];
			$finaltgtsetInfo[$i]["EFF_COV"]								=   $val_tgtset[EFF_COV];
			$finaltgtsetInfo[$i]["PRO_COV"]								=   $val_tgtset[PRO_COV];	
			$finaltgtsetInfo[$i]["FOCUS_COV"]							=   $val_tgtset[FOCUS_COV];
			$finaltgtsetInfo[$i]["FOCUS_EFF"]							=   $val_tgtset[FOCUS_EFF];
			$finaltgtsetInfo[$i]["PRO_INC"]								=   $transTotalProdTgtSetInfo[$val_tgtset["DSRCode"]][prod_visit];	
			$finaltgtsetInfo[$i]["EFF_INC"]								=   $transTotalProdTgtSetInfo[$val_tgtset["DSRCode"]][eff_visit];
			
			$finaltgtsetInfo[$i]["COV_INC"]								=   $transTotalProdTgtSetInfo[$val_tgtset["DSRCode"]][cov_visit];
			$finaltgtsetInfo[$i]["ECO_INC"]								=   ($finaltgtsetInfo[$i]["EFF_COV"])*($finaltgtsetInfo[$i]["EFF_INC"]);
			$i++;
		}
	}

	$finalSearchInfo				=		$finaltgtsetInfo;
	//pre($finalSearchInfo);
	//exit;

	//ALL METRICS ENDS HERE




	//POSM COVERAGE STARTS HERE


	$query_trans										=   "SELECT KD_Code,DSR_Code,Product_code,Transaction_Number,Sold_quantity AS SOLQTY FROM transaction_line WHERE POSM_Flag = '1' AND Transaction_Number IN ('".$transno_Total."') $prodcodecol ORDER BY Product_code";
	//echo $query_trans;
	//exit;
	$res_trans											=   mysql_query($query_trans);

	while($row_trans									=   mysql_fetch_assoc($res_trans)) {
		//$transAllInfo[$row_trans["Transaction_Number"]]=	$row_trans;
		$transno_trans[]								=	$row_trans["Transaction_Number"];
		$kdcode_trans[]									=	$row_trans["KD_Code"];
		$dsrcode_trans[]								=	$row_trans["DSR_Code"];
		$prodcode_trans[]								=	$row_trans["Product_code"];
		$transAllDetInfo[]								=	$row_trans;
		$transNoInfo[$row_trans["Transaction_Number"].$row_trans["Product_code"]]	=	$row_trans;
	}
	 
	//echo count($transInfo)."jungle";
	$transno_trans		=	array_unique($transno_trans);
	$transno_Total		=	implode("','",$transno_trans);

	$kdcode_trans		=	array_unique($kdcode_trans);
	$kdcodes_Total		=	implode("','",$kdcode_trans);

	$dsrcode_trans		=	array_unique($dsrcode_trans);
	$dsrcodes_Total		=	implode("','",$dsrcode_trans);

	$prodcode_trans		=	array_unique($prodcode_trans);
	$prodcode_Total		=	implode("','",$prodcode_trans);

	$product_countcheck	=	count($prodcode_trans);

	//pre($transInfo);
	//exit;
	
	//pre($finalAllProdInfo);
	//pre($transNoInfo);
	//exit;

	$i=0;
	$k=0;
	foreach($transNoInfo as $val_transno){
		if($transhdrInfo[$val_transno["Transaction_Number"]]["Transaction_Number"] == $val_transno["Transaction_Number"]) {
			$finaltranslineInfo[$i]["DSRCode"]						=   $val_transno["DSR_Code"];
			$finaltranslineInfo[$i]["Product_code"]					=   $val_transno["Product_code"];
			$finaltranslineInfo[$i]["KD_Code"]						=   $val_transno["KD_Code"];
			$finaltranslineInfo[$i]["CUS_CODE"]						=   $transhdrInfo[$val_transno["Transaction_Number"]]["Customer_code"];
			$finaltranslineInfo[$i]["SOLQTY"]						=   $val_transno["SOLQTY"];
			$finaltranslineInfo[$i]["TRANSNO"]						=   $val_transno["Transaction_Number"];
			$finaltranslineInfo[$i]["CUSCNT"]						=   1;
			$i++;
		//pre($val_transno);
		}
	}

	$finalAllProdInfo				=		$finaltranslineInfo;
	//pre($finalAllProdInfo);
	//exit;

	$orderbycolumns     =   'Product_code';
	$orderbysorting     =   'ASC';

	if($orderbysorting == 'DESC') {
		$dir        =   'arsort';               
	} else {
		$dir        =   'asort';   
	}
	$finalAllProdInfo	=	subval_sort($finalAllProdInfo,$orderbycolumns,$dir);

	$y			=	0;
	foreach($finalAllProdInfo AS $key=>$value) {
		$DSRCode											=	$value[DSRCode];
		$Product_code										=	$value[Product_code];
		$KD_Code											=	$value[KD_Code];
		$check[$DSRCode.$Product_code.$KD_Code]				=	$DSRCode.$Product_code.$KD_Code;

		//echo $check[$DSRCode.$Product_code.$KD_Code] ." == ". $checkagain[$DSRCode.$Product_code.$KD_Code]."<br>";

		if(($check[$DSRCode.$Product_code.$KD_Code] == $checkagain[$DSRCode.$Product_code.$KD_Code]) && ($check[$DSRCode.$Product_code.$KD_Code] != '' &&  $checkagain[$DSRCode.$Product_code.$KD_Code] != '')) {			
			$Sold_Qty[$DSRCode.$Product_code.$KD_Code]		+=	$value[SOLQTY];
			$VALUE_NAIRA[$DSRCode.$Product_code.$KD_Code]	+=	$value[CUSCNT];
			//$VALUE_NAIRA[$DSRCode.$Product_code.$KD_Code]	+=	$value[VALUE_NAIRA];

			//echo $Sold_Qty[$DSRCode.$Product_code.$KD_Code]."==". $DSRCode.$Product_code.$KD_Code. "<br>";
			//echo $VALUE_NAIRA[$DSRCode.$Product_code.$KD_Code]."==". $DSRCode.$Product_code.$KD_Code. "<br>";

			//echo $gettingi[$DSRCode.$Product_code.$KD_Code]-1;
			//echo $Sold_Qty[$DSRCode.$Product_code.$KD_Code];
			$finalsumInfo[$gettingi[$DSRCode.$Product_code.$KD_Code]]["SOLQTY"]				=   $Sold_Qty[$DSRCode.$Product_code.$KD_Code];
			$finalsumInfo[$gettingi[$DSRCode.$Product_code.$KD_Code]]["CUSCNT"]				=   $VALUE_NAIRA[$DSRCode.$Product_code.$KD_Code];
			//$finalsumInfo[$gettingi[$DSRCode.$Product_code.$KD_Code]]["VALUE_NAIRA"]			=   $VALUE_NAIRA[$DSRCode.$Product_code.$KD_Code];
		} else {
			$finalsumInfo[$i]["Product_code"]				=   $value["Product_code"];
			//$finalsumInfo[$i]["CUS_CODE"]					=   $value["Product_Id"];
			$finalsumInfo[$i]["DSRCode"]					=   $value["DSRCode"];
			$finalsumInfo[$i]["KD_Code"]					=   $value["KD_Code"];
			$finalsumInfo[$i]["SOLQTY"]						=   $value["SOLQTY"];
			$finalsumInfo[$i]["CUS_CODE"]					=   $value["CUS_CODE"];
			$finalsumInfo[$i]["TRANSNO"]					=   $value["TRANSNO"];
			$finalsumInfo[$i]["CUSCNT"]						=   $value["CUSCNT"];
			$Sold_Qty[$DSRCode.$Product_code.$KD_Code]		+=	$value[SOLQTY];
			$VALUE_NAIRA[$DSRCode.$Product_code.$KD_Code]	+=	$value[CUSCNT];
			//$VALUE_NAIRA[$DSRCode.$Product_code.$KD_Code]		+=	$value[VALUE_NAIRA];
			$gettingi[$DSRCode.$Product_code.$KD_Code]		=	$i;
			$i++;
			$checkagain[$DSRCode.$Product_code.$KD_Code]	=	$check[$DSRCode.$Product_code.$KD_Code];
		}
		$cuscodeInfo[]										=   $value["CUS_CODE"];
	}

	//pre($Sold_Qty);
	//pre($VALUE_NAIRA);
	//pre($gettingi);
	//pre($finalsumInfo);
	//pre($cuscodeInfo);

	$cuscodeInfo		=	array_unique($cuscodeInfo);
	$cuscode_Total		=	implode("','",$cuscodeInfo);

	//exit;
	$finalAllProdInfo			=	$finalsumInfo;
	//pre($finalAllProdInfo);
	//exit;
	
	$query_transcustype									=   "SELECT customer_type,customer_code FROM customer WHERE customer_code IN ('".$cuscode_Total."')";
	//echo $query_transcustype;
	//exit;
	$res_transcustype									=   mysql_query($query_transcustype);

	while($row_transcustype								=   mysql_fetch_assoc($res_transcustype)) {
		//$transAllInfo[$row_trans["Transaction_Number"]]=	$row_trans;
		$transCusTypeInfo[$row_transcustype[customer_code]]								=	$row_transcustype;
		$transCusTypeVal[]																=	$row_transcustype[customer_type];
	}
	
	$transCusTypeVal		=	array_unique($transCusTypeVal);
	$custype_Total			=	implode("','",$transCusTypeVal);

	//pre($transCusTypeInfo);
	//exit;

	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_transno){
		if($transCusTypeInfo[$val_transno["CUS_CODE"]]["customer_code"] == $val_transno["CUS_CODE"]) {
			
			if($custypeval != '') {
				if(array_search($transCusTypeInfo[$val_transno["CUS_CODE"]]["customer_type"],$custypeval) !== false) {
					$finalcusTypeInfo[$i]["Product_code"]				=   $val_transno["Product_code"];
					//$finalcusTypeInfo[$i]["CUS_CODE"]					=   $val_transno["Product_Id"];
					$finalcusTypeInfo[$i]["DSRCode"]					=   $val_transno["DSRCode"];
					$finalcusTypeInfo[$i]["KD_Code"]					=   $val_transno["KD_Code"];
					$finalcusTypeInfo[$i]["SOLQTY"]						=   $val_transno["SOLQTY"];
					$finalcusTypeInfo[$i]["CUS_CODE"]					=   $val_transno["CUS_CODE"];
					$finalcusTypeInfo[$i]["TRANSNO"]					=   $val_transno["TRANSNO"];
					$finalcusTypeInfo[$i]["CUSCNT"]						=   $val_transno["CUSCNT"];
					$finalcusTypeInfo[$i]["CUSTYP"]						=   $transCusTypeInfo[$val_transno["CUS_CODE"]]["customer_type"];
				} else {
					
				}
			} else {
				$finalcusTypeInfo[$i]["Product_code"]				=   $val_transno["Product_code"];
				//$finalcusTypeInfo[$i]["CUS_CODE"]					=   $val_transno["Product_Id"];
				$finalcusTypeInfo[$i]["DSRCode"]					=   $val_transno["DSRCode"];
				$finalcusTypeInfo[$i]["KD_Code"]					=   $val_transno["KD_Code"];
				$finalcusTypeInfo[$i]["SOLQTY"]						=   $val_transno["SOLQTY"];
				$finalcusTypeInfo[$i]["CUS_CODE"]					=   $val_transno["CUS_CODE"];
				$finalcusTypeInfo[$i]["TRANSNO"]					=   $val_transno["TRANSNO"];
				$finalcusTypeInfo[$i]["CUSCNT"]						=   $val_transno["CUSCNT"];
				$finalcusTypeInfo[$i]["CUSTYP"]						=   $transCusTypeInfo[$val_transno["CUS_CODE"]]["customer_type"];
			}
			$i++;
		}
	}

	$finalAllProdInfo				=		$finalcusTypeInfo;
	//pre($finalAllProdInfo);
	//exit;




	/*$query_kd										=   "SELECT KD_Name,KD_Code FROM kd WHERE KD_Code IN ('".$kdcodes_Total."')";
	$res_kd											=   mysql_query($query_kd);
	while($row_kd									=   mysql_fetch_assoc($res_kd)) {
		$kdInfo[$row_kd["KD_Code"]]					=	$row_kd;
	}
	 
	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_kd){
		//$transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
		if($kdInfo[$val_kd["KD_Code"]]["KD_Code"] == $val_kd[KD_Code]) { 
			
			$finalkdInfo[$i]["KD_Name"]						=   $kdInfo[$val_kd["KD_Code"]]["KD_Name"];
			$finalkdInfo[$i]["Product_code"]				=   $val_kd["Product_code"];
			//$finalkdInfo[$i]["CUS_CODE"]					=   $val_kd["Product_Id"];
			$finalkdInfo[$i]["DSRCode"]						=   $val_kd["DSRCode"];
			$finalkdInfo[$i]["KD_Code"]						=   $val_kd["KD_Code"];
			$finalkdInfo[$i]["SOLQTY"]						=   $val_kd["SOLQTY"];
			$finalkdInfo[$i]["CUS_CODE"]					=   $val_kd["CUS_CODE"];
			$finalkdInfo[$i]["TRANSNO"]						=   $val_kd["TRANSNO"];
			$finalkdInfo[$i]["CUSCNT"]						=   $val_kd["CUSCNT"];
			$finalkdInfo[$i]["CUSTYP"]						=   $val_kd["CUSTYP"];
			$i++;
		}
		$k++;
	}

	$finalAllProdInfo          =   $finalkdInfo;
	//pre($finalAllProdInfo);
	//exit;

//


	$query_dsr										=   "SELECT DSRName,DSR_Code FROM dsr WHERE DSR_Code IN ('".$dsrcodes_Total."')";
	$res_dsr										=   mysql_query($query_dsr);
	while($row_dsr									=   mysql_fetch_assoc($res_dsr)) {
		$dsrInfo[$row_dsr["DSR_Code"]]				=	$row_dsr;
	}
	
	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_dsr){
		//echo $dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] . "-". $val_dsr["DSRCode"]."<br>";
		if($dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] == $val_dsr["DSRCode"]) { 
			
			$finaldsrInfo[$i]["DSR_Name"]					=   $dsrInfo[$val_dsr["DSRCode"]]["DSRName"];
			$finaldsrInfo[$i]["KD_Name"]					=   $val_dsr["KD_Name"];
			$finaldsrInfo[$i]["Product_code"]				=   $val_dsr["Product_code"];
			//$finaldsrInfo[$i]["CUS_CODE"]					=   $val_dsr["Product_Id"];
			$finaldsrInfo[$i]["DSRCode"]					=   $val_dsr["DSRCode"];
			$finaldsrInfo[$i]["KD_Code"]					=   $val_dsr["KD_Code"];
			$finaldsrInfo[$i]["SOLQTY"]						=   $val_dsr["SOLQTY"];
			$finaldsrInfo[$i]["CUS_CODE"]					=   $val_dsr["CUS_CODE"];
			$finaldsrInfo[$i]["TRANSNO"]					=   $val_dsr["TRANSNO"];
			$finaldsrInfo[$i]["CUSCNT"]						=   $val_dsr["CUSCNT"];
			$finaldsrInfo[$i]["CUSTYP"]						=   $val_dsr["CUSTYP"];
			$i++;
		}
		$k++;
	}

	$finalAllProdInfo          =   $finaldsrInfo;
	//pre($finalAllProdInfo);
	//exit;
*/

	$query_prod										=   "SELECT id,Product_code FROM product WHERE Product_code IN ('".$prodcode_Total."')";
	$res_prod										=   mysql_query($query_prod);
	while($row_prod									=   mysql_fetch_assoc($res_prod)) {
		$prodInfo[$row_prod["Product_code"]]		=	$row_prod;
	}
	
	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_prod){
		//$transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
		if($prodInfo[$val_prod["Product_code"]]["Product_code"] == $val_prod["Product_code"]) {			
			$finalprodInfo[$i]["Product_Id"]				=   $prodInfo[$val_prod["Product_code"]]["id"];
			$finalprodInfo[$i]["Product_code"]				=   $val_prod["Product_code"];
			$finalprodInfo[$i]["DSRCode"]					=   $val_prod["DSRCode"];
			$finalprodInfo[$i]["KD_Code"]					=   $val_prod["KD_Code"];
			$finalprodInfo[$i]["SOLQTY"]					=   $val_prod["SOLQTY"];
			$finalprodInfo[$i]["CUS_CODE"]					=   $val_prod["CUS_CODE"];
			$finalprodInfo[$i]["TRANSNO"]					=   $val_prod["TRANSNO"];
			$finalprodInfo[$i]["CUSCNT"]					=   $val_prod["CUSCNT"];
			$finalprodInfo[$i]["CUSTYP"]					=   $val_prod["CUSTYP"];						
		} else {
			$finalprodInfo[$i]["Product_code"]				=   $val_prod["Product_code"];
			$finalprodInfo[$i]["DSRCode"]					=   $val_prod["DSRCode"];
			$finalprodInfo[$i]["KD_Code"]					=   $val_prod["KD_Code"];
			$finalprodInfo[$i]["SOLQTY"]					=   $val_prod["SOLQTY"];
			$finalprodInfo[$i]["CUS_CODE"]					=   $val_prod["CUS_CODvE"];
			$finalprodInfo[$i]["TRANSNO"]					=   $val_prod["TRANSNO"];
			$finalprodInfo[$i]["CUSCNT"]					=   $val_prod["CUSCNT"];
			$finalprodInfo[$i]["CUSTYP"]					=   $val_prod["CUSTYP"];						
		}
		$i++;
		$k++;
	}

	$finalAllProdInfo          =   $finalprodInfo;
	//pre($finalAllProdInfo);
	//exit;




	$query_prod										=   "SELECT Product_id,Product_code FROM customertype_product WHERE Product_code IN ('".$prodcode_Total."')";
	$res_prod										=   mysql_query($query_prod);
	while($row_prod									=   mysql_fetch_assoc($res_prod)) {
		$prodInfoAnot[$row_prod["Product_code"]]		=	$row_prod;
	}
	
	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_prod){
		//$transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
		if($prodInfoAnot[$val_prod["Product_code"]]["Product_code"] == $val_prod["Product_code"]) {
			
			$finalprodInfo[$i]["Product_Id"]				=   $prodInfoAnot[$val_prod["Product_code"]]["Product_id"];
			$finalprodInfo[$i]["Product_code"]				=   $val_prod["Product_code"];
			$finalprodInfo[$i]["DSRCode"]					=   $val_prod["DSRCode"];
			$finalprodInfo[$i]["KD_Code"]					=   $val_prod["KD_Code"];
			$finalprodInfo[$i]["SOLQTY"]					=   $val_prod["SOLQTY"];
			$finalprodInfo[$i]["CUS_CODE"]					=   $val_prod["CUS_CODE"];
			$finalprodInfo[$i]["TRANSNO"]					=   $val_prod["TRANSNO"];
			$finalprodInfo[$i]["CUSCNT"]					=   $val_prod["CUSCNT"];
			$finalprodInfo[$i]["CUSTYP"]					=   $val_prod["CUSTYP"];						
		} else {
			
			$finalprodInfo[$i]["Product_Id"]				=   $val_prod["Product_Id"];
			$finalprodInfo[$i]["Product_code"]				=   $val_prod["Product_code"];
			$finalprodInfo[$i]["DSRCode"]					=   $val_prod["DSRCode"];
			$finalprodInfo[$i]["KD_Code"]					=   $val_prod["KD_Code"];
			$finalprodInfo[$i]["SOLQTY"]					=   $val_prod["SOLQTY"];
			$finalprodInfo[$i]["CUS_CODE"]					=   $val_prod["CUS_CODE"];
			$finalprodInfo[$i]["TRANSNO"]					=   $val_prod["TRANSNO"];
			$finalprodInfo[$i]["CUSCNT"]					=   $val_prod["CUSCNT"];
			$finalprodInfo[$i]["CUSTYP"]					=   $val_prod["CUSTYP"];						
		}
		$i++;
		$k++;
	}

	$finalAllProdInfo          =   $finalprodInfo;
	//pre($finalAllProdInfo);
	//exit;

	/*
	$query_brand									=   "SELECT id,brand FROM brand WHERE id IN ('".$brandid_Total."')";
	$res_brand										=   mysql_query($query_brand);
	while($row_brand								=   mysql_fetch_assoc($res_brand)) {
		$brandInfo[$row_brand["id"]]				=	$row_brand;
	}

	//pre($rsmInfo);
	//exit;
	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_brand){
		//echo $rsmInfo[$val_rsm["RSM_Code"]]["id"] . "-". $val_rsm["RSM_Code"]."<br>";
		if($brandInfo[$val_brand["Brand_Id"]]["id"] == $val_brand["Brand_Id"]) {       
			
			$finalbrandInfo[$i]["Brand_Id"]					=   $val_brand["Brand_Id"];
			$finalbrandInfo[$i]["Brand_Name"]				=   $brandInfo[$val_brand["Brand_Id"]]["brand"];
			$finalbrandInfo[$i]["Product_Name"]				=   $val_brand["Product_Name"];
			$finalbrandInfo[$i]["Product_Id"]				=   $val_brand["Product_Id"];
			$finalbrandInfo[$i]["Product_code"]				=   $val_brand["Product_code"];
			$finalbrandInfo[$i]["Principal_Id"]				=   $val_brand["Principal_Id"];
			$finalbrandInfo[$i]["DSR_Name"]					=   $val_brand["DSR_Name"];
			$finalbrandInfo[$i]["DSRCode"]					=   $val_brand["DSRCode"];
			$finalbrandInfo[$i]["KD_Name"]					=   $val_brand["KD_Name"];
			$finalbrandInfo[$i]["KD_Code"]					=   $val_brand["KD_Code"];
			//$finalbrandInfo[$i]["CUS_CODE"]				=   $val_brand["Product_Id"];						
			$finalbrandInfo[$i]["SOLQTY"]					=   $val_brand["SOLQTY"];
			$finalbrandInfo[$i]["CUS_CODE"]					=   $val_brand["CUS_CODE"];
			$finalbrandInfo[$i]["TRANSNO"]					=   $val_brand["TRANSNO"];
			$finalbrandInfo[$i]["CUSCNT"]					=   $val_brand["CUSCNT"];
			$finalbrandInfo[$i]["CUSTYP"]					=   $val_brand["CUSTYP"];
			$i++;
		}
		$k++;
	}

	$finalAllProdInfo          =   $finalbrandInfo;
	//pre($finalAllProdInfo);
	//exit;	

//


	$query_princ									=   "SELECT id,principal FROM principal WHERE id IN ('".$principalid_Total."')";
	//echo $query_princ;
	//exit;
	$res_princ										=   mysql_query($query_princ);
	while($row_princ								=   mysql_fetch_assoc($res_princ)) {
		$princInfo[$row_princ["id"]]				=	$row_princ;
	}

	//pre($princInfo);
	//exit;
	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_princ){
		//echo $princInfo[$val_princ["Principal_Id"]]["id"] . "-". $val_princ["Principal_Id"]."<br>";
		if($princInfo[$val_princ["Principal_Id"]]["id"] == $val_princ["Principal_Id"]) {       
			
			$finalprincInfo[$i]["Brand_Id"]					=   $val_princ["Brand_Id"];
			$finalprincInfo[$i]["Brand_Name"]				=   $val_princ["Brand_Name"];
			$finalprincInfo[$i]["Product_Name"]				=   $val_princ["Product_Name"];
			$finalprincInfo[$i]["Product_Id"]				=   $val_princ["Product_Id"];
			$finalprincInfo[$i]["Product_code"]				=   $val_princ["Product_code"];
			$finalprincInfo[$i]["Principal_Id"]				=   $val_princ["Principal_Id"];
			$finalprincInfo[$i]["Principal_Name"]			=   $princInfo[$val_princ["Principal_Id"]]["principal"];
			$finalprincInfo[$i]["DSR_Name"]					=   $val_princ["DSR_Name"];
			$finalprincInfo[$i]["DSRCode"]					=   $val_princ["DSRCode"];
			$finalprincInfo[$i]["KD_Name"]					=   $val_princ["KD_Name"];
			$finalprincInfo[$i]["KD_Code"]					=   $val_princ["KD_Code"];
			//$finalprincInfo[$i]["CUS_CODE"]				=   $val_princ["Product_Id"];						
			$finalprincInfo[$i]["SOLQTY"]					=   $val_princ["SOLQTY"];
			$finalprincInfo[$i]["CUS_CODE"]					=   $val_princ["CUS_CODE"];
			$finalprincInfo[$i]["TRANSNO"]					=   $val_princ["TRANSNO"];
			$finalprincInfo[$i]["CUSCNT"]					=   $val_princ["CUSCNT"];
			$finalprincInfo[$i]["CUSTYP"]					=   $val_princ["CUSTYP"];
			$i++;
		}
		$k++;
	}

	$finalAllProdInfo          =   $finalprincInfo;
	//pre($finalAllProdInfo);
	//exit;	
	

	*/
	
	$orderbycolumns     =   'Product_Id';
	$orderbysorting     =   'ASC';

	if($orderbysorting == 'DESC') {
		$dir        =   'arsort';               
	} else {
		$dir        =   'asort';   
	}
	$finalAllProdInfo	=	subval_sort($finalAllProdInfo,$orderbycolumns,$dir);
	//pre($finalAllProdInfo);
	//exit;

	//echo $target_query;
	//exit;
	$query_target										=   "SELECT KD_Code,productId,noofcus,unitval FROM posmtarget $target_query ORDER BY productId";
	//echo $query_target;
	//exit;
	$res_target											=   mysql_query($query_target);
	while($row_target									=	mysql_fetch_assoc($res_target)) {
		$Product_id										=	$row_target[productId];
		$KD_Code										=	$row_target[KD_Code];
		$targetNocus[$Product_id.$KD_Code]["NOCUS"]		=	$row_target["noofcus"];
		$targetUnits[$Product_id.$KD_Code]["UNITVAL"]	=	$row_target["unitval"];
		$targetInfo[$Product_id.$KD_Code]				=	$Product_id.$KD_Code;
	}

	//pre($targetInfo);
	//pre($finalSearchInfo);
	//exit;
	$i=0;
	foreach($finalAllProdInfo as $val_target)	{
		$PRODUCT_ID			=	$val_target["Product_Id"];
		$KD_CODE			=	$val_target["KD_Code"];

		$INDEX_VAL			=	$PRODUCT_ID.$KD_CODE;
		//echo	$targetInfo[$INDEX_VAL]	. "==".	$INDEX_VAL."<br>"; 
		if($targetInfo[$INDEX_VAL]	==	$INDEX_VAL) {
			$finalAllProdInfo[$i]["UNITVAL"]				=   $targetUnits[$INDEX_VAL]["UNITVAL"];
			$finalAllProdInfo[$i]["NOCUS"]					=   $targetNocus[$INDEX_VAL]["NOCUS"];
			$finalAllProdInfo[$i]["NOTCOV"]					=   $finalAllProdInfo[$i]["NOCUS"] - $finalAllProdInfo[$i]["CUSCNT"];
			$finalAllProdInfo[$i]["SHORTUTS"]				=   $finalAllProdInfo[$i]["UNITVAL"] - $finalAllProdInfo[$i]["SOLQTY"];
			$finalAllProdInfo[$i]["CUSPER"]					=   round(($finalAllProdInfo[$i]["CUSCNT"]/$finalAllProdInfo[$i]["NOCUS"])*(100));
			$finalAllProdInfo[$i]["UNIPER"]					=   round(($finalAllProdInfo[$i]["SOLQTY"]/$finalAllProdInfo[$i]["UNITVAL"])*(100));
		}
		$i++;
	}
	//pre($finalAllProdInfo);
	//exit;


	$query_transcustypeval									=   "SELECT customer_type,id FROM customer_type WHERE id IN ('".$custype_Total."')";
	//echo $query_transcustype;
	//exit;
	$res_transcustypeval									=   mysql_query($query_transcustypeval);

	while($row_transcustypeval								=   mysql_fetch_assoc($res_transcustypeval)) {
		$transCusTypeValInfo[$row_transcustypeval[id]]		=	$row_transcustypeval;
	}
	
	//pre($transCusTypeValInfo);
	//exit;

	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_custypeval){
		//echo	$transCusTypeValInfo[$val_custypeval["CUSTYP"]]["id"] ."==". $val_custypeval["CUSTYP"]."<br>";
		if($transCusTypeValInfo[$val_custypeval["CUSTYP"]]["id"] == $val_custypeval["CUSTYP"]) {						
			$finalcusTypeValInfo[$i]["Product_Id"]				=   $val_custypeval["Product_Id"];
			$finalcusTypeValInfo[$i]["Product_code"]			=   $val_custypeval["Product_code"];
			$finalcusTypeValInfo[$i]["DSRCode"]					=   $val_custypeval["DSRCode"];
			$finalcusTypeValInfo[$i]["KD_Code"]					=   $val_custypeval["KD_Code"];
			$finalcusTypeValInfo[$i]["SOLQTY"]					=   $val_custypeval["SOLQTY"];
			$finalcusTypeValInfo[$i]["CUS_CODE"]				=   $val_custypeval["CUS_CODE"];
			$finalcusTypeValInfo[$i]["TRANSNO"]					=   $val_custypeval["TRANSNO"];
			$finalcusTypeValInfo[$i]["CUSCNT"]					=   $val_custypeval["CUSCNT"];
			$finalcusTypeValInfo[$i]["CUSTYP"]					=   $val_custypeval["CUSTYP"];
			$finalcusTypeValInfo[$i]["UNITVAL"]					=   $val_custypeval["UNITVAL"];
			$finalcusTypeValInfo[$i]["NOCUS"]					=   $val_custypeval["NOCUS"];
			$finalcusTypeValInfo[$i]["NOTCOV"]					=   $val_custypeval["NOTCOV"];
			$finalcusTypeValInfo[$i]["SHORTUTS"]				=   $val_custypeval["SHORTUTS"];
			$finalcusTypeValInfo[$i]["CUSPER"]					=   $val_custypeval["CUSPER"];
			$finalcusTypeValInfo[$i]["UNIPER"]					=   $val_custypeval["UNIPER"];
			$finalcusTypeValInfo[$i]["CUSTYPVAL"]				=   $transCusTypeValInfo[$val_custypeval["CUSTYP"]]["customer_type"];
		$i++;
		}			
	}

	$finalAllProdInfo				=		$finalcusTypeValInfo;
	//pre($finalAllProdInfo);
	//exit;
	

	$totalActCus						=	multi_array_sum($finalAllProdInfo, 'CUSCNT');
	$totalTgtCus						=	multi_array_sum($finalAllProdInfo, 'NOCUS');
	$totalActSalvalue					=	multi_array_sum($finalAllProdInfo, 'SOLQTY');
	$totalTgtSalvalueSalesValue			=	multi_array_sum($finalAllProdInfo, 'UNITVAL');

	//POSM COVERAGE ENDS HERE



	//pre($finalSearchInfo);
	//exit;

	/*$orderbycolumns     =   $reportby;
	$orderbysorting     =   'ASC';

	if($orderbysorting == 'DESC') {
		$dir        =   'arsort';               
	} else {
		$dir        =   'asort';   
	}
	$finalSearchInfo	=	subval_sort($finalSearchInfo,$orderbycolumns,$dir);

	*/

	//pre($finalSearchInfo);
	//exit;
$arrcnt				=	count($finalSearchInfo);
$arrallProd			=	count($finalAllProdInfo);
if($arrcnt > 0) {
	foreach($finalSearchInfo AS $ResultVal) {
	?>	 
      <table border="1" width="100%" bgcolor="#CCCCCC">
	  <tr>
	    <td>
	  <table border="1" width="100%" bgcolor="#CCCCCC"> <!--First Table-->
				<thead>
				  <tr>
					<th align="center" style="width:50%">Sales(Naira)</th>
					<th align="center" style="width:50%">Customer Coverage</th>
			  </tr>
			  </thead>
			 <tbody>
			 <tr> <!--First TR Starts-->
			 <td style="background-color:#999"> <!-- Sale Starts-->
			 <table width="100%" bgcolor="#999999" height="80px">
			 <tr><td width="50%">Target</td><td><?php if($ResultVal[TOTAL_TGT_NAIRA] != '') { echo $ResultVal[TOTAL_TGT_NAIRA]; } else { echo "0"; } ?></td></tr>
			 <tr><td width="50%">Actual</td><td><?php if($ResultVal[TOTAL_ACT_NAIRA] != '') { echo $ResultVal[TOTAL_ACT_NAIRA]; } else { echo "0"; } ?></td></tr>
			 <tr><td width="50%">% Achievement</td><td><?php if($ResultVal[ACH_PER] != '') { echo $ResultVal[ACH_PER]; } else { echo "0"; } ?></td></tr>
			 </table>
			 </td> <!-- Sale Ends-->
			
			 <td> <!--customer coverage start-->
			 <table width="100%" bgcolor="#999999">
			 <tr>
			 <td align="center">Total Customers</td>
			 <td align="center">Customer Visits(MIN 1)</td>
			 <td align="center">Sale Customer</td>
			 <td align="center">Productivity</td>
			 <td align="center">ECO</td>
			 </tr>
			 <tr>
			 <td height="40px"><?php if($ResultVal[TOTCUS] != '') { echo $ResultVal[TOTCUS]; } else { echo "0"; } ?></td>
			 <td height="40px"><?php if($ResultVal[EFFCNT] != '') { echo $ResultVal[EFFCNT]; } else { echo "0"; } ?></td>
			 <td height="40px"><?php if($ResultVal[PROCNT] != '') { echo $ResultVal[PROCNT]; } else { echo "0"; } ?></td>
			 <td height="40px"><?php if($ResultVal[PRO_COV] != '') { echo $ResultVal[PRO_COV]; } else { echo "0"; } ?></td>
			 <td height="40px"><?php if($ResultVal[EFF_COV] != '') { echo $ResultVal[EFF_COV]; } else { echo "0"; } ?></td>
			 </tr>
			 </table>
			 </td>  <!--customer coverage start-->      
			</tr> <!--First TR End-->
			</tbody>
			</table>	
	  </td>
	</tr>

  <tr>
  <td>
  <table width="100%" bgcolor="#CCCCCC" border="1"> <!--Second Table-->
 		<thead>
			  <tr>
				<th align="center" style="width:25%">ITEM</th>
				<th align="center" style="width:25%">VALUE</th>
                <th align="center" style="width:50%">METRIC</th>
		  </tr>
		  </thead>

		<tbody>
			 <table width="50%" bgcolor="#999999" align="left">
			 <tr>
				 <td>Total Sale</td>
				 <td><?php if($ResultVal[ACTSALTOT] != '') { echo $ResultVal[ACTSALTOT]; } else { echo "0"; } ?></td>
			 </tr>
			 <tr>
				 <td>Total Lines</td>
				 <td><?php if($ResultVal[ACTLINCNT] != '') { echo $ResultVal[ACTLINCNT]; } else { echo "0"; } ?></td>
			 </tr>


			 <tr>
				 <td>Total Focus Products</td>
				 <td><?php if($ResultVal[TOTFOCPRO] != '') { echo $ResultVal[TOTFOCPRO]; } else { echo "0"; } ?></td>
			 </tr>
			 <tr>
				 <td>Total Focus Lines</td>
				 <td><?php if($ResultVal[TOTFOCLIN] != '') { echo $ResultVal[TOTFOCLIN]; } else { echo "0"; } ?></td>
			 </tr>
			 <tr>
				 <td>Total Checked In Hours</td>
				 <td><?php if($ResultVal[TOTALHRS] != '') { echo $ResultVal[TOTALHRS]; } else { echo "0"; } ?></td>
			 </tr>
		  </table>
		  
			<table width="50%" bgcolor="#999999" align="right"><!-- Metric Start Table-->
			 <tr>
				 <td align="center"><strong>Drop Size</strong></td>
				 <td align="center"><strong>Basket Size</strong></td>
				 <td align="center"><strong>Focus Coverage</strong></td>
				 <td align="center"><strong>Efficiency</strong></td>
			 </tr>
			 
			 <tr>
				 <td height="75px" align="center"><?php if($ResultVal[ACTDROP] != '') { echo $ResultVal[ACTDROP]; } else { echo "0"; } ?></td>
				 <td height="75px" align="center"><?php if($ResultVal[ACTBAS] != '') { echo $ResultVal[ACTBAS]; } else { echo "0"; } ?></td>
				 <td height="75px" align="center"><?php if($ResultVal[FOCUS_COV] != '') { echo $ResultVal[FOCUS_COV]; } else { echo "0"; } ?></td>
				 <td height="75px" align="center"><?php if($ResultVal[FOCUS_EFF] != '') { echo $ResultVal[FOCUS_EFF]; } else { echo "0"; } ?></td>
			 </tr>
			 </table>
			</tbody>
			  <!-- Metric Ends-->
			</table>
	</td>
</tr>


       
	<tr>
	  <td>
		
		   <table width="100%" bgcolor="#CCCCCC" border="1"> <!--Third Table-->
			<thead>
			  <tr>
				<th align="center" style="width:25%">POSM COVERAGE</th>
				<th align="center" style="width:25%">INCENTIVES(NAIRA)</th>
			  </tr>
			</thead>

		<tbody>

		<tr>
		  <td valign="top">
			<div style="overflow:auto; height:50px;">
			 <table width="100%" bgcolor="#999999" align="left">
			 <tr>
				 <td width="10%">Customer Type</td>
				 <td width="20%" align="center">Customer Coverage
					<table width="100%">
						<tr>
							<td align="center" style="background-color:#999"><b>Planned</b></td>
							<td align="center" style="background-color:#999"><b>Actual</b></td>
						</tr>
					 </table>
				 </td>
				 <td width="20%" align="center">Items
					<table width="100%">
						<tr>
							<td align="center" style="background-color:#999"><b>Planned</b></td>
							<td align="center" style="background-color:#999"><b>Given</b></td>
						</tr>
					</table>
				 </td>
			 </tr>
			<?php 
		if($arrallProd	> 0) {
		foreach($finalAllProdInfo AS $SearchVal) { ?>
			 <tr>
				 <td height="50px" ><?php echo $SearchVal[CUSTYPVAL]; ?></td>
				 <td >
					<table width="100%">
						<tr><td align="center"><?php echo $SearchVal[NOCUS]; ?></td><td align="center"><?php echo $SearchVal[CUSCNT]; ?></td></tr>
					</table>
				</td>
				 <td >
					<table width="100%" >
						<tr><td align="center"><?php echo $SearchVal[UNITVAL]; ?></td><td align="center"><?php echo $SearchVal[SOLQTY]; ?></td></tr>
					</table>
				</td>
			 </tr>
			<?php } 
			  }	else { ?>
			  <tr>
				 <td colspan="3" align="center" height="50px"><strong>NO RECORDS FOUND</strong></td>
			   </tr>
			 <?php }
			
			if($arrallProd	> 0) {
			?>
			<tr>
				<td height="50px"><b>Total</b></td>
				<td>
					<table width="100%">
						<tr><td align="center"><?php echo $totalTgtCus; ?></td><td align="center"><?php echo $totalActCus; ?></td></tr>
					</table>
				</td>
				<td>
					<table width="100%">
						<tr><td align="center"><?php echo $totalTgtSalvalueSalesValue; ?></td><td align="center"><?php echo $totalActSalvalue; ?></td></tr>
					</table>
				</td> 
			</tr>
			<?php } ?>
		  </table>
		  </div>
	  </td>
		
    <!-- <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],
          ['2004',  1000,      400],
          ['2005',  1170,      460],
          ['2006',  660,       1120],
          ['2007',  1030,      540]
        ]);
    
        var options = {
          title: 'Company Performance',
          hAxis: {title: 'Year', titleTextStyle: {color: 'red'}}
        };
    
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script> -->

		<td valign="top">
			<table width="100%" bgcolor="#999999" align="right"><!-- Incentive Start Table-->
			 <tr>
				 <td align="center" width="15%"><strong>ECO INCENTIVE</strong></td>
				 <td align="center" width="10%"><?php if($ResultVal[ACTDROP] != '') { echo $ResultVal[ECO_INC]; } else { echo "0"; } ?></td>
				 <td align="center" width="15%"><strong>QUANTITY INCENTIVE</strong></td>
				 <td align="center" width="10%"><?php if($ResultVal[TOTAL_ACT_NAIRA] != '') { echo $ResultVal[TOTAL_ACT_NAIRA]; } else { echo "0"; } ?></td>
			 </tr>
			 <!-- <tr><td colspan="10" align="center" style="background-color:#CCC"><b>GRAPH</b></td></tr> -->
			 
			 <!-- <tr style="overflow:scroll">
			 				<td colspan="10" >
			 				
			 				<div style="width: 900px; height: 500px;" id="chart_div" ></div>
			 				<img src="mygraph.php" alt="Your generated image" />
			 				
			 				</td>
			 </tr> -->			 
			</table>
	   </td>
	 </tr>
  </tbody>
			  <!-- Incentive Ends-->
			</table>
			
			
			<!--Third Table End-->
			</td>
		</tr>
	 </table>
	<?php
	} //foreach loop
	?>
	
	<span id="printopen" style="padding-left:470px;padding-top:10px;<?php if($arrcnt > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printsrmetricsreport');"></span>
<form id="printsrmetricsreport" target="_blank" action="printsrmetricsreport.php" method="post">
	<input type="hidden" name="srcode" id="srcode" value="<?php echo $srcode; ?>" />
	<input type="hidden" name="propmonths" id="propmonths" value="<?php echo $propmonths; ?>" />
	<input type="hidden" name="propyears" id="propyears" value="<?php echo $propyears; ?>" />
</form>




<?php
} // if loop
 else { 
	 	 
?>
<table border="1" width="100%" bgcolor="#CCCCCC">
	  <tr>
	    <td>
	
		  <table border="1" width="100%" bgcolor="#CCCCCC"> <!--First Table-->
			<thead>
			  <tr>
				<th align="center" style="width:50%">Sales(Naira)</th>
				<th align="center" style="width:50%">Customer Coverage</th>
		  </tr>
		  </thead>
	     <tbody>
         <tr> <!--First TR Starts-->
		 <td style="background-color:#999"> <!-- Sale Starts-->
         <table width="100%" bgcolor="#999999" height="80px">
         <tr><td width="50%">Target</td><td align="center">-</td></tr>
         <tr><td width="50%">Actual</td><td align="center">-</td></tr>
         <tr><td width="50%">% Achievement</td><td align="center">-</td></tr>
         </table>
         </td> <!-- Sale Ends-->
        
         <td> <!--customer coverage start-->
         <table width="100%" bgcolor="#999999">
         <tr>
         <td align="center">Total Customers</td>
         <td align="center">Customer Visits(MIN 1)</td>
         <td align="center">Sale Customer</td>
         <td align="center">Productivity</td>
         <td align="center">ECO</td>
         </tr>
         <tr>
         <td height="40px" colspan="5" align="center"><strong>NO RECORDS FOUND</strong></td>
         <!-- <td height="40px">&nbsp;</td>
         <td height="40px">&nbsp;</td>
         <td height="40px">&nbsp;</td>
         <td height="40px">&nbsp;</td> -->
         </tr>
         </table>
         </td>  <!--customer coverage start-->      
        </tr> <!--First TR End-->
        </tbody>
		</table>
	  </td>
	</tr>

  <tr>
  <td>
  <table width="100%" bgcolor="#CCCCCC" border="1"> <!--Second Table-->
 		<thead>
			  <tr>
				<th align="center" style="width:25%">ITEM</th>
				<th align="center" style="width:25%">VALUE</th>
                <th align="center" style="width:50%">METRIC</th>
		  </tr>
		  </thead>

		<tbody>
         <table width="50%" bgcolor="#999999" align="left">
         <tr>
			 <td>Total Sale</td>
			 <td align="center"><strong>-</strong></td>
         </tr>
         <tr>
			 <td>Total Lines</td>
			 <td align="center">-</td>
         </tr>


         <tr>
			 <td>Total Focus Products</td>
			 <td align="center">-</td>
         </tr>
         <tr>
			 <td>Total Focus Lines</td>
			 <td align="center">-</td>
         </tr>
         <tr>
			 <td>Total Checked In Hours</td>
			 <td align="center">-</td>
         </tr>
      </table>
      
        <table width="50%" bgcolor="#999999" align="right"><!-- Metric Start Table-->
         <tr>
			 <td align="center"><strong>Drop Size</strong></td>
			 <td align="center"><strong>Basket Size</strong></td>
			 <td align="center"><strong>Focus Coverage</strong></td>
			 <td align="center"><strong>Efficiency</strong></td>
         </tr>
         
         <tr>
			 <td height="75px" align="center" colspan="4"><strong>NO RECORDS FOUND</strong></td>
			 <!-- <td height="75px" align="center">3</td>
			 <td height="75px" align="center">4</td>
			 <td height="75px" align="center">3</td> -->
         </tr>
         </table>
		 </tbody>
          <!-- Metric Ends-->
        </table>
	</td>
</tr>


       
	<tr>
	  <td>
	   <table width="100%" bgcolor="#CCCCCC" border="1"> <!--Third Table-->
 		<thead>
		  <tr>
			<th align="center" style="width:25%">POSM COVERAGE</th>
			<th align="center" style="width:25%">INCENTIVES(NAIRA)</th>
		  </tr>
		</thead>

		<tbody>

		<tr>
		  <td valign="top">

		<div style="overflow:auto; height:50px;">
         <table width="100%" bgcolor="#999999" align="left">
         <tr>
			 <td width="10%">Customer Type</td>
			 <td width="20%" align="center">Customer Coverage
				<table width="100%">
					<tr>
						<td align="center" style="background-color:#999"><b>Planned</b></td>
						<td align="center" style="background-color:#999"><b>Actual</b></td>
					</tr>
				 </table>
			 </td>
			 <td width="20%" align="center">Items
				<table width="100%">
					<tr>
						<td align="center" style="background-color:#999"><b>Planned</b></td>
						<td align="center" style="background-color:#999"><b>Given</b></td>
					</tr>
				</table>
			 </td>
         </tr>
         <tr>
			 <td colspan="3" align="center" height="50px"><strong>NO RECORDS FOUND</strong></td>
			 <!-- <td>
					<table width="100%">
						<tr><td align="center">10</td><td align="center">8</td></tr>
					</table>
				</td>
			 <td>
					<table width="100%">
						<tr><td align="center">10</td><td align="center">6</td></tr>
					</table>
				</td> -->
         </tr>
       </table>
	   </div>

	  </td>

		<td>
        <table width="100%" bgcolor="#999999" align="right"><!-- Incentive Start Table-->
         <tr>
			 <td align="center" width="15%"><strong>ECO INCENTIVE</strong></td>
			 <td align="center" width="10%">-</td>
			 <td align="center" width="15%"><strong>QUANTITY INCENTIVE</strong></td>
			 <td align="center" width="10%">-</td>
         </tr>
        <!--  <tr><td colspan="10" align="center" style="background-color:#CCC"><b>GRAPH</b></td></tr>         
         <tr style="overflow:scroll"><td colspan="10" align="center"><strong>
         		 NO RECORDS FOUND</strong>
         		 <div id="chart_div" style="width: 900px; height: 500px;"></div>
         		 <img src="mygraph.php" alt="Your generated image" />		 
         		 </td></tr> -->
         </table>
	   </td>
	   </tr>

		 </tbody>
          <!-- Incentive Ends-->
        </table><!--Third Table End-->
        </td>
		</tr>
	 </table>

 
 
 <?php }
   
  } else { ?>


 <table border="1" width="100%" bgcolor="#CCCCCC">
	  <tr>
	    <td>
	
		  <table border="1" width="100%" bgcolor="#CCCCCC"> <!--First Table-->
			<thead>
			  <tr>
				<th align="center" style="width:50%">Sales(Naira)</th>
				<th align="center" style="width:50%">Customer Coverage</th>
		  </tr>
		  </thead>
	     <tbody>
         <tr> <!--First TR Starts-->
		 <td style="background-color:#999"> <!-- Sale Starts-->
         <table width="100%" bgcolor="#999999" height="80px">
         <tr><td width="50%">Target</td><td align="center">-</td></tr>
         <tr><td width="50%">Actual</td><td align="center">-</td></tr>
         <tr><td width="50%">% Achievement</td><td align="center">-</td></tr>
         </table>
         </td> <!-- Sale Ends-->
        
         <td> <!--customer coverage start-->
         <table width="100%" bgcolor="#999999">
         <tr>
         <td align="center">Total Customers</td>
         <td align="center">Customer Visits(MIN 1)</td>
         <td align="center">Sale Customer</td>
         <td align="center">Productivity</td>
         <td align="center">ECO</td>
         </tr>
         <tr>
         <td height="40px" colspan="5" align="center"><strong>NO RECORDS FOUND</strong></td>
         <!-- <td height="40px">&nbsp;</td>
         <td height="40px">&nbsp;</td>
         <td height="40px">&nbsp;</td>
         <td height="40px">&nbsp;</td> -->
         </tr>
         </table>
         </td>  <!--customer coverage start-->      
        </tr> <!--First TR End-->
        </tbody>
		</table>
	  </td>
	</tr>

  <tr>
  <td>
  <table width="100%" bgcolor="#CCCCCC" border="1"> <!--Second Table-->
 		<thead>
			  <tr>
				<th align="center" style="width:25%">ITEM</th>
				<th align="center" style="width:25%">VALUE</th>
                <th align="center" style="width:50%">METRIC</th>
		  </tr>
		  </thead>

		<tbody>
         <table width="50%" bgcolor="#999999" align="left">
         <tr>
			 <td>Total Sale</td>
			 <td align="center"><strong>-</strong></td>
         </tr>
         <tr>
			 <td>Total Lines</td>
			 <td align="center">-</td>
         </tr>


         <tr>
			 <td>Total Focus Products</td>
			 <td align="center">-</td>
         </tr>
         <tr>
			 <td>Total Focus Lines</td>
			 <td align="center">-</td>
         </tr>
         <tr>
			 <td>Total Checked In Hours</td>
			 <td align="center">-</td>
         </tr>
      </table>
      
        <table width="50%" bgcolor="#999999" align="right"><!-- Metric Start Table-->
         <tr>
			 <td align="center"><strong>Drop Size</strong></td>
			 <td align="center"><strong>Basket Size</strong></td>
			 <td align="center"><strong>Focus Coverage</strong></td>
			 <td align="center"><strong>Efficiency</strong></td>
         </tr>
         
         <tr>
			 <td height="75px" align="center" colspan="4"><strong>NO RECORDS FOUND</strong></td>
			 <!-- <td height="75px" align="center">3</td>
			 <td height="75px" align="center">4</td>
			 <td height="75px" align="center">3</td> -->
         </tr>
         </table>
		 </tbody>
          <!-- Metric Ends-->
        </table>
	</td>
</tr>


       
	<tr>
	  <td>
	   <table width="100%" bgcolor="#CCCCCC" border="1"> <!--Third Table-->
 		<thead>
		  <tr>
			<th align="center" style="width:25%">POSM COVERAGE</th>
			<th align="center" style="width:25%">INCENTIVES(NAIRA)</th>
		  </tr>
		</thead>

		<tbody>

		<tr>
		  <td valign="top">

		<div style="overflow:auto; height:50px;">
         <table width="100%" bgcolor="#999999" align="left">
         <tr>
			 <td width="10%">Customer Type</td>
			 <td width="20%" align="center">Customer Coverage
				<table width="100%">
					<tr>
						<td align="center" style="background-color:#999"><b>Planned</b></td>
						<td align="center" style="background-color:#999"><b>Actual</b></td>
					</tr>
				 </table>
			 </td>
			 <td width="20%" align="center">Items
				<table width="100%">
					<tr>
						<td align="center" style="background-color:#999"><b>Planned</b></td>
						<td align="center" style="background-color:#999"><b>Given</b></td>
					</tr>
				</table>
			 </td>
         </tr>
         <tr>
			 <td colspan="3" align="center" height="50px"><strong>NO RECORDS FOUND</strong></td>
			 <!-- <td>
					<table width="100%">
						<tr><td align="center">10</td><td align="center">8</td></tr>
					</table>
				</td>
			 <td>
					<table width="100%">
						<tr><td align="center">10</td><td align="center">6</td></tr>
					</table>
				</td> -->
         </tr>
       </table>
	   </div>

	  </td>

		<td>
        <table width="100%" bgcolor="#999999" align="right"><!-- Incentive Start Table-->
         <tr>
			 <td align="center" width="15%"><strong>ECO INCENTIVE</strong></td>
			 <td align="center" width="10%">-</td>
			 <td align="center" width="15%"><strong>QUANTITY INCENTIVE</strong></td>
			 <td align="center" width="10%">-</td>
         </tr>
         <!-- <tr><td colspan="10" align="center" style="background-color:#CCC"><b>GRAPH</b></td></tr>         
         <tr style="overflow:scroll"><td colspan="10" align="center"><strong>
		 NO RECORDS FOUND</strong>
		 <div id="chart_div" style="width: 900px; height: 500px;"></div>
		 <img src="mygraph.php" alt="Your generated image" />		 
		 </td></tr> -->
         </table>
	   </td>
	   </tr>

		 </tbody>
          <!-- Incentive Ends-->
        </table><!--Third Table End-->
        </td>
		</tr>
	 </table>


<?php } exit(0); ?>