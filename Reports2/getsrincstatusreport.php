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

//error_reporting(E_ALL & ~ E_NOTICE);
//ini_set("display_errors",true);
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
		if(is_array($asmcode)) {
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
					
	$query_trans									=   "SELECT KD_Code,DSR_Code,Date,visit_Count,Invoice_Count,SUM(effective_count) AS EFF_CNT,SUM(productive_count) AS PROD_CNT,Invoice_Line_Count,Total_Sale_Value,Drop_Size_Value,Basket_Size_Value FROM dsr_metrics $complete_query GROUP BY DSR_Code ORDER BY Date";
	
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
			} if($finalSearchInfo[$i]["EFF_STA"] == '10') {

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
			if($finalSearchInfo[$i]["COV_STA"] == '5') {
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
			} if($finalSearchInfo[$i]["COV_STA"] == '10') {
				$finalSearchInfo[$i]["COV_COV"]						=	ceil($finalSearchInfo[$i]["visit_Count"]/$finalSearchInfo[$i]["COV_PER"]);
				$finalSearchInfo[$i]["POS_INC_COV"]					=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
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
				$finalSearchInfo[$i]["POS_INC_COV"]					=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
				$finalSearchInfo[$i]["PAY_INC_COV"]					=   $finalSearchInfo[$i]["COV_COV"]*$finalSearchInfo[$i]["COV_VIS"];
			}
			//THIS IS FOR COVERAGE ENDS HERE

			//THIS IS FOR PRODUCTIVITY COVERAGE STARTS HERE
			if($finalSearchInfo[$i]["PRO_STA"] == '5') {
				$finalSearchInfo[$i]["VISIT_PRO_ACT"]				= ($finalSearchInfo[$i]["PRO_PER"]/100)*($finalSearchInfo[$i]["CNTID"]);

				if($finalSearchInfo[$i]["tgtTypeProd"] == '0') {
					if($finalSearchInfo[$i]["PRO_COV"] >= $finalSearchInfo[$i]["VISIT_PRO_ACT"]) {
						$finalSearchInfo[$i]["PAY_INC_PRO"]			=   $finalSearchInfo[$i]["PRO_COV"]*$finalSearchInfo[$i]["PRO_VIS"];
					} else {
						$finalSearchInfo[$i]["PAY_INC_PRO"]			=   0;
					}
				} if($finalSearchInfo[$i]["tgtTypeProd"] == '1') {
					$finalSearchInfo[$i]["PAY_INC_PRO"]				=   $finalSearchInfo[$i]["PRO_COV"]*$finalSearchInfo[$i]["PRO_VIS"];
				}				
			} if($finalSearchInfo[$i]["PRO_STA"] == '10') {				
				
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
			//THIS IS FOR PRODUCTIVITY COVERAGE ENDS HERE
			$i++;
		}
		$k++;
	}
	
	//pre($finalSearchInfo);
	//exit;
	
	?>
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
		 <td>&nbsp;<table width="100%"><tr><td><?php if($SearchVal["EFF_STA"] == '5') {
					echo $SearchVal["EFF_PER"]; } elseif($SearchVal["EFF_STA"] == '10') {
						echo $SearchVal["VISIT_COV"];
					}
		 ?></td><td><?php echo $SearchVal["EFF_VIS"]; ?></td></tr></table></td>	
		 <td><?php echo $SearchVal["visit_Count"]; ?></td>
		 <td><?php echo $SearchVal["PROD_CNT"]; ?></td>	
		 <td><?php echo $SearchVal["EFF_COV"]; ?></td>
		 <td <?php 
		 if($SearchVal["EFF_STA"] == '5') {
			 if($SearchVal["targetFlag"] == '0') {
				if($SearchVal["EFF_COV"] >= $SearchVal["EFF_PER"]) {
					?>style="color:green;" <?php 
				} else { 
					?>style="color:red;" <?php 
				}
			 } elseif($SearchVal["targetFlag"] == '1') {
					?>style="color:green;" <?php
			 }
		 }  if($SearchVal["EFF_STA"] == '10') {
				if($SearchVal["targetFlag"] == '0') {
					if($SearchVal["EFF_COV"] >= $SearchVal["VISIT_COV"]) {
						?>style="color:green;" <?php 
					} else { 
						?>style="color:red;" <?php 
					} 
				} elseif($SearchVal["targetFlag"] == '1') {
						?>style="color:green;" <?php
				}
		 }
		 ?> ><?php echo $SearchVal["POS_INC"]; ?></td>	
		 <td><?php echo $SearchVal["PAY_INC"]; ?></td>	
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
	<?php echo "~";
	//EFFECTIVE COVERAGE ENDS HERE



	//COVERAGE STARTS HERE
	//if($w != 0) { ?>
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
		 <td>&nbsp;<table width="100%"><tr><td><?php if($SearchVal["COV_STA"] == '5') {
					echo $SearchVal["COV_PER"]; } elseif($SearchVal["COV_STA"] == '10') {
						echo $SearchVal["VISIT_COV_ACT"];
					}
		 ?></td><td><?php echo $SearchVal["COV_VIS"]; ?></td></tr></table></td>	
		 <td><?php echo $SearchVal["visit_Count"]; ?></td>
		 <td><?php echo $SearchVal["PROD_CNT"]; ?></td>	
		 <td><?php echo $SearchVal["COV_COV"]; ?></td>
		 <td <?php 
		 if($SearchVal["COV_STA"] == '5') {
			 if($SearchVal["targetFlag"] == '0') {
				if($SearchVal["COV_COV"] >= $SearchVal["COV_PER"]) {
					?>style="color:green;" <?php 
				} else { 
					?>style="color:red;" <?php 
				}
			 } elseif($SearchVal["targetFlag"] == '1') {
					?>style="color:green;" <?php
			 }
		 }  if($SearchVal["COV_STA"] == '10') {
				if($SearchVal["targetFlag"] == '0') {
					if($SearchVal["COV_COV"] >= $SearchVal["VISIT_COV_ACT"]) {
						?>style="color:green;" <?php 
					} else { 
						?>style="color:red;" <?php 
					} 
				} elseif($SearchVal["targetFlag"] == '1') {
						?>style="color:green;" <?php
				}
		 }
		 ?> ><?php echo $SearchVal["POS_INC_COV"]; ?></td>	
		 <td><?php echo $SearchVal["PAY_INC_COV"]; ?></td>	
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
	<?php echo "~";
	//}
	//COVERAGE ENDS HERE


	//PRODUCTIVITY COVERAGE STARTS HERE
	//if($e != 0) { ?>
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
				 <td>&nbsp;<table width="100%"><tr><td><?php if($SearchVal["PRO_STA"] == '5') {
							echo $SearchVal["PRO_PER"]; } elseif($SearchVal["PRO_STA"] == '10') {
								echo $SearchVal["VISIT_PRO_ACT"];
							}
				 ?></td><td><?php echo $SearchVal["PRO_VIS"]; ?></td></tr></table></td>	
				 <td><?php echo $SearchVal["visit_Count"]; ?></td>
				 <td><?php echo $SearchVal["PROD_CNT"]; ?></td>	
				 <td><?php echo $SearchVal["PRO_COV"]; ?></td>
				 <td <?php 
				 if($SearchVal["PRO_STA"] == '5') {
					 if($SearchVal["targetFlag"] == '0') {
						if($SearchVal["PRO_COV"] >= $SearchVal["COV_PER"]) {
							?>style="color:green;" <?php 
						} else { 
							?>style="color:red;" <?php 
						}
					 } elseif($SearchVal["targetFlag"] == '1') {
							?>style="color:green;" <?php
					 }
				 }  if($SearchVal["PRO_STA"] == '10') {
						if($SearchVal["targetFlag"] == '0') {
							if($SearchVal["PRO_COV"] >= $SearchVal["VISIT_COV_ACT"]) {
								?>style="color:green;" <?php 
							} else { 
								?>style="color:red;" <?php 
							} 
						} elseif($SearchVal["targetFlag"] == '1') {
								?>style="color:green;" <?php
						}
				 }
				 ?> ><?php echo $SearchVal["POS_INC_PRO"]; ?></td>	
				 <td><?php echo $SearchVal["PAY_INC_PRO"]; ?></td>	
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
	<?php echo "~";
	//}
	//PRODUCTIVITY COVERAGE ENDS HERE


	//ALL PRODUCTS COMBINED STARTS HERE
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
	?>
	<tr>
	 <td><?php echo $SearchVal["DSR_Name"]; ?></td>
	 <td>&nbsp;<table width="100%"><tr><td><?php echo $SearchVal["TGT_UNT"]; ?></td><td><?php echo $SearchVal["TGT_NAI"]; ?></td></tr></table></td>	
	 <td><?php echo $SearchVal["SUM_QTY"]; ?></td>
	 <td <?php 
		 if($SearchVal["SRINCTGTFLAG"] == '0') {
			if($SearchVal["SUM_QTY"] >= $SearchVal["TGT_UNT"]) {
				?>style="color:green;" <?php 
			} else { 
				?>style="color:red;" <?php 
			}
		 } elseif($SearchVal["SRINCTGTFLAG"] == '1') {
				?>style="color:green;" <?php
		 }
	 ?> ><?php echo $SearchVal["POS_INC"]; ?></td>	
	 <td><?php echo $SearchVal["PAY_INC"]; ?></td>	
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
<?php
echo "~";
	//ALL PRODUCTS COMBINED ENDS HERE

	
	//ALL PRODUCTS INDIVIDUAL STARTS HERE
	//echo $transno_Total;
	$query_trans									=   "SELECT KD_Code,DSR_Code,Product_code,Transaction_Number,SUM(Sold_quantity) AS SOLQTY FROM transaction_line WHERE Transaction_Number IN ('".$transno_Total."') $prodcodecol GROUP BY Product_code, DSR_Code ORDER BY Product_code";
	//echo $query_trans;
	//exit;
	$res_trans										=   mysql_query($query_trans);

	while($row_trans								=   mysql_fetch_assoc($res_trans)) {
		//$transAllInfo[$row_trans["Transaction_Number"]]=	$row_trans;
		$transno_trans[]							=	$row_trans["Transaction_Number"];
		$kdcode_trans[]								=	$row_trans["KD_Code"];
		$dsrcode_trans[]							=	$row_trans["DSR_Code"];
		$prodcode_trans[]							=	$row_trans["Product_code"];
		$transAllDetInfo[]							=	$row_trans;
		//$transAllDetInfoVal[$row_trans["DSR_Code"].$row_trans["Product_code"]]							=	$row_trans;
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

	//echo $product_countcheck."<br>";
	//pre($prodcode_trans);
	//exit;
	
	$finalAllProdDetInfo	=	$transAllDetInfo;

	//pre($finalAllProdDetInfo);
	//pre($transAllDetInfo);
	//exit;

	$query_dsr										=   "SELECT DSRName,DSR_Code FROM dsr WHERE DSR_Code IN ('".$dsrcodes_Total."')";
	//echo $query_dsr;
	//exit;
	$res_dsr										=   mysql_query($query_dsr);
	while($row_dsr									=   mysql_fetch_assoc($res_dsr)) {
		$dsrInfo[$row_dsr["DSR_Code"]]				=	$row_dsr;
	}
	
	//pre($dsrInfo);
	//exit;

	$i=0;
	$k=0;
	foreach($finalAllProdDetInfo as $val_dsr){
		//echo $dsrInfo[$val_dsr["DSRCode"]]["DSR_Code"] . "-". $val_dsr["DSRCode"]."<br>";
		if($dsrInfo[$val_dsr["DSR_Code"]]["DSR_Code"] == $val_dsr["DSR_Code"]) {                                    
			$finaldsrInfosDet[$i]["DSR_Name"]							=   $dsrInfo[$val_dsr["DSR_Code"]]["DSRName"];
			$finaldsrInfosDet[$i]["KD_Code"]							=   $val_dsr["KD_Code"];
			$finaldsrInfosDet[$i]["DSRCode"]							=   $val_dsr["DSR_Code"];
			$finaldsrInfosDet[$i]["SOLQTY"]								=   $val_dsr["SOLQTY"];
			$finaldsrInfosDet[$i]["Product_code"]						=   $val_dsr["Product_code"];
			$i++;
		}
		$k++;
	}

	$finalAllProdDetInfo          =   $finaldsrInfosDet;
	//pre($finalAllProdDetInfo);
	//exit;


	$query_prod													=   "SELECT brand,id,Product_description1,Product_code FROM product WHERE Product_code IN ('".$prodcode_Total."')";
	$res_prod													=   mysql_query($query_prod);
	while($row_prod												=   mysql_fetch_assoc($res_prod)) {
		$prodInfo[$row_prod["Product_code"]]					=	$row_prod;
		//$prodInfoDet[$row_prod[Product_code]][Product_code]	=	$row_prod[Product_code];
		$prodInfoDet[]											=	$row_prod;
	}
		
	//pre($prodInfo);
	//exit;

	//pre($finalAllProdDetInfo);
	//exit;

	$i=0;
	$k=0;
	foreach($dsrInfo AS $DSRCODEVAL) {	
		foreach($prodInfo AS $EachProdKey=>$EachProdVal) {
			
			//echo $EachProdKey."==<br>";
			$checked_Product_Code	=	myfunction_tosearch_arraykey($finalAllProdDetInfo, $EachProdKey,'Product_code',$DSRCODEVAL[DSR_Code],'DSRCode');

			//echo $EachProdKey."<==>".$DSRCODEVAL[DSR_Code]."<br>";
			//echo $checked_Product_Code."==<br>";
			if($checked_Product_Code) {
				$finalprodInfo[$i]["DSRCode"]							=   $finalAllProdDetInfo[$checked_Product_Code]["DSRCode"];
				$finalprodInfo[$i]["DSR_Name"]							=   $finalAllProdDetInfo[$checked_Product_Code]["DSR_Name"];
				$finalprodInfo[$i]["Product_code"]						=   $EachProdVal["Product_code"];
				$finalprodInfo[$i]["Product_Name"]						=   $EachProdVal["Product_description1"];
				$finalprodInfo[$i]["Brand_Id"]							=   $EachProdVal["brand"];
				$finalprodInfo[$i]["Product_Id"]						=   $EachProdVal["id"];
				$finalprodInfo[$i]["SOLQTY"]							=   $finalAllProdDetInfo[$checked_Product_Code]["SOLQTY"];
				//$finalprodInfo[$i]["KD_Code"]							=   $finalAllProdDetInfo[$checked_Product_Code]["KD_Code"];
			} else {
				$finalprodInfo[$i]["DSRCode"]							=   $DSRCODEVAL[DSR_Code];
				$finalprodInfo[$i]["DSR_Name"]							=   getdbval($DSRCODEVAL[DSR_Code],'DSRName','DSR_Code','dsr');
				$finalprodInfo[$i]["Product_code"]						=   $EachProdVal["Product_code"];
				$finalprodInfo[$i]["Product_Name"]						=   $EachProdVal["Product_description1"];
				$finalprodInfo[$i]["Brand_Id"]							=   $EachProdVal["brand"];
				$finalprodInfo[$i]["Product_Id"]						=   $EachProdVal["id"];
				$finalprodInfo[$i]["SOLQTY"]							=   0;
				//$finalprodInfo[$i]["KD_Code"]							=   getdbval($DSRCODEVAL[DSR_Code],"KD_Code"],'','dsr';
			}
			$checked_Product_Code		=	'';
			$i++;
		}
	}

	$finalAllProdDetInfo          =   $finalprodInfo;
	//pre($finalAllProdDetInfo);
	//count($finalAllProdDetInfo)."<br>";
	//exit;


	$query_prod													=   "SELECT brand,Product_id,Product_description1,Product_code FROM customertype_product WHERE Product_code IN ('".$prodcode_Total."')";
	$res_prod													=   mysql_query($query_prod);
	while($row_prod												=   mysql_fetch_assoc($res_prod)) {
		$prodInfoAnot[$row_prod["Product_code"]]					=	$row_prod;
		//$prodInfoDet[$row_prod[Product_code]][Product_code]	=	$row_prod[Product_code];
		$prodInfoDet[]											=	$row_prod;
	}
	
	if(mysql_num_rows($res_prod) > 0) {
		$i=0;
		$k=0;
		foreach($finalAllProdDetInfo as $val_prod){
			//$transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
			if($prodInfoAnot[$val_prod["Product_code"]]["Product_code"] == $val_prod["Product_code"]) {                                     
				$finalprodInfo[$i]["DSRCode"]							=   $val_prod["DSRCode"];
				$finalprodInfo[$i]["DSR_Name"]							=   $val_prod["DSR_Name"];
				$finalprodInfo[$i]["Product_code"]						=   $val_prod["Product_code"];
				$finalprodInfo[$i]["Product_Name"]						=   $prodInfoAnot[$val_prod["Product_code"]]["Product_description1"];
				$finalprodInfo[$i]["Brand_Id"]							=   $prodInfoAnot[$val_prod["Product_code"]]["brand"];
				$finalprodInfo[$i]["Product_Id"]						=   $prodInfoAnot[$val_prod["Product_code"]]["Product_id"];
				$finalprodInfo[$i]["SOLQTY"]							=   $val_prod["SOLQTY"];
				//$finalprodInfo[$i]["KD_Code"]							=   $val_prod["KD_Code"];			
			} else {
				$finalprodInfo[$i]["DSRCode"]							=   $val_prod["DSRCode"];
				$finalprodInfo[$i]["DSR_Name"]							=   getdbval($DSRCODEVAL[DSR_Code],'DSRName','DSR_Code','dsr');
				$finalprodInfo[$i]["Product_code"]						=   $val_prod["Product_code"];
				$finalprodInfo[$i]["Product_Name"]						=   $val_prod["Product_Name"];
				$finalprodInfo[$i]["Brand_Id"]							=   $val_prod["Brand_Id"];
				$finalprodInfo[$i]["Product_Id"]						=   $val_prod["Product_Id"];
				$finalprodInfo[$i]["SOLQTY"]							=   0;
				//$finalprodInfo[$i]["KD_Code"]							=   $val_prod["KD_Code"];			
			}
			$i++;
			$k++;
		}

		$finalAllProdDetInfo          =   $finalprodInfo;
		//pre($finalAllProdDetInfo);
		//count($finalAllProdDetInfo)."<br>";
		//exit;

	}

	$query_target									=   "SELECT KD_Code,DSR_Code,Product_id,monthval,yearval,target_units AS TGT_UNT,target_naira AS TGT_NAI,targetFlag FROM sr_incentive $target_query ORDER BY Product_id";
	//echo $query_target;
	//exit;
	$res_target														=   mysql_query($query_target);
	while($row_target												=   mysql_fetch_assoc($res_target)) {
		$SR_Code													=	$row_target[DSR_Code];
		$Product_id													=	$row_target[Product_id];
		$targetProdUnitsDet[$SR_Code.$Product_id]["TGT_UNT"]		=	$row_target["TGT_UNT"];
		$targetProdNairaDet[$SR_Code.$Product_id]["TGT_NAI"]		=	$row_target["TGT_NAI"];
		$targettargetFlagDet[$SR_Code.$Product_id]["SRINCTGTFLAG"]	=	$row_target["targetFlag"];
		$targetInfoDet[$SR_Code.$Product_id]						=	$SR_Code.$Product_id;
	}	
	//pre($targetInfo);
	//pre($targetNaira);
	//pre($targetUnits);
	//pre($targettargetFlag);
	//pre($finalAllProdInfo);
	//exit;


	$i=0;
	foreach($finalAllProdDetInfo as $val_target)	{
		$SRCODEVAL			=	$val_target["DSRCode"];
		$Product_id			=	$val_target["Product_Id"];

		$INDEX_VAL			=	$SRCODEVAL.$Product_id;
		//echo	$targetInfoDet[$INDEX_VAL]	. "==".	$INDEX_VAL."<br>"; 
		if($targetInfoDet[$INDEX_VAL]	==	$INDEX_VAL) {

			$finalAllProdDetInfo[$i]["TGT_UNT"]			=   $targetProdUnitsDet[$INDEX_VAL]["TGT_UNT"];
			$finalAllProdDetInfo[$i]["TGT_NAI"]			=   $targetProdNairaDet[$INDEX_VAL]["TGT_NAI"];
			$finalAllProdDetInfo[$i]["SRINCTGTFLAG"]	=   $targettargetFlagDet[$INDEX_VAL]["SRINCTGTFLAG"];
			$finalAllProdDetInfo[$i]["POS_INC"]			=   $finalAllProdDetInfo[$i]["SOLQTY"]*$finalAllProdDetInfo[$i]["TGT_NAI"];

			if($finalAllProdDetInfo[$i]["SRINCTGTFLAG"] == '0') {
				if($finalAllProdDetInfo[$i]["SOLQTY"] >= $finalAllProdDetInfo[$i]["TGT_UNT"]) {
					$finalAllProdDetInfo[$i]["PAY_INC"]			=   $finalAllProdDetInfo[$i]["SOLQTY"]*$finalAllProdDetInfo[$i]["TGT_NAI"];
				} else {
					$finalAllProdDetInfo[$i]["PAY_INC"]			=   0;
				}
			} if($finalAllProdDetInfo[$i]["SRINCTGTFLAG"] == '1') {
				$finalAllProdDetInfo[$i]["PAY_INC"]			=   $finalAllProdDetInfo[$i]["SOLQTY"]*$finalAllProdDetInfo[$i]["TGT_NAI"];
			}
		}
		$i++;
	}

	//pre($finalAllProdDetInfo);
	//exit;

	/*$orderbycolumns     =   'DSRCode';
	$orderbysorting     =   'ASC';

	if($orderbysorting == 'DESC') {
		$dir        =   'arsort';               
	} else {
		$dir        =   'asort';   
	}
	$finalAllProdDetInfo	=	subval_sort($finalAllProdDetInfo,$orderbycolumns,$dir);

	*/

	//pre($prodInfoDet);

	//pre($finalAllProdDetInfo);
	//exit;

	//pre($prodcode_trans);
	//echo count($prodcode_trans);
	//exit;
	$orderbycolumns     =   'Product_code';
	$orderbysorting     =   'ASC';

	if($orderbysorting == 'DESC') {
		$dir        =   'arsort';               
	} else {
		$dir        =   'asort';   
	}
	$prodInfoDetail	=	subval_sort($prodInfo,$orderbycolumns,$dir);

	

	$finalAllProdDetInfo	=	array_multi_sort($finalAllProdDetInfo, "DSRCode","Product_code", $order=SORT_ASC);
	

	//pre($finalAllProdDetInfo);
	//pre($prodInfoDetail);
	//exit;

	//pre($finalAllProdDetInfo);

  $k						=	0;
  $arrcnt					=	count($finalAllProdDetInfo);
  $DSRVAL					=	'';
  $DSRVALUE					=	'';
  $breakval					=	0;
  if($arrcnt > 0) { 
  ?>
  <table border="1" width="100%">
	
	
	<!--<thead>
	  <tr>
		<th align="center" style="width:10%">SR</th>
		<?php foreach($prodInfoDet AS $prodInfoDetKey=>$prodInfoDetVal) { 
			if($DSRVAL == 	$prodInfoDetVal[Product_code]) { ?>
				</tr><tr>
			<?php }
		
		?>
		<th align="center" style="width:1%"><?php echo $prodInfoDetVal[Product_description1]; ?>
		 <table  width="100%">
		 <tr>
		 <td>Target</td>
		 <td>Naira</td>
		 <td>Sold Quantity</td>
		 <td>Possible Incentive</td>
		 <td>Payable Incentive</td>
		 </tr>
		 </table>
		</th>
		<?php 		
		$DSRVAL			=		$prodInfoDetVal[Product_code];
		} ?>
	</tr>
 </thead>--> 

 <thead>
	<tr>
		<th align="left" colspan="19">SR ALL PRODUCTS DETAILS</th>
	</tr>
  	  <tr>
  		<th align="center" style="width:10%">SR</th>
  		<?php foreach($prodInfoDetail AS $prodInfoDetKey=>$prodInfoDetVal) { 
				//if($DSRVAL == 	$prodInfoDetVal[Product_code]) { ?>
					<!-- </tr><tr> -->
				<?php // }
			if($prodInfoDetVal[Product_code] != '') {
				?>
				<th align="center" style="width:1%"><?php echo getdbval($prodInfoDetVal[Product_code],'Product_description1','Product_code','product'); ?>
				 <table  width="100%">
				 <tr>
				 <td>Target</td>
				 <td>Naira</td>
				 <td>Sold Quantity</td>
				 <td>Possible Incentive</td>
				 <td>Payable Incentive</td>
				 </tr>
				 </table>
				</th>
				<?php 		
				$DSRVAL			=		$prodInfoDetVal;
			}		
		} ?>
  	</tr>
  </thead>



  <!--<tbody>
  	<tr>
  	<?php 
  	$y	=	0;
  	foreach($finalAllProdDetInfo AS $SearchKey=>$SearchVal) { 
  		//echo $DSRVALUE ."==". 	$SearchVal[DSRCode]."<br>";		
  		if($DSRVALUE != '') {
  			if($DSRVALUE != 	$SearchVal[DSRCode]) { 
  				$y	=	0;
  				?>
  				</tr><tr>
  				<td><?php echo $SearchVal[DSR_Name]; ?></td>	
  			<?php }
  		} else { ?>
  			<td><?php echo $SearchVal[DSR_Name]; ?></td>	
  		<?php }
  		//echo $prodInfoDet[$y][Product_code] ."==". $SearchVal[Product_code]."<br>";
  	if($prodInfoDet[$y][Product_code] == $SearchVal[Product_code]) { ?>
  	 <td><table  width="100%">
  			 <tr>
  			 <td><?php echo $SearchVal[TGT_UNT]; ?></td>
  			 <td><?php echo $SearchVal[TGT_NAI]; ?></td>
  			 <td><?php echo $SearchVal[SOLQTY]; ?></td>
  			 <td><?php echo $SearchVal[POS_INC]; ?></td>
  			 <td><?php echo $SearchVal[PAY_INC]; ?></td>
  			 </tr>
  	  </table>
  	 </td>	
  	 <?php } else { 
  	 
  	// $product_countcheck	is the total unique products to display
  	 
  	 for($t=$y+1; $t<$product_countcheck-$y; $t++) {
  	
  	//echo $t."==".$product_countcheck-$y."<br>";
  	//echo "334"
  		 
  	 ?>
  	 <td><table  width="100%">
  		 <tr>
  		 <td>0</td>
  		 <td>0</td>
  		 <td>0</td>
  		 <td>0</td>
  		 <td>0</td>
  		 </tr>
  		  </table>
  	 </td>	
  
  	<?php //$t++; 
  	//echo $prodInfoDet[$t][Product_code] ."==". $SearchVal[Product_code]."<br>";
  	if($prodInfoDet[$t][Product_code] == $SearchVal[Product_code]) { ?> 
  	 <td><table  width="100%">
  		 <tr>
  		 <td><?php echo $SearchVal[TGT_UNT]; ?></td>
  		 <td><?php echo $SearchVal[TGT_NAI]; ?></td>
  		 <td><?php echo $SearchVal[SOLQTY]; ?></td>
  		 <td><?php echo $SearchVal[POS_INC]; ?></td>
  		 <td><?php echo $SearchVal[PAY_INC]; ?></td>
  		 </tr>
  		</table>
  	 </td>
  	<?php 
  		$breakval = 1;	
  		
  
  	} //if loop
  		if($breakval == 1) {
  			$breakval	=	0;
  			break; // IF THE FOR LOOP FOUND THE PRODUCT THEN DISCONTINUE FROM THE FOR LOOP
  		}		
  	 } //for loop ?>
  
  
  
  	<?php } //else loop
  		$DSRVALUE			=		$SearchVal[DSRCode]; 
  	$y++;
  	} //foreach ends here ?>
  	 </tr>
  </tbody> -->



 <tbody>
  	<tr>
  	<?php 
  	$y	=	0;
  	foreach($finalAllProdDetInfo AS $SearchKey=>$SearchVal) { 
		
		if($first_DSR	!=	$SearchVal[DSR_Name]) {
			$y=0;
			echo "</tr><tr>";
		}

		if($y == 0) { 
			$first_DSR		=	$SearchVal[DSR_Name];
			
			?>
			<td><?php echo $SearchVal[DSR_Name]; ?></td>
			<td><table  width="100%">
					 <tr>
					 <td><?php echo $SearchVal[TGT_UNT]; ?></td>
					 <td><?php echo $SearchVal[TGT_NAI]; ?></td>
					 <td><?php echo $SearchVal[SOLQTY]; ?></td>
					 <td><?php echo $SearchVal[POS_INC]; ?></td>
					 <td><?php echo $SearchVal[PAY_INC]; ?></td>
					 </tr>
			  </table>
			 </td>

   <?php $y++; } else { ?>
  	 <td><table  width="100%">
  			 <tr>
  			 <td><?php echo $SearchVal[TGT_UNT]; ?></td>
  			 <td><?php echo $SearchVal[TGT_NAI]; ?></td>
  			 <td><?php echo $SearchVal[SOLQTY]; ?></td>
  			 <td><?php echo $SearchVal[POS_INC]; ?></td>
  			 <td><?php echo $SearchVal[PAY_INC]; ?></td>
  			 </tr>
  	  </table>
  	 </td>
  	<?php 
	}		//ELSE LOOP ENDS HERE
		} //foreach ends here ?>
  	 </tr>
  </tbody>


 </table>
<span id="printopen" style="padding-left:470px;padding-top:10px;<?php if($arrcnt > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printsrincstatusajax');"></span>
<form id="printsrincstatusajax" target="_blank" action="printsrincstatusajax.php" method="post">
	<input type="hidden" name="srcode" id="srcode" value="<?php echo $srcodeprint; ?>" />
	<input type="hidden" name="propmonths" id="propmonths" value="<?php echo $propmonths; ?>" />
	<input type="hidden" name="propyears" id="propyears" value="<?php echo $propyears; ?>" />
</form>


<?php 
  } else { ?>
	<table border="1" width="100%">
		<thead>
		  <tr>				
			<th align="center" style="width:10%">SR</th>
			<th align="center" style="width:10%">Product1
			 <table  width="100%">
			 <tr>
			 <td>Target</td>
			 <td>Naira</td>
			 <td>Possible Incentive</td>
			 </tr>
			 </table></th>				
			</tr>
	  </thead>
	 <tbody>
	 <tr>
	 <td colspan="2" align="center"><strong>NO RECORDS FOUND</strong</td>
	 </tr>
	 </tbody>
	</table>
	<?php }  
} exit(0);?>