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
$focuscheck_query		=	'';
$target_query		=	'';
$reportby		=	'DateVal';
if(isset($_REQUEST[fromdatevalue]) && $_REQUEST[fromdatevalue] !='') {
		
	$datecol		=	"(Date >= '".$fromdatevalue."' AND Date <= '".$todatevalue."')";
	$datecolfocus	=	"(LEFT(Date,10) >= '".$fromdatevalue."' AND LEFT(Date,10) <= '".$todatevalue."')";
	
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
		$DSR_Codestr		=	implode("','",$srcode);
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
					
	$query_trans									=   "SELECT KD_Code,DSR_Code,Date,visit_Count,Invoice_Count,effective_count,productive_count,Invoice_Line_Count,Total_Sale_Value,Drop_Size_Value,Basket_Size_Value FROM dsr_metrics $complete_query ORDER BY Date";
	
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

	/* CUSTOMER TYPE SELECT OPTION STARTS HERE */
	
	/*$query_cusname									=   "SELECT id,Customer_Name,customer_code,customer_type FROM customer WHERE customer_code IN ('".$cuscode_Total."')";
	//echo $query_cusname;
	//exit;
	$res_cusname									=   mysql_query($query_cusname);
	while($row_cusname								=   mysql_fetch_assoc($res_cusname)) {
		$cusInfo[$row_cusname["customer_code"]]		=	$row_cusname;
		$custype_cus[]								=	$row_cusname["customer_type"];
	}
	
	$custype_Total			=	implode("','",$custype_cus);

	//pre($cusInfo);
	//exit;
	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_cusname){
		//echo $rsmInfo[$val_rsm["RSM_Code"]]["id"] . "-". $val_rsm["RSM_Code"]."<br>";
		if($cusInfo[$val_cusname["Customer_Code"]]["customer_code"] == $val_cusname["Customer_Code"]) {                                     
			$finalcusnameInfo[$i]["ASM_Name"]							=   $val_cusname["ASM_Name"];
			$finalcusnameInfo[$i]["ASM_Id"]								=   $val_cusname["ASM_Id"];
			$finalcusnameInfo[$i]["RSM_Name"]							=   $val_cusname["RSM_Name"];
			$finalcusnameInfo[$i]["RSM_Id"]								=   $val_cusname["RSM_Id"];
			$finalcusnameInfo[$i]["DSR_Name"]							=   $val_cusname["DSR_Name"];
			$finalcusnameInfo[$i]["DSRCode"]							=   $val_cusname["DSRCode"];
			$finalcusnameInfo[$i]["KD_Name"]							=   $val_cusname["KD_Name"];
			$finalcusnameInfo[$i]["KD_Code"]							=   $val_cusname["KD_Code"];
			$finalcusnameInfo[$i]["Customer_Name"]						=   $cusInfo[$val_cusname["Customer_Code"]]["Customer_Name"];
			$finalcusnameInfo[$i]["Customer_Code"]						=   $val_cusname["Customer_Code"];
			$finalcusnameInfo[$i]["customer_type"]						=   $cusInfo[$val_cusname["Customer_Code"]]["customer_type"];
			$finalcusnameInfo[$i]["DateVal"]							=   $val_cusname["DateVal"];
			$finalcusnameInfo[$i]["Checkintime"]						=   $val_cusname["Checkintime"];
			$finalcusnameInfo[$i]["Checkouttime"]						=   $val_cusname["Checkouttime"];
			$i++;
		}
		$k++;
	}

	$finalSearchInfo          =   $finalcusnameInfo;
	//pre($finalSearchInfo);
	//exit;	

	if($custype	==	'') {
		$Custypestr		=	'';
	} elseif($custype	!=	'') {
		$Custypestr		=	implode(",",$custype);

		foreach($finalSearchInfo AS $val_custypekey=>$val_custypecheck)  {
			//pre($val_custypecheck);
			//pre($val_custypekey);
			if(!strstr($Custypestr,$val_custypecheck[customer_type])) {
				unset($finalSearchInfo[$val_custypekey]);
			} else {
				$dateval_date[]			=	$val_custypecheck[DateVal];
			}
		}
	}
	
	//$dateval_date			=	array_unique($dateval_date);
	$dateval_Total			=	implode("','",$dateval_date);
	
	//pre($dateval_Total);
	//pre($finalSearchInfo);
	//exit;
	*/
	/* CUSTOMER TYPE SELECT OPTION ENDS  HERE */

	$query_dsrmetrics	=   "SELECT DSR_Code,Date,Check_In_time,Check_Out_time,Customer_Code FROM customer_visit_tracking WHERE $datecol ORDER BY Date,Check_In_time";
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
		$timestartdate									=	$checktime["Date"];
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
				$timecal[$combinedatedsr][LASTOUT]		=	$lasttime[$combinedatedsr];
				$timecal[$combinedatedsr][CUSTCOUNT]	=	$checkCustCounts[$combinedatedsr];
				$timecal[$combinedatedsr][TOTAL]		=	($outtime[$combinedatedsr]	-	$intime[$combinedatedsr])/3600;
				$intime[$checkdatedsr]					=	strtotime($checkintimeval);
				$firsttime[$checkdatedsr]				=	$timeCheck_In_time;
			}
		}
		if($checkarrcnt != $p) {
			//echo $outtime[$combinedatedsr]."<br>"; 
			$combinedatedsr								=	$timestartdate.$timestartdsr;
			//echo $outtime[$combinedatedsr]."<br>"; 
			$outtime[$checkdatedsr]						=	strtotime($checkouttimeval);
			$lasttime[$checkdatedsr]					=	$timeCheck_Out_time;
			$checkCustCounts[$checkdatedsr]				=	$checkCustCount;
		} else {
			$outtime[$checkdatedsr]						=	strtotime($checkouttimeval);
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
	$timecal[$combinedatedsr][TOTAL]		=	($outtime[$checkdatedsr]	-	$intime[$combinedatedsr])/3600;
	$timecal[$combinedatedsr][FIRSTIN]		=	$firsttime[$combinedatedsr];
	$timecal[$combinedatedsr][LASTOUT]		=	$lasttime[$checkdatedsr];
	$timecal[$combinedatedsr][CUSTCOUNT]		=	$checkCustCounts[$checkdatedsr];
	$timecal[$combinedatedsr][DSRDATE]		=	$checkdatedsr;
	
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
			$finalcheckinInfo[$i]["CUSTCNT"]							=   $timecal[$valDateCHC.$valDSRCHC]["CUSTCOUNT"];
			$finalcheckinInfo[$i]["TOTALNOT"]							=   $finalcheckinInfo[$i]["CUSTCNT"]-$finalcheckinInfo[$i]["visit_Count"];
			$finalcheckinInfo[$i]["FIRSTIN"]							=   $timecal[$valDateCHC.$valDSRCHC]["FIRSTIN"];
			$finalcheckinInfo[$i]["LASTOUT"]							=   $timecal[$valDateCHC.$valDSRCHC]["LASTOUT"];
			$finalcheckinInfo[$i]["TOTALHR"]							=	round( $timecal[$valDateCHC.$valDSRCHC]["TOTAL"]);
			$i++;
		}
		$k++;
	}

	$finalSearchInfo          =   $finalcheckinInfo;
	//pre($finalSearchInfo);
	//exit;	

	$query_target												=   "SELECT KD_Code,SR_Code,productive_percent FROM coverage_target_setting $target_query ORDER BY SR_Code";
	//echo $query_target;
	//exit;
	$res_target													=   mysql_query($query_target);
	while($row_target											=   mysql_fetch_assoc($res_target)) {
		$SR_Code												=	$row_target[SR_Code];
		$targetUnits[$SR_Code]["productive_percent"]			=	$row_target["productive_percent"];
		$targetInfo[$SR_Code]									=	$SR_Code;
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
		if($targetInfo[$INDEX_VAL]	==	$INDEX_VAL) {
			$finalSearchInfo[$i]["PRO_TGT"]			=   $targetUnits[$INDEX_VAL]["productive_percent"];
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
			if($row_transhdr[transaction_Reference_Number] !='' && $row_transhdr[transaction_Reference_Number] != 0) {
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
		if(array_search($REFVAL,$transno_transhdr)) {
			$arraysearchval		=	array_search($REFVAL,$transno_transhdr);
			unset($transno_transhdr[$arraysearchval]);
			unset($transhdr_result[$arraysearchval]);
		}
	}

	//pre($transno_transhdr);
	foreach($transno_cancel_number AS $REFVAL){
		if(array_search($REFVAL,$transno_transhdr)) {
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


	$query_focusact										=   "SELECT id,DSR_Code,LEFT(Date,10) AS DATEFOC,SUM(replace(focus_Flag,'Yes','1')) AS FOCUS_ACT FROM dailystockloading $focuscheck_query AND (focus_Flag ='Yes' OR focus_Flag ='yes' OR focus_Flag = 'YES') GROUP BY DSR_Code,Date";
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

	$i=0;
	foreach($finalSearchInfo as $val_focussold)	{
		$SRCODEVAL			=	$val_focussold["DSRCode"];
		$DateValCK			=	$val_focussold["DateVal"];

		$INDEX_VAL			=	$DateValCK.$SRCODEVAL;
		//echo	$focusflagitems[$INDEX_VAL][FOC_ID]	. "==".	$INDEX_VAL."<br>"; 
		if($focusactInfo[$INDEX_VAL][FOCUS_ID]	==	$INDEX_VAL) {
			$finalSearchInfo[$i]["FOCUS_ACTUAL"]			=   $focusactInfo[$INDEX_VAL]["FOCUS_ACTUAL"];
			$finalSearchInfo[$i]["PRO_ACT"]					=   $finalSearchInfo[$i]["visit_Count"]/$finalSearchInfo[$i]["SALES_Count"];
			$finalSearchInfo[$i]["FOCUS_COV"]				=   round($finalSearchInfo[$i]["FOC_CNT"]/$finalSearchInfo[$i]["FOCUS_ACTUAL"]);
		} else {
			$finalSearchInfo[$i]["FOCUS_ACTUAL"]			=   0;
			$finalSearchInfo[$i]["PRO_ACT"]					=   $finalSearchInfo[$i]["visit_Count"]/$finalSearchInfo[$i]["SALES_Count"];
			$finalSearchInfo[$i]["FOCUS_COV"]				=   round($finalSearchInfo[$i]["FOC_CNT"]/$finalSearchInfo[$i]["FOCUS_ACTUAL"]);
		}
		$i++;
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
	$finalSearchInfo	=	subval_sort($finalSearchInfo,$orderbycolumns,$dir);
	//pre($finalSearchInfo);
	//exit;

} else {
	$nextrecval			=	"";
}
$num_rows		=	count($finalSearchInfo);
?>
  <table border="1" width="100%">
	<thead>
	  <tr>
		<th align="center" style="width:10%">SR Name</th>
		<th align="center" style="width:10%">Date</th>
		<th align="center" style="width:10%">First Check In Time</th>
		<th align="center" style="width:10%">Last Check Out Time</th>
		<th align="center" style="width:10%">TotalHours</th>
		<th align="center" style="width:10%">Total Customers Planned</th>
		<th align="center" style="width:10%">Total Customer Visited</th>
		<th align="center" style="width:10%">TotalCustomers Not Covered</th>
		<th align="center" style="width:10%">Total Sale Visits</th>
		<th align="center" style="width:10%">Productivity %
		 <table  width="100%"><tr><td>Target</td><td>Actual</td></tr></table>
		</th>
		<th align="center" style="width:10%">Total Sales (Naira)</th>
		<th align="center" style="width:10%">Total Invoices</th>
		<th align="center" style="width:10%">Total Line Items</th>
		<th align="center" style="width:10%">Focus Items
		<table  width="100%"><tr><td>In Plan</td><td>Sold</td></tr></table>
		</th>
		<th align="center" style="width:10%">Metrics
		<table  width="100%"><tr><td>Drop Size</td><td>Basket Size</td><td>Focus Coverage</td></tr></table>
		</th> 
  </tr>
  </thead>
 <tbody>

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
	$total_hours			+=	$SearchVal["TOTALHR"];
	$total_cus_plan			+=	$SearchVal["CUSTCNT"];
	$total_cus_vis			+=	$SearchVal["visit_Count"];
	$total_not_cov			+=	$SearchVal["TOTALNOT"];
	$total_sal_vis			+=	$SearchVal["SALES_Count"];
	$total_prod_tgt			+=	$SearchVal["PRO_TGT"];
	$total_prod_act			+=	$SearchVal["PRO_ACT"];
	$total_sales			+=	$SearchVal["Sale_Value"];
	$total_invoice			+=	$SearchVal["SALES_Count"];
	$total_line_items		+=	$SearchVal["Invoice_Line_Count"];
	$total_focus_inplan		+=	$SearchVal["FOCUS_ACTUAL"];
	$total_focus_sold		+=	$SearchVal["FOC_CNT"];
	$total_drop				+=	$SearchVal["Drop_Value"];
	$total_bas				+=	$SearchVal["Basket_Value"];
	$total_focus_cov		+=	$SearchVal["FOCUS_COV"];
	
	elseif($reportby == 'DateVal') {
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
				$tot_hours				+=	$SearchVal["TOTALHR"];
				$tot_cus_plan			+=	$SearchVal["CUSTCNT"];
				$tot_cus_vis			+=	$SearchVal["visit_Count"];
				$tot_not_cov			+=	$SearchVal["TOTALNOT"];
				$tot_sal_vis			+=	$SearchVal["SALES_Count"];
				$tot_prod_tgt			+=	$SearchVal["PRO_TGT"];
				$tot_prod_act			+=	$SearchVal["PRO_ACT"];
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
				$tot_hours				+=	$SearchVal["TOTALHR"];
				$tot_cus_plan			+=	$SearchVal["CUSTCNT"];
				$tot_cus_vis			+=	$SearchVal["visit_Count"];
				$tot_not_cov			+=	$SearchVal["TOTALNOT"];
				$tot_sal_vis			+=	$SearchVal["SALES_Count"];
				$tot_prod_tgt			+=	$SearchVal["PRO_TGT"];
				$tot_prod_act			+=	$SearchVal["PRO_ACT"];
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
				$tot_hours				+=	$SearchVal["TOTALHR"];
				$tot_cus_plan			+=	$SearchVal["CUSTCNT"];
				$tot_cus_vis			+=	$SearchVal["visit_Count"];
				$tot_not_cov			+=	$SearchVal["TOTALNOT"];
				$tot_sal_vis			+=	$SearchVal["SALES_Count"];
				$tot_prod_tgt			+=	$SearchVal["PRO_TGT"];
				$tot_prod_act			+=	$SearchVal["PRO_ACT"];
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
				$tot_hours				+=	$SearchVal["TOTALHR"];
				$tot_cus_plan			+=	$SearchVal["CUSTCNT"];
				$tot_cus_vis			+=	$SearchVal["visit_Count"];
				$tot_not_cov			+=	$SearchVal["TOTALNOT"];
				$tot_sal_vis			+=	$SearchVal["SALES_Count"];
				$tot_prod_tgt			+=	$SearchVal["PRO_TGT"];
				$tot_prod_act			+=	$SearchVal["PRO_ACT"];
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
		$tot_hours				+=	$SearchVal["TOTALHR"];
		$tot_cus_plan			+=	$SearchVal["CUSTCNT"];
		$tot_cus_vis			+=	$SearchVal["visit_Count"];
		$tot_not_cov			+=	$SearchVal["TOTALNOT"];
		$tot_sal_vis			+=	$SearchVal["SALES_Count"];
		$tot_prod_tgt			+=	$SearchVal["PRO_TGT"];
		$tot_prod_act			+=	$SearchVal["PRO_ACT"];
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
		   <td><?php echo $tot_hours; ?></td>	
			 <td><?php echo $tot_cus_plan; ?></td>
			 <td><?php echo $tot_cus_vis; ?></td>	
			 <td><?php echo $tot_not_cov; ?></td>	
			 <td><?php echo $tot_sal_vis; ?></td>	
			 <td>&nbsp;
			 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_prod_tgt; ?></td><td><?php echo $tot_prod_act; ?></td></tr></table>
			 </td>	
			 <td><?php echo $tot_sales; ?></td>	
			 <td><?php echo $tot_invoice; ?></td>	
			 <td><?php echo $tot_line_items; ?></td>	
			 <td>&nbsp;
			 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_focus_inplan; ?></td><td><?php echo $tot_focus_sold; ?></td></tr></table>
			 </td>	
			 <td>&nbsp;
			 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_drop; ?></td><td><?php echo $tot_bas; ?></td><td><?php echo $tot_focus_cov; ?></td></tr></table>
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
<tr>
	 <td <?php if($reportby == 'DSR_Name') { ?> style="background-color:#31859C;" <?php } ?>><?php echo ucwords(strtolower($SearchVal[DSR_Name])); ?></td>
	 <td <?php if($reportby == 'DateVal') { ?> style="background-color:#31859C;" <?php } ?>><?php echo $SearchVal[DateVal]; ?></td>
	 <td><?php echo $SearchVal[FIRSTIN]; ?></td>	
	 <td><?php echo $SearchVal[LASTOUT]; ?></td>
	 <td><?php echo $SearchVal[TOTALHR]; ?></td>	
	 <td><?php echo $SearchVal[CUSTCNT]; ?></td>
	 <td><?php echo $SearchVal[visit_Count]; ?></td>	
	 <td><?php echo $SearchVal[TOTALNOT]; ?></td>	
	 <td><?php echo $SearchVal[SALES_Count]; ?></td>	
	 <td>&nbsp;
	 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $SearchVal[PRO_TGT]; ?></td><td><?php echo $SearchVal[PRO_ACT]; ?></td></tr></table>
	 </td>	
	 <td><?php echo $SearchVal[Sale_Value]; ?></td>	
	 <td><?php echo $SearchVal[SALES_Count]; ?></td>	
	 <td><?php echo $SearchVal[Invoice_Line_Count]; ?></td>	
	 <td>&nbsp;
	 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $SearchVal[FOCUS_ACTUAL]; ?></td><td><?php echo $SearchVal[FOC_CNT]; ?></td></tr></table>
	 </td>	
	 <td>&nbsp;
	 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $SearchVal[Drop_Value]; ?></td><td><?php echo $SearchVal[Basket_Value]; ?></td><td><?php echo $SearchVal[FOCUS_COV]; ?></td></tr></table>
	  </td>	
	  
 </tr>
 <?php $k++; } ?> 
 <tr>
	 <td colspan="4" align="right"><strong>Sub Total<strong></td>
	 <td><?php echo $tot_hours; ?></td>	
	 <td><?php echo $tot_cus_plan; ?></td>
	 <td><?php echo $tot_cus_vis; ?></td>	
	 <td><?php echo $tot_not_cov; ?></td>	
	 <td><?php echo $tot_sal_vis; ?></td>	
	 <td>&nbsp;
	 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_prod_tgt; ?></td><td><?php echo $tot_prod_act; ?></td></tr></table>
	 </td>	
	 <td><?php echo $tot_sales; ?></td>	
	 <td><?php echo $tot_invoice; ?></td>	
	 <td><?php echo $tot_line_items; ?></td>	
	 <td>&nbsp;
	 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_focus_inplan; ?></td><td><?php echo $tot_focus_sold; ?></td></tr></table>
	 </td>	
	 <td>&nbsp;
	 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_drop; ?></td><td><?php echo $tot_bas; ?></td><td><?php echo $tot_focus_cov; ?></td></tr></table>
	  </td>
 </tr>
 <tr>
	 <td colspan="4" align="right"><strong>Grand Total<strong></td>
	 <td><?php echo $total_hours; ?></td>	
	 <td><?php echo $total_cus_plan; ?></td>
	 <td><?php echo $total_cus_vis; ?></td>	
	 <td><?php echo $total_not_cov; ?></td>	
	 <td><?php echo $total_sal_vis; ?></td>	
	 <td>&nbsp;
	 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $total_prod_tgt; ?></td><td><?php echo $total_prod_act; ?></td></tr></table>
	 </td>	
	 <td><?php echo $total_sales; ?></td>	
	 <td><?php echo $total_invoice; ?></td>	
	 <td><?php echo $total_line_items; ?></td>	
	 <td>&nbsp;
	 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $total_focus_inplan; ?></td><td><?php echo $total_focus_sold; ?></td></tr></table>
	 </td>	
	 <td>&nbsp;
	 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $total_drop; ?></td><td><?php echo $total_bas; ?></td><td><?php echo $total_focus_cov; ?></td></tr></table>
	  </td>
 </tr>
 </tbody>	
</table>
<span id="printopen" style="padding-left:470px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printkdsaleslistajax');"></span>
<form id="printkdsaleslistajax" target="_blank" action="printkdsaleslistajax.php" method="post">
	<input type="hidden" name="kdcode" id="kdcode" value="<?php echo $kdcode; ?>" />
	<input type="hidden" name="brandcode" id="brandcode" value="<?php echo $brandcode; ?>" />
	<input type="hidden" name="prodcode" id="prodcode" value="<?php echo $prodcode; ?>" />
	<input type="hidden" name="asmcode" id="asmcode" value="<?php echo $asmcode; ?>" />
	<input type="hidden" name="rsmcode" id="rsmcode" value="<?php echo $rsmcode; ?>" />
	<input type="hidden" name="fromdatevalue" id="fromdatevalue" value="<?php echo $fromdatevalue; ?>" />
	<input type="hidden" name="todatevalue" id="todatevalue" value="<?php echo $todatevalue; ?>" />
</form>
<?php } else { ?>
 <tr>
	<td colspan="10" align='center'><strong>NO RECORDS FOUND</strong></td>
 </tr>
<?php } exit(0); ?>