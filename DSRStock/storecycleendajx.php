<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_REQUEST);

//pre($_REQUEST);
//exit;

/*
TESTING CODE FOR CUSTOMER RETURN INSERT INTO OPENING STOCK UPDATE AND CUSTOMER RETURN TABLES

$cycleStartDate										=	"2013-08-21 10:39:41";
$cycleEndDate										=	"2013-08-22 12:53:05";
$DSR_Code											=	"DSR002";
$TodayDate											=	date('Y-m-d');
$TransactionType_cus								=	'CUSTOMER Return';
$KD_Code											=	getKDCode();
$supplier_name_cus									=	"FMCL";

$return_query_opening					= "INSERT INTO opening_stock_update (`UOM1`,`Product_code`,`TransactionQty`,`BalanceQty`,`Date`,`StockDateTime`,`TransactionType`,`TransactionNo`,`AddedFirstTime`,`KD_Code`,`Product_description`) VALUES  ";

$query_return_trno					=	"SELECT id,Transaction_Number,Customer_code FROM transaction_hdr WHERE Transaction_type = '4' AND (CONCAT(Date, ' ', Time) >= '$cycleStartDate' AND CONCAT(Date, ' ', Time) <= '$cycleEndDate') AND DSR_Code='$DSR_Code'";
$res_return_trno					=	mysql_query($query_return_trno)or die(mysql_error());
$rowcnt_return_trno					=	mysql_num_rows($res_return_trno);
$eo									=	0;
$create_return_query				=	'';
$opening_return_query				=	'';
$updated_qty_ret					=	0;
$final_qty_ret						=	0;


if($rowcnt_return_trno > 0 ) {
	while($row_return_trno			=	mysql_fetch_array($res_return_trno)) {
		$Transaction_Number			=	$row_return_trno[Transaction_Number];
		$Customer_code				=	$row_return_trno[Customer_code];
		$Customer_name				=	finddbval("('".$row_return_trno[Customer_code]."')",'Customer_Name','customer_code','customer');
		$Customer_id				=	finddbval("('".$row_return_trno[Customer_code]."')",'id','customer_code','customer');
		$query_returnlist			=	"SELECT Product_code,UOM,Reurn_quantity FROM transaction_return_line WHERE (DSR_Code='$DSR_Code' AND Transaction_Number = '$Transaction_Number')";
		$res_returnlist						=	mysql_query($query_returnlist)or die(mysql_error());
		$rowcnt_returnlist					=	mysql_num_rows($res_returnlist);
		if($rowcnt_returnlist > 0 ){
			while($row_returnlist				=	mysql_fetch_array($res_returnlist)) {				
				$Return_quantity				=	$row_returnlist[Reurn_quantity];
				$Return_PCODE					=	$row_returnlist[Product_code];
				$Return_UOM						=	$row_returnlist[UOM];
				$productdescription_cus			=	getproductval($Return_PCODE,'Product_description1','Product_code');
				$sel							=	"select BalanceQty from opening_stock_update where Product_code ='$Return_PCODE' ORDER BY id desc";
				$sel_query						=	mysql_query($sel) or die(mysql_error());
				if(mysql_num_rows($sel_query) > 0) {
					$row_qty						=	mysql_fetch_array($sel_query);
					$updated_qty_ret				=	$row_qty[BalanceQty];
					$final_qty_ret					=	($updated_qty_ret) + ($Return_quantity);
				}								
				//echo $sel."<br>";
				//echo $final_qty_ret."<br>";
				if($eo == 0) {
					$query_oldtranum					=	"SELECT TransactionNo FROM opening_stock_update WHERE TransactionNo LIKE 'CUSRET%' ORDER BY id DESC";			
					$res_oldtranum						=	mysql_query($query_oldtranum) or die(mysql_error());
					$rowcnt_oldtranum					=	mysql_num_rows($res_oldtranum);
					//$rowcnt_oldtranum					=	0; // comment if live
					//echo $rowcnt_oldtranum."<br>";
					if($rowcnt_oldtranum > 0) {
						//echo $rowcnt_oldtranum."<br>";
						$row_oldtranum					=	mysql_fetch_array($res_oldtranum);
						$Old_Transaction_number				=	$row_oldtranum['TransactionNo'];

						$gettxnno						=	abs(str_replace("CUSRET",'',strstr($Old_Transaction_number,"CUSRET")));
						$gettxnno++;
						if($gettxnno < 10) {
							$createdcode	=	"00".$gettxnno;
						} else if($gettxnno < 100) {
							$createdcode	=	"0".$gettxnno;
						} else {
							$createdcode	=	$gettxnno;
						}
						//echo $createdcode."<br>";
						$Transaction_number_cus				=	"CUSRET".$createdcode;
					} else {
						//echo $rowcnt_oldtranum."EDer<br>";
						$Transaction_number_cus				=	"CUSRET001";
					}
					//exit;
					$create_return_query			.=	"('".$Customer_id."','".$Customer_name."','".$supplier_name_cus."','".$Transaction_Number."','".$TodayDate."','".$Return_PCODE."','".$Return_UOM."','".$Return_quantity."')";
					$opening_return_query			=	"('".$Return_UOM."','".$Return_PCODE."','".$Return_quantity."','".$final_qty_ret."','".$TodayDate."',NOW(),'".$TransactionType_cus."','".$Transaction_number_cus."','Y','".$KD_Code."','".$productdescription_cus."')";
					$return_query_opening_fin					= $return_query_opening." ".$opening_return_query;
					$res_return_opening_qry						=	mysql_query($return_query_opening_fin) or die(mysql_error());
					$eo++;
				} elseif($eo > 0) {					
					$gettxnno						=	abs(str_replace("CUSRET",'',strstr($Transaction_number_cus,"CUSRET")));
					$gettxnno++;
					if($gettxnno < 10) {
						$createdcode	=	"00".$gettxnno;
					} else if($gettxnno < 100) {
						$createdcode	=	"0".$gettxnno;
					} else {
						$createdcode	=	$gettxnno;
					}
					$Transaction_number_cus				=	"CUSRET".$createdcode;
					$create_return_query			.=	",('".$Customer_id."','".$Customer_name."','".$supplier_name_cus."','".$Transaction_Number."','".$TodayDate."','".$Return_PCODE."','".$Return_UOM."','".$Return_quantity."')";
					$opening_return_query			=	"('".$Return_UOM."','".$Return_PCODE."','".$Return_quantity."','".$final_qty_ret."','".$TodayDate."',NOW(),'".$TransactionType_cus."','".$Transaction_number_cus."','Y','".$KD_Code."','".$productdescription_cus."')";
					$return_query_opening_fin					= $return_query_opening." ".$opening_return_query;
					$res_return_opening_qry						=	mysql_query($return_query_opening_fin) or die(mysql_error());
				}
				$updated_qty_ret				=	0;
				$final_qty_ret					=	0;

			}
		}
	}
	if($eo > 0) {
		$return_final_query							= "INSERT INTO `customer_return` (`cust_id`,`customerName`,`supplier_name`,`Transaction_number`,`DATE`,`Product_code`,`UOM`,`quantity`) VALUES $create_return_query";
		$res_return_qry								=	mysql_query($return_final_query) or die(mysql_error());		
	}
}

exit;

*/


$orig											=	$_REQUEST['orig'];
$last_inserted_id								=	'';
$linenumberval										=	0;
$KD_Code										=	getKDCode();
$TransactionType								=	'DSR Return';
$TransactionType_cus							=	'Customer Return';
$TransactionType_stoadj							=	'Adjustment';
$Transaction_number_Opening						=	'DSRETURN10001';
$TodayDate										=	date('Y-m-d');
$supplier_name									=	"DSR";
$supplier_name_cus								=	"FMCL";
$updated_qty									=	0;
$final_qty										=	0;
$productdescription								=	'';

$sel_dsrtransno									=	"SELECT Transaction_number FROM `dsr_return` ORDER BY id DESC";
$res_dsrtransno									=	mysql_query($sel_dsrtransno) or die(mysql_error());	
$rowcnt_dsrtransno								=	mysql_num_rows($res_dsrtransno);
if($rowcnt_dsrtransno > 0){
	$row_dsrtransno								=	mysql_fetch_array($res_dsrtransno);
	$dsrtransno									=	$row_dsrtransno['Transaction_number'];
} else {
	$dsrtransno									=	rand(1,99);
}

if(isset($_REQUEST['orig']) && $_REQUEST['orig'] != '') { 
	$origarr									=	explode('newline=&',$orig);

	//$params	=	array();
	//parse_str($orig, $params);

	//print_r($origarr);
	//exit;

	$ins_first_query							= "INSERT INTO `cycle_end_reconciliation`(`DSR_Code`,`cycle_start_date`,`cycle_end_date`,`device_code`,`vehicle_code`,`UOM`,`currency`,`total_sales_value`,`total_deposit_value`,`amount_shortage`,`line_number`,`Product_code`,`cycle_start_loaded_qty`,`daily_total_loaded_qty`,`total_sold_qty`,`total_cancelled_qty`,`total_sales_returned_qty`,`total_ind_prod_sale_value`,`DSR_returned_qty`,`KD_returned_qty`,`quantity_shortage`) VALUES ";
	$ins_first_query_opening					= "INSERT INTO opening_stock_update (`UOM1`,`Product_code`,`TransactionQty`,`BalanceQty`,`Date`,`StockDateTime`,`TransactionType`,`TransactionNo`,`AddedFirstTime`,`KD_Code`,`Product_description`) VALUES ";

	$ins_stoadj_query_opening					= "INSERT INTO opening_stock_update (`UOM1`,`Product_code`,`TransactionQty`,`BalanceQty`,`Date`,`StockDateTime`,`TransactionType`,`TransactionNo`,`AddedFirstTime`,`KD_Code`,`Product_description`) VALUES ";

	$ins_stoadj_query							= "INSERT INTO stock_adjustment (`DSR_Code`,`UOM`,`Product_code`,`reason`,`quantity`,`Date`,`Transaction_number`,`line_number`,`KD_Code`,`opening_id`) VALUES ";

	$ins_first_query_dsr						= "INSERT INTO `dsr_return` (`UOM`,`Product_code`,`quantity`,`dsr_id`,`DSRName`,`supplier_name`,`Transaction_number`,`Date`) VALUES ";
	//$return_final_query							= "INSERT INTO `customer_return` (`UOM`,`Product_code`,`quantity`,`cust_id`,`customerName`,`supplier_name`,`Transaction_number`,`DATE`) VALUES ";
	$query_form									=	'';
	$query_form_opening							=	'';
	$query_stoadj_opening						=	'';
	$query_stoadj								=	'';
	$query_form_dsr								=	'';
	$query_form_cus								=	'';
	$final_query_dsr							=	'';
	$t											=	0;	
	foreach($origarr as $origVal){
		//echo $t;
		if($t == 0){		
			$firstline	=	explode("&",$origVal);
			foreach($firstline as $firstlineval){
				if(strstr($firstlineval,'cycleStartDate')){
					$cycleStartDate				=	explode('=',$firstlineval);			
					$query_form					.=	"'".$cycleStartDate[1]."',";
				}
				if(strstr($firstlineval,'cycleEndDate')){
					$cycleEndDate				=	explode('=',$firstlineval);
					$query_form					.=	"'".$cycleEndDate[1]."',";
				}
				if(strstr($firstlineval,'DSR_Code')){				
					$DSR_Code					=	explode('=',$firstlineval);
					$query_form					.=	"('".$DSR_Code[1]."',";
					$query_stoadj				.=	"('".$DSR_Code[1]."',";
				}
				if(strstr($firstlineval,'vehicleCode')){
					$vehicleCode				=	explode('=',$firstlineval);
					$query_form					.=	"'".$vehicleCode[1]."',";
				}
				if(strstr($firstlineval,'deviceCode')){
					$deviceCode					=	explode('=',$firstlineval);
					$query_form					.=	"'".$deviceCode[1]."',";
				}			
				if(strstr($firstlineval,'UOM')){
					$UOM						=	explode('=',$firstlineval);
					$query_form					.=	"'".$UOM[1]."',";
					$query_form_opening			.=	"('".$UOM[1]."',";
					$query_stoadj_opening		.=	"('".$UOM[1]."',";
					$query_stoadj				.=	"'".$UOM[1]."',";
					$query_form_dsr				.=	"('".$UOM[1]."',";
					$query_form_cus				.=	"('".$UOM[1]."',";
				}
				if(strstr($firstlineval,'currency')){
					$currency					=	explode('=',$firstlineval);
					$query_form					.=	"'".$currency[1]."',";
				}
				if(strstr($firstlineval,'netSaleValue')){
					$netSaleValue				=	explode('=',$firstlineval);
					//echo $netSaleValue[1];
					//exit;
					$query_form					.=	"'".remcyccom(remdot($netSaleValue[1]))."',";
					//echo $query_form;
					//exit;
				}
				if(strstr($firstlineval,'depositValue')){
					$depositValue				=	explode('=',$firstlineval);
					$query_form					.=	"'".remcyccom(remdot($depositValue[1]))."',";
				}
				if(strstr($firstlineval,'shortageVal')){
					$shortageVal				=	explode('=',$firstlineval);
					$query_form					.=	"'".remcyccom(remdot($shortageVal[1]))."',";
				}						
			}
			//$origfinal	=	str_replace("&","',",$origstr);		
			$t++;
			continue;
		}

		$nextline								=	explode("&",$origVal);
		$final_query							=	'';
		$final_query_opening					=	'';
		$final_stoadj_opening					=	'';
		$final_stoadj							=	'';
		$final_query_dsr						=	'';
		$final_query_cus						=	'';

		foreach($nextline as $nextlineval){		
			if(strstr($nextlineval,'cycle_start_loaded_qty')){
				$cycle_start_loaded_qty			=	explode('=',$nextlineval);			
				$final_query					.=	"'".$cycle_start_loaded_qty[1]."',";
			}
			if(strstr($nextlineval,'daily_total_loaded_qty')){
				$daily_total_loaded_qty			=	explode('=',$nextlineval);			
				$final_query					.=	"'".$daily_total_loaded_qty[1]."',";
			}
			if(strstr($nextlineval,'total_sold_qty')){
				$total_sold_qty					=	explode('=',$nextlineval);			
				$final_query					.=	"'".$total_sold_qty[1]."',";
			}
			if(strstr($nextlineval,'total_cancel_qty')){
				$total_cancel_qty				=	explode('=',$nextlineval);			
				$final_query					.=	"'".$total_cancel_qty[1]."',";
			}
			if(strstr($nextlineval,'total_sales_returned_qty')){
				$total_sales_returned_qty		=	explode('=',$nextlineval);			
				$final_query					.=	"'".$total_sales_returned_qty[1]."',";
				//$final_query_cus				.=	"'".$total_sales_returned_qty[1]."',";
			}
			if(strstr($nextlineval,'total_ind_prod_sale_value')){
				$total_ind_prod_sale_value		=	explode('=',$nextlineval);			
				$final_query					.=	"'".$total_ind_prod_sale_value[1]."',";
				//$final_query_cus				.=	"'".$total_ind_prod_sale_value[1]."',";
			}
			if(strstr($nextlineval,'DSR_returned_qty')){
				$DSR_returned_qty				=	explode('=',$nextlineval);			
				$final_query					.=	"'".$DSR_returned_qty[1]."',";
			}
			if(strstr($nextlineval,'short_reason')){
				$short_reason					=	explode('=',$nextlineval);			
				$final_stoadj					.=	"'".$short_reason[1]."',";
			}
			if(strstr($nextlineval,'KD_returned_qty')){
				$KD_returned_qty				=	explode('=',$nextlineval);			
				$final_query					.=	"'".remcyccom($KD_returned_qty[1])."',";
				$total_dsr_returned_qty			=	(remcyccom($KD_returned_qty[1])) - ($total_sales_returned_qty[1]);
				$final_query_dsr				.=	"'".$total_dsr_returned_qty."',";
				$final_query_opening			.=	"'".$total_dsr_returned_qty."',";
			}
			if(strstr($nextlineval,'quantity_shortage')){
				$quantity_shortage				=	explode('=',$nextlineval);			
				$final_query					.=	"'".$quantity_shortage[1]."')";
				if($quantity_shortage[1] != '0') {
					
					if(strstr($quantity_shortage[1],'-')) {
					//if($quantity_shortage[1][0] == '-') {
						$short_qty_without_minus		=	substr($quantity_shortage[1],1);
						//echo $short_qty_without_minus;
						//exit;				
						$final_stoadj					.=	"'".$short_qty_without_minus."',";
						$final_stoadj_opening			.=	"'".$short_qty_without_minus."',";
					} else {
						$final_stoadj					.=	"'-".$quantity_shortage[1]."',";
						$final_stoadj_opening			.=	"'-".$quantity_shortage[1]."',";
					}
				}
				//exit;
			}
			if(strstr($nextlineval,'line_number')){			
				$linenumber						=	explode('=',$nextlineval);			
				$final_query					.=	"'".$linenumber[1]."',";
			}
			if(strstr($nextlineval,'Product_code')){
				$Product_code					=	explode('=',$nextlineval);
				$final_query					.=	"'".$Product_code[1]."',";
				$final_query_opening			.=	"'".$Product_code[1]."',";
				$final_stoadj_opening			.=	"'".$Product_code[1]."',";
				$final_stoadj					.=	"'".$Product_code[1]."',";
				$final_query_dsr				.=	"'".$Product_code[1]."',";
				$final_query_cus				.=	"'".$Product_code[1]."',";
				$productdescription				=	getproductval($Product_code[1],'Product_description1','Product_code');
				if($productdescription == '') {
					$productdescription							=	getdbval($Product_code[1],'Product_description1','Product_code','customertype_product');
				}
				$sel							=	"select id,BalanceQty from opening_stock_update where Product_code ='$Product_code[1]' AND KD_Code = '$KD_Code' ORDER BY id desc";
				$sel_query						=	mysql_query($sel) or die(mysql_error());
				if(mysql_num_rows($sel_query) > 0) {
					$row_qty					=	mysql_fetch_array($sel_query);
					$open_id					=	$row_qty[id];
					$updated_qty				=	$row_qty[BalanceQty];
				}
			}
		}
		$final_qty								=	($updated_qty) + ($total_dsr_returned_qty);
		$dsr_idval								=	getdsrval($DSR_Code[1],'id','DSR_Code');
		$dsr_nameval							=	getdsrval($DSR_Code[1],'DSRName','DSR_Code');
		$dsrtransno++;
		
		$middle_opening							=	"'$final_qty','$TodayDate',NOW(),'$TransactionType','$Transaction_number_Opening','Y','$KD_Code','$productdescription')";

		$middle_dsr								=	"'$dsr_idval','$dsr_nameval','$supplier_name','$dsrtransno','$TodayDate')";
		
		//$middle_cus							=	"'$dsr_idval','$dsr_nameval','$supplier_name','$dsrtransno','$TodayDate')";

		$full_query								=	$ins_first_query.$query_form.$final_query;
		//echo $full_query."<br>";
		//exit;
		mysql_query($full_query) or die(mysql_error());
		//exit;
		if($last_inserted_id == '') {
			$last_inserted_id					=	mysql_insert_id();
		} else {
			$last_inserted_id					.=	"~".mysql_insert_id();
		}

		$full_query_opening						=	$ins_first_query_opening.$query_form_opening.$final_query_opening.$middle_opening;
		
		if(!strstr($total_dsr_returned_qty,'-')){
			if($total_dsr_returned_qty > 0) {
				//echo $full_query_opening."<br>";
				mysql_query($full_query_opening) or die(mysql_error());
			}
		}

		$full_query_dsr							=	$ins_first_query_dsr.$query_form_dsr.$final_query_dsr.$middle_dsr;

		if(!strstr($total_dsr_returned_qty,'-')){
			if($total_dsr_returned_qty > 0) {
				//echo $full_query_dsr."<br>";
				mysql_query($full_query_dsr) or die(mysql_error());	
			}
		}
		

		if($quantity_shortage[1] != '0') {
			$linenumberval++;
			$sel_adj							=	"select id,BalanceQty from opening_stock_update where Product_code ='$Product_code[1]' AND KD_Code = '$KD_Code' ORDER BY id desc";
			$sel_query_adj						=	mysql_query($sel_adj) or die(mysql_error());
			if(mysql_num_rows($sel_query_adj) > 0) {
				$row_qty_adj					=	mysql_fetch_array($sel_query_adj);
				$open_id_adj					=	$row_qty_adj[id];
				$updated_qty_adj				=	$row_qty_adj[BalanceQty];
			}
			
			if($Transaction_number_adj == '' || $Transaction_number_opening == ''){
				$query_oldtranumadjsto			=	"SELECT Transaction_number FROM stock_adjustment ORDER BY id DESC";			
				$res_oldtranumadjsto			=	mysql_query($query_oldtranumadjsto) or die(mysql_error());
				$rowcnt_oldtranumadjsto			=	mysql_num_rows($res_oldtranumadjsto);
				//$rowcnt_oldtranumadjsto		=	0; // comment if live
				//echo $rowcnt_oldtranumadjsto."<br>";
				if($rowcnt_oldtranumadjsto > 0) {
					//echo $rowcnt_oldtranumadjsto."<br>";
					$row_oldtranumadjsto				=	mysql_fetch_array($res_oldtranumadjsto);
					$Old_Transaction_number_adjsto		=	$row_oldtranumadjsto['Transaction_number'];

					$gettxnnoadj						=	abs(str_replace('ADJ','',strstr($Old_Transaction_number_adjsto,"ADJ")));
					$gettxnnoadj++;
					if($gettxnnoadj < 10) {
						$createdcodeadj	=	"00".$gettxnnoadj;
					} else if($gettxnnoadj < 100) {
						$createdcodeadj	=	"0".$gettxnnoadj;
					} else {
						$createdcodeadj	=	$gettxnnoadj;
					}
					//echo $createdcode."<br>";
					$Transaction_number_adj			=	$KD_Code."ADJ".$createdcodeadj;
					$Transaction_number_opening		=	"ADJ".$createdcodeadj;
				} else {
					//echo $rowcnt_oldtranum."EDer<br>";
					$Transaction_number_adj			=	$KD_Code."ADJ001";
					$Transaction_number_opening		=	"ADJ001";
				}
			}

			$final_qty_adj							=	($updated_qty_adj) - ($quantity_shortage[1]);
			$middle_opening_stoadj					=	"'$final_qty_adj','$TodayDate',NOW(),'$TransactionType_stoadj','$Transaction_number_opening','Y','$KD_Code','$productdescription')";
	
			//echo $updated_qty_adj."<br>";
			//echo $quantity_shortage[1]."<br>";
			//echo $final_qty_adj."<br>";
			//echo $full_query_opening;
			$full_query_opening_stoadj				=	$ins_stoadj_query_opening.$query_stoadj_opening.$final_stoadj_opening.$middle_opening_stoadj;
			mysql_query($full_query_opening_stoadj) or die(mysql_error());

			//echo $full_query_opening_stoadj."<br>";
			$opening_id								=	mysql_insert_id();

			$middle_stoadj							=	"'$TodayDate','$Transaction_number_adj','$linenumberval','$KD_Code','$opening_id')";
	
			$full_query_stoadj						=	$ins_stoadj_query.$query_stoadj.$final_stoadj.$middle_stoadj;
			mysql_query($full_query_stoadj) or die(mysql_error());

			//echo $full_query_stoadj."<br>";
		}

		//exit;
		$updated_qty							=	0;
		$final_qty								=	0;
		$productdescription						=	'';
		$total_dsr_returned_qty					=	0;
	}

	//RETURN TRANSACTION ADDED TO CUSTOMER RETURN AND THE OPENING STOCK TABLE STARTS HERE

	$return_query_opening					= "INSERT INTO opening_stock_update (`UOM1`,`Product_code`,`TransactionQty`,`BalanceQty`,`Date`,`StockDateTime`,`TransactionType`,`TransactionNo`,`AddedFirstTime`,`KD_Code`,`Product_description`) VALUES  ";
	
	$cycleStartDate[1]					=		urldecode($cycleStartDate[1]);
	$cycleEndDate[1]					=		urldecode($cycleEndDate[1]);
	$query_return_trno					=	"SELECT id,Transaction_Number,Customer_code,return_reason FROM transaction_hdr WHERE Transaction_type = '4' AND (CONCAT(Date, ' ', Time) >= '$cycleStartDate[1]' AND CONCAT(Date, ' ', Time) <= '$cycleEndDate[1]') AND DSR_Code='$DSR_Code[1]'";
	//echo $query_return_trno;
	//exit;
	$res_return_trno					=	mysql_query($query_return_trno)or die(mysql_error());
	$rowcnt_return_trno					=	mysql_num_rows($res_return_trno);
	$eo									=	0;
	$create_return_query				=	'';
	$opening_return_query				=	'';
	$updated_qty_ret					=	0;
	$final_qty_ret						=	0;

	if($rowcnt_return_trno > 0 ) {
		while($row_return_trno			=	mysql_fetch_array($res_return_trno)) {
			$Transaction_Number			=	$row_return_trno[Transaction_Number];
			$Customer_code				=	$row_return_trno[Customer_code];
			$return_reason				=	$row_return_trno[return_reason];
			$Customer_name				=	finddbval("('".$row_return_trno[Customer_code]."')",'Customer_Name','customer_code','customer');
			$Customer_id				=	finddbval("('".$row_return_trno[Customer_code]."')",'id','customer_code','customer');
			$query_returnlist			=	"SELECT Product_code,UOM,Reurn_quantity FROM transaction_return_line WHERE (DSR_Code='$DSR_Code[1]' AND Transaction_Number = '$Transaction_Number')";
			//echo $query_returnlist."<br>";
			//exit;
			$res_returnlist						=	mysql_query($query_returnlist)or die(mysql_error());
			$rowcnt_returnlist					=	mysql_num_rows($res_returnlist);
			if($rowcnt_returnlist > 0 ){
				while($row_returnlist				=	mysql_fetch_array($res_returnlist)) {				
					$Return_quantity				=	$row_returnlist[Reurn_quantity];
					$Return_PCODE					=	$row_returnlist[Product_code];
					$Return_UOM						=	$row_returnlist[UOM];
					$productdescription_cus			=	getproductval($Return_PCODE,'Product_description1','Product_code');
					$sel							=	"select BalanceQty from opening_stock_update where Product_code ='$Return_PCODE' ORDER BY id desc";
					$sel_query						=	mysql_query($sel) or die(mysql_error());
					if(mysql_num_rows($sel_query) > 0) {
						$row_qty						=	mysql_fetch_array($sel_query);
						$updated_qty_ret				=	$row_qty[BalanceQty];
						$final_qty_ret					=	($updated_qty_ret) + ($Return_quantity);
					}								
					//echo $sel."<br>";
					//echo $final_qty_ret."<br>";
					if($eo == 0) {
						$query_oldtranum					=	"SELECT TransactionNo FROM opening_stock_update WHERE TransactionNo LIKE 'CUSRET%' ORDER BY id DESC";			
						$res_oldtranum						=	mysql_query($query_oldtranum) or die(mysql_error());
						$rowcnt_oldtranum					=	mysql_num_rows($res_oldtranum);
						//$rowcnt_oldtranum					=	0; // comment if live
						//echo $rowcnt_oldtranum."<br>";
						if($rowcnt_oldtranum > 0) {
							//echo $rowcnt_oldtranum."<br>";
							$row_oldtranum					=	mysql_fetch_array($res_oldtranum);
							$Old_Transaction_number				=	$row_oldtranum['TransactionNo'];

							$gettxnno						=	abs(str_replace("CUSRET",'',strstr($Old_Transaction_number,"CUSRET")));
							$gettxnno++;
							if($gettxnno < 10) {
								$createdcode	=	"00".$gettxnno;
							} else if($gettxnno < 100) {
								$createdcode	=	"0".$gettxnno;
							} else {
								$createdcode	=	$gettxnno;
							}
							//echo $createdcode."<br>";
							$Transaction_number_cus				=	"CUSRET".$createdcode;
						} else {
							//echo $rowcnt_oldtranum."EDer<br>";
							$Transaction_number_cus				=	"CUSRET001";
						}
						//exit;
						$create_return_query			.=	"('".$Customer_id."','".$Customer_name."','".$supplier_name_cus."','".$Transaction_Number."','".$TodayDate."','".$Return_PCODE."','".$Return_UOM."','".$Return_quantity."','".$return_reason."')";
						$opening_return_query			=	"('".$Return_UOM."','".$Return_PCODE."','".$Return_quantity."','".$final_qty_ret."','".$TodayDate."',NOW(),'".$TransactionType_cus."','".$Transaction_number_cus."','Y','".$KD_Code."','".$productdescription_cus."')";
						$return_query_opening_fin					= $return_query_opening." ".$opening_return_query;
						$res_return_opening_qry						=	mysql_query($return_query_opening_fin) or die(mysql_error());
						$eo++;
					} elseif($eo > 0) {					
						$gettxnno						=	abs(str_replace("CUSRET",'',strstr($Transaction_number_cus,"CUSRET")));
						$gettxnno++;
						if($gettxnno < 10) {
							$createdcode	=	"00".$gettxnno;
						} else if($gettxnno < 100) {
							$createdcode	=	"0".$gettxnno;
						} else {
							$createdcode	=	$gettxnno;
						}
						$Transaction_number_cus				=	"CUSRET".$createdcode;
						$create_return_query			.=	",('".$Customer_id."','".$Customer_name."','".$supplier_name_cus."','".$Transaction_Number."','".$TodayDate."','".$Return_PCODE."','".$Return_UOM."','".$Return_quantity."','".$return_reason."')";
						$opening_return_query			=	"('".$Return_UOM."','".$Return_PCODE."','".$Return_quantity."','".$final_qty_ret."','".$TodayDate."',NOW(),'".$TransactionType_cus."','".$Transaction_number_cus."','Y','".$KD_Code."','".$productdescription_cus."')";
						$return_query_opening_fin					= $return_query_opening." ".$opening_return_query;
						$res_return_opening_qry						=	mysql_query($return_query_opening_fin) or die(mysql_error());
					}
					$updated_qty_ret				=	0;
					$final_qty_ret					=	0;

				}
			}
		}
		if($eo > 0) {
			$return_final_query							= "INSERT INTO `customer_return` (`cust_id`,`customerName`,`supplier_name`,`Transaction_number`,`DATE`,`Product_code`,`UOM`,`quantity`,`return_reason`) VALUES $create_return_query";
			//echo $return_final_query."<br>";
			$res_return_qry								=	mysql_query($return_final_query) or die(mysql_error());		
		}
	}
	
	//RETURN TRANSACTION ADDED TO CUSTOMER RETURN AND THE OPENING STOCK TABLE ENDS HERE


	//exit;	
	$dsr_idval									=	getdsrval($DSR_Code[1],'id','DSR_Code');
	//$upcycle_query								=	"UPDATE `cycle_flag` SET cycle_end_flag = '1',cycle_start_flag = '0', cycle_end_date = '$cycleEndDate[1]' WHERE (cycle_end_flag = '0' AND cycle_start_flag = '1' OR cycle_end_flag = '0' AND cycle_start_flag = '0') AND dsr_id = '$dsr_idval'";
	$upcycle_query								=	"UPDATE `cycle_flag` SET cycle_end_flag = '1',cycle_start_flag = '0', cycle_end_date = '$cycleEndDate[1]' WHERE (cycle_end_flag = '0' AND cycle_start_flag = '1') AND dsr_id = '$dsr_idval'";
	$upcycle_res								=	mysql_query($upcycle_query) or die(mysql_error());

	//$upcycle_query								=	"UPDATE `cycle_assignment` SET end_flag_status = '1',flag_status='0' WHERE (end_flag_status = '0' AND flag_status = '1' OR end_flag_status = '0' AND flag_status = '0') AND dsr_id = '$dsr_idval'";
	$upcycle_query								=	"UPDATE `cycle_assignment` SET end_flag_status = '1',flag_status='0' WHERE (end_flag_status = '0' AND flag_status = '1') AND dsr_id = '$dsr_idval'";
	$upcycle_res								=	mysql_query($upcycle_query) or die(mysql_error());

	/*$query_cycleflag							=	"INSERT INTO cycle_flag SET KD_Code='$KD_Code',dsr_id='$dsr_idval',cycle_start_flag='0',cycle_end_date=NOW(),cycle_end_flag='1'";
	$res_cycleflag								=	mysql_query($query_cycleflag) or die (mysql_error());
	$last_inserted_idval						=	mysql_insert_id();

	$ass_sql					=	"INSERT INTO cycle_assignment SET KD_Code='$KD_Code',flag_status='0',dsr_id='$dsr_idval',dsr_code='$DSR_Code[1]',Date='$cur_date',end_flag_status='1',assign_id='$last_inserted_idval'";
	$res_cycleflag				=	mysql_query($ass_sql) or die (mysql_error());*/

	$upsalescol_query							=	"UPDATE `sale_and_collection` SET cycle_start_flag = '2' WHERE (cycle_start_flag = '1') AND dsr_code = '$DSR_Code[1]' AND device_code = '$deviceCode[1]'";
	$upcycle_res								=	mysql_query($upsalescol_query) or die(mysql_error());	
	echo $last_inserted_id;
	exit;
} else {
	echo "INVALID";
}
exit(0);?>