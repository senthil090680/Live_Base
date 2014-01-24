<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
//require_once "../include/ps_pagination.php";
?>
<title>SR INCENTIVE STATUS</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<style type="text/css">
.heading_report{
	background:#a09e9e;
	width:100%;
	margin-left:auto;
	margin-right:auto;
	height:25px;
	padding-top:5px;
	border-radius:6px;
	font-weight:bold;
	font-size:14px;
	clear:both;
}
#mytableform_report{
	background:#fff;
	width:99%;
	margin-left:auto;
	margin-right:auto;
	height:auto;
	overflow:auto;
}
.alignment_report{
width:96%;
padding-left:20px;
margin-left:10px;
font-size:16px;
}
.condaily_routeplan th,.conproduct th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}
.condaily_routeplan td,.conproduct td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	padding-left:10px;
	color:#000;
	font-size:14px;
}
.condaily_routeplan tbody tr:hover td,.conproduct tbody tr:hover td {
	background: #c1c1c1;
}
.condaily_routeplan{
	width:100%;
	text-align:left;
	height:auto;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	overflow:auto;
	overflow-x:hidden;
}
.conproduct{
	width:100%;
	text-align:left;
	height:auto;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	/* overflow:auto;
	overflow-x:scroll; */
}
#errormsgsrsta {
	display:none;
	width:40%;
	height:30px;
	background:#c1c1c1;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	padding-top:0px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	-ms-border-radius:10px;
	-o-border-radius:10px;
	text-align:center;
}
.myalignsrsta {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}

.buttons_new{
	-webkit-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	-moz-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	border-bottom-color:#333;
	border:1px solid #686868;
	background-color:#31859C;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	color:#000;
	font-family:Calibri;
	font-size:12px;
	padding:3px;
	cursor:pointer;
	width:160px;
	height:15px;
}
.buttons_gray {
	-webkit-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	-moz-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	border-bottom-color:#333;
	border:1px solid #686868;
	background-color:#A09E9E;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	color:#000;
	font-family:Calibri;
	font-size:12px;
	padding:3px;
	cursor:pointer;
	width:240px;
	height:15px;
}

.align2 {
	padding-left:10px;
}
.lefttable{
	width:48%;
	float:left;
	height:210px;
}
.righttable{
	width:48%;
	float:right;
	height:210px;
}
#colors{
	background-color:#CCC;
} 
</style>
<?php
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
$monthplan_query	=	'';
if(isset($_REQUEST[propmonths]) && $_REQUEST[propmonths] !='') {
	
	//$propmonths				=	"October";
	$finddates				=	$propmonths." ".$propyears; 
	$fromdatevalue			=	date('Y-m-01',strtotime($finddates));
	$todatevalue			=	date('Y-m-t',strtotime($finddates));
	//echo $propmonths;
	$monthval				=	ltrim(date('m',strtotime($propmonths)),0);

	//echo $monthval;
	/*echo $fromdatevalue."<br>";
	echo $todatevalue."<br>";
	exit;*/
	
	//$datecol		=	"(Date >= '".$fromdatevalue."' AND Date <= '".$todatevalue."')";
	//$datecolfocus	=	"(LEFT(Date,10) >= '".$fromdatevalue."' AND LEFT(Date,10) <= '".$todatevalue."')";
	$datecol		=	"(LEFT(Date,10) >= '".$fromdatevalue."' AND LEFT(Date,10) <= '".$todatevalue."')";

	if($monthplan_query	==	'') {
		//$propmonthsval			=	ltrim($monthval,0);
		$monthplan_query		.=	" WHERE routemonth = '$monthval' AND routeyear = '$propyears'";
	} else if($monthplan_query	!=	'') {
		//$propmonthsval			=	ltrim($monthval,0);
		$monthplan_query		.=	" AND routemonth = '$monthval' AND routeyear = '$propyears'";
	}
	
	if($asmcode	==	'' || $asmcode == 'null') {
		$asmcodecol		=	'';
		$wherefordsr	=	'';
	} elseif($asmcode	!=	'') {
		//$asmcodestr	=	implode("','",$asmcode);
		$asmcodestr		=	$asmcode;
		$asmcodecol		=	"ASM IN ('".$asmcodestr."')";
		$asmcodecolval	=	"DSR_Code IN ('".$asmcodestr."')";
		$wherefordsr	=	'WHERE';
	}

	if($rsmcode	==	'' || $rsmcode == 'null') {
		$rsmcodecol		=	'';
	} elseif($rsmcode	!=	'') {
		//$rsmcodestr	=	implode("','",$rsmcode);
		$rsmcodestr		=	$rsmcode;
		$rsmcodecol		=	"RSM IN ('".$rsmcodestr."')";
	}
	
	if($srcode	==	'' || $srcode == 'null') {
		$DSR_Codestr		=	'';
	} elseif($srcode	!=	'') {
		$DSR_Codestr		=	$srcode;
		//$DSR_Codestr		=	implode("','",$srcode);
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
	
	if($monthplan_query	==	'') {
		if($DSR_Codestr	==	'') {
			$monthplan_query		.=	"";
		} else {
			$monthplan_query		.=	" WHERE DSR_Code IN ('".$DSR_Codestr."')";
		}
	} else if($monthplan_query	!=	'') {
		if($DSR_Codestr	==	'') {
			$monthplan_query		.=	"";
		} else {
			$monthplan_query		.=	" AND DSR_Code IN ('".$DSR_Codestr."')";
		}
	}

	//echo $monthplan_query;
	//exit;
	if($Custypestr	==	'') {
		$custype_query		.=	"";
	} else {
		$custype_query		.=	" WHERE customer_type IN ('".$Custypestr."')";
	}

	if($target_query	==	'') {
		if($DSR_Codestr	==	'') {
			$mistarget_query		=	"";
		} else {
			$mistarget_query		=	" WHERE SR_Code IN ('".$DSR_Codestr."')";
		}
	} else if($target_query	!=	'') {
		if($DSR_Codestr	==	'') {
			$mistarget_query		=	"";
		} else {
			$mistarget_query		=	$target_query." AND SR_Code IN ('".$DSR_Codestr."')";
		}
	}

	if($target_query	==	'') {
		if($DSR_Codestr	==	'') {
			$target_query		.=	"";
		} else {
			$target_query		.=	" WHERE DSR_Code IN ('".$DSR_Codestr."')";
		}
	} else if($target_query	!=	'') {
		if($DSR_Codestr	==	'') {
			$target_query		.=	"";
		} else {
			$target_query		.=	" AND DSR_Code IN ('".$DSR_Codestr."')";
		}
	}


	if($complete_query	==	'') {
		$complete_query			.=	" WHERE $datecol";
		//$complete_query		.=	" WHERE $datecol";
	} else if($complete_query	!=	'') {
		$complete_query			.=	" AND $datecol";
		//$complete_query		.=	" AND $datecol";
	}
	
	if($target_query	==	'') {		
		$target_query		.=	" WHERE monthval = '$monthval' AND yearval = '$propyears'";
	} else if($target_query	!=	'') {
		$target_query		.=	" AND monthval = '$monthval' AND yearval = '$propyears'";
	}

	if($mistarget_query	==	'') {		
		$mistarget_query		.=	" WHERE monthval = '$monthval' AND yearval = '$propyears'";
	} else if($mistarget_query	!=	'') {
		$mistarget_query		.=	" AND monthval = '$monthval' AND yearval = '$propyears'";
	}

	if($focuscheck_query	==	'') {
		$focuscheck_query			.=	" WHERE $datecolfocus";
		//$complete_query		.=	" WHERE $datecol";
	} else if($focuscheck_query	!=	'') {
		$focuscheck_query			.=	" AND $datecolfocus";
		//$complete_query		.=	" AND $datecol";
	}

	//echo $complete_query."<br>";
	//echo $target_query."<br>";
	//exit;
					
	$query_trans									=   "SELECT DISTINCT KD_Code,DSR_Code,Date,visit_Count,Invoice_Count,SUM(effective_count) AS EFF_CNT,SUM(productive_count) AS PROD_CNT,Invoice_Line_Count,Total_Sale_Value,Drop_Size_Value,Basket_Size_Value FROM dsr_metrics $complete_query GROUP BY DSR_Code ORDER BY Date";
	
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
			$finalkdInfo[$i]["EFF_CNT"]								=   $val_kd["EFF_CNT"];
			$finalkdInfo[$i]["PROD_CNT"]							=   $val_kd["PROD_CNT"];
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


	$query_dsr										=   "SELECT DSRName,DSR_Code FROM dsr WHERE DSR_Code IN ('".$dsrcode_Total."')";
	//echo $query_dsr;
	//exit;
	$res_dsr										=   mysql_query($query_dsr);
	while($row_dsr									=   mysql_fetch_assoc($res_dsr)) {
		$dsrInfo[$row_dsr["DSR_Code"]]				=	$row_dsr;
		$asmcode_dsr[]								=	$row_dsr["ASM"];
	}
	
	//pre($dsrInfo);
	//exit;
	//$asmcode_dsr				=	array_unique($asmcode_dsr);
	//$asmcode_Total			=	implode("','",$asmcode_dsr);

	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_dsr){
		//echo $dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] . "-". $val_dsr["DSRCode"]."<br>";
		if($dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] == $val_dsr["DSRCode"]) {                                    
			$finaldsrInfo[$i]["DSR_Name"]							=   $dsrInfo[$val_dsr["DSRCode"]]["DSRName"];
			$finaldsrInfo[$i]["KD_Name"]							=   $val_dsr["KD_Name"];
			$finaldsrInfo[$i]["KD_Code"]							=   $val_dsr["KD_Code"];
			$finaldsrInfo[$i]["DSRCode"]							=   $val_dsr["DSRCode"];
			$finaldsrInfo[$i]["DateVal"]							=   $val_dsr["DateVal"];
			$finaldsrInfo[$i]["visit_Count"]						=   $val_dsr["visit_Count"];
			$finaldsrInfo[$i]["SALES_Count"]						=   $val_dsr["SALES_Count"];
			$finaldsrInfo[$i]["EFF_CNT"]							=   $val_dsr["EFF_CNT"];
			$finaldsrInfo[$i]["PROD_CNT"]							=   $val_dsr["PROD_CNT"];
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
	
	$query_route										=   "SELECT id,KD_Code,DSR_Code,day1,day2,day3,day4,day5,day6,day7,day8,day9,day10,day11,day12,day13,day14,day15,day16,day17,day18,day19,day20,day21,day22,day23,day24,day25,day26,day27,day28,day29,day30,day31 FROM routemonthplan $monthplan_query";
	//echo $query_route;
	//exit;

	$res_route											=   mysql_query($query_route);
	while($row_route									=   mysql_fetch_assoc($res_route)) {
		//$routeInfo[$row_route["DSR_Code"]]				=	array_filter(array_unique(array($row_route[day1],$row_route[day2],$row_route[day3],$row_route[day4],$row_route[day5],$row_route[day6],$row_route[day7],$row_route[day8],$row_route[day9],$row_route[day10],$row_route[day11],$row_route[day12],$row_route[day13],$row_route[day14],$row_route[day15],$row_route[day16],$row_route[day17],$row_route[day18],$row_route[day19],$row_route[day20],$row_route[day21],$row_route[day22],$row_route[day23],$row_route[day24],$row_route[day25],$row_route[day26],$row_route[day27],$row_route[day28],$row_route[day29],$row_route[day30],$row_route[day31])));
		
		$routeInfoCount[$row_route["DSR_Code"]]				=	array_filter(array($row_route[day1],$row_route[day2],$row_route[day3],$row_route[day4],$row_route[day5],$row_route[day6],$row_route[day7],$row_route[day8],$row_route[day9],$row_route[day10],$row_route[day11],$row_route[day12],$row_route[day13],$row_route[day14],$row_route[day15],$row_route[day16],$row_route[day17],$row_route[day18],$row_route[day19],$row_route[day20],$row_route[day21],$row_route[day22],$row_route[day23],$row_route[day24],$row_route[day25],$row_route[day26],$row_route[day27],$row_route[day28],$row_route[day29],$row_route[day30],$row_route[day31])); // to find the 
	}

	//pre($routeInfo);
	//exit;
	//pre($routeInfoCount);
	
	foreach($routeInfoCount AS $routeFindKey=>$routeFind) {
		$routeCntCus[$routeFindKey]		=	array_count_values($routeFind);
	}
	//pre($routeCntCus);
	//exit;
	foreach($routeCntCus AS $rtecntKey=>$rtecntVal) {
		foreach($rtecntVal AS $rtevalKey=>$rtevalVal) {
			$actualcus						=	findCustomerCount($rtevalKey,$rtecntKey);
			//echo $actualcus."<br>";
			//echo $rtevalVal."<br>";
			$routestring[$rtecntKey][CNTID]			+=	($actualcus*$rtevalVal);
			$routestring[$rtecntKey][DSRID]			=	$rtecntKey;
		}
		//$routeCntCust[$rtecntKey.]		=	;
	}
	
	//pre($routestring);
	//exit;
	
	/*foreach($routeInfo AS $routeInx=>$routeArr) {
		$routestr		=	implode("','",$routeArr);
		$dsridval		=	$routeInx;
		$routeidval		=	$routestr;
		if($routeidval) {
			$routestring[$routeInx][CNTID]	=	findCustomerCount($routestr,$routeInx);
			$routestring[$routeInx][DSRID]	=	$routeInx;
		}
	}*/

	//pre($routestring);
	//exit;

	$query_mistarget									=   "SELECT KD_Code,SR_Code,monthval,yearval,coverage_percent,productive_percent,effective_percent,cov_visit,prod_visit,eff_visit,cov_status,prod_status,eff_status,tgtTypeCov,tgtTypeProd,tgtTypeEff FROM coverage_target_setting $mistarget_query ORDER BY SR_Code";
	//echo $query_mistarget;
	//exit;
	$res_mistarget												=   mysql_query($query_mistarget);
	while($row_mistarget										=   mysql_fetch_assoc($res_mistarget)) {
		$SR_Code												=	$row_mistarget[SR_Code];
		$KD_Code												=	$row_mistarget[KD_Code];
		$COVPER[$SR_Code.$KD_Code]["COV_PER"]					=	$row_mistarget["coverage_percent"];
		$PROPER[$SR_Code.$KD_Code]["PRO_PER"]					=	$row_mistarget["productive_percent"];
		$EFFPER[$SR_Code.$KD_Code]["EFF_PER"]					=	$row_mistarget["effective_percent"];
		$COVVIS[$SR_Code.$KD_Code]["COV_VIS"]					=	$row_mistarget["cov_visit"];
		$PROVIS[$SR_Code.$KD_Code]["PRO_VIS"]					=	$row_mistarget["prod_visit"];
		$EFFVIS[$SR_Code.$KD_Code]["EFF_VIS"]					=	$row_mistarget["eff_visit"];
		$COVSTA[$SR_Code.$KD_Code]["COV_STA"]					=	$row_mistarget["cov_status"];
		$PROSTA[$SR_Code.$KD_Code]["PRO_STA"]					=	$row_mistarget["prod_status"];
		$EFFSTA[$SR_Code.$KD_Code]["EFF_STA"]					=	$row_mistarget["eff_status"];
		$TARFLAG[$SR_Code.$KD_Code]["tgtTypeCov"]				=	$row_mistarget["tgtTypeCov"];
		$TARFLAG[$SR_Code.$KD_Code]["tgtTypeProd"]				=	$row_mistarget["tgtTypeProd"];
		$TARFLAG[$SR_Code.$KD_Code]["tgtTypeEff"]				=	$row_mistarget["tgtTypeEff"];
		
		//$TARFLAG[$SR_Code.$KD_Code]["targetFlag"]				=	$row_mistarget["targetFlag"];
		$targetInfo[$SR_Code.$KD_Code]							=	$SR_Code.$KD_Code;
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

		$INDEX_VAL			=	$SRCODEVAL.$KD_CODE;
		//echo	$targetInfo[$INDEX_VAL]	. "==".	$INDEX_VAL."<br>"; 
		if($targetInfo[$INDEX_VAL]	==	$INDEX_VAL) {
			$finalSearchInfo[$i]["COV_PER"]			=   $COVPER[$INDEX_VAL]["COV_PER"];
			$finalSearchInfo[$i]["PRO_PER"]			=   $PROPER[$INDEX_VAL]["PRO_PER"];
			$finalSearchInfo[$i]["EFF_PER"]			=   $EFFPER[$INDEX_VAL]["EFF_PER"];
			$finalSearchInfo[$i]["COV_VIS"]			=   $COVVIS[$INDEX_VAL]["COV_VIS"];
			$finalSearchInfo[$i]["PRO_VIS"]			=   $PROVIS[$INDEX_VAL]["PRO_VIS"];
			$finalSearchInfo[$i]["EFF_VIS"]			=   $EFFVIS[$INDEX_VAL]["EFF_VIS"];
			$finalSearchInfo[$i]["COV_STA"]			=   $COVSTA[$INDEX_VAL]["COV_STA"];
			$finalSearchInfo[$i]["PRO_STA"]			=   $PROSTA[$INDEX_VAL]["PRO_STA"];
			$finalSearchInfo[$i]["EFF_STA"]			=   $EFFSTA[$INDEX_VAL]["EFF_STA"];
			//$finalSearchInfo[$i]["targetFlag"]	=   $TARFLAG[$INDEX_VAL]["targetFlag"];
						
			$finalSearchInfo[$i]["tgtTypeCov"]		=   $TARFLAG[$INDEX_VAL]["tgtTypeCov"];
			$finalSearchInfo[$i]["tgtTypeProd"]		=   $TARFLAG[$INDEX_VAL]["tgtTypeProd"];
			$finalSearchInfo[$i]["tgtTypeEff"]		=   $TARFLAG[$INDEX_VAL]["tgtTypeEff"];
			
			$finalSearchInfo[$i]["EFF_COV"]			=   ceil($finalSearchInfo[$i]["PROD_CNT"]/$finalSearchInfo[$i]["visit_Count"]);
			$finalSearchInfo[$i]["PRO_COV"]			=   ceil($finalSearchInfo[$i]["SALES_Count"]/$finalSearchInfo[$i]["visit_Count"]);			
			$finalSearchInfo[$i]["POS_INC"]			=   $finalSearchInfo[$i]["EFF_COV"]*$finalSearchInfo[$i]["EFF_VIS"];
			$finalSearchInfo[$i]["POS_INC_PRO"]		=   $finalSearchInfo[$i]["PRO_COV"]*$finalSearchInfo[$i]["PRO_VIS"];
		}
		$i++;
	}
	//pre($finalSearchInfo);
	//exit;
	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_route){
		//echo $routestring[$val_route["DSRCode"]][DSRID] . "-". $val_route["DSRCode"]."<br>";
		if($routestring[$val_route["DSRCode"]][DSRID] == $val_route["DSRCode"]) {          
			$finalSearchInfo[$i]["CNTID"]			=  $routestring[$val_route["DSRCode"]][CNTID];
			//echo $finalSearchInfo[$i]["CNTID"]."<br>";
			//$finalSearchInfo[$i]["COV_COV"]						=	ceil($finalSearchInfo[$i]["visit_Count"]/$finalSearchInfo[$i]["CNTID"]);

			//THIS IS FOR EFFECTIVE COVERAGE STARTS HERE
			if($finalSearchInfo[$i]["EFF_STA"] == '5') {
				$finalSearchInfo[$i]["VISIT_COV"]					=	($finalSearchInfo[$i]["EFF_PER"]/100)*($finalSearchInfo[$i]["CNTID"]);

				if($finalSearchInfo[$i]["tgtTypeEff"] == '0') {
					if($finalSearchInfo[$i]["EFF_COV"] >= $finalSearchInfo[$i]["VISIT_COV"]) {
						$finalSearchInfo[$i]["PAY_INC"]				=   $finalSearchInfo[$i]["EFF_COV"]*$finalSearchInfo[$i]["EFF_VIS"];
					} else {
						$finalSearchInfo[$i]["PAY_INC"]				=   0;
					}
				} if($finalSearchInfo[$i]["tgtTypeEff"] == '1') {
					$finalSearchInfo[$i]["PAY_INC"]					=   $finalSearchInfo[$i]["EFF_COV"]*$finalSearchInfo[$i]["EFF_VIS"];
				}				
			} else if($finalSearchInfo[$i]["EFF_STA"] == '10') {

				//$finalSearchInfo[$i]["VISIT_COV"]					=	($finalSearchInfo[$i]["EFF_PER"]/100)*($finalSearchInfo[$i]["CNTID"]);
				$finalSearchInfo[$i]["VISIT_COV"]					=	$finalSearchInfo[$i]["EFF_PER"];
				
				if($finalSearchInfo[$i]["tgtTypeEff"] == '0') {
					if($finalSearchInfo[$i]["EFF_COV"] >= $finalSearchInfo[$i]["VISIT_COV"]) {
						$finalSearchInfo[$i]["PAY_INC"]				=   $finalSearchInfo[$i]["EFF_COV"]*$finalSearchInfo[$i]["EFF_VIS"];
					} else {
						$finalSearchInfo[$i]["PAY_INC"]				=   0;
					}
				} if($finalSearchInfo[$i]["tgtTypeEff"] == '1') {
					$finalSearchInfo[$i]["PAY_INC"]					=   $finalSearchInfo[$i]["EFF_COV"]*$finalSearchInfo[$i]["EFF_VIS"];
				}
			} else {	
				$finalSearchInfo[$i]["PAY_INC"]						=   $finalSearchInfo[$i]["EFF_COV"]*$finalSearchInfo[$i]["EFF_VIS"];
			}

			//THIS IS FOR EFFECTIVE COVERAGE ENDS HERE

			//THIS IS FOR COVERAGE STARTS HERE
			//echo "Pictures";
			//exit;

			/*echo $finalSearchInfo[$i]["COV_STA"]."9282";
			echo $finalSearchInfo[$i]["EFF_STA"]."<br>223";
			echo $finalSearchInfo[$i]["PRO_STA"]."<br>9282<br>";*/
			//echo $i."<br>";

			if($finalSearchInfo[$i]["COV_STA"] == '5') {
				//echo "ere";
				//exit;
				$finalSearchInfo[$i]["COV_COV"]						=	ceil($finalSearchInfo[$i]["visit_Count"]/$finalSearchInfo[$i]["CNTID"]);
				$finalSearchInfo[$i]["POS_INC_COV"]					=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
				$finalSearchInfo[$i]["VISIT_COV_ACT"]				=	($finalSearchInfo[$i]["COV_PER"]/100)*($finalSearchInfo[$i]["CNTID"]);

				if($finalSearchInfo[$i]["tgtTypeCov"] == '0') {
					if($finalSearchInfo[$i]["COV_COV"] >= $finalSearchInfo[$i]["VISIT_COV_ACT"]) {
						$finalSearchInfo[$i]["PAY_INC_COV"]			=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
					} else {
						$finalSearchInfo[$i]["PAY_INC_COV"]			=   0;
					}
				} if($finalSearchInfo[$i]["tgtTypeCov"] == '1') {
					$finalSearchInfo[$i]["PAY_INC_COV"]				=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
				}				
			} else if($finalSearchInfo[$i]["COV_STA"] == '10') {
				$finalSearchInfo[$i]["COV_COV"]						=	ceil($finalSearchInfo[$i]["visit_Count"]/$finalSearchInfo[$i]["COV_PER"]);
				$finalSearchInfo[$i]["POS_INC_COV"]					=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
				//echo "234efe";
				//exit;
				//$finalSearchInfo[$i]["VISIT_COV_ACT"]				=	($finalSearchInfo[$i]["COV_PER"]/100)*($finalSearchInfo[$i]["CNTID"]);
				$finalSearchInfo[$i]["VISIT_COV_ACT"]				=	$finalSearchInfo[$i]["COV_PER"];

				if($finalSearchInfo[$i]["tgtTypeCov"] == '0') {
					if($finalSearchInfo[$i]["COV_COV"] >= $finalSearchInfo[$i]["VISIT_COV_ACT"]) {
						$finalSearchInfo[$i]["PAY_INC_COV"]			=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
					} else {
						$finalSearchInfo[$i]["PAY_INC_COV"]			=   0;
					}
				} if($finalSearchInfo[$i]["tgtTypeCov"] == '1') {
					$finalSearchInfo[$i]["PAY_INC_COV"]				=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
				}
			} else {
				//echo "dere";
				$finalSearchInfo[$i]["POS_INC_COV"]					=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
				$finalSearchInfo[$i]["PAY_INC_COV"]					=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
			}
			//THIS IS FOR COVERAGE ENDS HERE

			//THIS IS FOR PRODUCTIVITY COVERAGE STARTS HERE
			if($finalSearchInfo[$i]["PRO_STA"] == '5') {
				$finalSearchInfo[$i]["VISIT_PRO_ACT"]				= ($finalSearchInfo[$i]["PRO_PER"]/100)*($finalSearchInfo[$i]["CNTID"]);

				if($finalSearchInfo[$i]["tgtTypeProd"] == '0') {

					//echo $finalSearchInfo[$i]["PRO_COV"]."===".$finalSearchInfo[$i]["VISIT_PRO_ACT"]."<br>";
					if($finalSearchInfo[$i]["PRO_COV"] >= $finalSearchInfo[$i]["VISIT_PRO_ACT"]) {
						$finalSearchInfo[$i]["PAY_INC_PRO"]			=   $finalSearchInfo[$i]["PRO_COV"]*$finalSearchInfo[$i]["PRO_VIS"];
					} else {
						$finalSearchInfo[$i]["PAY_INC_PRO"]			=   0;
					}
				} if($finalSearchInfo[$i]["tgtTypeProd"] == '1') {
					$finalSearchInfo[$i]["PAY_INC_PRO"]				=   $finalSearchInfo[$i]["PRO_COV"]*$finalSearchInfo[$i]["PRO_VIS"];
				}
				//echo $finalSearchInfo[$i]["PAY_INC_PRO"]."<br>";
			} else if($finalSearchInfo[$i]["PRO_STA"] == '10') {			
				
				//$finalSearchInfo[$i]["VISIT_PRO_ACT"]				=	($finalSearchInfo[$i]["PRO_PER"]/100)*($finalSearchInfo[$i]["CNTID"]);
				$finalSearchInfo[$i]["VISIT_PRO_ACT"]				=	$finalSearchInfo[$i]["PRO_PER"];

				if($finalSearchInfo[$i]["tgtTypeProd"] == '0') {
					if($finalSearchInfo[$i]["PRO_COV"] >= $finalSearchInfo[$i]["VISIT_PRO_ACT"]) {
						$finalSearchInfo[$i]["PAY_INC_PRO"]			=   $finalSearchInfo[$i]["PRO_COV"]*$finalSearchInfo[$i]["PRO_VIS"];
					} else {
						$finalSearchInfo[$i]["PAY_INC_PRO"]			=   0;
					}
				} if($finalSearchInfo[$i]["tgtTypeProd"] == '1') {
					$finalSearchInfo[$i]["PAY_INC_PRO"]				=   $finalSearchInfo[$i]["PRO_COV"]*$finalSearchInfo[$i]["PRO_VIS"];
				}
			} else {
				$finalSearchInfo[$i]["PAY_INC_PRO"]					=   $finalSearchInfo[$i]["PRO_COV"]*$finalSearchInfo[$i]["PRO_VIS"];
			}
			$i++;
			//THIS IS FOR PRODUCTIVITY COVERAGE ENDS HERE			
		} else {
			$i++;
		}
		$k++;
	}
	
	//pre($finalSearchInfo);
	//exit;
	
	$orderbycolumns     =   'DSR_Name';
	$orderbysorting     =   'ASC';
	if($orderbysorting == 'DESC') {
		$dir        =   'arsort';               
	} else {
		$dir        =   'asort';   
	}
	$finalSearchInfo	=	subval_sort($finalSearchInfo,$orderbycolumns,$dir);
	
	
	?>
	<div class="condaily_routeplan">

	<table border="1" width="100%">
		<thead>
			<tr>
				<th align="center">SR INCENTIVE STATUS</th>
			</tr>
			<tr>
				<th align="left" colspan="19"><?php echo "Month & Year : &nbsp;&nbsp;&nbsp;".$propmonths."&nbsp;".$propyears."&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;SR : &nbsp;&nbsp;";
				
				if($srcode == '' || $srcode == 'null') {
					echo "ALL";
				} else {
					$explode_srcode		=	explode(",", str_replace("'","",$srcode));
					$tom	=	0;
					foreach($explode_srcode AS $srval) {
						if($tom	==	0) {
							echo upperstate(getdbval($srval,'DSRName','DSR_Code','dsr'));
						} else {
							echo ",&nbsp;".upperstate(getdbval($srval,'DSRName','DSR_Code','dsr'));
						}
						$tom++;
					}
				}
				//exit;
				/*if(is_array_empty($srcode)){
					echo getdbval($srcode,'DSRName','DSR_Code','dsr');
				} else{
					echo "ALL";
				}*/
				?></th>		
			</tr>
		</thead>
	</table>

	<table border="1" width="100%">
	<thead>
		<tr>
			<th align="center" colspan="19">SR EFFECTIVE COVERAGE REPORT</th>
		</tr>
	  <tr>
		<th align="center" style="width:10%">SR</th>
		<th align="center" style="width:10%">Target Incentive
		 <table  width="100%"><tr><td>ECO</td><td>Naira</td></tr></table>
		</th>
		<th align="center" style="width:10%">Total Visits</th>
		<th align="center" style="width:10%">Sale Visits</th>
		<th align="center" style="width:10%">ECO</th>
		<th align="center" style="width:10%">Possible Incentive</th>
		<th align="center" style="width:10%">Payable Incentive </th>
		</tr>
   </thead>
   <tbody>
<?php
//EFFECTIVE COVERAGE STARTS HERE
$k						=	0;
$arrcnt					=	count($finalSearchInfo);
$w						=	0;
$e						=	0;
if($arrcnt > 0) { 
 foreach($finalSearchInfo AS $SearchKey=>$SearchVal) { 	
	if($SearchVal["PRO_PER"] != '') {
		$w++;
	}
	if($SearchVal["COV_PER"] != '') {
		$e++;
	}
?>
	 <tr>
		 <td><?php echo $SearchVal["DSR_Name"]; ?></td>
		 <td>
			<table width="100%" style="table-layout:fixed;">
				<tr>
					<td align="right" style="width:60px;" nowrap="nowrap"><?php if($SearchVal["EFF_STA"] == '5') {
					if($SearchVal["EFF_PER"] != '') { echo $SearchVal["EFF_PER"]; } else {  echo '0';  } } elseif($SearchVal["EFF_STA"] == '10') {
						if($SearchVal["VISIT_COV"] != '') { echo $SearchVal["VISIT_COV"]; } else {  echo '0';  }
					} else {
						echo '0';
					}
					?>
					</td>
					<td align="right" style="width:60px;" nowrap="nowrap"><?php if($SearchVal["EFF_VIS"] != '') { echo number_format($SearchVal["EFF_VIS"],2); } else {  echo '0.00';  } ?></td>
				</tr>
			</table>
		 </td>	
		 <td align="right"><?php if($SearchVal["visit_Count"] != '') { echo $SearchVal["visit_Count"]; } else {  echo '0';  } ?></td>
		 <td align="right"><?php if($SearchVal["PROD_CNT"] != '') { echo $SearchVal["PROD_CNT"]; } else {  echo '0';  } ?></td>	
		 <td align="right"><?php if($SearchVal["EFF_COV"] != '') { echo $SearchVal["EFF_COV"]; } else {  echo '0';  } ?></td>
		 <td <?php 
		 if($SearchVal["EFF_STA"] == '5') {
			//echo "hi"; exit; 
			 if($SearchVal["tgtTypeEff"] == '0') {
				if($SearchVal["EFF_COV"] >= $SearchVal["EFF_PER"]) {
					?>style="color:#008000;" <?php 
				} else { 
					?>style="color:#FF0000;" <?php 
				}
			 } elseif($SearchVal["tgtTypeEff"] == '1') {
					?>style="color:#008000;" <?php
			 }
		 }  if($SearchVal["EFF_STA"] == '10') {
			 //pre($SearchVal); //exit;
				if($SearchVal["tgtTypeEff"] == '0') {					
					if($SearchVal["EFF_COV"] >= $SearchVal["VISIT_COV"]) {
						?>style="color:#008000;" <?php 
					} else { 
						?>style="color:#FF0000;" <?php 
					} 
				} elseif($SearchVal["tgtTypeEff"] == '1') {
					//echo "eere"; exit;
						?>style="color:#008000;" <?php
				}
		 } if($SearchVal["EFF_STA"] == '') {
				?>style="color:#FF0000;" <?php
		 }
		 ?> align="right"  ><?php echo number_format($SearchVal["POS_INC"],2); ?></td>	
		 <td align="right" ><?php echo number_format($SearchVal["PAY_INC"],2); ?></td>	
	 </tr>	
<?php $k++; }
} else { ?>
	<tr>
		 <td colspan="7" align="center"><strong>NO RECORDS FOUND</strong></td>
	</tr>
<?php }
 ?>
  </tbody>
 </table>
 </div>
	<?php //echo "~";
	//EFFECTIVE COVERAGE ENDS HERE



	//COVERAGE STARTS HERE
	//if($w != 0) { ?>
		<div class="condaily_routeplan">

		<table border="1" width="100%">
	<thead>
	<tr>
		<th align="center" colspan="19">SR COVERAGE REPORT</th>
	</tr>
	  <tr>
		<th align="center" style="width:10%">SR</th>
		<th align="center" style="width:10%">Target Incentive
		 <table  width="100%"><tr><td>COV</td><td>Naira</td></tr></table>
		</th>
		<th align="center" style="width:10%">Total Visits</th>
		<th align="center" style="width:10%">Sale Visits</th>
		<th align="center" style="width:10%">COV</th>
		<th align="center" style="width:10%">Possible Incentive</th>
		<th align="center" style="width:10%">Payable Incentive </th>
		</tr>
   </thead>
   <tbody>
	<?php
		$k						=	0;
		$arrcnt					=	count($finalSearchInfo);
if($arrcnt > 0) { 
 foreach($finalSearchInfo AS $SearchKey=>$SearchVal) { 	
?>
	 <tr>
		 <td><?php echo $SearchVal["DSR_Name"]; ?></td>
		 <td>
			<table width="100%" style="table-layout:fixed;">
				<tr>
					<td align="right" style="width:60px;" nowrap="nowrap"><?php if($SearchVal["COV_STA"] == '5') {
						if($SearchVal["COV_PER"] != '') { echo $SearchVal["COV_PER"]; } else { echo '0'; } 
					} elseif($SearchVal["COV_STA"] == '10') {
						if($SearchVal["VISIT_COV_ACT"] != '') { echo $SearchVal["VISIT_COV_ACT"]; } else { echo '0'; }
					} else {
						echo '0';
					}
					?></td>
					<td align="right" style="width:60px;"><?php 
					if($SearchVal["COV_VIS"] != '') { echo number_format($SearchVal["COV_VIS"],2); } else { echo '0.00'; } ?></td>
				</tr>
			</table>
		 </td>	
		 <td align="right"><?php if($SearchVal["visit_Count"] != '') { echo $SearchVal["visit_Count"]; } else { echo '0'; } ?></td>
		 <td align="right"><?php if($SearchVal["PROD_CNT"] != '') { echo $SearchVal["PROD_CNT"]; } else { echo '0';  }?></td>	
		 <td align="right"><?php if($SearchVal["COV_COV"] != '') { echo $SearchVal["COV_COV"]; } else { echo '0'; } ?></td>
		 <td <?php 
		 if($SearchVal["COV_STA"] == '5') {
			 if($SearchVal["tgtTypeCov"] == '0') {
				if($SearchVal["COV_COV"] >= $SearchVal["COV_PER"]) {
					?>style="color:#008000;" <?php 
				} else { 
					?>style="color:#FF0000;" <?php 
				}
			 } elseif($SearchVal["tgtTypeCov"] == '1') {
					?>style="color:#008000;" <?php
			 }
		 }  if($SearchVal["COV_STA"] == '10') {
				if($SearchVal["tgtTypeCov"] == '0') {
					if($SearchVal["COV_COV"] >= $SearchVal["VISIT_COV_ACT"]) {
						?>style="color:#008000;" <?php 
					} else { 
						?>style="color:#FF0000;" <?php 
					} 
				} elseif($SearchVal["tgtTypeCov"] == '1') {
						?>style="color:#008000;" <?php
				}
		 } if($SearchVal["COV_STA"] == '') {
				?>style="color:#FF0000;" <?php
		 }
		 ?> align="right"  ><?php echo number_format($SearchVal["POS_INC_COV"],2); ?></td>	
		 <td align="right" ><?php echo number_format($SearchVal["PAY_INC_COV"],2); ?></td>	
	 </tr>	
<?php }
} else { ?>
	<tr>
		 <td colspan="7" align="center"><strong>NO RECORDS FOUND</strong></td>
	</tr>
<?php }
 ?>
  </tbody>
 </table>
 </div>
	<?php //echo "~";
	//}
	//COVERAGE ENDS HERE


	//PRODUCTIVITY COVERAGE STARTS HERE
	//if($e != 0) { ?>
		<div class="condaily_routeplan">
		<table border="1" width="100%">
			<thead>
			<tr>
				<th align="center" colspan="19">SR PRODUCTIVITY COVERAGE REPORT</th>
			</tr>
			  <tr>
				<th align="center" style="width:10%">SR</th>
				<th align="center" style="width:10%">Target Incentive
				 <table  width="100%"><tr><td>PRO</td><td>Naira</td></tr></table>
				</th>
				<th align="center" style="width:10%">Total Visits</th>
				<th align="center" style="width:10%">Sale Visits</th>
				<th align="center" style="width:10%">PRO</th>
				<th align="center" style="width:10%">Possible Incentive</th>
				<th align="center" style="width:10%">Payable Incentive </th>
				</tr>
		   </thead>
		   <tbody>
			<?php
				$k						=	0;
				$arrcnt					=	count($finalSearchInfo);
		if($arrcnt > 0) { 
		 foreach($finalSearchInfo AS $SearchKey=>$SearchVal) { 	
		?>
			 <tr>
				 <td><?php echo $SearchVal["DSR_Name"]; ?></td>
				 <td>
					<table width="100%" style="table-layout:fixed;">
						<tr>
							<td align="right" style="width:60px;" nowrap="nowrap"><?php if($SearchVal["PRO_STA"] == '5') {
								if($SearchVal["PRO_PER"] != '' ) { echo $SearchVal["PRO_PER"]; } else { echo '0'; } 
							} elseif($SearchVal["PRO_STA"] == '10') {
								if($SearchVal["VISIT_PRO_ACT"] != '' ) { echo $SearchVal["VISIT_PRO_ACT"]; } else { echo '0'; } 
							} else {
								echo '0';
							}
							?></td>
							<td align="right" style="width:60px;" nowrap="nowrap"><?php if($SearchVal["PRO_VIS"] != '') { 
							echo number_format($SearchVal["PRO_VIS"],2); } else { echo '0.00'; }  ?></td>
						</tr>
					</table>
				</td>	
				 <td align="right"><?php if($SearchVal["visit_Count"] != '' ) { echo $SearchVal["visit_Count"]; } else { echo '0'; } ?></td>
				 <td align="right"><?php if($SearchVal["PROD_CNT"] != '' ) { echo $SearchVal["PROD_CNT"]; } else { echo '0'; } ?></td>
				 <td align="right"><?php if($SearchVal["PRO_COV"] != '' ) { echo $SearchVal["PRO_COV"]; } else { echo '0'; } ?> </td>
				 <td <?php 
				 if($SearchVal["PRO_STA"] == '5') {
					 if($SearchVal["tgtTypeProd"] == '0') {
						if($SearchVal["PRO_COV"] >= $SearchVal["PRO_PER"]) {
							?>style="color:#008000;" <?php 
						} else { 
							?>style="color:#FF0000;" <?php 
						}
					 } elseif($SearchVal["tgtTypeProd"] == '1') {
							?>style="color:#008000;" <?php
					 }
				 }  if($SearchVal["PRO_STA"] == '10') {
						if($SearchVal["tgtTypeProd"] == '0') {
							if($SearchVal["PRO_COV"] >= $SearchVal["VISIT_PRO_ACT"]) {
								?>style="color:#008000;" <?php 
							} else { 
								?>style="color:#FF0000;" <?php 
							} 
						} elseif($SearchVal["tgtTypeProd"] == '1') {
								?>style="color:#008000;" <?php
						}
				 } if($SearchVal["PRO_STA"] == '') {
						?>style="color:#FF0000;" <?php
				 }
				 ?> align="right"><?php if($SearchVal["POS_INC_PRO"] != '') { echo number_format($SearchVal["POS_INC_PRO"],2); } else { echo '0.00'; } ?></td>	
				 <td align="right"><?php if($SearchVal["PAY_INC_PRO"] != '') { echo number_format($SearchVal["PAY_INC_PRO"],2); } else { echo '0.00'; } ?></td>	
			 </tr>	
		<?php }
		} else { ?>
			<tr>
				 <td colspan="7" align="center"><strong>NO RECORDS FOUND</strong></td>
			</tr>
		<?php }
		 ?>
		  </tbody>
		 </table>
		 </div>
	<?php //echo "~";
	//}
	//PRODUCTIVITY COVERAGE ENDS HERE

	//ALL BRANDS COMBINED STARTS HERE
	
	
	
	$query_transhdr													=   "SELECT id,Transaction_Number,Date,Time,transaction_Reference_Number FROM transaction_hdr $complete_query";
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

	$finalAllBrandInfo					=	$transhdr_result;
	//pre($finalAllBrandInfo);
	//echo $transno_Total;
	//exit;
				
	$query_trans									=   "SELECT KD_Code,DSR_Code,Product_code,SUM(Sold_Quantity) AS SUM_QTY,Transaction_Number FROM transaction_line WHERE Transaction_Number IN ('".$transno_Total."') $prodcodecol GROUP BY DSR_Code ORDER BY Transaction_Number";
	//echo $query_trans;
	//exit;
	$res_trans										=   mysql_query($query_trans);

	while($row_trans								=   mysql_fetch_assoc($res_trans)) {
		$transInfo[$row_trans["Transaction_Number"]]=	$row_trans;
		$transno_trans[]							=	$row_trans["Transaction_Number"];
		$kdcode_trans[]								=	$row_trans["KD_Code"];
		$dsrcode_trans[]							=	$row_trans["DSR_Code"];
		$prodcode_trans[]							=	$row_trans["Product_code"];
	}
	 
	//echo count($transInfo)."jungle";
	$kdcode_trans		=	array_unique($kdcode_trans);
	$kdcodes_Total		=	implode("','",$kdcode_trans);

	$dsrcode_trans		=	array_unique($dsrcode_trans);
	$dsrcodes_Total		=	implode("','",$dsrcode_trans);

	$prodcode_trans		=	array_unique($prodcode_trans);
	$prodcode_Total		=	implode("','",$prodcode_trans);

	//pre($transInfo);
	//exit;

	$i=0;
	$k=0;
	//pre($finalAllBrandInfo);
	//pre($transInfo);
	//exit;

	foreach($finalAllBrandInfo as $val_transno){
		//echo $transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"] . "-". $val_transno["Transaction_Number"]."<br>";
		if($transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"] == $val_transno["Transaction_Number"]) {                                     
			$finaltranslineInfo[$i]["DSRCode"]						=   $transInfo[$val_transno["Transaction_Number"]]["DSR_Code"];
			$finaltranslineInfo[$i]["DateVal"]						=   $val_transno["Date"];
			$finaltranslineInfo[$i]["Product_code"]					=   $transInfo[$val_transno["Transaction_Number"]]["Product_code"];
			$finaltranslineInfo[$i]["KD_Code"]						=   $transInfo[$val_transno["Transaction_Number"]]["KD_Code"];
			$finaltranslineInfo[$i]["KD_Code"]						=   $transInfo[$val_transno["Transaction_Number"]]["KD_Code"];
			$finaltranslineInfo[$i]["SUM_QTY"]						=   $transInfo[$val_transno["Transaction_Number"]]["SUM_QTY"];
			$finaltranslineInfo[$i]["trans_id"]						=   $val_transno["id"];
			$finaltranslineInfo[$i]["TRANSNO"]						=   $transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
			$i++;
		}
		$k++;
	}

	$finalAllBrandInfo          =   $finaltranslineInfo;
	//pre($finalAllProdInfo);
	//exit;

	
	$query_kd										=   "SELECT KD_Name,KD_Code FROM kd WHERE KD_Code IN ('".$kdcodes_Total."')";
	$res_kd											=   mysql_query($query_kd);
	while($row_kd									=   mysql_fetch_assoc($res_kd)) {
		$kdInfo[$row_kd["KD_Code"]]					=	$row_kd;
	}
	 
	$i=0;
	$k=0;
	foreach($finalAllBrandInfo as $val_kd){
		//$transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
		if($kdInfo[$val_kd["KD_Code"]]["KD_Code"] == $val_kd[KD_Code]) {                                     
			$finalkdInfos[$i]["KD_Name"]								=   $kdInfo[$val_kd["KD_Code"]]["KD_Name"];
			$finalkdInfos[$i]["DSRCode"]								=   $val_kd["DSRCode"];
			$finalkdInfos[$i]["KD_Code"]								=   $val_kd["KD_Code"];
			$finalkdInfos[$i]["DateVal"]								=   $val_kd["DateVal"];
			$finalkdInfos[$i]["Product_code"]							=   $val_kd["Product_code"];
			$finalkdInfos[$i]["SUM_QTY"]								=   $val_kd["SUM_QTY"];
			$i++;
		}
		$k++;
	}

	$finalAllBrandInfo          =   $finalkdInfos;
	//pre($finalAllProdInfo);
	//exit;


	$query_dsr										=   "SELECT DSRName,DSR_Code FROM dsr WHERE DSR_Code IN ('".$dsrcodes_Total."')";
	//echo $query_dsr;
	//exit;
	$res_dsr										=   mysql_query($query_dsr);
	while($row_dsr									=   mysql_fetch_assoc($res_dsr)) {
		$dsrInfo[$row_dsr["DSR_Code"]]				=	$row_dsr;
		$asmcode_dsr[]								=	$row_dsr["ASM"];
	}
	
	//pre($dsrInfo);
	//exit;
	//$asmcode_dsr				=	array_unique($asmcode_dsr);
	//$asmcode_Total			=	implode("','",$asmcode_dsr);

	$i=0;
	$k=0;
	foreach($finalAllBrandInfo as $val_dsr){
		//echo $dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] . "-". $val_dsr["DSRCode"]."<br>";
		if($dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] == $val_dsr["DSRCode"]) {                                    
			$finaldsrInfos[$i]["DSR_Name"]							=   $dsrInfo[$val_dsr["DSRCode"]]["DSRName"];
			$finaldsrInfos[$i]["KD_Name"]							=   $val_dsr["KD_Name"];
			$finaldsrInfos[$i]["KD_Code"]							=   $val_dsr["KD_Code"];
			$finaldsrInfos[$i]["DSRCode"]							=   $val_dsr["DSRCode"];
			$finaldsrInfos[$i]["DateVal"]							=   $val_dsr["DateVal"];
			$finaldsrInfos[$i]["Product_code"]						=   $val_dsr["Product_code"];
			$finaldsrInfos[$i]["SUM_QTY"]							=   $val_dsr["SUM_QTY"];
			$i++;
		}
		$k++;
	}

	$finalAllBrandInfo          =   $finaldsrInfos;
	//pre($finalAllProdInfo);
	//exit;
	$query_target									=   "SELECT KD_Code,DSR_Code,monthval,yearval,SUM(target_units) AS TGT_UNT,SUM(target_naira) AS TGT_NAI,targetFlag FROM srbrand_incentive $target_query GROUP BY DSR_Code ORDER BY DSR_Code";
	//echo $query_target;
	//exit;
	$res_target													=   mysql_query($query_target);
	while($row_target											=   mysql_fetch_assoc($res_target)) {
		$SR_Code												=	$row_target[DSR_Code];
		$KD_Code												=	$row_target[KD_Code];
		$targetProdUnits[$SR_Code.$KD_Code]["TGT_UNT"]			=	$row_target["TGT_UNT"];
		$targetProdNaira[$SR_Code.$KD_Code]["TGT_NAI"]			=	$row_target["TGT_NAI"];
		$targettargetFlag[$SR_Code.$KD_Code]["SRINCTGTFLAG"]	=	$row_target["targetFlag"];
		$targetInfo[$SR_Code.$KD_Code]							=	$SR_Code.$KD_Code;
	}	
	//pre($targetInfo);
	//pre($targetNaira);
	//pre($targetUnits);
	//pre($targettargetFlag);
	//pre($finalAllProdInfo);
	//exit;
	$i=0;
	foreach($finalAllBrandInfo as $val_target)	{
		$SRCODEVAL			=	$val_target["DSRCode"];
		$KD_CODE			=	$val_target["KD_Code"];

		$INDEX_VAL			=	$SRCODEVAL.$KD_CODE;
		//echo	$targetInfo[$INDEX_VAL]	. "==".	$INDEX_VAL."<br>"; 
		if($targetInfo[$INDEX_VAL]	==	$INDEX_VAL) {
			$finalAllBrandInfo[$i]["TGT_UNT"]			=   $targetProdUnits[$INDEX_VAL]["TGT_UNT"];
			$finalAllBrandInfo[$i]["TGT_NAI"]			=   $targetProdNaira[$INDEX_VAL]["TGT_NAI"];
			$finalAllBrandInfo[$i]["SRINCTGTFLAG"]		=   $targettargetFlag[$INDEX_VAL]["SRINCTGTFLAG"];
			$finalAllBrandInfo[$i]["POS_INC"]			=   $finalAllBrandInfo[$i]["SUM_QTY"]*$finalAllBrandInfo[$i]["TGT_NAI"];

			if($finalAllBrandInfo[$i]["SRINCTGTFLAG"] == '0') {
				if($finalAllBrandInfo[$i]["SUM_QTY"] >= $finalAllBrandInfo[$i]["TGT_UNT"]) {
					$finalAllBrandInfo[$i]["PAY_INC"]			=   $finalAllBrandInfo[$i]["SUM_QTY"]*$finalAllBrandInfo[$i]["TGT_NAI"];
				} else {
					$finalAllBrandInfo[$i]["PAY_INC"]			=   0;
				}
			} if($finalAllBrandInfo[$i]["SRINCTGTFLAG"] == '1') {
				$finalAllBrandInfo[$i]["PAY_INC"]			=   $finalAllBrandInfo[$i]["SUM_QTY"]*$finalAllBrandInfo[$i]["TGT_NAI"];
			} 
		}
		$i++;
	}

	//pre($finalAllBrandInfo);
	//exit;
	?>
	<div class="condaily_routeplan">
	<table border="1" width="100%">
		<thead>
		<tr>
			<th align="center" colspan="19">SR ALL BRANDS REPORT</th>
		</tr>
		  <tr>
			<th align="center" style="width:10%">SR</th>
			<th align="center" style="width:10%">All Brands Target
			 <table  width="100%"><tr><td>Units</td><td>Naira</td></tr></table>
			</th>
			<th align="center" style="width:10%">Sold Units</th>
			<th align="center" style="width:10%">Possible Incentive</th>
			<th align="center" style="width:10%">Payable Incentive </th>
			</tr>
	  </thead>
	 <tbody>
	 <?php
		$k						=	0;
		$arrcnt					=	count($finalAllBrandInfo);
		if($arrcnt > 0) { 
		 foreach($finalAllBrandInfo AS $SearchKey=>$SearchVal) {
			 $DSRCODE_BRAND			=	$SearchVal["DSRCode"];
	?>
	<tr>
	 <td><?php echo $SearchVal["DSR_Name"]; ?></td>
	 <td>
		<table width="100%" style='table-layout:fixed;'>
			<tr>
				<td align='right' style='width:60px;'><?php if($SearchVal["TGT_UNT"] != '') { echo number_format($SearchVal["TGT_UNT"]); } else { echo '0'; } ?></td>
				<td align='right' style='width:60px;'><?php if($SearchVal["TGT_NAI"] != '') { echo number_format($SearchVal["TGT_NAI"],2); } else { echo '0.00'; } ?></td>
			</tr>
		</table>
	</td>	
	 <td align='right'><?php if($SearchVal["SUM_QTY"] != '') { echo number_format($SearchVal["SUM_QTY"]); } else { echo '0'; } ?></td>
	 <td <?php 
		 if($SearchVal["SRINCTGTFLAG"] == '0') {
			if($SearchVal["SUM_QTY"] >= $SearchVal["TGT_UNT"]) {
				?>style="color:#008000;" <?php 
			} else { 
				?>style="color:#FF0000;" <?php 
			}
		 } elseif($SearchVal["SRINCTGTFLAG"] == '1') {
			?>style="color:#008000;" <?php
		 } else {
			?>style="color:#FF0000;" <?php
		 }
	 ?> align="right"><?php if($SearchVal["POS_INC"] != '') { echo number_format($SearchVal["POS_INC"],2); } else { echo '0.00'; } ?></td>
	 <td align="right"><?php if($SearchVal["PAY_INC"] != '') { echo number_format($SearchVal["PAY_INC"],2); } else { echo '0.00'; } ?></td>
	 </tr>	
	 <?php } //foreach loop
	
		
		} // if loop
		else { ?>
		<tr>
			 <td colspan="5" align="center"><strong>NO RECORDS FOUND</strong></td>
		</tr>
		<?php } ?>
	 </tbody>
	</table>
	</div>
	
	<?php 
	//ALL BRANDS COMBINED ENDS HERE


	//ALL PRODUCTS COMBINED STARTS HERE
	

	$finalAllProdInfo					=	$transhdr_result;
	//pre($finalAllProdInfo);
	//echo $transno_Total;
	//exit;
				
	$query_trans									=   "SELECT KD_Code,DSR_Code,Product_code,SUM(Sold_Quantity) AS SUM_QTY,Transaction_Number FROM transaction_line WHERE Transaction_Number IN ('".$transno_Total."') $prodcodecol GROUP BY DSR_Code ORDER BY Transaction_Number";
	//echo $query_trans;
	//exit;
	$res_trans										=   mysql_query($query_trans);

	while($row_trans								=   mysql_fetch_assoc($res_trans)) {
		$transInfo[$row_trans["Transaction_Number"]]=	$row_trans;
		$transno_trans[]							=	$row_trans["Transaction_Number"];
		$kdcode_trans[]								=	$row_trans["KD_Code"];
		$dsrcode_trans[]							=	$row_trans["DSR_Code"];
		$prodcode_trans[]							=	$row_trans["Product_code"];
	}
	 
	//echo count($transInfo)."jungle";
	$kdcode_trans		=	array_unique($kdcode_trans);
	$kdcodes_Total		=	implode("','",$kdcode_trans);

	$dsrcode_trans		=	array_unique($dsrcode_trans);
	$dsrcodes_Total		=	implode("','",$dsrcode_trans);

	$prodcode_trans		=	array_unique($prodcode_trans);
	$prodcode_Total		=	implode("','",$prodcode_trans);

	//pre($transInfo);
	//exit;

	$i=0;
	$k=0;
	//pre($finalAllProdInfo);
	//pre($transInfo);
	//exit;

	foreach($finalAllProdInfo as $val_transno){
		//echo $transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"] . "-". $val_transno["Transaction_Number"]."<br>";
		if($transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"] == $val_transno["Transaction_Number"]) {                                     
			$finaltranslineInfo[$i]["DSRCode"]						=   $transInfo[$val_transno["Transaction_Number"]]["DSR_Code"];
			$finaltranslineInfo[$i]["DateVal"]						=   $val_transno["Date"];
			$finaltranslineInfo[$i]["Product_code"]					=   $transInfo[$val_transno["Transaction_Number"]]["Product_code"];
			$finaltranslineInfo[$i]["KD_Code"]						=   $transInfo[$val_transno["Transaction_Number"]]["KD_Code"];
			$finaltranslineInfo[$i]["KD_Code"]						=   $transInfo[$val_transno["Transaction_Number"]]["KD_Code"];
			$finaltranslineInfo[$i]["SUM_QTY"]						=   $transInfo[$val_transno["Transaction_Number"]]["SUM_QTY"];
			$finaltranslineInfo[$i]["trans_id"]						=   $val_transno["id"];
			$finaltranslineInfo[$i]["TRANSNO"]						=   $transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
			$i++;
		}
		$k++;
	}

	$finalAllProdInfo          =   $finaltranslineInfo;
	//pre($finalAllProdInfo);
	//exit;

	
	$query_kd										=   "SELECT KD_Name,KD_Code FROM kd WHERE KD_Code IN ('".$kdcodes_Total."')";
	$res_kd											=   mysql_query($query_kd);
	while($row_kd									=   mysql_fetch_assoc($res_kd)) {
		$kdInfo[$row_kd["KD_Code"]]					=	$row_kd;
	}
	 
	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_kd){
		//$transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
		if($kdInfo[$val_kd["KD_Code"]]["KD_Code"] == $val_kd[KD_Code]) {                                     
			$finalkdInfos[$i]["KD_Name"]								=   $kdInfo[$val_kd["KD_Code"]]["KD_Name"];
			$finalkdInfos[$i]["DSRCode"]								=   $val_kd["DSRCode"];
			$finalkdInfos[$i]["KD_Code"]								=   $val_kd["KD_Code"];
			$finalkdInfos[$i]["DateVal"]								=   $val_kd["DateVal"];
			$finalkdInfos[$i]["Product_code"]							=   $val_kd["Product_code"];
			$finalkdInfos[$i]["SUM_QTY"]								=   $val_kd["SUM_QTY"];
			$i++;
		}
		$k++;
	}

	$finalAllProdInfo          =   $finalkdInfos;
	//pre($finalAllProdInfo);
	//exit;


	$query_dsr										=   "SELECT DSRName,DSR_Code FROM dsr WHERE DSR_Code IN ('".$dsrcodes_Total."')";
	//echo $query_dsr;
	//exit;
	$res_dsr										=   mysql_query($query_dsr);
	while($row_dsr									=   mysql_fetch_assoc($res_dsr)) {
		$dsrInfo[$row_dsr["DSR_Code"]]				=	$row_dsr;
		$asmcode_dsr[]								=	$row_dsr["ASM"];
	}
	
	//pre($dsrInfo);
	//exit;
	//$asmcode_dsr				=	array_unique($asmcode_dsr);
	//$asmcode_Total			=	implode("','",$asmcode_dsr);

	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_dsr){
		//echo $dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] . "-". $val_dsr["DSRCode"]."<br>";
		if($dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] == $val_dsr["DSRCode"]) {                                    
			$finaldsrInfos[$i]["DSR_Name"]							=   $dsrInfo[$val_dsr["DSRCode"]]["DSRName"];
			$finaldsrInfos[$i]["KD_Name"]							=   $val_dsr["KD_Name"];
			$finaldsrInfos[$i]["KD_Code"]							=   $val_dsr["KD_Code"];
			$finaldsrInfos[$i]["DSRCode"]							=   $val_dsr["DSRCode"];
			$finaldsrInfos[$i]["DateVal"]							=   $val_dsr["DateVal"];
			$finaldsrInfos[$i]["Product_code"]						=   $val_dsr["Product_code"];
			$finaldsrInfos[$i]["SUM_QTY"]							=   $val_dsr["SUM_QTY"];
			$i++;
		}
		$k++;
	}

	$finalAllProdInfo          =   $finaldsrInfos;
	//pre($finalAllProdInfo);
	//exit;
	$query_target									=   "SELECT KD_Code,DSR_Code,monthval,yearval,SUM(target_units) AS TGT_UNT,SUM(target_naira) AS TGT_NAI,targetFlag FROM sr_incentive $target_query GROUP BY DSR_Code ORDER BY DSR_Code";
	//echo $query_target;
	//exit;
	$res_target													=   mysql_query($query_target);
	while($row_target											=   mysql_fetch_assoc($res_target)) {
		$SR_Code												=	$row_target[DSR_Code];
		$KD_Code												=	$row_target[KD_Code];
		$targetProdUnits[$SR_Code.$KD_Code]["TGT_UNT"]			=	$row_target["TGT_UNT"];
		$targetProdNaira[$SR_Code.$KD_Code]["TGT_NAI"]			=	$row_target["TGT_NAI"];
		$targettargetFlag[$SR_Code.$KD_Code]["SRINCTGTFLAG"]	=	$row_target["targetFlag"];
		$targetInfo[$SR_Code.$KD_Code]							=	$SR_Code.$KD_Code;
	}	
	//pre($targetInfo);
	//pre($targetNaira);
	//pre($targetUnits);
	//pre($targettargetFlag);
	//pre($finalAllProdInfo);
	//exit;
	$i=0;
	foreach($finalAllProdInfo as $val_target)	{
		$SRCODEVAL			=	$val_target["DSRCode"];
		$KD_CODE			=	$val_target["KD_Code"];

		$INDEX_VAL			=	$SRCODEVAL.$KD_CODE;
		//echo	$targetInfo[$INDEX_VAL]	. "==".	$INDEX_VAL."<br>"; 
		if($targetInfo[$INDEX_VAL]	==	$INDEX_VAL) {
			$finalAllProdInfo[$i]["TGT_UNT"]			=   $targetProdUnits[$INDEX_VAL]["TGT_UNT"];
			$finalAllProdInfo[$i]["TGT_NAI"]			=   $targetProdNaira[$INDEX_VAL]["TGT_NAI"];
			$finalAllProdInfo[$i]["SRINCTGTFLAG"]		=   $targettargetFlag[$INDEX_VAL]["SRINCTGTFLAG"];
			$finalAllProdInfo[$i]["POS_INC"]			=   $finalAllProdInfo[$i]["SUM_QTY"]*$finalAllProdInfo[$i]["TGT_NAI"];

			if($finalAllProdInfo[$i]["SRINCTGTFLAG"] == '0') {
				if($finalAllProdInfo[$i]["SUM_QTY"] >= $finalAllProdInfo[$i]["TGT_UNT"]) {
					$finalAllProdInfo[$i]["PAY_INC"]			=   $finalAllProdInfo[$i]["SUM_QTY"]*$finalAllProdInfo[$i]["TGT_NAI"];
				} else {
					$finalAllProdInfo[$i]["PAY_INC"]			=   0;
				}
			} if($finalAllProdInfo[$i]["SRINCTGTFLAG"] == '1') {
				$finalAllProdInfo[$i]["PAY_INC"]			=   $finalAllProdInfo[$i]["SUM_QTY"]*$finalAllProdInfo[$i]["TGT_NAI"];
			} 
		}
		$i++;
	}

	//pre($finalAllProdInfo);
	//exit;
	?>
	<div class="condaily_routeplan">
	<table border="1" width="100%">
		<thead>
		<tr>
			<th align="center" colspan="19">SR ALL PRODUCTS REPORT</th>
		</tr>
		  <tr>
			<th align="center" style="width:10%">SR</th>
			<th align="center" style="width:10%">All Products Target
			 <table  width="100%"><tr><td>Units</td><td>Naira</td></tr></table>
			</th>
			<th align="center" style="width:10%">Sold Units</th>
			<th align="center" style="width:10%">Possible Incentive</th>
			<th align="center" style="width:10%">Payable Incentive </th>
			</tr>
	  </thead>
	 <tbody>
	 <?php
		$k						=	0;
		$arrcnt					=	count($finalAllProdInfo);
		if($arrcnt > 0) { 
		 foreach($finalAllProdInfo AS $SearchKey=>$SearchVal) { 	
			$DSRCODE_PROD			=	$SearchVal["DSRCode"];
	?>
	<tr>
	 <td><?php echo $SearchVal["DSR_Name"]; ?></td>
	 <td>
		<table width="100%" style='table-layout:fixed;'>
			<tr>
				<td align='right' style='width:60px;'><?php if($SearchVal["TGT_UNT"] != '') { echo number_format($SearchVal["TGT_UNT"]); } else { echo '0'; } ?></td>
				<td align='right' style='width:60px;'><?php if($SearchVal["TGT_NAI"] != '') { echo number_format($SearchVal["TGT_NAI"],2); } else { echo '0.00'; } ?></td>
			</tr>
		</table>
	</td>	
	 <td align='right'><?php if($SearchVal["SUM_QTY"] != '') { echo number_format($SearchVal["SUM_QTY"]); } else { echo '0'; } ?>
	 
	 <?php //if($SearchVal["SUM_QTY"] != '') { echo number_format($SearchVal["SUM_QTY"]); } else { echo '0'; } ?></td>
	 <td <?php 
		 if($SearchVal["SRINCTGTFLAG"] == '0') {
			if($SearchVal["SUM_QTY"] >= $SearchVal["TGT_UNT"]) {
				?>style="color:#008000;" <?php 
			} else { 
				?>style="color:#FF0000;" <?php 
			}
		 } elseif($SearchVal["SRINCTGTFLAG"] == '1') {
				?>style="color:#008000;" <?php
		 } else {
				?>style="color:#FF0000;" <?php
		 }
	 ?> align="right"><?php if($SearchVal["POS_INC"] != '') { echo number_format($SearchVal["POS_INC"],2); } else { echo '0.00'; } ?></td>
	 <td align="right"><?php if($SearchVal["PAY_INC"] != '') { echo number_format($SearchVal["PAY_INC"],2); } else { echo '0.00'; } ?></td>
 </tr>	
 <?php } //foreach loop
	} // if loop
	else { ?>
	<tr>
		 <td colspan="5" align="center"><strong>NO RECORDS FOUND</strong></td>
	</tr>
	<?php } ?>
 </tbody>
</table>
</div>
<?php
//echo "~";
	//ALL PRODUCTS COMBINED ENDS HERE

} ?> <span id="printopen" style="padding-left:580px;padding-top:10px;<?php if($arrcnt > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0);?>