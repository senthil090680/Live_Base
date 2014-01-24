<?php
include "../include/config.php";
include "../include/ajax_pagination.php";
$tables[0]		=	"";
$tables[1]		=	"transaction_hdr";
$tables[2]		=	"transaction_line"; // need to be changed to product code
$tables[3]		=	"device_transaction_batch"; // need to be changed to product code
$tables[4]		=	"transaction_return_line";  // need to be changed to product code
$tables[5]		=	"transaction_return_batch";  // need to be changed to product code
$tables[6]		=	"customer";
$tables[7]		=	"feedback";
$tables[8]		=	"vehicle_stock";  // need to be changed to product code
$tables[9]		=	"vehicle_stock_batch";  // need to be changed to product code
$tables[10]		=	"sale_and_collection"; 
$tables[11]		=	"dsr_metrics";
$tables[12]		=	"customer_visit_tracking";
$tables[14]		=	"sales_list";

$KD_Code = getKDCode();

// TO GET THE DEVICE CODE IF THE DEVICE IS ONLINE STARTS HERE

$query					=	"SELECT ID,DEVICE_CODE,STATUS,ACTION FROM ping_table";
$result					=	mysql_query($query);
$checkonline			=	"5";	
while ($data = mysql_fetch_array($result))
{
	if($data['STATUS'] == "ONLINE") {
		$devcode		=	$data['DEVICE_CODE'];
		$checkonline	=	6;
	}
}

if($checkonline	== "6") {

} elseif($checkonline	== "5") {
	echo "2";
	exit;
}
// TO GET THE DEVICE CODE IF THE DEVICE IS ONLINE ENDS HERE


$fileextension		=	date('d-m-y').".txt";
$filename			=	$devcode."_txn_".$fileextension;
//echo $filename;
//exit;

//$file = @fopen("Upload.txt", "r");
//$datavalue		=	file("Upload.txt");

//$directory = "../../Upload/";			//WHEN LIVE COMMENT THIS 

$directory = "../../../Upload/";		//WHEN LIVE UNCOMMENT THIS

$file = @fopen($directory.$filename, "r");

//$file			=	@fopen($filename, "r");
$datavalue		=	file($filename);
$linecount		=	count($datavalue);
$linecountval	=	0;

//pre($datavalue);
//exit;
if ($file) {
    while (($line = fgets($file, 4096)) !== false) {

        $pos = strpos($line, "^");
        $index = substr($line, 0, $pos);

		if($linecount	==	$linecountval) {
	        $line = substr($line, $pos + 1, -1);
		} else {
			$line = substr($line, $pos + 1, -3);
		}

		//$index	=	"27";
		
		//echo $line."<br>";
		
        $line = str_replace("^", "','", $line);
		
		//$index	=	1;

        if ($index == 1) {
			
			//echo "hi";
			//exit;
			$lineproduct		=	$line;

			$lineproductarr		=	explode("','",$lineproduct);
		
			$query_transhdr = "SELECT id from " . $tables[$index] . " WHERE Date='$lineproductarr[3]' AND Time='$lineproductarr[4]' AND Transaction_type='$lineproductarr[7]' AND Transaction_Number='$lineproductarr[8]'";
			//exit;
			$result_transhdr = mysql_query($query_transhdr) or die(mysql_error());
			$rowcnt_transhdr = mysql_num_rows($result_transhdr);

			if($rowcnt_transhdr == 0){
				$query = "insert into " . $tables[$index] . " values ('','" . $line . "')";
				//exit;
				$result = mysql_query($query);
			}

			$todaydate	=	date('d');
			if($todaydate >= 1 && $todaydate <= 4) {
				$prevmonth				=	date('m',strtotime("-1 month"));
				$currmonth				=	ltrim(date('m'),0);
				
				if($currmonth == 1) {
					$findyear				=	date('Y',strtotime("-1 year"));
				} else {
					$findyear				=	date('Y');
				}
				$trimprevmonth				=	ltrim($prevmonth,0);
				
				//$prevmonth					=	"07";
				//$trimprevmonth				=	"7";

				$joinmonyear				=	$findyear."-".$prevmonth;

				$query_transhdr													=   "SELECT Customer_code,DSR_Code,KD_Code, Transaction_Number,transaction_Reference_Number FROM transaction_hdr WHERE Date LIKE '$joinmonyear%' AND Transaction_type IN ('2','3')";
				//echo $query_transhdr;
				//exit;
				$res_transhdr											=   mysql_query($query_transhdr);
				$transno_transhdr										=	array();
				while($row_transhdr										=   mysql_fetch_assoc($res_transhdr)) {							
					if($row_transhdr[transaction_Reference_Number] != '' && $row_transhdr[transaction_Reference_Number] != '0') {
						$transaction_Reference_Number_cancel[]			=   $row_transhdr[transaction_Reference_Number];
					} else {
						$Transaction_Number									=	$row_transhdr[Transaction_Number];
						$transhdr_result[]									=   $row_transhdr;
						$transhdrInfo[$row_transhdr[Transaction_Number]]	=   $row_transhdr;
						$transno_transhdr[]									=   $row_transhdr[Transaction_Number];
					}					
				}
				 
				//pre($transno_transhdr);	
				//pre($transaction_Reference_Number_cancel);
				
				if($transaction_Reference_Number_cancel != '') {
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
				}

				//pre($transno_transhdr);				
				//exit;
				$transno_transhdr			=	array_unique($transno_transhdr);
				$transno_Total			=	implode("','",$transno_transhdr);
				
				$query_receipthdr												=   "SELECT Customer_code,DSR_Code,KD_Code,SUM(Collection_Value) AS COL_VAL FROM transaction_hdr WHERE Date LIKE '$joinmonyear%' AND Transaction_type IN ('5') GROUP BY Customer_code";
				//echo $query_receipthdr;
				//exit;
				$res_receipthdr													=   mysql_query($query_receipthdr);
				$transno_receipthdr												=	array();
				while($row_receipthdr											=   mysql_fetch_assoc($res_receipthdr)) {
					$receipthdrInfo[$row_receipthdr[Customer_code]]				=   $row_receipthdr;
				}

				//pre($receipthdrInfo);
				//exit;
				$sel_baldue					=	"SELECT Customer_code,DSR_Code,KD_Code,SUM(Balance_Due_Value) AS BALANCE_DUE FROM transaction_hdr WHERE Transaction_Number IN ('".$transno_Total."') GROUP BY Customer_code";
				$res_baldue					=	mysql_query($sel_baldue) or die(mysql_error());
				$rowcnt_baldue				=	mysql_num_rows($res_baldue);
				if($rowcnt_baldue > 0) {
					while($row_baldue		=	mysql_fetch_array($res_baldue)) {
						$KD_Codeval			=	$row_baldue[KD_Code];
						$DSR_Codeval		=	$row_baldue[DSR_Code];
						$Customer_codeval	=	$row_baldue[Customer_code];
						$BALANCE_DUE		=	$row_baldue[BALANCE_DUE];
						
						$custcollvalue		=	$receipthdrInfo[$Customer_codeval][COL_VAL];

						if($custcollvalue	!= '') {
							$BALANCE_DUE	=	$BALANCE_DUE	-	$custcollvalue;
						}
						
						$sel_chkbaldue		=	"SELECT id FROM customer_outstanding WHERE monthval = '$trimprevmonth' AND yearval = '$findyear' AND customer_id = '$Customer_codeval' AND KD_Code = '$KD_Codeval'";
						$res_chkbaldue		=	mysql_query($sel_chkbaldue) or die(mysql_error());
						$rowcnt_chkbaldue	=	mysql_num_rows($res_chkbaldue);
						if($rowcnt_chkbaldue == 0) {
							if($BALANCE_DUE != 0) {
								$ins_cusout	=	"INSERT INTO customer_outstanding SET monthval = '$trimprevmonth', yearval = '$findyear',customer_id = '$Customer_codeval', KD_Code = '$KD_Codeval',total_due='$BALANCE_DUE',DateValue=NOW(),insertdatetime=NOW(),DSR_Code='$DSR_Codeval'";
								$res_cusout	=	mysql_query($ins_cusout) or die(mysql_error());
							}
						}		


					}
				}
			}

            if ($result == true)
                echo $index . "  success";
            else
                echo $index . "  fail";

            echo "<br>";
			//exit;
        }
		else if ($index == 2) {
            
			$lineproduct			=	$line;

			$lineproductarr			=	explode("','",$lineproduct);
			//pre($lineproductarr);
			$product_codeval		=	getproductval($lineproductarr[5],'Product_code','id');
			//echo $product_codeval;
			//exit;
			$lineproductarr[5]		=	$product_codeval;
			
			$lineagain				=	implode("','",$lineproductarr);
			//exit;
			
			$query_transline		=	"SELECT id from " . $tables[$index] . " WHERE Transaction_type='$lineproductarr[2]' AND Transaction_Number='$lineproductarr[3]' AND Transaction_Line_Number='$lineproductarr[4]' AND Product_code='$lineproductarr[5]'";
			//echo $query_transline;
			//exit;
			$result_transline		=	mysql_query($query_transline) or die(mysql_error());
			$rowcnt_transline		=	mysql_num_rows($result_transline);

			if($rowcnt_transline == 0) {

				$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "')";
				//echo $query;
				//exit;
				$result = mysql_query($query);
				
				$sel_cuscode				=	"SELECT Customer_code FROM transaction_hdr WHERE Transaction_Number = '$lineproductarr[3]'";
				$res_cuscode				=	mysql_query($sel_cuscode) or die(mysql_error());
				$rowcnt_cuscode				=	mysql_num_rows($res_cuscode);
				if($rowcnt_cuscode > 0) { 
					$row_cuscode			=	mysql_fetch_array($res_cuscode);
					$Customer_id			=	getdbval($row_cuscode[Customer_code],'id','customer_code','customer');
				}

				$routemonth					=	ltrim(date('m'),0);
				$routeyear					=	date('Y');
				$dayvalue					=	ltrim(date('d'),0);
				$daycolumn					=	"day".$dayvalue;
				$monthyear					=	$routemonth."-".$routeyear;

				$sel_routecode				=	"SELECT $daycolumn FROM routemonthplan WHERE DSR_Code = '$lineproductarr[1]' AND routemonth = '$routemonth' AND routeyear = '$routeyear'";
				$res_routecode				=	mysql_query($sel_routecode) or die(mysql_error());
				$rowcnt_routecode			=	mysql_num_rows($res_routecode);
				if($rowcnt_routecode > 0) { 
					$row_routecode			=	mysql_fetch_array($res_routecode);
					$routeid				=	getrouteval($row_routecode[$daycolumn],'id','route_code');
				}								

				$query_saleslist = "INSERT INTO ". $tables[14] ." SET KD_Code = '$KD_Code',DSR_Code = '$lineproductarr[1]',DateValue=NOW(),monthyear='$monthyear',route_id='$routeid',customer_id='$Customer_code',Product_code='$lineproductarr[5]',quantity='$lineproductarr[15]',rateval='$lineproductarr[16]',valueval='$lineproductarr[17]',transtype='$lineproductarr[2]',insertdatetime=NOW()";
				$res_saleslist = mysql_query($query_saleslist) or die(mysql_error());
				//exit;
				
				if ($result == true && $res_saleslist == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		else if ($index == 3) {

			$lineproduct		=	$line;

			$lineproductarr		=	explode("','",$lineproduct);

			$product_codeval	=	getproductval($lineproductarr[5],'Product_code','id');

			$lineproductarr[5]	=	$product_codeval;
			
			$lineagain			=	implode("','",$lineproductarr);

			$query_transbatch		=	"SELECT id from " . $tables[$index] . " WHERE Transaction_type='$lineproductarr[2]' AND Transaction_number='$lineproductarr[3]' AND Transaction_Line_Number='$lineproductarr[4]' AND Product_code='$lineproductarr[5]'";
			//exit;
			$result_transbatch		=	mysql_query($query_transbatch) or die(mysql_error());
			$rowcnt_transbatch		=	mysql_num_rows($result_transbatch);

			if($rowcnt_transbatch == 0) {

				$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "')";
				$result = mysql_query($query);

				if ($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		else if ($index == 4) {

			$lineproduct		=	$line;

			$lineproductarr		=	explode("','",$lineproduct);

			$product_codeval	=	getproductval($lineproductarr[4],'Product_code','id');

			$lineproductarr[4]	=	$product_codeval;
			
			$lineagain			=	implode("','",$lineproductarr);

			$query_returnline		=	"SELECT id from " . $tables[$index] . " WHERE Transaction_Number='$lineproductarr[2]' AND Transaction_Line_Number='$lineproductarr[3]' AND Product_code='$lineproductarr[4]'";
			//exit;
			$result_returnline		=	mysql_query($query_returnline) or die(mysql_error());
			$rowcnt_returnline		=	mysql_num_rows($result_returnline);

			if($rowcnt_returnline == 0) {
				$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "')";
				$result = mysql_query($query);

				if ($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		else if ($index == 5) {

			$lineproduct		=	$line;

			$lineproductarr		=	explode("','",$lineproduct);

			$product_codeval	=	getproductval($lineproductarr[4],'Product_code','id');

			$lineproductarr[4]	=	$product_codeval;
			
			$lineagain			=	implode("','",$lineproductarr);
			
			$query_returnbatch		=	"SELECT id from " . $tables[$index] . " WHERE Transaction_Number='$lineproductarr[2]' AND Transaction_line_Number='$lineproductarr[3]' AND Product_code='$lineproductarr[4]'";
			//exit;
			$result_returnbatch		=	mysql_query($query_returnbatch) or die(mysql_error());
			$rowcnt_returnbatch		=	mysql_num_rows($result_returnbatch);

			if($rowcnt_returnbatch == 0) {
			
				$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "')";
				$result = mysql_query($query);

				if ($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		else if ($index == 7) {

			$lineproduct		=	$line;

			$lineproductarr		=	explode("','",$lineproduct);

			$query_feed			=	"SELECT id from " . $tables[$index] . " WHERE Date='$lineproductarr[2]' AND Transaction_Number='$lineproductarr[3]'";
			//echo $query_feed;
			//exit;
			$result_feed		=	mysql_query($query_feed) or die(mysql_error());
			$rowcnt_feed		=	mysql_num_rows($result_feed);

			if($rowcnt_feed == 0) {
			
				$query = "insert into " . $tables[$index] . " values ('','" . $line . "')";
				$result = mysql_query($query);

				if ($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		else if ($index == 8) {

			$lineproduct		=	$line;

			$lineproductarr		=	explode("','",$lineproduct);

			$product_codeval	=	getproductval($lineproductarr[6],'Product_code','id');
			
			$date_stock			=	$lineproductarr[4];

			$lineproductarr[6]	=	$product_codeval;
			$lineproductarr[4]	=	$lineproductarr[4]." ".date('H:i:s');
			
			$lineagain			=	implode("','",$lineproductarr);

			$query_vehiclestock		=	"SELECT id from " . $tables[$index] . " WHERE KD_Code='$lineproductarr[0]' AND DSR_Code='$lineproductarr[1]' AND Device_Code='$lineproductarr[2]' AND Vehicle_Code='$lineproductarr[3]' AND Date LIKE '$date_stock%' AND Cycle_Start_Flag='$lineproductarr[5]' AND Product_Code='$lineproductarr[6]' AND Loaded_quantity='$lineproductarr[8]' AND Sold_quantity='$lineproductarr[9]' AND Return_quantity='$lineproductarr[10]' AND Stock_Quantity='$lineproductarr[11]'";
			//echo $query_vehiclestock;
			//exit;
			$result_vehiclestock		=	mysql_query($query_vehiclestock) or die(mysql_error());
			$rowcnt_vehiclestock		=	mysql_num_rows($result_vehiclestock);

			if($rowcnt_vehiclestock == 0) {
			
				$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "',NOW())";
				//echo $query;
				//exit;
				$result = mysql_query($query);

				if ($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		else if ($index == 9) {
			//echo $index."<br>";

			$lineproduct				=	$line;

			$lineproductarr				=	explode("','",$lineproduct);

			$product_codeval			=	getproductval($lineproductarr[5],'Product_code','id');
			
			$date_stockbatch			=	$lineproductarr[4];
			
			$lineproductarr[5]			=	$product_codeval;

			$lineproductarr[4]			=	$lineproductarr[4]." ".date('H:i:s');
			
			$lineagain					=	implode("','",$lineproductarr);

			$query_vehiclebatch			=	"SELECT id from " . $tables[$index] . " WHERE KD_Code='$lineproductarr[0]' AND DSR_Code='$lineproductarr[1]' AND Device_Code='$lineproductarr[2]' AND Vehicle_Code='$lineproductarr[3]' AND Date LIKE '$date_stockbatch%' AND Product_code='$lineproductarr[5]' AND Batch='$lineproductarr[7]' AND Expiry='$lineproductarr[8]' AND Stock_Quantity='$lineproductarr[9]'";
			//echo $query_vehiclestock;
			//exit;
			$result_vehiclebatch		=	mysql_query($query_vehiclebatch) or die(mysql_error());
			$rowcnt_vehiclebatch		=	mysql_num_rows($result_vehiclebatch);

			if($rowcnt_vehiclebatch == 0) {

				$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "')";
				$result = mysql_query($query);

				if ($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		else if ($index == 10) {

			$lineproduct				=	$line;

			$lineproductarr				=	explode("','",$lineproduct);

			$date_salescol				=	$lineproductarr[4];
	
			$lineproductarr[4]			=	$lineproductarr[4]." ".date('H:i:s');
			
			$lineagain					=	implode("','",$lineproductarr);

			$query_salescol				=	"SELECT id from " . $tables[$index] . " WHERE KD_Code='$lineproductarr[0]' AND DSR_Code='$lineproductarr[1]' AND device_code='$lineproductarr[2]' AND Vehicle_Code='$lineproductarr[3]' AND Date LIKE '$date_salescol%' AND cycle_start_flag='$lineproductarr[5]' AND total_sale_value='$lineproductarr[7]' AND total_collection_value='$lineproductarr[8]'";
			//echo $query_salescol;
			//exit;
			$result_salescol			=	mysql_query($query_salescol) or die(mysql_error());
			$rowcnt_salescol			=	mysql_num_rows($result_salescol);

			if($rowcnt_salescol == 0) {

				$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "',NOW())";
				//exit;
				$result = mysql_query($query);

				if ($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		else if ($index == 11) {

			$lineproduct		=	$line;
			$linenewarr			=	array();

			$lineproductarr		=	explode("','",$lineproduct);

			$linenewarr[0]		=	$lineproductarr[0];		//KD CODE 
			$linenewarr[1]		=	$lineproductarr[1];		//DSR CODE
			$linenewarr[2]		=	$lineproductarr[2];		//Device CODE
			$linenewarr[3]		=	$lineproductarr[3];		//Date
			$linenewarr[4]		=	$lineproductarr[4];		//Visit Count
			$linenewarr[5]		=	$lineproductarr[5];		//Invoice Count
			$linenewarr[8]		=	$lineproductarr[6];		//Invoice Line Count
			$linenewarr[9]		=	$lineproductarr[7];		//Currency
			$linenewarr[10]		=	$lineproductarr[8];		//Total Sale Value
			$linenewarr[11]		=	$lineproductarr[9];		//Drop Size Value
			$linenewarr[12]		=	$lineproductarr[10];	//Basket Size Value
			$linenewarr[13]		=	$lineproductarr[11];	//Target Sales
			$linenewarr[14]		=	$lineproductarr[12];	//Achievement Percent
			$linenewarr[7]		=	$lineproductarr[13];	//Productivity Visit
			$linenewarr[6]		=	$lineproductarr[14];	//Effectivity Visit
			$linenewarr[15]		=	$lineproductarr[15];	//Productivity Incentive
			$linenewarr[16]		=	$lineproductarr[16];	//Effective Incentive
			
			$lineagain			=	implode("','",$linenewarr);

			$linecolumns		=	"(`KD_Code`,`DSR_Code`,`Device_Code`,`Date`,`visit_Count`,`Invoice_Count`,`Invoice_Line_Count`,`Currency`,`Total_Sale_Value`,`Drop_Size_Value`,`Basket_Size_Value`,`targetSales`,`achievementPercent`,`productive_count`,`effective_count`,`prodIncentive`,`effIncentive`)";
			
			$query_metrics				=	"SELECT id from " . $tables[$index] . " WHERE KD_Code='$linenewarr[0]' AND DSR_Code='$linenewarr[1]' AND Device_Code='$linenewarr[2]' AND Date='$linenewarr[3]' AND visit_Count = '$linenewarr[4]' AND Invoice_Count='$linenewarr[5]' AND Invoice_Line_Count='$linenewarr[8]' AND Total_Sale_Value='$linenewarr[10]' AND Drop_Size_Value='$linenewarr[11]' AND Basket_Size_Value='$linenewarr[12]' AND targetSales='$linenewarr[13]' AND achievementPercent='$linenewarr[14]' AND effective_count='$linenewarr[6]' AND productive_count='$linenewarr[7]' AND prodIncentive='$linenewarr[15]' AND effIncentive='$linenewarr[16]'";
			//echo $query_metrics;
			//exit;
			$result_metrics			=	mysql_query($query_metrics) or die(mysql_error());
			$rowcnt_metrics			=	mysql_num_rows($result_metrics);

			if($rowcnt_metrics == 0) {

				$query = "insert into " . $tables[$index] ."  ". $linecolumns . "  values ('" . $lineagain . "')";
				//exit;
				$result = mysql_query($query);

				if ($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		else if ($index == 12) {			

			$lineproduct				=	$line;
	
			$lineproductarr				=	explode("','",$lineproduct);
			
			$date_cusvisit				=	$lineproductarr[2];
	
			$lineproductarr[2]			=	$lineproductarr[2]." ".date('H:i:s');

			//$lineproductarr[2]		=	date('Y-m-d H:i:s');
			
			$lineagain					=	implode("','",$lineproductarr);

			$query_cusvisit				=	"SELECT id from " . $tables[$index] . " WHERE KD_Code='$lineproductarr[0]' AND DSR_Code='$lineproductarr[1]' AND Date LIKE '$date_cusvisit%' AND Sequence_Number = '$lineproductarr[3]' AND Customer_Code='$lineproductarr[4]' AND Check_In_time='$lineproductarr[5]' AND Checkin_GPS='$lineproductarr[6]' AND Check_Out_time='$lineproductarr[7]' AND Checkout_GPS='$lineproductarr[8]' AND check_out_id='$lineproductarr[9]'";
			//echo $query_cusvisit;
			//exit;
			$result_cusvisit			=	mysql_query($query_cusvisit) or die(mysql_error());
			$rowcnt_cusvisit			=	mysql_num_rows($result_cusvisit);

			if($rowcnt_cusvisit == 0) {

				$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "')";
				//echo $query;
				//exit;
				$result = mysql_query($query);

				if ($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		/*else if ($index != 6) {
            $query = "insert into " . $tables[$index] . " values ('','" . $line . "')";
            $result = mysql_query($query);

            if ($result == true)
                echo $index . "  success";
            else
                echo $index . "  fail";

            echo "<br>";
        }*/
        else {
			//echo $line."<br>";
			$lineproduct				=	$line;
	
			$lineproductarr				=	explode("','",$lineproduct);
			
			$query_customer				=	"SELECT id from customer WHERE KD_Code='$lineproductarr[0]' AND DSR_Code='$lineproductarr[1]' AND customer_code =   '$lineproductarr[2]' AND Customer_Name = '$lineproductarr[3]' AND AddressLine1='$lineproductarr[4]' AND AddressLine2='$lineproductarr[5]' AND AddressLine3='$lineproductarr[6]' AND PostCode='$lineproductarr[7]' AND contactperson='$lineproductarr[8]' AND contactnumber='$lineproductarr[9]' AND route='$lineproductarr[10]' AND customer_type='$lineproductarr[11]'";
			//echo $query_customer;
			//exit;
			$result_customer			=	mysql_query($query_customer) or die(mysql_error());
			$rowcnt_customer			=	mysql_num_rows($result_customer);

			if($rowcnt_customer == 0) {

				$query = "INSERT INTO customer (KD_Code,DSR_Code,customer_code,Customer_Name,AddressLine1,AddressLine2,AddressLine3,PostCode,contactperson,contactnumber,route,customer_type) values('". $line ."')";
				$result = mysql_query($query);
				if($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";
			}
        }
		$line				=		'';
		$lineagain			=		'';	
	
		$linecountval++;
	}

    fclose($file);
}
?>