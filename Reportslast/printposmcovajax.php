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
		
	/*$datecol		=	"(Date >= '".$fromdatevalue."' AND Date <= '".$todatevalue."')";
	$datecolfocus	=	"(LEFT(Date,10) >= '".$fromdatevalue."' AND LEFT(Date,10) <= '".$todatevalue."')";*/

	$datecolvalue	=	$propyears."-".$propmonths;
	$datecol		=	"Date LIKE '".$datecolvalue."%'";
	
	if($asmcode	==	'' || $asmcode	==	'null') {
		$asmcodecol		=	'';
		$wherefordsr	=	'';
	} elseif($asmcode	!=	'') {
		//$asmcodestr		=	implode("','",$asmcode);
		$asmcodestr		=	$asmcode;
		$asmcodecol		=	"ASM IN ('".$asmcodestr."')";
		$asmcodecolval	=	"DSR_Code IN ('".$asmcodestr."')";
		$wherefordsr	=	'WHERE';
	}

	if($rsmcode	==	'' || $rsmcode	==	'null') {
		$rsmcodecol		=	'';
	} elseif($rsmcode	!=	'') {
		//$rsmcodestr	=	implode("','",$rsmcode);
		$rsmcodestr		=	$rsmcode;
		$rsmcodecol		=	"RSM IN ('".$rsmcodestr."')";
	}
	
	if($srcode	==	'' || $srcode	==	'null') {
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
	
	//pre($custype);

	if($custype == '' || $custype == 'null') {
		$custypeval		=	'';
	} else {
		//$custypeval		=	implode(",",$custype);
		$custypeval		=	$custype;
	}

	if($target_query	==	'') {
		$propmonthstrim		=	ltrim($propmonths,0);
		$target_query	=	"WHERE monthval = '$propmonthstrim' AND yearval = '$propyears'";
	} else if($target_query	!=	'') {
		$target_query	=	"AND monthval = '$propmonths' AND yearval = '$propyears'";
	}

	if($complete_query	==	'') {
		$complete_query			.=	" WHERE $datecol AND Transaction_type IN ('2','3','4')";
		//$complete_query		.=	" WHERE $datecol";
	} else if($complete_query	!=	'') {
		$complete_query			.=	" AND $datecol AND Transaction_type IN ('2','3','4')";
		//$complete_query		.=	" AND $datecol";
	}

	if($focuscheck_query	==	'') {
		$focuscheck_query			.=	" WHERE $datecolfocus";
		//$complete_query		.=	" WHERE $datecol";
	} else if($focuscheck_query	!=	'') {
		$focuscheck_query			.=	" AND $datecolfocus";
		//$complete_query		.=	" AND $datecol";
	}

	$query_transhdr													=   "SELECT id,Customer_code,Transaction_Number,Date,Time,transaction_Reference_Number FROM transaction_hdr $complete_query";
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


	//$query_trans										=   "SELECT KD_Code,DSR_Code,Product_code,Transaction_Number,SUM(Sold_quantity) AS SOLQTY FROM transaction_line WHERE POSM_Flag = '1' AND Transaction_Number IN ('".$transno_Total."') $prodcodecol GROUP BY Product_code, DSR_Code ORDER BY Product_code";
	$query_trans										=   "SELECT KD_Code,DSR_Code,Product_code,Transaction_Number,Sold_quantity AS SOLQTY FROM transaction_line WHERE POSM_Flag = '1' AND Transaction_Number IN ('".$transno_Total."') AND Product_code != '0' $prodcodecol ORDER BY Product_code";
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
	}
	 
	//pre($transCusTypeInfo);
	//exit;

	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_transno){
		if($transCusTypeInfo[$val_transno["CUS_CODE"]]["customer_code"] == $val_transno["CUS_CODE"]) {
			
			if($custypeval != '') {
				//echo ($custypeval);
				//echo $transCusTypeInfo[$val_transno["CUS_CODE"]]["customer_type"]."<br>";
				
				//if(array_search($transCusTypeInfo[$val_transno["CUS_CODE"]]["customer_type"],$custypeval) !== false) {
				if(strstr($custypeval,$transCusTypeInfo[$val_transno["CUS_CODE"]]["customer_type"])) {
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
					//echo "godofd";
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

	$query_prod										=   "SELECT principal,brand,Product_id AS id,Product_description1,Product_code FROM customertype_product WHERE Product_code IN ('".$prodcode_Total."')";
	$res_prod										=   mysql_query($query_prod);
	while($row_prod									=   mysql_fetch_assoc($res_prod)) {
		$prodInfo[$row_prod["Product_code"]]		=	$row_prod;
		$brandid_brand[]							=	$row_prod["brand"];
		$principalid_princ[]						=	$row_prod["principal"];
	}
	
	$brandid_Total				=	implode("','",$brandid_brand);
	$principalid_Total			=	implode("','",$principalid_princ);

	$i=0;
	$k=0;
	foreach($finalAllProdInfo as $val_prod){
		//$transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
		if($prodInfo[$val_prod["Product_code"]]["Product_code"] == $val_prod["Product_code"]) {                                     
			
			$finalprodInfo[$i]["Product_Name"]				=   $prodInfo[$val_prod["Product_code"]]["Product_description1"];
			$finalprodInfo[$i]["Brand_Id"]					=   $prodInfo[$val_prod["Product_code"]]["brand"];
			$finalprodInfo[$i]["Product_Id"]				=   $prodInfo[$val_prod["Product_code"]]["id"];
			$finalprodInfo[$i]["Principal_Id"]				=   $prodInfo[$val_prod["Product_code"]]["principal"];
			$finalprodInfo[$i]["DSR_Name"]					=   $val_prod["DSR_Name"];
			$finalprodInfo[$i]["KD_Name"]					=   $val_prod["KD_Name"];
			$finalprodInfo[$i]["Product_code"]				=   $val_prod["Product_code"];
			//$finalprodInfo[$i]["CUS_CODE"]				=   $val_prod["Product_Id"];
			$finalprodInfo[$i]["DSRCode"]					=   $val_prod["DSRCode"];
			$finalprodInfo[$i]["KD_Code"]					=   $val_prod["KD_Code"];
			$finalprodInfo[$i]["SOLQTY"]					=   $val_prod["SOLQTY"];
			$finalprodInfo[$i]["CUS_CODE"]					=   $val_prod["CUS_CODE"];
			$finalprodInfo[$i]["TRANSNO"]					=   $val_prod["TRANSNO"];
			$finalprodInfo[$i]["CUSCNT"]					=   $val_prod["CUSCNT"];
			$finalprodInfo[$i]["CUSTYP"]					=   $val_prod["CUSTYP"];			
			$i++;
		}
		$k++;
	}

	$finalAllProdInfo          =   $finalprodInfo;
	//pre($finalAllProdInfo);
	//exit;

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

	$orderbycolumns     =   $reportby;
	$orderbysorting     =   'ASC';

	if($orderbysorting == 'DESC') {
		$dir        =   'arsort';               
	} else {
		$dir        =   'asort';   
	}
	$finalAllProdInfo	=	subval_sort($finalAllProdInfo,$orderbycolumns,$dir);
	//pre($finalAllProdInfo);
	//exit;
} else {
	$nextrecval			=	"";
}
$num_rows		=	count($finalAllProdInfo);
?>
<title>POSM COVERAGE</title>
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
	height:480px;
}
.alignment_report{
width:96%;
padding-left:20px;
margin-left:10px;
font-size:16px;
}
.condaily_routeplan th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}
.condaily_routeplan td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	padding-left:10px;
	color:#000;
	font-size:14px;
}
.condaily_routeplan tbody tr:hover td {
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
#errormsgposmcov {
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
.myalignposmcov {
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

#span1 {
	width: 30px; 
	float:left;
  }
#span2 { 
    width: 30px; 
	float:right;
	}
	
#colors{
	background-color:#CCC;
}
  
</style>

  <div class="condaily_routeplan">

  <table border="1" width="100%">
	<thead>
		<tr>
			<th align="center" colspan="19">POSM COVERAGE</th>
		</tr>
		<tr>
			<th align="left" colspan="19"><?php echo "Month & Year : &nbsp;&nbsp;&nbsp;".date("F",mktime(0,0,0,$propmonths))."&nbsp;".$propyears."&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;SR : &nbsp;&nbsp;";

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

			echo "&nbsp;&nbsp;&nbsp;Customer Type : &nbsp;&nbsp;";

			if($custype == '' || $custype == 'null') {
				echo "ALL";
			} else {
				$explode_custype		=	explode(",", str_replace("'","",$custype));
				$tom	=	0;
				foreach($explode_custype AS $custypeval) {
					if($tom	==	0) {
						echo upperstate(getdbval($custypeval,'customer_type','id','customer_type'));
					} else {
						echo ",&nbsp;".upperstate(getdbval($custypeval,'customer_type','id','customer_type'));
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
	   <tr>
		<th align="center" style="width:10%">SR Name</th>
		<th align="center" style="width:10%">Principal</th>
		<th align="center" style="width:10%">Brand</th>
		<th align="center" style="width:70%">POSM Item</th>
		<th align="center" style="width:10%">Total Customer To be Covered</th>
		<th align="center" style="width:10%">Units Per Customer</th>
		<th align="center" style="width:10%">Total Customer Covered</th>
		<th align="center" style="width:10%">Units Given</th>
		<th align="center" style="width:10%">Customers Not Covered</th>
		<th align="center" style="width:10%">Short Units</th>
		<th align="center" style="width:10%">POSM Coverage %
		 <table  width="100%"><tr><td>Customer</td><td>Units</td></tr></table>
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
		$arrcnt					=	count($finalAllProdInfo);
		$subtotalcheckfor		=	1;
		
		$total_cus				=	'';
		$total_units			=	'';
		$total_cuscov			=	'';
		$total_unicov			=	'';
		$total_notcov			=	'';
		$total_shouni			=	'';
		$total_cusper			=	'';
		$total_uniper			=	'';

		$tot_cus				=	'';
		$tot_units			=	'';
		$tot_cuscov			=	'';
		$tot_unicov			=	'';
		$tot_notcov			=	'';
		$tot_shouni			=	'';
		$tot_cusper			=	'';
		$tot_uniper			=	'';

if($arrcnt > 0) {
 foreach($finalAllProdInfo AS $SearchKey=>$SearchVal) { 
	$total_cus				+=	$SearchVal["NOCUS"];
	$total_units			+=	$SearchVal["UNITVAL"];
	$total_cuscov			+=	$SearchVal["CUSCNT"];
	$total_unicov			+=	$SearchVal["SOLQTY"];
	$total_notcov			+=	$SearchVal["NOTCOV"];
	$total_shouni			+=	$SearchVal["SHORTUTS"];
	$total_cusper			+=	$SearchVal["CUSPER"];
	$total_uniper			+=	$SearchVal["UNIPER"];
	
	if($reportby == 'DSR_Name') {
		if($checkfor		==	'') {
			$checkfor		=	$SearchVal["DSR_Name"];
			$checkoutfor	=	$SearchVal["DSR_Name"];
			
			$tot_cus			=	'';
			$tot_units			=	'';
			$tot_cuscov			=	'';
			$tot_unicov			=	'';
			$tot_notcov			=	'';
			$tot_shouni			=	'';
			$tot_cusper			=	'';
			$tot_uniper			=	'';

			if($subtotalcheckfor == 2) {
				$subtotalcheckfor = 1;
				$tot_cus			+=	$SearchVal["NOCUS"];
				$tot_units			+=	$SearchVal["UNITVAL"];
				$tot_cuscov			+=	$SearchVal["CUSCNT"];
				$tot_unicov			+=	$SearchVal["SOLQTY"];
				$tot_notcov			+=	$SearchVal["NOTCOV"];
				$tot_shouni			+=	$SearchVal["SHORTUTS"];
				$tot_cusper			+=	$SearchVal["CUSPER"];
				$tot_uniper			+=	$SearchVal["UNIPER"];
			}
		} else {
			$checkoutfor	=	$SearchVal["DSR_Name"];
			if($subtotalcheckfor == 1) {
				$tot_cus			+=	$SearchVal["NOCUS"];
				$tot_units			+=	$SearchVal["UNITVAL"];
				$tot_cuscov			+=	$SearchVal["CUSCNT"];
				$tot_unicov			+=	$SearchVal["SOLQTY"];
				$tot_notcov			+=	$SearchVal["NOTCOV"];
				$tot_shouni			+=	$SearchVal["SHORTUTS"];
				$tot_cusper			+=	$SearchVal["CUSPER"];
				$tot_uniper			+=	$SearchVal["UNIPER"];
			}
		}
	} elseif($reportby == 'Principal_Name') {
		if($checkfor		==	'') {
			$checkfor		=	$SearchVal["Principal_Name"];
			$checkoutfor	=	$SearchVal["Principal_Name"];
			
			$tot_cus			=	'';
			$tot_units			=	'';
			$tot_cuscov			=	'';
			$tot_unicov			=	'';
			$tot_notcov			=	'';
			$tot_shouni			=	'';
			$tot_cusper			=	'';
			$tot_uniper			=	'';

			if($subtotalcheckfor == 2) {
				$subtotalcheckfor = 1;
				$tot_cus			+=	$SearchVal["NOCUS"];
				$tot_units			+=	$SearchVal["UNITVAL"];
				$tot_cuscov			+=	$SearchVal["CUSCNT"];
				$tot_unicov			+=	$SearchVal["SOLQTY"];
				$tot_notcov			+=	$SearchVal["NOTCOV"];
				$tot_shouni			+=	$SearchVal["SHORTUTS"];
				$tot_cusper			+=	$SearchVal["CUSPER"];
				$tot_uniper			+=	$SearchVal["UNIPER"];
			}
		} else {
			$checkoutfor	=	$SearchVal["Principal_Name"];
			if($subtotalcheckfor == 1) {
				$tot_cus			+=	$SearchVal["NOCUS"];
				$tot_units			+=	$SearchVal["UNITVAL"];
				$tot_cuscov			+=	$SearchVal["CUSCNT"];
				$tot_unicov			+=	$SearchVal["SOLQTY"];
				$tot_notcov			+=	$SearchVal["NOTCOV"];
				$tot_shouni			+=	$SearchVal["SHORTUTS"];
				$tot_cusper			+=	$SearchVal["CUSPER"];
				$tot_uniper			+=	$SearchVal["UNIPER"];
			}
		}		
	}  elseif($reportby == 'Brand_Name') {
		if($checkfor		==	'') {
			$checkfor		=	$SearchVal["Brand_Name"];
			$checkoutfor	=	$SearchVal["Brand_Name"];
			
			$tot_cus			=	'';
			$tot_units			=	'';
			$tot_cuscov			=	'';
			$tot_unicov			=	'';
			$tot_notcov			=	'';
			$tot_shouni			=	'';
			$tot_cusper			=	'';
			$tot_uniper			=	'';

			if($subtotalcheckfor == 2) {
				$subtotalcheckfor = 1;
				$tot_cus			+=	$SearchVal["NOCUS"];
				$tot_units			+=	$SearchVal["UNITVAL"];
				$tot_cuscov			+=	$SearchVal["CUSCNT"];
				$tot_unicov			+=	$SearchVal["SOLQTY"];
				$tot_notcov			+=	$SearchVal["NOTCOV"];
				$tot_shouni			+=	$SearchVal["SHORTUTS"];
				$tot_cusper			+=	$SearchVal["CUSPER"];
				$tot_uniper			+=	$SearchVal["UNIPER"];
			}
		} else {
			$checkoutfor	=	$SearchVal["Brand_Name"];
			if($subtotalcheckfor == 1) {
				$tot_cus			+=	$SearchVal["NOCUS"];
				$tot_units			+=	$SearchVal["UNITVAL"];
				$tot_cuscov			+=	$SearchVal["CUSCNT"];
				$tot_unicov			+=	$SearchVal["SOLQTY"];
				$tot_notcov			+=	$SearchVal["NOTCOV"];
				$tot_shouni			+=	$SearchVal["SHORTUTS"];
				$tot_cusper			+=	$SearchVal["CUSPER"];
				$tot_uniper			+=	$SearchVal["UNIPER"];
			}
		}
	} elseif($reportby == 'Product_Name') {
		if($checkfor		==	'') {
			$checkfor		=	$SearchVal["Product_Name"];
			$checkoutfor	=	$SearchVal["Product_Name"];
			
			$tot_cus			=	'';
			$tot_units			=	'';
			$tot_cuscov			=	'';
			$tot_unicov			=	'';
			$tot_notcov			=	'';
			$tot_shouni			=	'';
			$tot_cusper			=	'';
			$tot_uniper			=	'';

			if($subtotalcheckfor == 2) {
				$subtotalcheckfor = 1;
				$tot_cus			+=	$SearchVal["NOCUS"];
				$tot_units			+=	$SearchVal["UNITVAL"];
				$tot_cuscov			+=	$SearchVal["CUSCNT"];
				$tot_unicov			+=	$SearchVal["SOLQTY"];
				$tot_notcov			+=	$SearchVal["NOTCOV"];
				$tot_shouni			+=	$SearchVal["SHORTUTS"];
				$tot_cusper			+=	$SearchVal["CUSPER"];
				$tot_uniper			+=	$SearchVal["UNIPER"];
			}
		} else {
			$checkoutfor	=	$SearchVal["Product_Name"];
			if($subtotalcheckfor == 1) {
				$tot_cus			+=	$SearchVal["NOCUS"];
				$tot_units			+=	$SearchVal["UNITVAL"];
				$tot_cuscov			+=	$SearchVal["CUSCNT"];
				$tot_unicov			+=	$SearchVal["SOLQTY"];
				$tot_notcov			+=	$SearchVal["NOTCOV"];
				$tot_shouni			+=	$SearchVal["SHORTUTS"];
				$tot_cusper			+=	$SearchVal["CUSPER"];
				$tot_uniper			+=	$SearchVal["UNIPER"];
			}
		}		
	} 

		
	if((($checkfor == $checkoutfor) && ($checkfor != '' && $checkoutfor !='')) && ($k != $arrcnt)) {  		
		$subtotalcheckfor = 2;
		$tot_cus			+=	$SearchVal["NOCUS"];
		$tot_units			+=	$SearchVal["UNITVAL"];
		$tot_cuscov			+=	$SearchVal["CUSCNT"];
		$tot_unicov			+=	$SearchVal["SOLQTY"];
		$tot_notcov			+=	$SearchVal["NOTCOV"];
		$tot_shouni			+=	$SearchVal["SHORTUTS"];
		$tot_cusper			+=	$SearchVal["CUSPER"];
		$tot_uniper			+=	$SearchVal["UNIPER"];
	} else {
		 
	if($k != 0) {
		 //echo $checkfor ."==" .$checkoutfor."<br>";
		 //$checkoutfor		=	$SearchVal["Brand_Name"];
	?>
	 <tr>
		 <td colspan="4" align="right"><strong><?php 
		 //echo $target_naira	. " == " . $target_units . " == " . $SUM_SQ . " == " . $VALUE_NAIRA . " == " . $diff_units . " == " . $diff_naira. " == " .  $subtotalcheckfor. "<br/>";
		 
		 //echo $checkfor ."==" .$checkoutfor."<br>"; ?> Sub Total<strong></td>
		<td><?php echo $tot_cus; ?></td>	
		<td><?php echo $tot_units; ?></td>
		<td><?php echo $tot_cuscov; ?></td>	
		<td><?php echo $tot_unicov; ?></td>	
		<td><?php echo $tot_notcov; ?></td>	
		<td><?php echo $tot_shouni; ?></td>	
		<td>&nbsp;
		<table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_cusper; ?></td><td><?php echo $tot_uniper; ?></td></tr></table>
		</td>			
  </tr>
<?php
	$checkfor			=	'';
	$subtotalcheckfor	=	'';
	$tot_cus			=	$SearchVal["NOCUS"];
	$tot_units			=	$SearchVal["UNITVAL"];
	$tot_cuscov			=	$SearchVal["CUSCNT"];
	$tot_unicov			=	$SearchVal["SOLQTY"];
	$tot_notcov			=	$SearchVal["NOTCOV"];
	$tot_shouni			=	$SearchVal["SHORTUTS"];
	$tot_cusper			=	$SearchVal["CUSPER"];
	$tot_uniper			=	$SearchVal["UNIPER"];

	//echo $target_naira	. " == " . $target_units . " == " . $SUM_SQ . " == " . $VALUE_NAIRA . " == " . $diff_units . " == " . $diff_naira. " == " .  $subtotalcheckfor."<br/>";
} }


$checkfor	=	$checkoutfor;

?>
<tr>
	 <td <?php if($reportby == 'DSR_Name') { ?> style="background-color:#31859C;" <?php } ?>><?php echo ucwords(strtolower($SearchVal[DSR_Name])); ?></td>
	 <td <?php if($reportby == 'Principal_Name') { ?> style="background-color:#31859C;" <?php } ?>><?php echo ucwords(strtolower($SearchVal[Principal_Name])); ?></td>
	 <td <?php if($reportby == 'Brand_Name') { ?> style="background-color:#31859C;" <?php } ?>><?php echo ucwords(strtolower($SearchVal[Brand_Name])); ?></td>
	 <td <?php if($reportby == 'Product_Name') { ?> style="background-color:#31859C;" <?php } ?>><?php echo ucwords(strtolower($SearchVal[Product_Name])); ?></td>
	 <td><?php echo $SearchVal[NOCUS]; ?></td>	
	 <td><?php echo $SearchVal[UNITVAL]; ?></td>
	 <td><?php echo $SearchVal[CUSCNT]; ?></td>	
	 <td><?php echo $SearchVal[SOLQTY]; ?></td>
	 <td><?php echo $SearchVal[NOTCOV]; ?></td>	
	 <td><?php echo $SearchVal[SHORTUTS]; ?></td>	
	 <td>&nbsp;
	 <table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $SearchVal[CUSPER]; ?></td><td><?php echo $SearchVal[UNIPER]; ?></td></tr></table>
	 </td>	
 </tr>
 <?php $k++; } ?> 
 <tr>
	<td colspan="4" align="right"><strong>Sub Total<strong></td>
	<td><?php echo $tot_cus; ?></td>	
	<td><?php echo $tot_units; ?></td>
	<td><?php echo $tot_cuscov; ?></td>	
	<td><?php echo $tot_unicov; ?></td>	
	<td><?php echo $tot_notcov; ?></td>	
	<td><?php echo $tot_shouni; ?></td>	
	<td>&nbsp;
	<table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $tot_cusper; ?></td><td><?php echo $tot_uniper; ?></td></tr></table>
	</td>		
 </tr>
 <tr>
	 <td colspan="4" align="right"><strong>Grand Total<strong></td>
	<td><?php echo $total_cus; ?></td>	
	<td><?php echo $total_units; ?></td>
	<td><?php echo $total_cuscov; ?></td>	
	<td><?php echo $total_unicov; ?></td>	
	<td><?php echo $total_notcov; ?></td>	
	<td><?php echo $total_shouni; ?></td>	
	<td>&nbsp;
	<table width="100%"><tr><td> <?php //echo $k . "+++++" . $arrcnt."<br/>"; echo $checkfor ."==" .$checkoutfor."<br>"; ?> <?php echo $total_cusper; ?></td><td><?php echo $total_uniper; ?></td></tr></table>
	</td>
 </tr>
 </tbody>	
</table>
</div>
<span id="printopen" style="padding-left:580px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php } else { ?>
 <tr>
	<td colspan="16" align='center'><strong>NO RECORDS FOUND</strong></td>
 </tr>
<?php } exit(0); ?>