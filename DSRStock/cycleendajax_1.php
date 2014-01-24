<?php
session_start();
ob_start();
ini_set("max_execution_time", "20000");
//echo ini_get("max_execution_time");
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";

//ini_set("display_errors",true);
//error_reporting(E_ALL);
if(isset($_REQUEST['logout'])){
	session_destroy();
	header("Location:../index.php");
}
date_default_timezone_set('Asia/Calcutta');
error_reporting(0);
extract($_REQUEST);
$Total_Amount_Deposited		=	0;
$Total_Sale_Value			=	0;
if(isset($_REQUEST[DateVal]) && $_REQUEST[DateVal] !='') {
	$query_curdatetime		=	"SELECT CURTIME() AS timeval";
	$res_curdatetime		=	mysql_query($query_curdatetime);
	$row_curdatetime		=	mysql_fetch_array($res_curdatetime);
	$curdatetime			=	$row_curdatetime[timeval];

	$cycle_end_date			=	$_REQUEST[DateVal];
	//$cycleenddatetime		=	$cycle_end_date." ".$curdatetime;
	$cycleenddatetime		=	$cycle_end_date;

	$DSR_id				=	getdsrval($DSR_Code,'id','DSR_Code');			
	$nextrecval			=	"WHERE (cycle_end_flag = '0' and cycle_start_flag = '1') AND dsr_id = '$DSR_id' ORDER BY cycle_start_date DESC";
} else {
	$nextrecval			=	"";
}
$where					=	"$nextrecval";

if(isset($_REQUEST) && $_REQUEST !='')
{
	$qry="SELECT * FROM `cycle_flag` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
//echo $qry;
//exit;
$results					=	mysql_query($qry);
$num_rows					=	mysql_num_rows($results);
//exit;			
$pager						=	new PS_Pagination($bd, $qry,15,15);
$results					=	$pager->paginate();

$sel_cyclestart				=	"SELECT * FROM `cycle_assignment` WHERE (flag_status = '1' and end_flag_status = '0') AND dsr_id = '$DSR_id' ORDER BY Date DESC";
$res_cyclestart				=	mysql_query($sel_cyclestart) or die(mysql_error());	
$row_cyclestart				=	mysql_fetch_array($res_cyclestart);
$cycle_start_date			=	$row_cyclestart['Date'];
$sel_cyclestartdatetime		=	"SELECT cycle_start_date FROM `cycle_flag` $where";
$res_cyclestartdatetime		=	mysql_query($sel_cyclestartdatetime) or die(mysql_error());	
$row_cyclestartdatetime		=	mysql_fetch_array($res_cyclestartdatetime);
$cyclestartdatetime			=	$row_cyclestartdatetime['cycle_start_date'];
$device_code				=	getdeviceval($row_cyclestart['device_id'],'device_code','id');
$vehicle_code				=	getvehicleval($row_cyclestart['vehicle_id'],'vehicle_code','id');
$device_name				=	getdeviceval($row_cyclestart['device_id'],'device_description','id');
$vehicle_name				=	getvehicleval($row_cyclestart['vehicle_id'],'vehicle_desc','id');

$sel_currency		=	"SELECT currency FROM `parameters`";
$res_currency		=	mysql_query($sel_currency) or die(mysql_error());	
$row_currency		=	mysql_fetch_array($res_currency);
$currency			=	$row_currency['currency'];

$sel_uom			=	"SELECT UOM_description FROM `uom`";
$res_uom			=	mysql_query($sel_uom) or die(mysql_error());	
$row_uom			=	mysql_fetch_array($res_uom);
$UOM_desc			=	$row_uom['UOM_description'];

//$sel_productcode		=	"SELECT Product_code from dailystockloading WHERE DSR_Code = '$DSR_Code' AND vehicle_code = '$vehicle_code' AND (Date >= '$cyclestartdatetime' AND Date <= '$cycleenddatetime') GROUP BY Product_code";
$sel_productcode		=	"SELECT Product_code from dailystockloading WHERE DSR_Code = '$DSR_Code' AND (Date >= '$cyclestartdatetime' AND Date <= '$cycleenddatetime') GROUP BY Product_code";
//echo $sel_productcode;
//exit;
$res_productcode			=	mysql_query($sel_productcode) or die(mysql_error());	

while($row_productcode		=	mysql_fetch_array($res_productcode)) {
	$productcode_ds[]		=	$row_productcode['Product_code'];
}

//pre($productcode_ds);
//exit;

$query_prodhdr							=	"SELECT id,Transaction_Number,Product_Line_count,Transaction_type FROM transaction_hdr WHERE Transaction_type IN ('2','3') AND (CONCAT(Date, ' ', Time) >= '$cyclestartdatetime' AND CONCAT(Date, ' ', Time) <= '$cycleenddatetime') AND DSR_Code='$DSR_Code'";
//echo $query_prodhdr;
//exit;
$res_prodhdr							=	mysql_query($query_prodhdr)or die(mysql_error());
$rowcnt_prodhdr							=	mysql_num_rows($res_prodhdr);

if($rowcnt_prodhdr > 0 ) {
	while($row_prodhdr					=	mysql_fetch_array($res_prodhdr)) {
		$Transaction_Number				=	$row_prodhdr[Transaction_Number];	
		$query_prodlist					=	"SELECT Product_code FROM transaction_line WHERE DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number' AND (Product_code != '' AND Product_code != '0')";
		$res_prodlist					=	mysql_query($query_prodlist) or die(mysql_error());
		$rowcnt_prodlist				=	mysql_num_rows($res_prodlist);
		while($row_prodlist				=	mysql_fetch_array($res_prodlist)) {
			$productcode_hdr[]			=	$row_prodlist[Product_code];
		}
	}
}

//pre($productcode_hdr);
//exit;

$query_prodhdrret						=	"SELECT id,Transaction_Number,Product_Line_count,Transaction_type FROM transaction_hdr WHERE Transaction_type IN ('4') AND (CONCAT(Date, ' ', Time) >= '$cyclestartdatetime' AND CONCAT(Date, ' ', Time) <= '$cycleenddatetime') AND DSR_Code='$DSR_Code'";
//echo $query_prodhdrret;
//exit;
$res_prodhdrret							=	mysql_query($query_prodhdrret)or die(mysql_error());
$rowcnt_prodhdrret						=	mysql_num_rows($res_prodhdrret);

if($rowcnt_prodhdrret > 0 ) {
	while($row_prodhdrret				=	mysql_fetch_array($res_prodhdrret)) {
		$Transaction_Number				=	$row_prodhdrret[Transaction_Number];	
		$query_prodlistret				=	"SELECT Product_code FROM transaction_return_line WHERE DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number'";
		$query_prodlistret;
		$res_prodlistret				=	mysql_query($query_prodlistret) or die(mysql_error());
		$rowcnt_prodlistret				=	mysql_num_rows($res_prodlistret);
		while($row_prodlistret			=	mysql_fetch_array($res_prodlistret)) {
			$productcode_hdrret[]			=	$row_prodlistret[Product_code];
		}
	}
}

//pre($productcode_hdrret);
//exit;

if(!empty($productcode_hdr)) {
	if(!empty($productcode_ds)) {
		$compl_array	=	array_merge($productcode_ds,$productcode_hdr);
	} else {
		$compl_array	=	$productcode_hdr;
	}
} else {
	if(!empty($productcode_ds)) {
		$compl_array	=	$productcode_ds;
	} else {
		$compl_array	=	'';
	}
}

if(!empty($productcode_hdrret)) {
	if($compl_array != '') {
		$compl_array	=	array_merge($compl_array,$productcode_hdrret);
	} else {
		$compl_array	=	$productcode_hdrret;
	}
} else {
	if($compl_array != '') {
		$compl_array	=	$compl_array;
	} else {
		$compl_array	=	'';
	}
}

$productcode		=	array_unique($compl_array);

//pre($productcode);
//exit;

	echo $cycle_start_date."~".$DSR_Code."~".$device_name."~".$device_code."~".$vehicle_name."~".$vehicle_code."~".$currency."~".$UOM_desc."~";
	?>
	<div class="<?php if(!empty($num_rows) && ($productcode != '' && !empty($productcode))) { 
		echo "con"; 
	} 
	?> condaily">
	<table class="tablesorter" id="sort" width="100%">
	<thead>
	<tr>
	<th class="rounded">S NO</th>
	<th>Product Name</th>
	<th>Cycle Start Loaded Quantity</th>
	<th>Daily Total Loaded Quantity</th>
	<th>Total Sold Quantity</th>
	<th>Total Cancelled Quantity</th>
	<!-- <th>Total Sales Returned Quantity</th> -->
	<th>Market Return Quantity</th>
	<th>Product Sales Value</th>
	<th>SR To Return Quantity (DSR)</th>
	<th>KD Received Quantity</th>
	<!-- <th>SR Returned Quantity (KD)</th> -->
	<th>Shortage</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if(!empty($num_rows)){
		//echo $num_rows."ABC";
	$c=0;$cc=1;
	$h=0;
	while($fetch = mysql_fetch_array($results)) {

	if($h > 0) {
		continue;
	}
	//echo $h."<br/>";
	$h++;

	if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
	$id= $fetch['id'];
		
	//print_r($productcode);exit;
	
	if($productcode != '' && !empty($productcode)) {
		
		$productcodestr					=	implode(",",$productcode);
		$Total_cancelquantity			=	0;
		$Total_salequantity				=	0;
		$Total_returnquantity			=	0;
		$total_ind_prod_sale_value		=	0;
		foreach($productcode as $P_code) { 
			?>		
			<tr>
			<td>
			<input type="hidden" name="newline" id="newline" value="" />
			<input type="hidden" name="line_number_<?php echo $P_code; ?>" id="line_number_<?php echo $P_code; ?>" value="<?php echo $cc; ?>" /><?php echo $cc; ?></td>
			<td><input type="hidden" name="Product_code_<?php echo $P_code; ?>" id="Product_code_<?php echo $P_code; ?>" value="<?php echo $P_code; ?>" /><?php 
			
			$query_prodname				=	"SELECT Product_description1 FROM product WHERE Product_code = '$P_code'";
			$res_prodname				=	mysql_query($query_prodname) or die(mysql_error());
			$rowcnt_prodname			=	mysql_num_rows($res_prodname);
			if($rowcnt_prodname > 0) {
				$row_prodname			=	mysql_fetch_array($res_prodname);
				echo $prodname				=	$row_prodname[Product_description1];
			} else {
				$query_prodname				=	"SELECT Product_description1 FROM customertype_product WHERE Product_code = '$P_code'";
				$res_prodname				=	mysql_query($query_prodname) or die(mysql_error());
				$rowcnt_prodname			=	mysql_num_rows($res_prodname);
				if($rowcnt_prodname > 0) {
					$row_prodname			=	mysql_fetch_array($res_prodname);
					echo $prodname				=	$row_prodname[Product_description1];
				}
			}
			//exit;
			?>

			<!-- <input type="hidden" name="Product_code" id="Product_code" value="<?php echo implode(", ",$cus_cuspcode); ?>" /> -->
			<?php 
			//$sel_loadedqty			=	"SELECT Loaded_Qty from dailystockloading WHERE DSR_Code = '$DSR_Code' AND vehicle_code = '$vehicle_code' AND (Date >= '$cyclestartdatetime' AND Date <= '$cycleenddatetime') AND Product_code = '$P_code' ORDER BY Date";
			$sel_loadedqty			=	"SELECT Loaded_Qty,Date from dailystockloading WHERE DSR_Code = '$DSR_Code' AND (Date >= '$cyclestartdatetime' AND Date <= '$cycleenddatetime') AND Product_code = '$P_code' ORDER BY Date";
			$res_loadedqty			=	mysql_query($sel_loadedqty) or die(mysql_error());	
			$k						=	0;
			$Total_Loaded_Quantity	=	0;
			$Loaded_Quantity		=	0;
			while($row_loadedqty	=	mysql_fetch_array($res_loadedqty)) {
				if($k == 0) {
					$Loaded_Date				=	$row_loadedqty['Date'];
					$startexactdate				=	explode(' ',$cyclestartdatetime);
					$loadedexactdate			=	explode(' ',$Loaded_Date);
					if($startexactdate[0] == $loadedexactdate[0]) {
						$Loaded_Quantity			=	$row_loadedqty['Loaded_Qty'];
					} elseif($startexactdate[0] != $loadedexactdate[0]) {
						$Total_Loaded_Quantity		+=	$row_loadedqty['Loaded_Qty'];
					} 
				} elseif($k > 0){
					$Total_Loaded_Quantity		+=	$row_loadedqty['Loaded_Qty'];
				}
				$k++;
			}
			
			$query_cancel					=	"SELECT id,Transaction_Number,Product_Line_count,Transaction_type FROM transaction_hdr WHERE Transaction_type = '3' AND (CONCAT(Date, ' ', Time) >= '$cyclestartdatetime' AND CONCAT(Date, ' ', Time) <= '$cycleenddatetime') AND DSR_Code='$DSR_Code'";
			$res_cancel					=	mysql_query($query_cancel)or die(mysql_error());
			$rowcnt_cancel				=	mysql_num_rows($res_cancel);

			if($rowcnt_cancel > 0 ) {
				while($row_cancel			=	mysql_fetch_array($res_cancel)) {
					$Transaction_Number			=	$row_cancel[Transaction_Number];
					$Transaction_type			=	$row_cancel[Transaction_type];
					
					$query_cancellist				=	"SELECT Sold_quantity FROM transaction_line WHERE (Transaction_type = '3' AND DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number' AND Product_code = '$P_code' AND Product_Scheme_Flag = 0)";
					$res_cancellist					=	mysql_query($query_cancellist) or die(mysql_error());
					$rowcnt_cancelquantity			=	mysql_num_rows($res_cancellist);
					while($row_cancellist					=	mysql_fetch_array($res_cancellist)) {
						$Total_cancelquantity			+=	$row_cancellist[Sold_quantity];
					}
				}
			}			
			//exit;
			//FOR INDIVIDUAL SALES VALUE CALCULATION STARTS HERE

			/*$query_trno					=	"SELECT id,Transaction_Number,Product_Line_count,Transaction_type FROM transaction_hdr WHERE Transaction_type in ('2','3','4') AND (CONCAT(Date, ' ', Time) >= '$cyclestartdatetime' AND CONCAT(Date, ' ', Time) <= '$cycleenddatetime') AND DSR_Code='$DSR_Code'";
			$res_trno					=	mysql_query($query_trno)or die(mysql_error());
			$rowcnt_trno				=	mysql_num_rows($res_trno);

			if($rowcnt_trno > 0 ) {
				while($row_trno					=	mysql_fetch_array($res_trno)) {
					$Transaction_Number			=	$row_trno[Transaction_Number];
					$Transaction_type			=	$row_trno[Transaction_type];
					
					// TO find focus items in line items
					$query_salelist				=	"SELECT Sold_quantity FROM transaction_line WHERE (Transaction_type = '2' AND DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number' AND Product_code = '$P_code')";
					//echo $query_salelist;
					//exit;
					$res_salelist					=	mysql_query($query_salelist) or die(mysql_error());
					$rowcnt_salequantity			=	mysql_num_rows($res_salelist);
					while($row_salelist				=	mysql_fetch_array($res_salelist)){
						$Total_salequantity			+=	$row_salelist[Sold_quantity];
					}
				}
			}*/


			$query_transhdr													=   "SELECT id,Transaction_Number,Product_Line_count,Transaction_type,Date,Time,transaction_Reference_Number FROM transaction_hdr WHERE Transaction_type in ('2','3','4') AND (CONCAT(Date, ' ', Time) >= '$cyclestartdatetime' AND CONCAT(Date, ' ', Time) <= '$cycleenddatetime') AND DSR_Code='$DSR_Code'";
			//echo $query_transhdr;
			//exit;
			$res_transhdr												=   mysql_query($query_transhdr);
			$transno_transhdr											=	array();
			while($row_transhdr											=   mysql_fetch_assoc($res_transhdr)) {		
				$Transaction_Number										=	$row_transhdr[Transaction_Number];
				//echo $Transaction_Number."man<br>";
				//echo $row_transhdr[transaction_Reference_Number]."not<br>";
				//if($row_transhdr[transaction_Reference_Number] !='' && $row_transhdr[transaction_Reference_Number] != 0) {
				if($row_transhdr[transaction_Reference_Number] !='' && $row_transhdr[transaction_Reference_Number] != '0') {
					$transaction_Reference_Number_cancel[]				=   $row_transhdr[transaction_Reference_Number];
					$transno_cancel_number[]							=   $row_transhdr[Transaction_Number];
				}
				//echo $row_transhdr[transaction_Reference_Number]."mu<br>";
				$transhdr_result[]										=   $row_transhdr;
				$transno_transhdr[]										=   $row_transhdr[Transaction_Number];
			}
			 
			//pre($transno_transhdr);
			//pre($transaction_Reference_Number_cancel);
			//pre($transno_cancel_number);
			//exit;
			foreach($transaction_Reference_Number_cancel AS $REFVAL){					// THIS IS TO REMOVE THE ACTUAL SALE TRANSACTION 
				if(array_search($REFVAL,$transno_transhdr) !== false) {
					$arraysearchval		=	array_search($REFVAL,$transno_transhdr);
					unset($transno_transhdr[$arraysearchval]);
				}
			}

			//pre($transno_transhdr);
			foreach($transno_cancel_number AS $REFVAL){									// THIS IS TO REMOVE THE CANCELLED TRANSACTION OF ACTUAL SALE TRANSACTION 
				if(array_search($REFVAL,$transno_transhdr) !== false) {
					$arraysearchval		=	array_search($REFVAL,$transno_transhdr);
					//unset($transno_transhdr[$arraysearchval]);
					//array_splice($transno_transhdr, $arraysearchval, 1);
					unset($transno_transhdr[$arraysearchval]);
				}
			}

			//pre($transno_transhdr);
			
			//exit;
			$transno_transhdr		=	array_unique($transno_transhdr);
			$transno_Total			=	implode("','",$transno_transhdr);

			//pre($transno_transhdr);                                                    
			//exit;

			$i=0;
			$k=0;

			$finalSearchInfo								=	$transhdr_result;
			//$finalSearchInfoVal								=	$transhdr_result;
			//pre($finalSearchInfo);
			exit;
			
			//$query_trans									=   "SELECT id,KD_Code,DSR_Code,Product_code,Transaction_Number,Sold_Quantity AS SUM_SQ,Value AS VALUE_NAIRA,Scheme_Code,Product_Scheme_Flag FROM transaction_line WHERE Transaction_Number IN ('".$transno_Total."') AND DSR_Code='$DSR_Code' AND Product_code = '$P_code' ORDER BY id";
			$query_trans									=   "SELECT id,KD_Code,DSR_Code,Product_code,Transaction_Number,Sold_Quantity AS SUM_SQ,Value AS VALUE_NAIRA,Scheme_Code,Product_Scheme_Flag FROM transaction_line WHERE Transaction_Number IN ('".$transno_Total."') AND DSR_Code='$DSR_Code' ORDER BY id";
			//echo $query_trans;
			//exit;
			$res_trans										=   mysql_query($query_trans);

			while($row_trans								=   mysql_fetch_assoc($res_trans)) {
				//$transInfo[$row_trans["Transaction_Number"]]=	$row_trans;
				$transInfo[$row_trans["Transaction_Number"]."-".$row_trans["id"]]	=	$row_trans;
			}
			
			//pre($transInfo);
			//exit;

			$i=0;
			$k=0;
			//pre($finalSearchInfo);
			//exit;

			foreach($transInfo as $val_transnokey=>$val_transno) {

				//echo $val_transnokey."<br>";

				$explode_transno		=	explode("-",$val_transnokey);

				$searchValue			=	myfunction_tosearch_arrayvalue($finalSearchInfo, $explode_transno[0],'Transaction_Number','Transaction_Number');

				//echo $searchValue."<br>";

				//echo $explode_transno[0]."<br>";

			//foreach($finalSearchInfo as $val_transno){
				
				//echo $transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"] . "-". $val_transno["Transaction_Number"]."<br>";
				if($searchValue) {

					//if($val_transno["Product_Scheme_Flag"] == 0 && $val_transno["Product_code"] != '0' && $val_transno["Product_code"] != '') {
						$finaltranslineInfo[$i]["DSRCode"]						=   $val_transno["DSR_Code"];
						$finaltranslineInfo[$i]["Scheme_Code"]					=   $val_transno["Scheme_Code"];
						$finaltranslineInfo[$i]["TRANS_NO"]						=   $val_transno["Transaction_Number"];
						$finaltranslineInfo[$i]["Product_code"]					=   $val_transno["Product_code"];
						$finaltranslineInfo[$i]["KD_Code"]						=   $val_transno["KD_Code"];
						$finaltranslineInfo[$i]["SUM_SQ"]						=   $val_transno["SUM_SQ"];
						$finaltranslineInfo[$i]["VALUE_NAIRA"]					=   $val_transno["VALUE_NAIRA"];
						$finaltranslineInfo[$i]["trans_id"]						=   myfunction_tosearch_arrayvalue($finalSearchInfo, $explode_transno[0],'Transaction_Number','id');
						$finaltranslineInfo[$i]["DateVal"]						=   myfunction_tosearch_arrayvalue($finalSearchInfo, $explode_transno[0],'Transaction_Number','Date');
						$i++;
					//}
				}
				$k++;
			}

			$finalSearchInfo          =   $finaltranslineInfo;
			//pre($finalSearchInfo);
			//exit;
			
			$i=0;
			foreach($finalSearchInfo as $val_kd) {
				
				if($val_kd["Scheme_Code"] !='' && $val_kd["Product_code"] !='' ) {
					$actual_schemeline[$val_kd["Scheme_Code"].$val_kd["TRANS_NO"].$i]		=	$val_kd[VALUE_NAIRA];
				} else if ($val_kd["Scheme_Code"] == '' && $val_kd["Product_code"] !='' && $val_kd["Product_code"] != '0') {
					$finalSearchInfo[$i]["VALUE_NAIRA"]		=	$val_kd[VALUE_NAIRA];
				}
				if($val_kd["Scheme_Code"] !='' && $val_kd["Product_code"] == '0') {
					for($y=0;$y<1000;$y++){
						if($actual_schemeline[$val_kd["Scheme_Code"].$val_kd["TRANS_NO"].$y] != ''){
							$finalSearchInfo[$y]["VALUE_NAIRA"]		=	($actual_schemeline[$val_kd["Scheme_Code"].$val_kd["TRANS_NO"].$y])+($val_kd[VALUE_NAIRA]);
							$actual_schemeline[$val_kd["Scheme_Code"].$val_kd["TRANS_NO"].$y]		=	'';
						}
						//finalSearchInfo$searchvalueinfo[$val_kd["Scheme_Code"].$val_kd["TRANS_NO"]]
					}
					unset($finalSearchInfo[$i]["DSRCode"]);
					unset($finalSearchInfo[$i]["Scheme_Code"]);
					unset($finalSearchInfo[$i]["TRANS_NO"]);
					unset($finalSearchInfo[$i]["Product_code"]);
					unset($finalSearchInfo[$i]["KD_Code"]);
					unset($finalSearchInfo[$i]["SUM_SQ"]);
					unset($finalSearchInfo[$i]["VALUE_NAIRA"]);
					unset($finalSearchInfo[$i]["trans_id"]);
					unset($finalSearchInfo[$i]["DateVal"]);
				}
				$i++;
			}

			//pre($finalSearchInfo);
			//exit;

			$i=0;
			foreach($finalSearchInfo as $val_check) {

				if($val_check["Product_code"] == $P_code) {
					if(!empty($val_check)) {
						$finalchecklineInfo[$i]["DSRCode"]						=   $val_check["DSRCode"];
						$finalchecklineInfo[$i]["Scheme_Code"]					=   $val_check["Scheme_Code"];
						$finalchecklineInfo[$i]["TRANS_NO"]						=   $val_check["Transaction_Number"];
						$finalchecklineInfo[$i]["Product_code"]					=   $val_check["Product_code"];
						$finalchecklineInfo[$i]["KD_Code"]						=   $val_check["KD_Code"];
						$finalchecklineInfo[$i]["SUM_SQ"]						=   $val_check["SUM_SQ"];
						$finalchecklineInfo[$i]["VALUE_NAIRA"]					=   $val_check["VALUE_NAIRA"];
						$finalchecklineInfo[$i]["trans_id"]						=   $val_check['trans_id'];
						$finalchecklineInfo[$i]["DateVal"]						=   $val_check['DateVal'];
						$i++;
					}
				}
			}

			$finalSearchInfo			=   $finalchecklineInfo;
			//pre($finalSearchInfo);

			$total_ind_prod_sale_value	=	multi_array_sum($finalSearchInfo,'VALUE_NAIRA');
			
			$i=0;
			foreach($finalSearchInfo as $val_check) {
				unset($finalSearchInfo[$i]["VALUE_NAIRA"]);
				$i++;
			}
			
			$finalSearchInfo			=	'';
			$transInfo					=	'';
			$actual_schemeline			=	'';
			$finalchecklineInfo			=	'';
			//exit;

			//FOR INDIVIDUAL SALES VALUE CALCULATION ENDS HERE 


			/*$query_trno					=	"SELECT id,Transaction_Number,Product_Line_count,Transaction_type FROM transaction_hdr WHERE Transaction_type = '2' AND (CONCAT(Date, ' ', Time) >= '$cyclestartdatetime' AND CONCAT(Date, ' ', Time) <= '$cycleenddatetime') AND DSR_Code='$DSR_Code'";
			$res_trno					=	mysql_query($query_trno)or die(mysql_error());
			$rowcnt_trno				=	mysql_num_rows($res_trno);

			if($rowcnt_trno > 0 ) {
				while($row_trno					=	mysql_fetch_array($res_trno)) {
					$Transaction_Number			=	$row_trno[Transaction_Number];
					$Transaction_type			=	$row_trno[Transaction_type];
					
					// TO find focus items in line items
					$query_salelist				=	"SELECT Sold_quantity FROM transaction_line WHERE (Transaction_type = '2' AND DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number' AND Product_code = '$P_code')";
					//echo $query_salelist;
					//exit;
					$res_salelist					=	mysql_query($query_salelist) or die(mysql_error());
					$rowcnt_salequantity			=	mysql_num_rows($res_salelist);
					while($row_salelist				=	mysql_fetch_array($res_salelist)){
						$Total_salequantity			+=	$row_salelist[Sold_quantity];
					}
				}
			}*/


			foreach($transno_transhdr AS $only_sales_trans_no) {

				$Transaction_Number			=	$only_sales_trans_no;
				$Transaction_type		=	$row_trno[Transaction_type];
				
				// TO find focus items in line items
				$query_salelist				=	"SELECT Sold_quantity FROM transaction_line WHERE (Transaction_type = '2' AND DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number' AND Product_code = '$P_code')";
				//echo $query_salelist;
				//exit;
				$res_salelist					=	mysql_query($query_salelist) or die(mysql_error());
				$rowcnt_salequantity			=	mysql_num_rows($res_salelist);
				while($row_salelist				=	mysql_fetch_array($res_salelist)){
					$Total_salequantity			+=	$row_salelist[Sold_quantity];
				}
			}

			$query_return_trno					=	"SELECT id,Transaction_Number,Product_Line_count,Transaction_type FROM transaction_hdr WHERE Transaction_type = '4' AND (CONCAT(Date, ' ', Time) >= '$cyclestartdatetime' AND CONCAT(Date, ' ', Time) <= '$cycleenddatetime') AND DSR_Code='$DSR_Code'";
			$res_return_trno					=	mysql_query($query_return_trno)or die(mysql_error());
			$rowcnt_return_trno					=	mysql_num_rows($res_return_trno);

			if($rowcnt_return_trno > 0 ) {
				while($row_return_trno			=	mysql_fetch_array($res_return_trno)) {
					$Transaction_Number			=	$row_return_trno[Transaction_Number];
					$Transaction_type			=	$row_return_trno[Transaction_type];
					
					// TO find focus items in line items
					$query_returnlist			=	"SELECT Reurn_quantity FROM transaction_return_line WHERE (DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number' AND Product_code = '$P_code')";
					$res_returnlist				=	mysql_query($query_returnlist)or die(mysql_error());
					$rowcnt_returnlist			=	mysql_num_rows($res_returnlist);
					while($row_returnlist				=	mysql_fetch_array($res_returnlist)) {
						$Total_returnquantity			+=	$row_returnlist[Reurn_quantity];
					}
					//exit;
				}
			}

			/*$sel_saleqty			=	"SELECT salequantity from devicetransactions WHERE dsr_id = '$DSR_Code' AND device_id = '$device_code' AND (Date >= '$cycle_start_date' AND Date <= '$cycle_end_date') AND transactiontype = 'sales' AND Product_code = '$P_code' ORDER BY Date";
			$res_saleqty			=	mysql_query($sel_saleqty) or die(mysql_error());	
			
			$Total_salequantity		=	0;
			while($row_saleqty			=	mysql_fetch_array($res_saleqty)) {
				$Total_salequantity	+=	$row_saleqty['salequantity'];
			}

			$sel_retqty				=	"SELECT returnquantity from devicetransactions WHERE dsr_id = '$DSR_Code' AND device_id = '$device_code' AND (Date >= '$cycle_start_date' AND Date <= '$cycle_end_date') AND transactiontype = 'returned' AND Product_code = '$P_code' ORDER BY Date";
			$res_retqty				=	mysql_query($sel_retqty) or die(mysql_error());	
			
			$Total_returnquantity	=	0;
			while($row_retqty		=	mysql_fetch_array($res_retqty)) {
				$Total_returnquantity	+=	$row_retqty['returnquantity'];
			}*/

			//$DSR_return_quantity				=	$Loaded_Quantity+$Total_Loaded_Quantity-$Total_salequantity+$Total_cancelquantity+$Total_returnquantity;
			
			/*echo $Loaded_Quantity."<br>";
			echo $Total_salequantity."<br>";
			echo $Total_cancelquantity."<br>";
			echo $Total_returnquantity."<br>";*/

			if($Total_Loaded_Quantity == 0){	
				$DSR_return_quantity				=	$Loaded_Quantity-$Total_salequantity+$Total_cancelquantity+$Total_returnquantity;
				$Total_Loaded_Quantity				=	$Loaded_Quantity;
			} else {
				$Total_Loaded_Quantity				=	$Total_Loaded_Quantity+$Loaded_Quantity;
				$DSR_return_quantity				=	$Total_Loaded_Quantity-$Total_salequantity+$Total_cancelquantity+$Total_returnquantity;				
			}
			
			$prod_name								=	getdbval($P_code,'Product_description1','Product_code','product');
			
			if($prod_name == '') {
				$prod_name							=	getdbval($P_code,'Product_description1','Product_code','customertype_product');
			}
			?></td>
			<td align="right"><input type="hidden" name="cycle_start_loaded_qty_<?php echo $P_code; ?>" id="cycle_start_loaded_qty_<?php echo $P_code; ?>" value="<?php echo $Loaded_Quantity; ?>" /><?php echo number_format($Loaded_Quantity); ?></td>
			<td align="right"><input type="hidden" name="daily_total_loaded_qty_<?php echo $P_code; ?>" id="daily_total_loaded_qty_<?php echo $P_code; ?>" value="<?php echo $Total_Loaded_Quantity; ?>" /><?php echo number_format($Total_Loaded_Quantity);?></td>
			<td align="right"><input type="hidden" name="total_sold_qty_<?php echo $P_code; ?>" id="total_sold_qty_<?php echo $P_code; ?>" value="<?php echo $Total_salequantity; ?>" /><?php echo number_format($Total_salequantity);?></td>
			<td align="right"><input type="hidden" name="total_cancel_qty_<?php echo $P_code; ?>" id="total_cancel_qty_<?php echo $P_code; ?>" value="<?php echo $Total_cancelquantity; ?>" /><?php echo number_format($Total_cancelquantity); ?></td>
			<td align="right"><input type="hidden" name="total_sales_returned_qty_<?php echo $P_code; ?>" id="total_sales_returned_qty_<?php echo $P_code; ?>" value="<?php echo $Total_returnquantity; ?>" /><?php echo number_format($Total_returnquantity); ?></td>
			<td align="right"><input type="hidden" name="total_ind_prod_sale_value_<?php echo $P_code; ?>" autocomplete='off' id="total_ind_prod_sale_value_<?php echo $P_code; ?>" value="<?php echo $total_ind_prod_sale_value; ?>" /><?php echo number_format($total_ind_prod_sale_value,2); ?></td>
			<td align="right"><input type="hidden" name="DSR_returned_qty_<?php echo $P_code; ?>" id="DSR_returned_qty_<?php echo $P_code; ?>" value="<?php echo $DSR_return_quantity; ?>" /><?php echo number_format($DSR_return_quantity); ?></td>
			<td align="right"><input type="text" name="KD_returned_qty_<?php echo $P_code; ?>" autocomplete='off' id="KD_returned_qty_<?php echo $P_code; ?>" style="text-align:right;" value="<?php echo number_format($DSR_return_quantity); ?>" onblur="qtyformatreturntoid(this.value,'KD_returned_qty_<?php echo $P_code; ?>');updateShortQty('<?php echo $P_code; ?>');"/></td>			
			<td align="right"><input type="hidden" readonly name="short_reason_<?php echo $P_code; ?>" id="short_reason_<?php echo $P_code; ?>" value="" /><input type="hidden" readonly name="quantity_shortage_<?php echo $P_code; ?>" id="quantity_shortage_<?php echo $P_code; ?>" value="" /><input type="hidden" readonly name="prod_name_<?php echo $P_code; ?>" id="prod_name_<?php echo $P_code; ?>" value="<?php echo $prod_name; ?>" /> <span id="qtyshotspan_<?php echo $P_code; ?>"></span></td>
			</tr>
			<?php $c++; $cc++; 
			$Total_salequantity				=	0;
			$Total_returnquantity			=	0;
			$DSR_return_quantity			=	0;
			$Total_Loaded_Quantity			=	0;
			$Total_cancelquantity			=	0;
			$Loaded_Quantity				=	0;
			$total_ind_prod_sale_value		=	0;
			$prod_name						=	'';
		}
		//echo "imdi";
	} //if statement to check for product code 
	else {	?>
		<tr>
			<td align='center' colspan='13'><b>No records found</b></td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
			<td style="display:none;" >Add Line2</td>
			<td style="display:none;" >LGA</td>
			<td style="display:none;" >City</td>
			<td style="display:none;" >Contact Person</td>
			<td style="display:none;" >Contact Number</td>
			<td style="display:none;" >City</td>
			
		</tr>						
		</tbody>
		</table>
		</div>
		<?php exit(0);
	}

	}		 
	}else{  //echo $num_rows."DEF";?>
		<tr>
			<td align='center' colspan='13'><b>No records found</b></td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
			<td style="display:none;" >Add Line2</td>
			<td style="display:none;" >LGA</td>
			<td style="display:none;" >City</td>
			<td style="display:none;" >Contact Person</td>
			<td style="display:none;" >Contact Number</td>
			<td style="display:none;" >City</td>			
		</tr>						
		</tbody>
		</table>
		</div> <?php exit(0);
	}  ?>
	</tbody>
	</table>
	</div>
<?php
	$lastweekshort				=	0;
	$sel_lastweekshort			=	"SELECT amount_shortage from cycle_end_reconciliation WHERE DSR_Code = '$DSR_Code' ORDER BY id DESC LIMIT 1";
	$res_lastweekshort			=	mysql_query($sel_lastweekshort) or die(mysql_error());	
	$rowcnt_lastweekshort		=	mysql_num_rows($res_lastweekshort);
	$row_lastweekshort			=	mysql_fetch_array($res_lastweekshort);
	if($rowcnt_lastweekshort > 0 ) {
		$lastweekshort				=	$row_lastweekshort['amount_shortage'];
	}

	$sel_amtdep					=	"SELECT Amount_Deposited from dsr_collection WHERE DSR_Code = '$DSR_Code' AND (Date >= '$cycle_start_date' AND Date <= '$cycle_end_date') ORDER BY Date";
	$res_amtdep					=	mysql_query($sel_amtdep) or die(mysql_error());	

	while($row_amtdep				=	mysql_fetch_array($res_amtdep)) {
		$Total_Amount_Deposited		+=	$row_amtdep['Amount_Deposited'];
	}	
	//$sel_salevalue			=	"SELECT total_sale_value from sale_and_collection WHERE DSR_Code = '$DSR_Code' AND device_code = '$device_code' AND (Date >= '$cycle_start_date' AND Date <= '$cycle_end_date') ORDER BY Date";
	$sel_salevalue			=	"SELECT total_sale_value from sale_and_collection WHERE DSR_Code = '$DSR_Code' AND (Date >= '$cycle_start_date' AND Date <= '$cycle_end_date') ORDER BY Date";
	$res_salevalue			=	mysql_query($sel_salevalue) or die(mysql_error());	

	while($row_salevalue	=	mysql_fetch_array($res_salevalue)) {
		$Total_Sale_Value	+=	$row_salevalue['total_sale_value'];
	}
	$Shortage_Amount		=	$lastweekshort+$Total_Sale_Value - $Total_Amount_Deposited;	
	echo "~".number_format($Total_Sale_Value,2)."~".number_format($Total_Amount_Deposited,2)."~".number_format($Shortage_Amount,2)."~".$productcodestr."~".$num_rows."~".number_format($lastweekshort,2); exit(0); ?>