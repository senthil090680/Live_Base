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

//ini_set("display_errors",true);
//error_reporting(E_ALL & ~E_NOTICE);

extract($_REQUEST);
//debugerr($_REQUEST);
//exit;
$params		=	$kdcode;
$where		=	"";
$complete_query		=	'';
$focuscheck_query	=	'';
$target_query		=	'';
if(isset($_REQUEST[fromdatevalue]) && $_REQUEST[fromdatevalue] !='') {
		
	$datecol		=	"(LEFT(Date,10) >= '".$fromdatevalue."' AND LEFT(Date,10) <= '".$todatevalue."')";
	$datecolfocus	=	"(LEFT(Date,10) >= '".$fromdatevalue."' AND LEFT(Date,10) <= '".$todatevalue."')";
	
	if($asmcode	==	'' || $asmcode == 'null') {
		$asmcodecol		=	'';
		$wherefordsr	=	'';
	} elseif($asmcode	!=	'') {
		$asmcodestr		=	implode("','",$asmcode);
		if(is_array($asmcode)) {
			$asmcodeprint		=	$asmcodestr;
		} else {
			$asmcodeprint		=	$asmcode;		
		}
		$asmcodecol		=	"ASM IN ('".$asmcodestr."')";
		$asmcodecolval	=	"DSR_Code IN ('".$asmcodestr."')";
		$wherefordsr	=	'WHERE';
	}

	if($rsmcode	==	'' || $rsmcode == 'null') {
		$rsmcodecol		=	'';
	} elseif($rsmcode	!=	'') {
		$rsmcodestr		=	implode("','",$rsmcode);
		if(is_array($rsmcode)) {
			$rsmcodeprint		=	$rsmcodestr;
		} else {
			$rsmcodeprint		=	$rsmcode;		
		}
		$rsmcodecol		=	"RSM IN ('".$rsmcodestr."')";
	}
	
	if($srcode	==	'' || $srcode == 'null') {
		$DSR_Codestr		=	'';
	} elseif($srcode	!=	'') {
		$DSR_Codestr		=	implode("','",$srcode);
		if(is_array($srcode)) {
			$srcodeprint		=	$DSR_Codestr;
		} else {
			$srcodeprint		=	$srcode;		
		}
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

	if($target_query	==	'') {
		if($DSR_Codestr	==	'') {
			$target_query		.=	"";
		} else {
			$target_query		.=	" WHERE SR_Code IN ('".$DSR_Codestr."')";
		}
	} else if($target_query	!=	'') {
		if($DSR_Codestr	==	'') {
			$target_query		.=	"";
		} else {
			$target_query		.=	" AND SR_Code IN ('".$DSR_Codestr."')";
		}
	}

	$get_monthsarr				=	array_unique(get_months($fromdatevalue,$todatevalue));
	$get_yearsarr				=	array_unique(get_years($fromdatevalue,$todatevalue));
	sort($get_monthsarr);
	sort($get_yearsarr);
	$monthstr					=	implode("','",$get_monthsarr);
	$yearstr					=	implode("','",$get_yearsarr);
	if($target_query	==	'') {
		$target_query		.=	" WHERE monthval IN ('".$monthstr."') AND yearval IN ('".$yearstr."')";
	} else if($target_query	!=	'') {
		$target_query		.=	" AND monthval IN ('".$monthstr."') AND yearval IN ('".$yearstr."')";
	}

	//pre($get_monthsarr);
	//pre($get_yearsarr);
	
	if($complete_query	==	'') {
		$complete_query			.=	" WHERE $datecol AND visit_Count != ''";
		//$complete_query		.=	" WHERE $datecol";
	} else if($complete_query	!=	'') {
		$complete_query			.=	" AND $datecol AND visit_Count != ''";
		//$complete_query		.=	" AND $datecol";
	}

	if($focuscheck_query	==	'') {
		$focuscheck_query			.=	" WHERE $datecolfocus";
		//$complete_query		.=	" WHERE $datecol";
	} else if($focuscheck_query	!=	'') {
		$focuscheck_query			.=	" AND $datecolfocus";
		//$complete_query		.=	" AND $datecol";
	}
					
	$query_trans									=   "SELECT DISTINCT KD_Code,DSR_Code,Date,visit_Count,Invoice_Count,effective_count,productive_count,Invoice_Line_Count,Total_Sale_Value,Drop_Size_Value,Basket_Size_Value FROM dsr_metrics $complete_query ORDER BY Date";
	
	//$query_trans									=   "SELECT KD_Code,DSR_Code,Date,Customer_Code,Check_In_time,Check_Out_time FROM customer_visit_tracking $complete_query";
	//echo $query_trans;
	//exit;
	$res_trans										=   mysql_query($query_trans);

	while($row_trans								=   mysql_fetch_assoc($res_trans)) {
		$customer_track[]							=	$row_trans;
		$kdcode_trans[]								=	$row_trans["KD_Code"];
		$dsrcode_trans[]							=	$row_trans["DSR_Code"];
		$cuscode_trans[]							=	$row_trans["Customer_Code"];
	}

	//echo count($transInfo)."jungle";
	$kdcode_trans		=	array_unique($kdcode_trans);
	$kdcode_Total		=	implode("','",$kdcode_trans);

	$dsrcode_trans		=	array_unique($dsrcode_trans);
	$dsrcode_Total		=	implode("','",$dsrcode_trans);

	$cuscode_trans		=	array_unique($cuscode_trans);
	$cuscode_Total		=	implode("','",$cuscode_trans);

	//pre($transInfo);
	//exit;
	$finalSearchInfo			=	$customer_track;
	//pre($finalSearchInfo);
	//exit;
	
	$query_kd										=   "SELECT KD_Name,KD_Code FROM kd WHERE KD_Code IN ('".$kdcode_Total."')";
	$res_kd											=   mysql_query($query_kd);
	while($row_kd									=   mysql_fetch_assoc($res_kd)) {
		$kdInfo[$row_kd["KD_Code"]]					=	$row_kd;
	}
	 
	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_kd){
		//$transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
		if($kdInfo[$val_kd["KD_Code"]]["KD_Code"] == $val_kd[KD_Code]) {                                     
			$finalkdInfo[$i]["KD_Name"]								=   $kdInfo[$val_kd["KD_Code"]]["KD_Name"];
			$finalkdInfo[$i]["DSRCode"]								=   $val_kd["DSR_Code"];
			$finalkdInfo[$i]["KD_Code"]								=   $val_kd["KD_Code"];
			$finalkdInfo[$i]["DateVal"]								=   $val_kd["Date"];
			$finalkdInfo[$i]["visit_Count"]							=   $val_kd["visit_Count"];
			$finalkdInfo[$i]["SALES_Count"]							=   $val_kd["Invoice_Count"];
			$finalkdInfo[$i]["EFF_Count"]							=   $val_kd["effective_count"];
			$finalkdInfo[$i]["PROD_Count"]							=   $val_kd["productive_count"];
			$finalkdInfo[$i]["Invoice_Line_Count"]					=   $val_kd["Invoice_Line_Count"];
			$finalkdInfo[$i]["Sale_Value"]							=   $val_kd["Total_Sale_Value"];
			$finalkdInfo[$i]["Drop_Value"]							=   $val_kd["Drop_Size_Value"];
			$finalkdInfo[$i]["Basket_Value"]						=   $val_kd["Basket_Size_Value"];
			$i++;
		}
		$k++;
	}

	$finalSearchInfo          =   $finalkdInfo;
	//pre($finalSearchInfo);
	//exit;


	$query_dsr										=   "SELECT ASM,DSRName,DSR_Code FROM dsr WHERE DSR_Code IN ('".$dsrcode_Total."')";
	//echo $query_dsr;
	//exit;
	$res_dsr										=   mysql_query($query_dsr);
	while($row_dsr									=   mysql_fetch_assoc($res_dsr)) {
		$dsrInfo[$row_dsr["DSR_Code"]]				=	$row_dsr;
		$asmcode_dsr[]								=	$row_dsr["ASM"];
	}
	
	//pre($dsrInfo);
	//exit;
	//$asmcode_dsr			=	array_unique($asmcode_dsr);
	$asmcode_Total			=	implode("','",$asmcode_dsr);

	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_dsr){
		//echo $dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] . "-". $val_dsr["DSRCode"]."<br>";
		if($dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] == $val_dsr["DSRCode"]) {                                    
			$finaldsrInfo[$i]["DSR_Name"]							=   $dsrInfo[$val_dsr["DSRCode"]]["DSRName"];
			$finaldsrInfo[$i]["ASM_Id"]								=   $dsrInfo[$val_dsr["DSRCode"]]["ASM"];
			$finaldsrInfo[$i]["KD_Name"]							=   $val_dsr["KD_Name"];
			$finaldsrInfo[$i]["KD_Code"]							=   $val_dsr["KD_Code"];
			$finaldsrInfo[$i]["DSRCode"]							=   $val_dsr["DSRCode"];
			$finaldsrInfo[$i]["DateVal"]							=   $val_dsr["DateVal"];
			$finaldsrInfo[$i]["visit_Count"]						=   $val_dsr["visit_Count"];
			$finaldsrInfo[$i]["SALES_Count"]						=   $val_dsr["SALES_Count"];
			$finaldsrInfo[$i]["EFF_Count"]							=   $val_dsr["EFF_Count"];
			$finaldsrInfo[$i]["PROD_Count"]							=   $val_dsr["PROD_Count"];
			$finaldsrInfo[$i]["Invoice_Line_Count"]					=   $val_dsr["Invoice_Line_Count"];
			$finaldsrInfo[$i]["Sale_Value"]							=   $val_dsr["Sale_Value"];
			$finaldsrInfo[$i]["Drop_Value"]							=   $val_dsr["Drop_Value"];
			$finaldsrInfo[$i]["Basket_Value"]						=   $val_dsr["Basket_Value"];
			$i++;
		}
		$k++;
	}

	$finalSearchInfo          =   $finaldsrInfo;
	//pre($finalSearchInfo);
	//exit;

	$query_asm										=   "SELECT id,DSRName,RSM FROM asm_sp WHERE id IN ('".$asmcode_Total."')";
	$res_asm										=   mysql_query($query_asm);
	while($row_asm									=   mysql_fetch_assoc($res_asm)) {
		$asmInfo[$row_asm["id"]]					=	$row_asm;
		$rsmcode_rsm[]								=	$row_asm["RSM"];
	}
	
	$rsmcode_Total			=	implode("','",$rsmcode_rsm);

	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_asm){
		//$transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
		if($asmInfo[$val_asm["ASM_Id"]]["id"] == $val_asm["ASM_Id"]) {                                     
			$finalasmInfo[$i]["ASM_Name"]							=   $asmInfo[$val_asm["ASM_Id"]]["DSRName"];
			$finalasmInfo[$i]["RSM_Id"]								=   $asmInfo[$val_asm["ASM_Id"]]["RSM"];
			$finalasmInfo[$i]["DSR_Name"]							=   $val_asm["DSR_Name"];
			$finalasmInfo[$i]["ASM_Id"]								=   $val_asm["ASM_Id"];
			$finalasmInfo[$i]["KD_Name"]							=   $val_asm["KD_Name"];
			$finalasmInfo[$i]["KD_Code"]							=   $val_asm["KD_Code"];
			$finalasmInfo[$i]["DSRCode"]							=   $val_asm["DSRCode"];
			$finalasmInfo[$i]["DateVal"]							=   $val_asm["DateVal"];
			$finalasmInfo[$i]["visit_Count"]						=   $val_asm["visit_Count"];
			$finalasmInfo[$i]["SALES_Count"]						=   $val_asm["SALES_Count"];
			$finalasmInfo[$i]["EFF_Count"]							=   $val_asm["EFF_Count"];
			$finalasmInfo[$i]["PROD_Count"]							=   $val_asm["PROD_Count"];
			$finalasmInfo[$i]["Invoice_Line_Count"]					=   $val_asm["Invoice_Line_Count"];
			$finalasmInfo[$i]["Sale_Value"]							=   $val_asm["Sale_Value"];
			$finalasmInfo[$i]["Drop_Value"]							=   $val_asm["Drop_Value"];
			$finalasmInfo[$i]["Basket_Value"]						=   $val_asm["Basket_Value"];
			$i++;
		}
		$k++;
	}

	$finalSearchInfo          =   $finalasmInfo;
	//pre($finalSearchInfo);
	//exit;

	$query_rsm										=   "SELECT id,DSRName,DSR_Code FROM rsm_sp WHERE id IN ('".$rsmcode_Total."')";
	$res_rsm										=   mysql_query($query_rsm);
	while($row_rsm									=   mysql_fetch_assoc($res_rsm)) {
		$rsmInfo[$row_rsm["id"]]					=	$row_rsm;
	}

	//pre($rsmInfo);
	//exit;
	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_rsm){
		//echo $rsmInfo[$val_rsm["RSM_Code"]]["id"] . "-". $val_rsm["RSM_Code"]."<br>";
		if($rsmInfo[$val_rsm["RSM_Id"]]["id"] == $val_rsm["RSM_Id"]) {                                     
			$finalrsmInfo[$i]["ASM_Name"]							=   $val_rsm["ASM_Name"];
			$finalrsmInfo[$i]["ASM_Id"]								=   $val_rsm["ASM_Id"];
			$finalrsmInfo[$i]["RSM_Name"]							=   $rsmInfo[$val_rsm["RSM_Id"]]["DSRName"];
			$finalrsmInfo[$i]["RSM_Id"]								=   $val_rsm["RSM_Id"];
			$finalrsmInfo[$i]["DSR_Name"]							=   $val_rsm["DSR_Name"];
			$finalrsmInfo[$i]["DSRCode"]							=   $val_rsm["DSRCode"];
			$finalrsmInfo[$i]["KD_Name"]							=   $val_rsm["KD_Name"];
			$finalrsmInfo[$i]["KD_Code"]							=   $val_rsm["KD_Code"];
			$finalrsmInfo[$i]["DateVal"]							=   $val_rsm["DateVal"];
			$finalrsmInfo[$i]["visit_Count"]						=   $val_rsm["visit_Count"];
			$finalrsmInfo[$i]["SALES_Count"]						=   $val_rsm["SALES_Count"];
			$finalrsmInfo[$i]["EFF_Count"]							=   $val_rsm["EFF_Count"];
			$finalrsmInfo[$i]["PROD_Count"]							=   $val_rsm["PROD_Count"];
			$finalrsmInfo[$i]["Invoice_Line_Count"]					=   $val_rsm["Invoice_Line_Count"];
			$finalrsmInfo[$i]["Sale_Value"]							=   $val_rsm["Sale_Value"];
			$finalrsmInfo[$i]["Drop_Value"]							=   $val_rsm["Drop_Value"];
			$finalrsmInfo[$i]["Basket_Value"]						=   $val_rsm["Basket_Value"];
			$i++;
		}
		$k++;
	}

	$finalSearchInfo          =   $finalrsmInfo;
	//pre($finalSearchInfo);
	//exit;

	$query_dsrmetrics	=   "SELECT DSR_Code,Date,Check_In_time,Check_Out_time,Customer_Code FROM customer_visit_tracking WHERE $datecol ORDER BY AUDIT_DATE_TIME,Date,Check_In_time";
	//echo $query_dsrmetrics;
	//exit;
	$res_dsrmetrics									=   mysql_query($query_dsrmetrics);
	while($row_dsrmetrics							=   mysql_fetch_assoc($res_dsrmetrics)) {
		$checktimeInfo[]							=	$row_dsrmetrics;
	}

	//pre($checktimeInfo);
	//exit;
	$p								=	1;
	$checkarrcnt					=	count($checktimeInfo);
	$combinedatedsr					=	'';
	$checkdatedsr					=	'';
	foreach($checktimeInfo AS $checktime) {		
		$timestartdateval								=	explode(' ',$checktime["Date"]);
		$timestartdate									=	$timestartdateval[0];
		$checkRoute_Code								=	getdbval($checktime["Customer_Code"],'route','customer_code','customer');
		$checkCustCount									=	findCustomerCount($checkRoute_Code,$checktime["DSR_Code"]);
		$timeCheck_In_time								=	$checktime["Check_In_time"];
		$timeCheck_Out_time								=	$checktime["Check_Out_time"];
		$timestartdsr									=	$checktime["DSR_Code"];
		$checkdatedsr									=	$timestartdate.$timestartdsr;
		$checkintimeval									=	$timestartdate." ".$timeCheck_In_time;
		$checkouttimeval								=	$timestartdate." ".$timeCheck_Out_time;


		//echo $checkdatedsr."<br>"; 
		if($p != 1) {			
			if($checkdatedsr  != $combinedatedsr) {				
				//echo $outtime[$combinedatedsr]. "---". $intime[$checkdatedsr]."<br>"; 
				$timecal[$combinedatedsr][DSRDATE]		=	$combinedatedsr;
				$timecal[$combinedatedsr][FIRSTIN]		=	$firsttime[$combinedatedsr];
				if($lasttime[$combinedatedsr] == '00:00:00') {
					$DATE_ALONE		=	substr($timecal[$combinedatedsr][DSRDATE],0,10);
					$DSR_ALONE		=	substr($timecal[$combinedatedsr][DSRDATE],10);
					
					$prevcheckouttime_val		=	checkforpreviouscheckouttime($checktimeInfo,$DATE_ALONE,$DSR_ALONE);
					//$timecal[$combinedatedsr][LASTOUT]		=	sum_the_time($lastcheckintime[$combinedatedsr],"00:20:00").":00";
					$timecal[$combinedatedsr][LASTOUT]		=	$prevcheckouttime_val;
				} else {
					$timecal[$combinedatedsr][LASTOUT]		=	$lasttime[$combinedatedsr];
				}
				$timecal[$combinedatedsr][CUSTCOUNT]	=	$checkCustCounts[$combinedatedsr];
				$timecal[$combinedatedsr][TOTAL]		=	ceil(($outtime[$combinedatedsr]	-	$intime[$combinedatedsr])/3600);
				$intime[$checkdatedsr]					=	strtotime($checkintimeval);
				$firsttime[$checkdatedsr]				=	$timeCheck_In_time;
			}
		}
		if($checkarrcnt != $p) {
			//echo $outtime[$combinedatedsr]."<br>"; 
			$combinedatedsr								=	$timestartdate.$timestartdsr;
			//echo $outtime[$combinedatedsr]."<br>"; 
			$outtime[$checkdatedsr]						=	strtotime($checkouttimeval);
			$lastcheckintime[$checkdatedsr]				=	$timeCheck_In_time;
			$lasttime[$checkdatedsr]					=	$timeCheck_Out_time;
			$checkCustCounts[$checkdatedsr]				=	$checkCustCount;
		} else {
			$combinedatedsr								=	$timestartdate.$timestartdsr;
			$outtime[$checkdatedsr]						=	strtotime($checkouttimeval);
			$lastcheckintime[$checkdatedsr]				=	$timeCheck_In_time;
			$lasttime[$checkdatedsr]					=	$timeCheck_Out_time;
			$checkCustCounts[$checkdatedsr]				=	$checkCustCount;
		}
		if($p	==	1) {
			$intime[$combinedatedsr]					=	strtotime($checkintimeval);
			$firsttime[$combinedatedsr]					=	$timeCheck_In_time;
		}
		$p++;
	}
	//echo $outtime[$combinedatedsr]. "---". $intime[$combinedatedsr]."<br>"; 
	$timecal[$combinedatedsr][DSRDATE]		=	$checkdatedsr;
	$timecal[$combinedatedsr][TOTAL]		=	($outtime[$checkdatedsr]	-	$intime[$combinedatedsr])/3600;
	$timecal[$combinedatedsr][FIRSTIN]		=	$firsttime[$combinedatedsr];
	if($lasttime[$checkdatedsr] == '00:00:00') {
		$DATE_ALONE								=	substr($timecal[$combinedatedsr][DSRDATE],0,10);
		$DSR_ALONE								=	substr($timecal[$combinedatedsr][DSRDATE],10);
		
		$prevcheckouttime_val					=	checkforpreviouscheckouttime($checktimeInfo,$DATE_ALONE,$DSR_ALONE);
		//echo $prevcheckouttime_val."<br>";
		//$timecal[$combinedatedsr][LASTOUT]		=	sum_the_time($lastcheckintime[$combinedatedsr],"00:20:00").":00";
		$timecal[$combinedatedsr][LASTOUT]		=	$prevcheckouttime_val;
	} else {
		$timecal[$combinedatedsr][LASTOUT]		=	$lasttime[$checkdatedsr];
	}
	//$timecal[$combinedatedsr][LASTOUT]		=	$lasttime[$checkdatedsr];
	$timecal[$combinedatedsr][CUSTCOUNT]	=	$cheackCustCounts[$checkdatedsr];
		
	//pre($timecal);
	//exit;
	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_checkin){
		$valDSRCHC									=	$val_checkin["DSRCode"];
		$valDateCHC									=	$val_checkin["DateVal"];
		//echo $timecal[$valDateCHC.$valDSRCHC]["DSRDATE"] . "++". $valDateCHC.$valDSRCHC."<br>";
		if($timecal[$valDateCHC.$valDSRCHC]["DSRDATE"] == $valDateCHC.$valDSRCHC) { 
			$finalcheckinInfo[$i]["ASM_Name"]							=   $val_checkin["ASM_Name"];
			$finalcheckinInfo[$i]["ASM_Id"]								=   $val_checkin["ASM_Id"];
			$finalcheckinInfo[$i]["RSM_Name"]							=   $val_checkin["RSM_Name"];
			$finalcheckinInfo[$i]["RSM_Id"]								=   $val_checkin["RSM_Id"];
			$finalcheckinInfo[$i]["DSR_Name"]							=   $val_checkin["DSR_Name"];
			$finalcheckinInfo[$i]["DSRCode"]							=   $val_checkin["DSRCode"];
			$finalcheckinInfo[$i]["KD_Name"]							=   $val_checkin["KD_Name"];
			$finalcheckinInfo[$i]["KD_Code"]							=   $val_checkin["KD_Code"];
			$finalcheckinInfo[$i]["DateVal"]							=   $val_checkin["DateVal"];
			$finalcheckinInfo[$i]["visit_Count"]						=   $val_checkin["visit_Count"];
			$finalcheckinInfo[$i]["SALES_Count"]						=   $val_checkin["SALES_Count"];
			$finalcheckinInfo[$i]["EFF_Count"]							=   $val_checkin["EFF_Count"];
			$finalcheckinInfo[$i]["PROD_Count"]							=   $val_checkin["PROD_Count"];
			$finalcheckinInfo[$i]["Invoice_Line_Count"]					=   $val_checkin["Invoice_Line_Count"];
			$finalcheckinInfo[$i]["Sale_Value"]							=   $val_checkin["Sale_Value"];
			$finalcheckinInfo[$i]["Drop_Value"]							=   $val_checkin["Drop_Value"];
			$finalcheckinInfo[$i]["Basket_Value"]						=   $val_checkin["Basket_Value"];
			
			$finalcheckinInfo[$i]["CUSTCNT"]							=   tofindplannedcust($finalcheckinInfo[$i]["DateVal"],$finalcheckinInfo[$i]["DSRCode"]);

			//$finalcheckinInfo[$i]["CUSTCNT"]."<br>";
			$finalcheckinInfo[$i]["TOTALNOT"]							=   $finalcheckinInfo[$i]["CUSTCNT"]-$finalcheckinInfo[$i]["visit_Count"];
			$finalcheckinInfo[$i]["FIRSTIN"]							=   $timecal[$valDateCHC.$valDSRCHC]["FIRSTIN"];
			$finalcheckinInfo[$i]["LASTOUT"]							=   $timecal[$valDateCHC.$valDSRCHC]["LASTOUT"];
			//$finalcheckinInfo[$i]["TOTALHR"]							=	round( $timecal[$valDateCHC.$valDSRCHC]["TOTAL"]);
			//$finalcheckinInfo[$i]["TOTALHR"]							=	FindHoursMinuteFromTwoTimes($finalcheckinInfo[$i]["FIRSTIN"],$finalcheckinInfo[$i]["LASTOUT"]);
			$finalcheckinInfo[$i]["TOTALHR"]							=	getTimeDiff($finalcheckinInfo[$i]["FIRSTIN"],$finalcheckinInfo[$i]["LASTOUT"]);			
			$i++;
		}
		$k++;
	}

	$finalSearchInfo			=   $finalcheckinInfo;
	//pre($finalSearchInfo);
	//exit;	

	$query_target												=   "SELECT KD_Code,SR_Code,productive_percent,prod_status,effective_percent,eff_status,monthval,yearval FROM coverage_target_setting $target_query ORDER BY SR_Code";
	//echo $query_target;
	//exit;
	$res_target													=   mysql_query($query_target);
	while($row_target											=   mysql_fetch_assoc($res_target)) {
		$SR_Code												=	$row_target[SR_Code];
		$monthval												=	$row_target[monthval];
		$yearval												=	$row_target[yearval];
		$targetUnits[$SR_Code]["productive_percent"][$monthval.$yearval]			=	$row_target["productive_percent"];
		$targetUnits[$SR_Code]["productive_status"][$monthval.$yearval]				=	$row_target["prod_status"];
		$targetUnits[$SR_Code]["effective_percent"][$monthval.$yearval]				=	$row_target["effective_percent"];
		$targetUnits[$SR_Code]["effective_status"][$monthval.$yearval]				=	$row_target["eff_status"];
		$targetInfo[$SR_Code]														=	$SR_Code;
	}

	
	//pre($targetInfo);
	//pre($targetNaira);
	//pre($targetUnits);
	//pre($finalSearchInfo);
	//exit;
	$i=0;
	foreach($finalSearchInfo as $val_target)	{
		$SRCODEVAL			=	$val_target["DSRCode"];
		$KD_CODE			=	$val_target["KD_Code"];

		$INDEX_VAL			=	$SRCODEVAL;
		//echo	$targetInfo[$INDEX_VAL]	. "==".	$INDEX_VAL."<br>"; 
		//echo $targetUnits[$INDEX_VAL]["productive_status"]."<br>";

		if($targetInfo[$INDEX_VAL]	==	$INDEX_VAL) {

			$monthyeararrval			=	explode('-',$finalSearchInfo[$i]["DateVal"]);
			$monthvalue					=	ltrim($monthyeararrval[1],0);
			
			$noofworkdays							=	noofworkdays($monthvalue,$monthyeararrval[0]);
			
			//echo noofworkdays($monthvalue,$monthyeararrval[0]);
			//exit;

			//echo	$targetUnits[$INDEX_VAL]["productive_status"][$monthvalue.$monthyeararrval[0]]."<br>"; 
			//echo	$targetUnits[$INDEX_VAL]["productive_percent"][$monthvalue.$monthyeararrval[0]]."<br>"; 
			//echo	$finalSearchInfo[$i]["DateVal"]."<br>"; 
			//echo	$monthyeararrval[0]."<br>"; 
			if($targetUnits[$INDEX_VAL]["productive_status"][$monthvalue.$monthyeararrval[0]] == '5') {
				
				//$finalSearchInfo[$i]["PRO_TGT"]					=   $targetUnits[$INDEX_VAL]["productive_percent"][$monthvalue.$monthyeararrval[0]];
				$finalSearchInfo[$i]["PRO_TGT"]					=   round(($targetUnits[$INDEX_VAL]["productive_percent"][$monthvalue.$monthyeararrval[0]]/100)*($finalSearchInfo[$i]["CUSTCNT"]));
				//$finalSearchInfo[$i]["PROVISIT_TGT"]			=	($finalSearchInfo[$i]["PRO_TGT"]/100)*($finalSearchInfo[$i]["CUSTCNT"]);
				$finalSearchInfo[$i]["PROVISIT_TGT"]			=	$finalSearchInfo[$i]["PRO_TGT"];
			} else if($targetUnits[$INDEX_VAL]["productive_status"][$monthvalue.$monthyeararrval[0]] == '10') {
				$finalSearchInfo[$i]["PRO_TGT"]					=   round($targetUnits[$INDEX_VAL]["productive_percent"][$monthvalue.$monthyeararrval[0]]/$noofworkdays);
				//$finalSearchInfo[$i]["PROVISIT_TGT"]			=	$finalSearchInfo[$i]["CUSTCNT"];
				$finalSearchInfo[$i]["PROVISIT_TGT"]			=	$finalSearchInfo[$i]["PRO_TGT"];
				//echo $finalSearchInfo[$i]["PROVISIT_TGT"]."<br>";
			} else {
				$finalSearchInfo[$i]["PRO_TGT"]					=	0;
			}

			if($targetUnits[$INDEX_VAL]["effective_status"][$monthvalue.$monthyeararrval[0]] == '5') {
				$finalSearchInfo[$i]["EFF_TGT"]					=   round(($targetUnits[$INDEX_VAL]["effective_percent"][$monthvalue.$monthyeararrval[0]]/100)*($finalSearchInfo[$i]["CUSTCNT"]));
				//$finalSearchInfo[$i]["EFFVISIT_TGT"]			=	($finalSearchInfo[$i]["EFF_TGT"]/100)*($finalSearchInfo[$i]["CUSTCNT"]);
				$finalSearchInfo[$i]["EFFVISIT_TGT"]			=	$finalSearchInfo[$i]["EFF_TGT"];
			} else if($targetUnits[$INDEX_VAL]["effective_status"][$monthvalue.$monthyeararrval[0]] == '10') {

				$finalSearchInfo[$i]["EFF_TGT"]					=   round($targetUnits[$INDEX_VAL]["effective_percent"][$monthvalue.$monthyeararrval[0]]/$noofworkdays);
				//$finalSearchInfo[$i]["EFFVISIT_TGT"]			=	$finalSearchInfo[$i]["CUSTCNT"];
				$finalSearchInfo[$i]["EFFVISIT_TGT"]			=	$finalSearchInfo[$i]["EFF_TGT"];
				//echo $finalSearchInfo[$i]["EFFVISIT_TGT"]."<br>";
			} else {
				$finalSearchInfo[$i]["EFF_TGT"]					=	0;
			}
		} else {
			$finalSearchInfo[$i]["PRO_TGT"]						=   0;
			$finalSearchInfo[$i]["PROVISIT_TGT"]				=	0;
			$finalSearchInfo[$i]["EFF_TGT"]						=   0;
			$finalSearchInfo[$i]["EFFVISIT_TGT"]				=	0;
		}
		$i++;
	}
	
	//pre($finalSearchInfo);
	//exit;	
	//echo $complete_query;
	$query_transhdr													=   "SELECT id,DSR_Code,Transaction_Number,Date,Time,transaction_Reference_Number FROM transaction_hdr $complete_query ORDER BY Date";
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
				$transaction_Reference_Number_cancel[]				=   $row_transhdr[transaction_Reference_Number];
				$transno_cancel_number[]							=   $row_transhdr[Transaction_Number];
			}
			$transhdr_result[]									=   $row_transhdr;
			$transhdrInfo[$row_transhdr[Transaction_Number]]	=   $row_transhdr;
			$transno_transhdr[]									=   $row_transhdr[Transaction_Number];
		}
	}
	 
	//pre($transno_transhdr);
	//pre($transaction_Reference_Number_cancel);
	//pre($transno_cancel_number);
	
	foreach($transaction_Reference_Number_cancel AS $REFVAL){
		if(array_search($REFVAL,$transno_transhdr) !== false) {
			$arraysearchval		=	array_search($REFVAL,$transno_transhdr);
			unset($transno_transhdr[$arraysearchval]);
			unset($transhdr_result[$arraysearchval]);
		}
	}

	//pre($transno_transhdr);
	foreach($transno_cancel_number AS $REFVAL){
		if(array_search($REFVAL,$transno_transhdr) !== false) {
			$arraysearchval		=	array_search($REFVAL,$transno_transhdr);
			unset($transno_transhdr[$arraysearchval]);
			unset($transhdr_result[$arraysearchval]);
			//array_splice($transno_transhdr, $arraysearchval, 1);
		}
	}

	//pre($transhdr_result);
	//pre($transno_transhdr);
	
	//exit;
	$transno_transhdr		=	array_unique($transno_transhdr);
	$transno_Total			=	implode("','",$transno_transhdr);

	//pre($transhdrInfo);                                                        

	$query_trans									=   "SELECT SUM(Focus_Flag) AS FOC_CNT,DSR_Code,Transaction_Number FROM transaction_line WHERE Transaction_Number IN ('".$transno_Total."') AND Focus_Flag = '1' GROUP BY Transaction_Number ORDER BY Transaction_Number";
	//echo $query_trans;
	//exit;
	$res_trans										=   mysql_query($query_trans);

	while($row_trans								=   mysql_fetch_assoc($res_trans)) {
		$transInfo[$row_trans["Transaction_Number"]]=	$row_trans;
		$transno_trans[]							=	$row_trans["Transaction_Number"];
		$kdcode_trans[]								=	$row_trans["KD_Code"];
		$dsrcode_trans[]							=	$row_trans["DSR_Code"];
		$transnofocus_trans[]						=	$row_trans;
	}

	//pre($transInfo);
	//exit;
	$i=0;
	foreach($transhdr_result as $val_transno)	{
		$Transaction_Number			=	$val_transno["Transaction_Number"];
		$FOCUS_DT					=	$val_transno["Date"];
		$DSRCD						=	$val_transno["DSR_Code"];

		//echo	$transInfo[$Transaction_Number][Transaction_Number]	. "==".	$Transaction_Number."<br>"; 
		if($transInfo[$Transaction_Number][Transaction_Number]	==	$Transaction_Number) {
			$focusflagitems[$FOCUS_DT.$DSRCD]["FOC_CNT"]		+=   $transInfo[$Transaction_Number]["FOC_CNT"];
			$focusflagitems[$FOCUS_DT.$DSRCD]["FOC_ID"]			=   $FOCUS_DT.$DSRCD;
		} else {
			$focusflagitems[$FOCUS_DT.$DSRCD]["FOC_CNT"]			+=   0;
		}
		$i++;
	}

	//pre($focusflagitems);
	//exit;

	$i=0;
	foreach($finalSearchInfo as $val_focussold)	{
		$SRCODEVAL			=	$val_focussold["DSRCode"];
		$DateValCK			=	$val_focussold["DateVal"];

		$INDEX_VAL			=	$DateValCK.$SRCODEVAL;
		//echo	$focusflagitems[$INDEX_VAL][FOC_ID]	. "==".	$INDEX_VAL."<br>"; 
		if($focusflagitems[$INDEX_VAL][FOC_ID]	==	$INDEX_VAL) {
			$finalSearchInfo[$i]["FOC_CNT"]			=   $focusflagitems[$INDEX_VAL]["FOC_CNT"];
		} else {
			$finalSearchInfo[$i]["FOC_CNT"]			=   0;
		}
		$i++;
	}

	//pre($finalSearchInfo);
	//exit;


	//$query_focusact										=   "SELECT id,DSR_Code,LEFT(Date,10) AS DATEFOC,SUM(replace(focus_Flag,'Yes','1')) AS FOCUS_ACT FROM dailystockloading $focuscheck_query AND (focus_Flag ='Yes' OR focus_Flag ='yes' OR focus_Flag = 'YES') GROUP BY DSR_Code,Date";
	$query_focusact										=   "SELECT id,DSR_Code,LEFT(Date,10) AS DATEFOC,SUM(focus_Flag) AS FOCUS_ACT FROM dailystockloading $focuscheck_query AND (focus_Flag ='1') GROUP BY DSR_Code,Date";
	//echo $query_focusact;
	//exit;
	$res_focusact										=   mysql_query($query_focusact);
	while($row_focusact									=   mysql_fetch_assoc($res_focusact)) {
		$FOCUS_DT										=	$row_focusact["DATEFOC"];
		$DSRCD											=	$row_focusact["DSR_Code"];
		$focusactInfo[$FOCUS_DT.$DSRCD][FOCUS_ACTUAL]	+=	$row_focusact[FOCUS_ACT];
		$focusactInfo[$FOCUS_DT.$DSRCD][FOCUS_ID]		=	$FOCUS_DT.$DSRCD;
	}

	//pre($focusactInfo);
	//exit;

	//pre($finalSearchInfo);
	//exit;
	
	$i=0;
	foreach($finalSearchInfo as $val_focussold)	{
		$SRCODEVAL			=	$val_focussold["DSRCode"];
		$DateValCK			=	$val_focussold["DateVal"];

		$INDEX_VAL			=	$DateValCK.$SRCODEVAL;
		//echo	$focusflagitems[$INDEX_VAL][FOC_ID]	. "==".	$INDEX_VAL."<br>"; 
		if($focusactInfo[$INDEX_VAL][FOCUS_ID]	==	$INDEX_VAL) {
			$finalSearchInfo[$i]["FOCUS_ACTUAL"]			=   $focusactInfo[$INDEX_VAL]["FOCUS_ACTUAL"];
			if($finalSearchInfo[$i]["PRO_TGT"] == 0 || $finalSearchInfo[$i]["PRO_TGT"] == 0 || $finalSearchInfo[$i]["PRO_TGT"] == '') {
				$finalSearchInfo[$i]["PRO_ACT"]				=   round(($finalSearchInfo[$i]["PROD_Count"]/100)*($finalSearchInfo[$i]["visit_Count"]));
			} else {				
				$finalSearchInfo[$i]["PRO_ACTVISIT"]		=   round(($finalSearchInfo[$i]["PROD_Count"]/100)*($finalSearchInfo[$i]["visit_Count"]));
				//$finalSearchInfo[$i]["PRO_ACT"]				=	round(($finalSearchInfo[$i]["PRO_ACTVISIT"]/$finalSearchInfo[$i]["PROVISIT_TGT"])*(100));
				$finalSearchInfo[$i]["PRO_ACT"]				=	round($finalSearchInfo[$i]["PRO_ACTVISIT"]);
				//echo $finalSearchInfo[$i]["PRO_ACTVISIT"]."<br>";
				//echo $finalSearchInfo[$i]["PRO_ACT"]."<br>";
			}

			//FOR EFFECTIVITY PERCENTAGE STARTS HERE
			if($finalSearchInfo[$i]["EFF_TGT"] == 0 || $finalSearchInfo[$i]["EFF_TGT"] == 0 || $finalSearchInfo[$i]["EFF_TGT"] == '') {
				$finalSearchInfo[$i]["EFF_ACT"]				=   round(($finalSearchInfo[$i]["EFF_Count"]/100)*($finalSearchInfo[$i]["visit_Count"]));
			} else {				
				$finalSearchInfo[$i]["EFF_ACTVISIT"]		=   round(($finalSearchInfo[$i]["EFF_Count"]/100)*($finalSearchInfo[$i]["visit_Count"]));
				//$finalSearchInfo[$i]["EFF_ACT"]				=	round(($finalSearchInfo[$i]["EFF_ACTVISIT"]/$finalSearchInfo[$i]["EFFVISIT_TGT"])*(100));
				$finalSearchInfo[$i]["EFF_ACT"]				=	round($finalSearchInfo[$i]["EFF_ACTVISIT"]);
				//echo $finalSearchInfo[$i]["PRO_ACTVISIT"]."<br>";
				//echo $finalSearchInfo[$i]["PRO_ACT"]."<br>";
			}
			//FOR EFFECTIVITY PERCENTAGE ENDS HERE

			//$finalSearchInfo[$i]["PRO_ACT"]					=   round($finalSearchInfo[$i]["SALES_Count"]/$finalSearchInfo[$i]["visit_Count"]);
			$finalSearchInfo[$i]["FOCUS_COV"]				=   round($finalSearchInfo[$i]["FOC_CNT"]/$finalSearchInfo[$i]["FOCUS_ACTUAL"]);

		} else {
			$finalSearchInfo[$i]["FOCUS_ACTUAL"]			=   0;
			//$finalSearchInfo[$i]["PRO_ACT"]				=   round($finalSearchInfo[$i]["SALES_Count"]/$finalSearchInfo[$i]["visit_Count"]);
			if($finalSearchInfo[$i]["PRO_TGT"] == '0' || $finalSearchInfo[$i]["PRO_TGT"] == 0 || $finalSearchInfo[$i]["PRO_TGT"] == '') {
				$finalSearchInfo[$i]["PRO_ACT"]				=   round(($finalSearchInfo[$i]["PROD_Count"]/100)*($finalSearchInfo[$i]["visit_Count"]));
			} else {				
				$finalSearchInfo[$i]["PRO_ACTVISIT"]		=   round(($finalSearchInfo[$i]["PROD_Count"]/100)*($finalSearchInfo[$i]["visit_Count"]));
				//$finalSearchInfo[$i]["PRO_ACT"]				=	round(($finalSearchInfo[$i]["PRO_ACTVISIT"]/$finalSearchInfo[$i]["PROVISIT_TGT"])*(100));
				$finalSearchInfo[$i]["PRO_ACT"]				=	round($finalSearchInfo[$i]["PRO_ACTVISIT"]);
				//echo $finalSearchInfo[$i]["PRO_ACTVISIT"]."<br>";
				//echo $finalSearchInfo[$i]["PRO_ACT"]."<br>";
			}
			
			//FOR EFFECTIVITY PERCENTAGE STARTS HERE
			if($finalSearchInfo[$i]["EFF_TGT"] == 0 || $finalSearchInfo[$i]["EFF_TGT"] == 0 || $finalSearchInfo[$i]["EFF_TGT"] == '') {
				$finalSearchInfo[$i]["EFF_ACT"]				=   round(($finalSearchInfo[$i]["EFF_Count"]/100)*($finalSearchInfo[$i]["visit_Count"]));
			} else {				
				$finalSearchInfo[$i]["EFF_ACTVISIT"]		=   round(($finalSearchInfo[$i]["EFF_Count"]/100)*($finalSearchInfo[$i]["visit_Count"]));
				//$finalSearchInfo[$i]["EFF_ACT"]				=	round(($finalSearchInfo[$i]["EFF_ACTVISIT"]/$finalSearchInfo[$i]["EFFVISIT_TGT"])*(100));
				$finalSearchInfo[$i]["EFF_ACT"]				=	round($finalSearchInfo[$i]["EFF_ACTVISIT"]);
				//echo $finalSearchInfo[$i]["PRO_ACTVISIT"]."<br>";
				//echo $finalSearchInfo[$i]["PRO_ACT"]."<br>";
			}
			//FOR EFFECTIVITY PERCENTAGE ENDS HERE			

			$finalSearchInfo[$i]["FOCUS_COV"]				=   round($finalSearchInfo[$i]["FOC_CNT"]/$finalSearchInfo[$i]["FOCUS_ACTUAL"]);
		}
		$i++;
	}

	//pre($finalSearchInfo);
	//exit;
	//echo $reportby; exit;

	if($reportby	!=	"DateVal") {
		$finalSearchInfo	=	array_multi_sort($finalSearchInfo, 'DSRCode','DateVal', $order=SORT_DESC); 
	}

	//pre($finalSearchInfo);
	//exit;

	$orderbycolumns     =   $reportby;
	$orderbysorting     =   'ASC';

	if($orderbysorting == 'DESC') {
		$dir        =   'arsort';               
	} else {
		$dir        =   'asort';   
	}
	//$finalSearchInfo	=	subval_sort($finalSearchInfo,$orderbycolumns,$dir);
	//pre($finalSearchInfo);
	//exit;

	
	//pre($finalSearchInfo);
	//exit;

} else {
	$nextrecval			=	"";
}
$num_rows		=	count($finalSearchInfo);
?>
<title>SR PERFORMANCE REPORT</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<style type="text/css">
	.metalign {
		padding:0px;
		width:10px;
	}
	.focalign {
		padding:0px;
		width:40px;
	}
</style>

  <table border="1" width="100%" style="border-collapse:collapse">
	<thead class="theadcls">
	  <tr>
		<th align="center" >SR Name</th>
		<th align="center" >Date</th>
		<th align="center" >First Check In Time</th>
		<th align="center" >Last Check Out Time</th>
		<th align="center" >Total Hours</th>
		<th align="center" >Total Customers Planned</th>
		<th align="center" >Total Customer Visited</th>
		<th align="center" >TotalCustomers Not Covered</th>
		<th align="center" >Total Sale Visits</th>
		<th align="center" >Coverage Visit
			<table  width="100%">
				<tr>
					<td align="center"><strong>TGT</strong></td>
					<td align="center"><strong>ACT</strong></td>
				</tr>
			</table>
		</th>
		<th align="center">Effvity Visit
			<table width="100%" >
				<tr>
					<td align="center"><strong>TGT</strong></td>
					<td align="center"><strong>ACT</strong></td>
				</tr>
			</table>
		</th>
		<th align="center" >Total Sales (Naira)</th>
		<th align="center" >Total Invoices</th>
		<th align="center" >Total Line Items</th>
		<th align="center" >Focus Items
		<table width="100%"><tr><td align="center">In Plan</td><td align="center">Sold</td></tr></table>
		</th>
		<th align="center" style="width:210px;">Metrics
		<table width="100%"><tr><td align="center">Drop Size</td><td align="center">Basket Size</td><td align="center">Focus Coverage</td></tr></table>
		</th> 
  </tr>
  </thead>
 <tbody class="tbodycls">

 <?php	$checkfor				=	'';
		$checkoutfor			=	'';
		$checkforkd				=	'';
		$checkoutforkd			=	'';
		$subtotalcheckforkd		=	1;
		$k						=	0;
		$arrcnt					=	count($finalSearchInfo);
		$subtotalcheckfor		=	1;
		$total_hours			=	'';
		$total_cus_plan			=	'';
		$total_cus_vis			=	'';
		$total_not_cov			=	'';
		$total_sal_vis			=	'';
		$total_prod_tgt			=	'';
		$total_prod_act			=	'';
		$total_eff_tgt			=	'';
		$total_eff_act			=	'';
		$total_sales			=	'';
		$total_invoice			=	'';
		$total_line_items		=	'';
		$total_focus_inplan		=	'';
		$total_focus_sold		=	'';
		$total_drop				=	'';
		$total_bas				=	'';
		$total_focus_cov		=	'';
		$tot_hours				=	'';
		$tot_cus_plan			=	'';
		$tot_cus_vis			=	'';
		$tot_not_cov			=	'';
		$tot_sal_vis			=	'';
		$tot_prod_tgt			=	'';
		$tot_prod_act			=	'';
		$tot_eff_tgt			=	'';
		$tot_eff_act			=	'';
		$tot_sales				=	'';
		$tot_invoice			=	'';
		$tot_line_items			=	'';
		$tot_focus_inplan		=	'';
		$tot_focus_sold			=	'';
		$tot_drop				=	'';
		$tot_bas				=	'';
		$tot_focus_cov			=	'';

if($arrcnt > 0) {
 foreach($finalSearchInfo AS $SearchKey=>$SearchVal) { 
	$total_hours			=	sum_the_time($total_hours,$SearchVal["TOTALHR"]);
	$total_cus_plan			+=	$SearchVal["CUSTCNT"];
	$total_cus_vis			+=	$SearchVal["visit_Count"];
	$total_not_cov			+=	$SearchVal["TOTALNOT"];
	$total_sal_vis			+=	$SearchVal["SALES_Count"];
	$total_prod_tgt			+=	$SearchVal["PRO_TGT"];
	$total_prod_act			+=	$SearchVal["PRO_ACT"];
	$total_eff_tgt			+=	$SearchVal["EFF_TGT"];
	$total_eff_act			+=	$SearchVal["EFF_ACT"];
	$total_sales			+=	$SearchVal["Sale_Value"];
	$total_invoice			+=	$SearchVal["SALES_Count"];
	$total_line_items		+=	$SearchVal["Invoice_Line_Count"];
	$total_focus_inplan		+=	$SearchVal["FOCUS_ACTUAL"];
	$total_focus_sold		+=	$SearchVal["FOC_CNT"];
	$total_drop				+=	$SearchVal["Drop_Value"];
	$total_bas				+=	$SearchVal["Basket_Value"];
	$total_focus_cov		+=	$SearchVal["FOCUS_COV"];
	
	if($reportby == 'DateVal') {
		if($checkfor		==	'') {
			$checkfor		=	$SearchVal["DateVal"];
			$checkoutfor	=	$SearchVal["DateVal"];
			
			$tot_hours				=	'';
			$tot_cus_plan			=	'';
			$tot_cus_vis			=	'';
			$tot_not_cov			=	'';
			$tot_sal_vis			=	'';
			$tot_prod_tgt			=	'';
			$tot_prod_act			=	'';
			$tot_eff_tgt			=	'';
			$tot_eff_act			=	'';
			$tot_sales				=	'';
			$tot_invoice			=	'';
			$tot_line_items			=	'';
			$tot_focus_inplan		=	'';
			$tot_focus_sold			=	'';
			$tot_drop				=	'';
			$tot_bas				=	'';
			$tot_focus_cov			=	'';

			if($subtotalcheckfor == 2) {
				$subtotalcheckfor = 1;
				$tot_hours				=	sum_the_time($tot_hours,$SearchVal["TOTALHR"]);
				$tot_cus_plan			+=	$SearchVal["CUSTCNT"];
				$tot_cus_vis			+=	$SearchVal["visit_Count"];
				$tot_not_cov			+=	$SearchVal["TOTALNOT"];
				$tot_sal_vis			+=	$SearchVal["SALES_Count"];
				$tot_prod_tgt			+=	$SearchVal["PRO_TGT"];
				$tot_prod_act			+=	$SearchVal["PRO_ACT"];
				$tot_eff_tgt			+=	$SearchVal["EFF_TGT"];
				$tot_eff_act			+=	$SearchVal["EFF_ACT"];
				$tot_sales				+=	$SearchVal["Sale_Value"];
				$tot_invoice			+=	$SearchVal["SALES_Count"];
				$tot_line_items			+=	$SearchVal["Invoice_Line_Count"];
				$tot_focus_inplan		+=	$SearchVal["FOCUS_ACTUAL"];
				$tot_focus_sold			+=	$SearchVal["FOC_CNT"];
				$tot_drop				+=	$SearchVal["Drop_Value"];
				$tot_bas				+=	$SearchVal["Basket_Value"];
				$tot_focus_cov			+=	$SearchVal["FOCUS_COV"];
			}
		} else {
			$checkoutfor	=	$SearchVal["DateVal"];
			if($subtotalcheckfor == 1) {
				$tot_hours				=	sum_the_time($tot_hours,$SearchVal["TOTALHR"]);
				$tot_cus_plan			+=	$SearchVal["CUSTCNT"];
				$tot_cus_vis			+=	$SearchVal["visit_Count"];
				$tot_not_cov			+=	$SearchVal["TOTALNOT"];
				$tot_sal_vis			+=	$SearchVal["SALES_Count"];
				$tot_prod_tgt			+=	$SearchVal["PRO_TGT"];
				$tot_prod_act			+=	$SearchVal["PRO_ACT"];
				$tot_eff_tgt			+=	$SearchVal["EFF_TGT"];
				$tot_eff_act			+=	$SearchVal["EFF_ACT"];
				$tot_sales				+=	$SearchVal["Sale_Value"];
				$tot_invoice			+=	$SearchVal["SALES_Count"];
				$tot_line_items			+=	$SearchVal["Invoice_Line_Count"];
				$tot_focus_inplan		+=	$SearchVal["FOCUS_ACTUAL"];
				$tot_focus_sold			+=	$SearchVal["FOC_CNT"];
				$tot_drop				+=	$SearchVal["Drop_Value"];
				$tot_bas				+=	$SearchVal["Basket_Value"];
				$tot_focus_cov			+=	$SearchVal["FOCUS_COV"];
			}
		}
	} elseif($reportby == 'DSR_Name') {
		if($checkfor		==	'') {
			$checkfor		=	$SearchVal["DSR_Name"];
			$checkoutfor	=	$SearchVal["DSR_Name"];
			
			$tot_hours				=	'';
			$tot_cus_plan			=	'';
			$tot_cus_vis			=	'';
			$tot_not_cov			=	'';
			$tot_sal_vis			=	'';
			$tot_prod_tgt			=	'';
			$tot_prod_act			=	'';
			$tot_eff_tgt			=	'';
			$tot_eff_act			=	'';
			$tot_sales				=	'';
			$tot_invoice			=	'';
			$tot_line_items			=	'';
			$tot_focus_inplan		=	'';
			$tot_focus_sold			=	'';
			$tot_drop				=	'';
			$tot_bas				=	'';
			$tot_focus_cov			=	'';

			if($subtotalcheckfor == 2) {
				$subtotalcheckfor = 1;
				$tot_hours				=	sum_the_time($tot_hours,$SearchVal["TOTALHR"]);
				$tot_cus_plan			+=	$SearchVal["CUSTCNT"];
				$tot_cus_vis			+=	$SearchVal["visit_Count"];
				$tot_not_cov			+=	$SearchVal["TOTALNOT"];
				$tot_sal_vis			+=	$SearchVal["SALES_Count"];
				$tot_prod_tgt			+=	$SearchVal["PRO_TGT"];
				$tot_prod_act			+=	$SearchVal["PRO_ACT"];
				$tot_eff_tgt			+=	$SearchVal["EFF_TGT"];
				$tot_eff_act			+=	$SearchVal["EFF_ACT"];
				$tot_sales				+=	$SearchVal["Sale_Value"];
				$tot_invoice			+=	$SearchVal["SALES_Count"];
				$tot_line_items			+=	$SearchVal["Invoice_Line_Count"];
				$tot_focus_inplan		+=	$SearchVal["FOCUS_ACTUAL"];
				$tot_focus_sold			+=	$SearchVal["FOC_CNT"];
				$tot_drop				+=	$SearchVal["Drop_Value"];
				$tot_bas				+=	$SearchVal["Basket_Value"];
				$tot_focus_cov			+=	$SearchVal["FOCUS_COV"];
			}
		} else {
			$checkoutfor	=	$SearchVal["DSR_Name"];
			if($subtotalcheckfor == 1) {
				$tot_hours				=	sum_the_time($tot_hours,$SearchVal["TOTALHR"]);
				$tot_cus_plan			+=	$SearchVal["CUSTCNT"];
				$tot_cus_vis			+=	$SearchVal["visit_Count"];
				$tot_not_cov			+=	$SearchVal["TOTALNOT"];
				$tot_sal_vis			+=	$SearchVal["SALES_Count"];
				$tot_prod_tgt			+=	$SearchVal["PRO_TGT"];
				$tot_prod_act			+=	$SearchVal["PRO_ACT"];
				$tot_eff_tgt			+=	$SearchVal["EFF_TGT"];
				$tot_eff_act			+=	$SearchVal["EFF_ACT"];
				$tot_sales				+=	$SearchVal["Sale_Value"];
				$tot_invoice			+=	$SearchVal["SALES_Count"];
				$tot_line_items			+=	$SearchVal["Invoice_Line_Count"];
				$tot_focus_inplan		+=	$SearchVal["FOCUS_ACTUAL"];
				$tot_focus_sold			+=	$SearchVal["FOC_CNT"];
				$tot_drop				+=	$SearchVal["Drop_Value"];
				$tot_bas				+=	$SearchVal["Basket_Value"];
				$tot_focus_cov			+=	$SearchVal["FOCUS_COV"];
			}
		}		
	} 
		
	if((($checkfor == $checkoutfor) && ($checkfor != '' && $checkoutfor !='')) && ($k != $arrcnt)) {  		
		$subtotalcheckfor = 2;
		$tot_hours				=	sum_the_time($tot_hours,$SearchVal["TOTALHR"]);
		$tot_cus_plan			+=	$SearchVal["CUSTCNT"];
		$tot_cus_vis			+=	$SearchVal["visit_Count"];
		$tot_not_cov			+=	$SearchVal["TOTALNOT"];
		$tot_sal_vis			+=	$SearchVal["SALES_Count"];
		$tot_prod_tgt			+=	$SearchVal["PRO_TGT"];
		$tot_prod_act			+=	$SearchVal["PRO_ACT"];
		$tot_eff_tgt			+=	$SearchVal["EFF_TGT"];
		$tot_eff_act			+=	$SearchVal["EFF_ACT"];
		$tot_sales				+=	$SearchVal["Sale_Value"];
		$tot_invoice			+=	$SearchVal["SALES_Count"];
		$tot_line_items			+=	$SearchVal["Invoice_Line_Count"];
		$tot_focus_inplan		+=	$SearchVal["FOCUS_ACTUAL"];
		$tot_focus_sold			+=	$SearchVal["FOC_CNT"];
		$tot_drop				+=	$SearchVal["Drop_Value"];
		$tot_bas				+=	$SearchVal["Basket_Value"];
		$tot_focus_cov			+=	$SearchVal["FOCUS_COV"];
	} else {
		 
	if($k != 0) {
		 //echo $checkfor ."==" .$checkoutfor."<br>";
		 //$checkoutfor		=	$SearchVal["Brand_Name"];
	?>
	 <tr>
		 <td colspan="4" align="right"><strong><?php 
		 //echo $target_naira	. " == " . $target_units . " == " . $SUM_SQ . " == " . $VALUE_NAIRA . " == " . $diff_units . " == " . $diff_naira. " == " .  $subtotalcheckfor. "<br/>";
		 
		 //echo $checkfor ."==" .$checkoutfor."<br>"; ?> Sub Total<strong></td>
		     <td align="right"><?php echo $tot_hours; ?></td>	
			 <td align="right"><?php echo $tot_cus_plan; ?></td>
			 <td align="right"><?php echo $tot_cus_vis; ?></td>	
			 <td align="right"><?php echo $tot_not_cov; ?></td>	
			 <td align="right"><?php echo $tot_sal_vis; ?></td>	
			 <td align="center" >
				 <table width="100%" border="0">
					<tr>
						<td align="right" ><?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_prod_tgt; ?></td>
						<td align="right" ><?php echo $tot_prod_act; ?></td>
					</tr>
				 </table>
			 </td>
			 <td align="center" >
				 <table width="100%" border="0">
					<tr>
						<td align="right" ><?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_eff_tgt; ?></td>
						<td align="right" ><?php echo $tot_eff_act; ?></td>
					</tr>
				 </table>
			 </td>
			 <td align="right"><?php echo number_format($tot_sales,2); ?></td>	
			 <td align="right"><?php echo $tot_invoice; ?></td>	
			 <td align="right"><?php echo $tot_line_items; ?></td>	
			 <td align="center" >
				 <table width="100%">
					<tr>
						<td align="right" > <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_focus_inplan; ?></td>
						<td align="right" ><?php echo $tot_focus_sold; ?></td>
					</tr>
				 </table>
			 </td>	
			 <td align="center" style="width:192px;">
				<table width="100%" class="tablefixed">
					<tr>
						<td align="right" ><?php echo number_format(($tot_sales/$tot_sal_vis),2); ?></td>
						<td align="right" ><?php echo number_format(($tot_sales/$tot_line_items),2); ?></td>
						<td align="right" ><?php echo $tot_focus_cov; ?></td>
					</tr>
				</table>				
			 </td>
  </tr>
<?php
	$checkfor			=	'';
	$subtotalcheckfor	=	'';
	$tot_hours				=	$SearchVal["TOTALHR"];
	$tot_cus_plan			=	$SearchVal["CUSTCNT"];
	$tot_cus_vis			=	$SearchVal["visit_Count"];
	$tot_not_cov			=	$SearchVal["TOTALNOT"];
	$tot_sal_vis			=	$SearchVal["SALES_Count"];
	$tot_prod_tgt			=	$SearchVal["PRO_TGT"];
	$tot_prod_act			=	$SearchVal["PRO_ACT"];
	$tot_eff_tgt			=	$SearchVal["EFF_TGT"];
	$tot_eff_act			=	$SearchVal["EFF_ACT"];
	$tot_sales				=	$SearchVal["Sale_Value"];
	$tot_invoice			=	$SearchVal["SALES_Count"];
	$tot_line_items			=	$SearchVal["Invoice_Line_Count"];
	$tot_focus_inplan		=	$SearchVal["FOCUS_ACTUAL"];
	$tot_focus_sold			=	$SearchVal["FOC_CNT"];
	$tot_drop				=	$SearchVal["Drop_Value"];
	$tot_bas				=	$SearchVal["Basket_Value"];
	$tot_focus_cov			=	$SearchVal["FOCUS_COV"];

	//echo $target_naira	. " == " . $target_units . " == " . $SUM_SQ . " == " . $VALUE_NAIRA . " == " . $diff_units . " == " . $diff_naira. " == " .  $subtotalcheckfor."<br/>";
} }


$checkfor	=	$checkoutfor;

?>
<tr >
	 <td class="tdcls" <?php if($reportby == 'DSR_Name') { ?>  <?php } ?>><?php echo ucwords(strtolower($SearchVal[DSR_Name])); ?></td>
	 <td class="tdcls" align="right" <?php if($reportby == 'DateVal') { ?> <?php } ?>><?php echo $SearchVal[DateVal]; ?></td>
	 <td align="right" ><?php echo $SearchVal[FIRSTIN]; ?></td>	
	 <td align="right"><?php echo $SearchVal[LASTOUT]; ?></td>
	 <td align="right"><?php echo $SearchVal[TOTALHR]; ?></td>	
	 <td align="right"><?php echo $SearchVal[CUSTCNT]; ?></td>
	 <td align="right"><?php echo $SearchVal[visit_Count]; ?></td>	
	 <td align="right"><?php echo $SearchVal[TOTALNOT]; ?></td>	
	 <td align="right"><?php echo $SearchVal[SALES_Count]; ?></td>	
	 <td align="center">
		<table width="100%">
			<tr>
				<td align="right" ><?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $SearchVal[PRO_TGT]; ?></td>
				<td align="right"><?php echo $SearchVal[PRO_ACT]; ?></td>
			</tr>
		</table>
	 </td>
	 <td align="center">
		<table width="100%">
			<tr>
				<td align="right" ><?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $SearchVal[EFF_TGT]; ?></td>
				<td align="right"><?php echo $SearchVal[EFF_ACT]; ?></td>
			</tr>
		</table>
	 </td>
	 <td align="right"><?php echo number_format($SearchVal[Sale_Value],2); ?></td>	
	 <td align="right"><?php echo $SearchVal[SALES_Count]; ?></td>	
	 <td align="right"><?php echo $SearchVal[Invoice_Line_Count]; ?></td>	
	 <td align="center" >
		<table width="100%">
			<tr>
				<td align="right"> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?><?php echo $SearchVal[FOCUS_ACTUAL]; ?></td>
				<td align="right" ><?php echo $SearchVal[FOC_CNT]; ?></td>
			</tr>
		</table>
	 </td>	
	 <td align="center" style="width:192px;">
		<table width="100%" class="tablefixed">
			<tr>
				<td align="right" ><?php echo number_format($SearchVal[Drop_Value],2); ?> </td>
				<td align="right" ><?php echo number_format($SearchVal[Basket_Value],2); ?></td>
				<td align="right" ><?php echo $SearchVal[FOCUS_COV]; ?></td>
			</tr>
		</table>
	  </td>	
	  
 </tr>
 <?php $k++; } ?> 
 <tr>
	 <td colspan="4" align="right"><strong>Sub Total<strong></td>
	 <td align="right"><?php echo $tot_hours; ?></td>	
	 <td align="right"><?php echo $tot_cus_plan; ?></td>
	 <td align="right"><?php echo $tot_cus_vis; ?></td>	
	 <td align="right"><?php echo $tot_not_cov; ?></td>	
	 <td align="right"><?php echo $tot_sal_vis; ?></td>	
	 <td align="center" >
		<table width="100%" border="0">
			<tr>
				<td align="right" ><?php echo $tot_prod_tgt; ?></td>
				<td align="right" ><?php echo $tot_prod_act; ?></td>
			</tr>
		</table>
	 </td>
	 <td align="center" >
		<table width="100%" border="0">
			<tr>
				<td align="right" ><?php echo $tot_eff_tgt; ?></td>
				<td align="right" ><?php echo $tot_eff_act; ?></td>
			</tr>
		</table>
	 </td>
	 <td align="right"><?php echo number_format($tot_sales,2); ?></td>	
	 <td align="right"><?php echo $tot_invoice; ?></td>	
	 <td align="right"><?php echo $tot_line_items; ?></td>	
	 <td align="center" >
		<table width="100%" border="0">
			<tr>
				<td align="right" ><?php echo $tot_focus_inplan; ?></td>
				<td align="right" ><?php echo $tot_focus_sold; ?></td>
			</tr>
		</table>
	 </td>	
	 <td align="center" style="width:192px;">
		<table width="100%" class="tablefixed">
			<tr>
				<td align="right" ><?php echo number_format(($tot_sales/$tot_sal_vis),2); ?></td>
				<td align="right" ><?php echo number_format(($tot_sales/$tot_line_items),2); ?></td>
				<td align="right" ><?php echo $tot_focus_cov; ?></td>
			</tr>
		</table>
	 </td>
 </tr>
 <tr >
	 <td colspan="4" align="right"><strong>Grand Total<strong></td>
	 <td align="right"><?php echo $total_hours; ?></td>	
	 <td align="right"><?php echo $total_cus_plan; ?></td>
	 <td align="right"><?php echo $total_cus_vis; ?></td>	
	 <td align="right"><?php echo $total_not_cov; ?></td>	
	 <td align="right"><?php echo $total_sal_vis; ?></td>	
	 <td align="center" >
		<table width="100%">
			<tr>
				<td align="right" ><?php echo $total_prod_tgt; ?></td>
				<td align="right" ><?php echo $total_prod_act; ?></td>
			</tr>
		</table>
	 </td>
	 <td align="center" >
		<table width="100%">
			<tr>
				<td align="right" ><?php echo $total_eff_tgt; ?></td>
				<td align="right" ><?php echo $total_eff_act; ?></td>
			</tr>
		</table>
	 </td>
	 <td align="right"><?php echo number_format($total_sales,2); ?></td>	
	 <td align="right"><?php echo $total_invoice; ?></td>	
	 <td align="right"><?php echo $total_line_items; ?></td>	
	 <td align="center" >
		<table width="100%">
			<tr>
				<td align="right">&nbsp;&nbsp;<?php echo $total_focus_inplan; ?>&nbsp;&nbsp;</td>
				<td align="right"><?php echo $total_focus_sold; ?></td>
			</tr>
		</table>
	 </td>	
	 <td align="center" style="width:192px;">
		<table width="100%" class="tablefixed">
			<tr>
				<td align="right" ><?php echo number_format(($total_sales/$total_sal_vis),2); ?></td>
				<td align="right" ><?php echo number_format(($total_sales/$total_line_items),2); ?></td>
				<td align="right" ><?php echo $total_focus_cov; ?></td>
			</tr>
		</table>
	 </td>
 </tr>
 </tbody>
</table>
<span id="printopen" style="padding-left:580px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php } else { ?>
 <tr>
	<td colspan="10" align='center'><strong>NO RECORDS FOUND</strong></td>
 </tr>
<?php } exit(0); ?>