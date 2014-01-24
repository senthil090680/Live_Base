<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
$params=$transno."&".$transtype."&".$fromdate."&".$todate."&".$kd_id."&".$dsr_id."&".$sortorder."&".$ordercol;
if(isset($_GET[transno]) && $_GET[transno] !='') {
	if($transtype == 2 || $transtype == 3) {
		$nextrecval		=	"WHERE Transaction_Number = '$transno' AND Product_Scheme_Flag = 0";
	} else {
		$nextrecval		=	"WHERE Transaction_Number = '$transno'";
	}
} else {
	echo "Invalid Query"; exit(0);
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='') {	
	if($transtype == 2 || $transtype == 3) { 
		//$qry="SELECT *,trli.id AS TLID,trli.Product_code AS PRODCODE FROM `transaction_line` AS trli LEFT JOIN product AS prod ON trli.Product_code = prod.Product_code $where";
		$qry="SELECT * FROM `transaction_line` $where";
	} else if($transtype == 4) {
		//$qry="SELECT *,trli.id AS TLID,trli.Product_code AS PRODCODE FROM `transaction_return_line` AS trli LEFT JOIN product AS prod ON trli.Product_code = prod.Product_code $where";
		$qry="SELECT * FROM `transaction_return_line` $where";
	}
}
else { 
	echo "Invalid Query";
	exit;
}
$results_dsr=mysql_query($qry) or die(mysql_error());
$num_rows= mysql_num_rows($results_dsr);

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 5;   // Records Per Page

$Page = $strPage;
if(!$strPage)
{
$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($num_rows<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($num_rows % $Per_Page)==0)
{
$Num_Pages =($num_rows/$Per_Page) ;
}
else
{
$Num_Pages =($num_rows/$Per_Page)+1;
$Num_Pages = (int)$Num_Pages;
}
if($sortorder == "")
{
	//$orderby	=	"ORDER BY trli.id ASC";
	$orderby	=	"ORDER BY id ASC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.="  $orderby LIMIT $Page_Start , $Per_Page";
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>
<div class="conitems">
	<table width="100%">
	<thead>
	<tr>
		<th align="center" colspan="11"><h2>Product Line Items</h2></th>
	</tr>
	<tr>
		<!-- <th align="center" <?php if($transtype == 2 || $transtype == 3) { ?> style="width:2%" <?php } else { ?> style="width:2%" <?php } ?> >Ln. Num.</th> -->
		<?php //echo $sortorderby;
		if($sortorder == 'ASC') {
			$sortorderby = 'DESC';
		} elseif($sortorder == 'DESC') {
			$sortorderby = 'ASC';
		} else {
			$sortorderby = 'DESC';
		}
		$paramsval	=	$transno."&".$transtype."&".$fromdate."&".$todate."&".$kd_id."&".$dsr_id."&".$sortorderby."&Product_code"; ?>
		<!-- <th align="center" <?php if($transtype == 2 || $transtype == 3) { ?> style="width:10%" <?php } else { ?> style="width:17%" <?php } ?> onClick="pag_devlineitemajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');" ><span style="cursor:hand;cursor:pointer;">Product Code<img src="../images/sort.png" width="13" height="13" /></span></th> -->
		<th align="center" <?php if($transtype == 2 || $transtype == 3) { ?> style="width:10%" <?php } else { ?> style="width:17%" <?php } ?> >Product Code</th>
		
		<th align="center" <?php if($transtype == 2 || $transtype == 3) { ?> style="width:62%" <?php } else { ?> style="width:73%" <?php } ?> > Product Name </th>
		<th align="center" <?php if($transtype == 2 || $transtype == 3) { ?> style="width:5%" <?php } else { ?> style="width:5%" <?php } ?> >UOM</th>

		<?php if($transtype == 2 || $transtype == 3) { ?>
		<th align="center" style="width:3%">Focus</th>
		<th align="center" style="width:3%">Scheme</th>
		<th align="center" style="width:3%">Customer Stock</th>
		<th align="center" style="width:3%">Product Type</th>
		<th style="width:5%"><table border="0" width="100%">
				<tr>
					<td align="center" colspan="3">Quantity</td>
				</tr>
				<tr>
					<td align="center">Order Quantity</td><td align="center">Sale Quantity</td><td align="center">Scheme Quantity</td>
				</tr>
				</table>
		</th>
		<th align="center" style="width:3%">Price</th>
		<th align="center" style="width:3%">Value</th>
		<?php } ?>

		<?php if($transtype == 4) { ?>
		<th align="center" style="width:5%">Returned Quantity</th>
		<?php } ?>
	</tr>
	</thead>
	<tbody>
	<?php
	if(!empty($num_rows)){
	$c=0;$cc=1;$totalval=0;
	while($fetch = mysql_fetch_array($results_dsr)) {
		$fetcharr[]		=	$fetch;
		$transInfo[$fetch["Transaction_Number"]."-".$fetch["id"]]	=	$fetch;
		if($fetch["Product_code"]	!= '' && $fetch["Product_code"]	!= '0') {
			$prodcode_trans[]							=	$fetch["Product_code"];
		}
	}
	//pre($prodcode_trans);

	$prodcode_trans		=	array_unique($prodcode_trans);
	$prodcode_Total		=	implode("','",$prodcode_trans);

	$i=0;
	$k=0;
	//pre($finalSearchInfo);
	//exit;
	
	foreach ($transInfo as $val_transnokey=>$val_transno) {

		//echo $val_transnokey."<br>";

		$explode_transno		=	explode("-",$val_transnokey);

		//$searchValue			=	myfunction_tosearch_arrayvalue($finalSearchInfo, $explode_transno[0],'Transaction_Number','Transaction_Number');

		//echo $searchValue."<br>";

		//echo $explode_transno[0]."<br>";

		//foreach($finalSearchInfo as $val_transno){
		
		//echo $transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"] . "-". $val_transno["Transaction_Number"]."<br>";
		//if($searchValue) {
			$finaltranslineInfo[$i]["DSR_Code"]						=   $val_transno["DSR_Code"];
			$finaltranslineInfo[$i]["Scheme_Code"]					=   $val_transno["Scheme_Code"];
			$finaltranslineInfo[$i]["Transaction_Number"]			=   $val_transno["Transaction_Number"];
			$finaltranslineInfo[$i]["Transaction_Line_Number"]		=   $val_transno["Transaction_Line_Number"];
			$finaltranslineInfo[$i]["Focus_Flag"]					=   $val_transno["Focus_Flag"];
			$finaltranslineInfo[$i]["POSM_Flag"]					=   $val_transno["POSM_Flag"];
			$finaltranslineInfo[$i]["Scheme_Flag"]					=   $val_transno["Scheme_Flag"];

			$finaltranslineInfo[$i]["Product_code"]					=   $val_transno["Product_code"];
			$finaltranslineInfo[$i]["UOM"]							=   $val_transno["UOM"];
			$finaltranslineInfo[$i]["Customer_Stock_quantity"]		=   $val_transno["Customer_Stock_quantity"];
			$finaltranslineInfo[$i]["Customer_Stock_Check"]			=   $val_transno["Customer_Stock_Check"];
			$finaltranslineInfo[$i]["Order_quantity"]				=   $val_transno["Order_quantity"];

			$finaltranslineInfo[$i]["KD_Code"]						=   $val_transno["KD_Code"];
			$finaltranslineInfo[$i]["Sold_quantity"]				=   $val_transno["Sold_quantity"];
			$finaltranslineInfo[$i]["Price"]						=   $val_transno["Price"];

			$finaltranslineInfo[$i]["VALUE_NAIRA"]					=   $val_transno["Value"];
			$finaltranslineInfo[$i]["id"]							=   $val_transno["id"];
			//$finaltranslineInfo[$i]["trans_id"]					=   myfunction_tosearch_arrayvalue($finalSearchInfo, $explode_transno[0],'Transaction_Number','id');
			$i++;
		//}
		$k++;
	}

	$finalSearchInfo          =   $finaltranslineInfo;
	//pre($finalSearchInfo);
	//exit;

	$i=0;
	foreach($finalSearchInfo as $val_kd)	{
		if($val_kd["Scheme_Code"] !='' && $val_kd["Product_code"] !='') {
			$actual_schemeline[$val_kd["Scheme_Code"].$val_kd["Transaction_Number"].$i]		=	$val_kd[VALUE_NAIRA];
		} /* else if ($val_kd["Scheme_Code"] == '' && $val_kd["Product_code"] !='' && $val_kd["Product_code"] != '0') {
			$finalSearchInfo[$i]["VALUE_NAIRA"]		=	$val_kd[VALUE_NAIRA];
		} */
		if($val_kd["Scheme_Code"] !='' && $val_kd["Product_code"] == '0') {
			for($y=0;$y<1000;$y++) {
				if($actual_schemeline[$val_kd["Scheme_Code"].$val_kd["Transaction_Number"].$y] != ''){
					$finalSearchInfo[$y]["VALUE_NAIRA"]		=	($actual_schemeline[$val_kd["Scheme_Code"].$val_kd["Transaction_Number"].$y])+($val_kd[VALUE_NAIRA]);
					$actual_schemeline[$val_kd["Scheme_Code"].$val_kd["Transaction_Number"].$y]		=	'';
				}
			//finalSearchInfo$searchvalueinfo[$val_kd["Scheme_Code"].$val_kd["TRANS_NO"]]
			}

			unset($finalSearchInfo[$i]["DSR_Code"]);
			unset($finalSearchInfo[$i]["Scheme_Code"]);
			unset($finalSearchInfo[$i]["Transaction_Number"]);
			unset($finalSearchInfo[$i]["Transaction_Line_Number"]);
			unset($finalSearchInfo[$i]["Focus_Flag"]);
			unset($finalSearchInfo[$i]["POSM_Flag"]);
			unset($finalSearchInfo[$i]["Scheme_Flag"]);

			unset($finalSearchInfo[$i]["Product_code"]);
			unset($finalSearchInfo[$i]["UOM"]);
			unset($finalSearchInfo[$i]["Customer_Stock_quantity"]);
			unset($finalSearchInfo[$i]["Customer_Stock_Check"]);
			unset($finalSearchInfo[$i]["Order_quantity"]);

			unset($finalSearchInfo[$i]["KD_Code"]);
			unset($finalSearchInfo[$i]["Sold_quantity"]);
			unset($finalSearchInfo[$i]["Price"]);

			unset($finalSearchInfo[$i]["VALUE_NAIRA"]);
			unset($finalSearchInfo[$i]["id"]);
		}
		$i++;
	}

	//pre($finalSearchInfo);
	//exit;

	$i=0;
	foreach($finalSearchInfo as $val_check) {
		if(!empty($val_check)) {
			$finalchecklineInfo[$i]["DSR_Code"]						=   $val_check["DSR_Code"];
			$finalchecklineInfo[$i]["Scheme_Code"]					=   $val_check["Scheme_Code"];
			$finalchecklineInfo[$i]["Transaction_Number"]			=   $val_check["Transaction_Number"];
			$finalchecklineInfo[$i]["Transaction_Line_Number"]		=   $val_check["Transaction_Line_Number"];
			$finalchecklineInfo[$i]["Focus_Flag"]					=   $val_check["Focus_Flag"];
			$finalchecklineInfo[$i]["POSM_Flag"]					=   $val_check["POSM_Flag"];
			$finalchecklineInfo[$i]["Scheme_Flag"]					=   $val_check["Scheme_Flag"];

			$finalchecklineInfo[$i]["Product_code"]					=   $val_check["Product_code"];
			$finalchecklineInfo[$i]["UOM"]							=   $val_check["UOM"];
			$finalchecklineInfo[$i]["Customer_Stock_quantity"]		=   $val_check["Customer_Stock_quantity"];
			$finalchecklineInfo[$i]["Customer_Stock_Check"]			=   $val_check["Customer_Stock_Check"];
			$finalchecklineInfo[$i]["Order_quantity"]				=   $val_check["Order_quantity"];

			$finalchecklineInfo[$i]["KD_Code"]						=   $val_check["KD_Code"];
			$finalchecklineInfo[$i]["Sold_quantity"]				=   $val_check["Sold_quantity"];
			$finalchecklineInfo[$i]["Price"]						=   $val_check["Price"];

			$finalchecklineInfo[$i]["VALUE_NAIRA"]					=   $val_check["VALUE_NAIRA"];
			$finalchecklineInfo[$i]["TLID"]							=   $val_check["id"];
			$i++;
		}		
	}

	$finalSearchInfo          =   $finalchecklineInfo;
	//pre($finalSearchInfo);
	//exit;



	$query_prod										=   "SELECT id,Product_description1,Product_code FROM product WHERE Product_code IN ('".$prodcode_Total."')";
	$res_prod										=   mysql_query($query_prod);
	while($row_prod									=   mysql_fetch_assoc($res_prod)) {
		$prodInfo[$row_prod["Product_code"]]		=	$row_prod;
	}
	

	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_prod){
		//$transInfo[$val_transno["Transaction_Number"]]["Transaction_Number"];
		//echo $prodInfo[$val_prod["Product_code"]]["Product_code"] ."==". $val_prod["Product_code"]."<br>";
		if($prodInfo[$val_prod["Product_code"]]["Product_code"] == $val_prod["Product_code"]) {
			$finalprodInfo[$i]["DSR_Code"]							=   $val_prod["DSR_Code"];
			$finalprodInfo[$i]["Scheme_Code"]						=   $val_prod["Scheme_Code"];
			$finalprodInfo[$i]["Transaction_Number"]				=   $val_prod["Transaction_Number"];
			$finalprodInfo[$i]["Transaction_Line_Number"]			=   $val_prod["Transaction_Line_Number"];
			$finalprodInfo[$i]["Focus_Flag"]						=   $val_prod["Focus_Flag"];
			$finalprodInfo[$i]["POSM_Flag"]							=   $val_prod["POSM_Flag"];
			$finalprodInfo[$i]["Scheme_Flag"]						=   $val_prod["Scheme_Flag"];

			$finalprodInfo[$i]["Product_code"]						=   $val_prod["Product_code"];
			$finalprodInfo[$i]["Product_Name"]						=   $prodInfo[$val_prod["Product_code"]]["Product_description1"];
			$finalprodInfo[$i]["UOM"]								=   $val_prod["UOM"];
			$finalprodInfo[$i]["Customer_Stock_quantity"]			=   $val_prod["Customer_Stock_quantity"];
			$finalprodInfo[$i]["Customer_Stock_Check"]				=   $val_prod["Customer_Stock_Check"];
			$finalprodInfo[$i]["Order_quantity"]					=   $val_prod["Order_quantity"];

			$finalprodInfo[$i]["KD_Code"]							=   $val_prod["KD_Code"];
			$finalprodInfo[$i]["Sold_quantity"]						=   $val_prod["Sold_quantity"];
			$finalprodInfo[$i]["Price"]								=   $val_prod["Price"];

			$finalprodInfo[$i]["VALUE_NAIRA"]						=   $val_prod["VALUE_NAIRA"];
			$finalprodInfo[$i]["TLID"]								=   $val_prod["TLID"];
		} else {
			$finalprodInfo[$i]["DSR_Code"]							=   $val_prod["DSR_Code"];
			$finalprodInfo[$i]["Scheme_Code"]						=   $val_prod["Scheme_Code"];
			$finalprodInfo[$i]["Transaction_Number"]				=   $val_prod["Transaction_Number"];
			$finalprodInfo[$i]["Transaction_Line_Number"]			=   $val_prod["Transaction_Line_Number"];
			$finalprodInfo[$i]["Focus_Flag"]						=   $val_prod["Focus_Flag"];
			$finalprodInfo[$i]["POSM_Flag"]							=   $val_prod["POSM_Flag"];
			$finalprodInfo[$i]["Scheme_Flag"]						=   $val_prod["Scheme_Flag"];

			$finalprodInfo[$i]["Product_code"]						=   $val_prod["Product_code"];
			$finalprodInfo[$i]["Product_Name"]						=   $val_prod["Product_Name"];
			$finalprodInfo[$i]["UOM"]								=   $val_prod["UOM"];
			$finalprodInfo[$i]["Customer_Stock_quantity"]			=   $val_prod["Customer_Stock_quantity"];
			$finalprodInfo[$i]["Customer_Stock_Check"]				=   $val_prod["Customer_Stock_Check"];
			$finalprodInfo[$i]["Order_quantity"]					=   $val_prod["Order_quantity"];

			$finalprodInfo[$i]["KD_Code"]							=   $val_prod["KD_Code"];
			$finalprodInfo[$i]["Sold_quantity"]						=   $val_prod["Sold_quantity"];
			$finalprodInfo[$i]["Price"]								=   $val_prod["Price"];

			$finalprodInfo[$i]["VALUE_NAIRA"]						=   $val_prod["VALUE_NAIRA"];
			$finalprodInfo[$i]["TLID"]								=   $val_prod["TLID"];
		}
		$i++;
		$k++;
	}

	$finalSearchInfo          =   $finalprodInfo;
	//pre($finalSearchInfo);
	//exit;


	$query_prod										=   "SELECT Product_id,Product_description1,Product_code FROM customertype_product WHERE Product_code IN ('".$prodcode_Total."')";
	$res_prod										=   mysql_query($query_prod);
	while($row_prod									=   mysql_fetch_assoc($res_prod)) {
		$prodInfoPOSM[$row_prod["Product_code"]]		=	$row_prod;
	}
	
	//pre($prodInfoPOSM);

	$i=0;
	$k=0;
	foreach($finalSearchInfo as $val_prodPOSM){
		//$transInfo[$val_prodPOSM["Transaction_Number"]]["Transaction_Number"];
		if($prodInfoPOSM[$val_prodPOSM["Product_code"]]["Product_code"] == $val_prodPOSM["Product_code"]) {
			$finalprodInfoPOSM[$i]["DSR_Code"]							=   $val_prodPOSM["DSR_Code"];
			$finalprodInfoPOSM[$i]["Scheme_Code"]						=   $val_prodPOSM["Scheme_Code"];
			$finalprodInfoPOSM[$i]["Transaction_Number"]				=   $val_prodPOSM["Transaction_Number"];
			$finalprodInfoPOSM[$i]["Transaction_Line_Number"]			=   $val_prodPOSM["Transaction_Line_Number"];
			$finalprodInfoPOSM[$i]["Focus_Flag"]						=   $val_prodPOSM["Focus_Flag"];
			$finalprodInfoPOSM[$i]["POSM_Flag"]							=   $val_prodPOSM["POSM_Flag"];
			$finalprodInfoPOSM[$i]["Scheme_Flag"]						=   $val_prodPOSM["Scheme_Flag"];

			$finalprodInfoPOSM[$i]["Product_code"]						=   $val_prodPOSM["Product_code"];
			$finalprodInfoPOSM[$i]["Product_Name"]						=   $prodInfoPOSM[$val_prodPOSM["Product_code"]]["Product_description1"];
			$finalprodInfoPOSM[$i]["UOM"]								=   $val_prodPOSM["UOM"];
			$finalprodInfoPOSM[$i]["Customer_Stock_quantity"]			=   $val_prodPOSM["Customer_Stock_quantity"];
			$finalprodInfoPOSM[$i]["Customer_Stock_Check"]				=   $val_prodPOSM["Customer_Stock_Check"];
			$finalprodInfoPOSM[$i]["Order_quantity"]					=   $val_prodPOSM["Order_quantity"];

			$finalprodInfoPOSM[$i]["KD_Code"]							=   $val_prodPOSM["KD_Code"];
			$finalprodInfoPOSM[$i]["Sold_quantity"]						=   $val_prodPOSM["Sold_quantity"];
			$finalprodInfoPOSM[$i]["Price"]								=   $val_prodPOSM["Price"];

			$finalprodInfoPOSM[$i]["VALUE_NAIRA"]						=   $val_prodPOSM["VALUE_NAIRA"];
			$finalprodInfoPOSM[$i]["TLID"]								=   $val_prodPOSM["TLID"];
		} else {
			$finalprodInfoPOSM[$i]["DSR_Code"]							=   $val_prodPOSM["DSR_Code"];
			$finalprodInfoPOSM[$i]["Scheme_Code"]						=   $val_prodPOSM["Scheme_Code"];
			$finalprodInfoPOSM[$i]["Transaction_Number"]				=   $val_prodPOSM["Transaction_Number"];
			$finalprodInfoPOSM[$i]["Transaction_Line_Number"]			=   $val_prodPOSM["Transaction_Line_Number"];
			$finalprodInfoPOSM[$i]["Focus_Flag"]						=   $val_prodPOSM["Focus_Flag"];
			$finalprodInfoPOSM[$i]["POSM_Flag"]							=   $val_prodPOSM["POSM_Flag"];
			$finalprodInfoPOSM[$i]["Scheme_Flag"]						=   $val_prodPOSM["Scheme_Flag"];

			$finalprodInfoPOSM[$i]["Product_code"]						=   $val_prodPOSM["Product_code"];
			$finalprodInfoPOSM[$i]["Product_Name"]						=   $val_prodPOSM["Product_Name"];
			$finalprodInfoPOSM[$i]["UOM"]								=   $val_prodPOSM["UOM"];
			$finalprodInfoPOSM[$i]["Customer_Stock_quantity"]			=   $val_prodPOSM["Customer_Stock_quantity"];
			$finalprodInfoPOSM[$i]["Customer_Stock_Check"]				=   $val_prodPOSM["Customer_Stock_Check"];
			$finalprodInfoPOSM[$i]["Order_quantity"]					=   $val_prodPOSM["Order_quantity"];

			$finalprodInfoPOSM[$i]["KD_Code"]							=   $val_prodPOSM["KD_Code"];
			$finalprodInfoPOSM[$i]["Sold_quantity"]						=   $val_prodPOSM["Sold_quantity"];
			$finalprodInfoPOSM[$i]["Price"]								=   $val_prodPOSM["Price"];

			$finalprodInfoPOSM[$i]["VALUE_NAIRA"]						=   $val_prodPOSM["VALUE_NAIRA"];
			$finalprodInfoPOSM[$i]["TLID"]								=   $val_prodPOSM["TLID"];
		}
		$i++;
		$k++;
	}

	$finalSearchInfo          =   $finalprodInfoPOSM;
	//pre($finalSearchInfo);
	//exit;



	//while($fetch = mysql_fetch_array($results_dsr)) {

	foreach($finalSearchInfo	AS $fetchVal) {

		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$devtransactionid			=		$fetchVal['TLID'];
		$Transaction_Number			=		$fetchVal['Transaction_Number'];
		$Transaction_Line_Number	=		$fetchVal['Transaction_Line_Number'];
		$Focus_Flag					=		$fetchVal['Focus_Flag'];
		$POSM_Flag					=		$fetchVal['POSM_Flag'];
		$Scheme_Flag				=		$fetchVal['Scheme_Flag'];
		$Product_Scheme_Flag		=		$fetchVal['Product_Scheme_Flag'];
		$Product_code				=		$fetchVal['Product_code'];
		$Product_Name				=		$fetchVal['Product_Name'];
		$UOM						=		$fetchVal['UOM'];
		$Customer_Stock_quantity	=		$fetchVal['Customer_Stock_quantity'];
		$Customer_Stock_Check		=		$fetchVal['Customer_Stock_Check'];
		$Order_quantity				=		$fetchVal['Order_quantity'];
		$Sold_quantity				=		$fetchVal['Sold_quantity'];
		$priceval					=		$fetchVal['Price'];
		$valueval					=		$fetchVal['VALUE_NAIRA'];

		$sel_batchcount		=	"SELECT id FROM `device_transaction_batch` WHERE Transaction_Number = '$Transaction_Number' AND Transaction_Line_Number = '$Transaction_Line_Number'";
		$results_batchcount	=	mysql_query($sel_batchcount);
		$rowcnt_batchcount	=	mysql_num_rows($results_batchcount);
		if($rowcnt_batchcount > 0) {
			$BatchCount		=	$rowcnt_batchcount;
		} else {
			$BatchCount		=	'';
		}

		$sel_returnqty		=	"SELECT Reurn_quantity FROM `transaction_return_line` WHERE Transaction_Number = '$Transaction_Number' AND Transaction_Line_Number = '$Transaction_Line_Number'";
		$results_returnqty	=	mysql_query($sel_returnqty);
		$rowcnt_returnqty	=	mysql_num_rows($results_returnqty);
		$row_returnqty		=	mysql_fetch_array($results_returnqty);
		if($rowcnt_returnqty > 0) {
			$returnqty		=	$row_returnqty['Reurn_quantity'];
		} else {
			$returnqty		=	0;
		}

		$sel_posmcount			=	"SELECT * FROM `transaction_line` WHERE (id = '$fetchVal[TLID]' AND POSM_Flag = 1)";
		$results_posmcount		=	mysql_query($sel_posmcount);
		$rowcnt_posmcount		=	mysql_num_rows($results_posmcount);
		if($rowcnt_posmcount > 0) {
			$Product_type		=	'POSM';
		} else {
			$Product_type		=	'Standard';
		}
		
		?>
		<tr>
		<!-- <td align="center" <?php if($transtype == 2 || $transtype == 3) { ?> style="width:2%" <?php } else { ?> style="width:2%" <?php } ?>><?php echo $fetchVal['Transaction_Line_Number'];?></td> -->
		<td align="center" <?php if($transtype == 2 || $transtype == 3) { ?> style="width:8%" <?php } else { ?> style="width:15%" <?php } ?>><span <?php if($BatchCount != '') { ?> onclick="getbatchcontrol('<?php echo $Transaction_Number; ?>','<?php echo $Transaction_Line_Number; ?>');" style="cursor:pointer;cursor:hand;" <?php } ?> ><?php echo $fetchVal['Product_code']; ?></span></td>
		<td align="left" <?php if($transtype == 2 || $transtype == 3) { ?> style="width:62%" <?php } else { ?> style="width:73%" <?php } ?>><span <?php if($BatchCount != '') { ?> onclick="getbatchcontrol('<?php echo $Transaction_Number; ?>','<?php echo $Transaction_Line_Number; ?>');" style="cursor:pointer;cursor:hand;" <?php } ?> ><?php 
		
		
			/*$sel_prname	=	"SELECT * FROM `product` WHERE Product_code = '$Product_code'"; 
			$results_prname=mysql_query($sel_prname);
			$row_prname= mysql_fetch_array($results_prname);
			echo ucwords(strtolower($row_prname['Product_description1'])); */

			echo ucwords(strtolower($Product_Name));
			
			?></span></td>
		<td align="center" <?php if($transtype == 2 || $transtype == 3) { ?> style="width:5%" <?php } else { ?> style="width:5%" <?php } ?>><?php echo $UOM;?></td>

		<?php if($transtype == 2 || $transtype == 3) { ?>
		<td align="center" style="width:3%"><?php if($Focus_Flag == 1) { echo "Yes"; } else { echo "No"; } ?></td>
		<td align="center" style="width:3%"><?php if($Scheme_Flag == 1) { echo "Yes"; } else { echo "No"; }  ?></td>
		<td align="center" style="width:3%"><?php echo $fetchVal['Customer_Stock_quantity']; ?></td>
		<td align="center" style="width:3%"><?php echo $Product_type; ?></td>
		<td style="width:5%"><table border="0" width="100%">
				<tr>
					<td align="center"><?php if($fetchVal['Order_quantity'] != '') { echo $fetchVal['Order_quantity']; } else { echo "0"; } ?></td><td align="center"><?php if($fetchVal['Sold_quantity'] != '') { echo $fetchVal['Sold_quantity']; } else { echo "0"; } ?></td><td align="center"><?php if($Product_Scheme_Flag == 1 && $Scheme_Flag == 0) { echo "1"; } else { echo "0"; } ?></td>
				</tr>
				</table></td>
		<td align="center" style="width:3%"><?php echo $priceval; ?></td>
		<td align="center" style="width:3%"><?php echo $valueval; ?></td>
		<?php }

		if($transtype == 4) { ?>
		<td align="center" style="width:5%"><?php echo $returnqty;?></td>
		<?php } ?>

		</tr>
		<?php $c++; $cc++;
	}		 
	}else{ ?>	
		<tr>
			<td align='center' colspan='11'><b>No records found</b></td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
			<td style="display:none;" >Add Line2</td>	
			<td style="display:none;" >Add Line2</td>
			
			<?php if($transtype == 2 || $transtype == 3) { ?>
			<td style="display:none;" >Add Line2</td>	
			<td style="display:none;" >Add Line2</td>	
			<td style="display:none;" >Add Line2</td>	
			<td style="display:none;" >Add Line2</td>	
			<td style="display:none;" >Add Line2</td>	
			<td style="display:none;" >Add Line2</td>
			<?php } ?>
		</tr>
	<?php } ?>
	</tbody>
	</table>
</div>   
<div class="paginationfile" align="center">
	<table>
		<tr>
			<th class="pagination" scope="col">          
				<?php 				
			if(!empty($num_rows)){									
				rendering_devajaxlineitempagination($Num_Pages,$Page,$Prev_Page,$Next_Page,$params);
			}				
			?>      
			</th>
		</tr>
	</table>
</div>
<?php if(!empty($num_rows)) { ?>
	 <div style="padding-left:450px;padding-top:5px;"><span ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printlineitems_<?php echo $transno; ?>');" ></span>&nbsp;&nbsp;&nbsp;<span ><input type="button" value="Close" class="buttons" onclick="javascript:return closeSecondEnquiry(this,'<?php echo $transno; ?>');"></span></div>
	<form id="printlineitems_<?php echo $transno; ?>" target="_blank" action="printgetdevicelineitems.php" method="post">
		<input type="hidden" name="transno" id="transno" value="<?php echo $transno; ?>" />
		<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
		<input type="hidden" name="transtype" id="transtype" value="<?php echo $transtype; ?>" />
		<input type="hidden" name="fromdate" id="fromdate" value="<?php echo $fromdate; ?>" />
		<input type="hidden" name="todate" id="todate" value="<?php echo $todate; ?>" />
		<input type="hidden" name="kd_id" id="kd_id" value="<?php echo $kd_id; ?>" />
		<input type="hidden" name="dsr_id" id="dsr_id" value="<?php echo $dsr_id; ?>" />
		<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
		<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
	</form>
	<?php } else { ?>
		<span style="padding-left:450px;padding-top:5px;"><input type="button" value="Close" class="buttons" onclick="javascript:return closeSecondEnquiry(this,'<?php echo $transno; ?>');" ></span>
	<?php } 
exit(0);?>