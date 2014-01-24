<?php
//ini_set("display_errors",true);
//error_reporting(E_ALL & ~E_NOTICE);

$cronlogpath				=	dirname(__FILE__);
//echo $cronlogpath."<br>";
$includefilepath			=	dirname(dirname(__FILE__));
//echo $includefilepath."<br>";
require_once $includefilepath."/include/config.php";
require_once $includefilepath."/include/ajax_pagination.php";

$KD_Code = getKDCode();

//echo $cronlogpath			=	dirname($_SERVER['SCRIPT_FILENAME']);
//echo $uploadfolderpath	=	dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])));
//$uploadfolderpath			=	dirname(dirname(dirname(__FILE__)));
$uploadfolderpath			=	dirname(dirname(dirname(dirname(__FILE__))));
//exit;
//echo $uploadfolderpath."<br>";

//pre($_SERVER);

//exit;

//$res_checkDeviceCode	= "insert into test (id) values ('5')";
//mysql_query($res_checkDeviceCode) or die(mysql_error());

$current_timestamp		=	date('Y-m-d H:i:s');
$current_datealone		=	date('Y-m-d');

if(!file_exists($cronlogpath."/".$current_datealone."_croncheck.txt")) {
//echo "good";
//exit;
	$filemode			=	fopen($cronlogpath."/".$current_datealone."_croncheck.txt","a+");
	chmod($cronlogpath."/".$current_datealone."_croncheck.txt", 0777);
	fwrite($filemode,"\n".$current_timestamp."_".$KD_Code);
} else {
//echo "bad";
//exit;
	$filemode			=	fopen($cronlogpath."/".$current_datealone."_croncheck.txt","a+");
	fwrite($filemode,"\n".$current_timestamp."_".$KD_Code);
}
fclose($filemode);

//exit;
//ini_set("display_errors",true);
//error_reporting(E_ALL);
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

// TO GET THE DEVICE CODE IF THE DEVICE IS ONLINE STARTS HERE

//$directory = "../../Upload/";		//WHEN LIVE COMMENT THIS
//$directory = "../../../Upload/";  //WHEN LIVE UNCOMMENT THIS 

//$uploadedDirectory			=	"../../UploadedFiles/";						//WHEN LIVE COMMENT THIS
//$uploadedDirectory = "../../../UploadedFiles/";					//WHEN LIVE UNCOMMENT THIS 



$query = "select device_code from device_registration";
$result = mysql_query($query);
while($data = mysql_fetch_array($result)){
$devicecode = $data['device_code'];

$curdate = date('dmY'); 

echo $zipfile = $KD_Code."_".$devicecode."_".Sign."_".$curdate .".zip";


$zipdirectory = "../../../zipfilesbck/";


 $filepath = '../../../signs/'; 
 
 $zip = new ZipArchive;
  $res = $zip->open($filepath.$zipfile);
  if ($res === TRUE) {
         $zip->extractTo('../../../signs/');
		 $zip->close();
		 copy($filepath.$zipfile, $zipdirectory.$zipfile);
		 unlink($filepath.$zipfile);
         echo 'ok';
    }
 } 


$directory					=	$uploadfolderpath."/Upload/";				//WHEN LIVE COMMENT THIS
$uploadedDirectory			=	$uploadfolderpath."/UploadedFiles/";		//WHEN LIVE COMMENT THIS

if(!is_dir($uploadedDirectory)) {
	if(mkdir($uploadedDirectory)) {	
	}
}

//$fileextension				=	date('d-m-y').".txt";
//$filename_withdate			=	"_up_".$fileextension;

$allfiles = scandir($directory);
//pre($allfiles);
//exit;
$individualFilesArr		=	'';
foreach($allfiles AS $individualFiles) {
	//echo $individualFiles;
	if($individualFiles === "." || $individualFiles === "..") {
		//echo $individualFiles;
	} else {
		$individualFilesArr		=	explode("_",$individualFiles);
		//echo $individualFiles."<br>";
		//echo $KD_Code ."==". $individualFilesArr[0]."<br>";

		if($KD_Code == $individualFilesArr[0]){
			$query_checkDeviceCode									=   "SELECT id FROM device_master WHERE device_code = '$individualFilesArr[1]'";
			//echo $query_checkDeviceCode;
			//exit;
			$res_checkDeviceCode									=   mysql_query($query_checkDeviceCode);
			$rowcnt_checkDeviceCode									=   mysql_num_rows($res_checkDeviceCode);

			if($rowcnt_checkDeviceCode > 0 ){ 
				$arrayFileNames[]		=	$individualFiles;
			}
		}
	}
	$individualFilesArr		=	'';
}

//pre($arrayFileNames);
//exit;

//TO GET THE DEVICE CODE IF THE DEVICE IS ONLINE ENDS HERE

//echo $filename;
//exit;

//$file = @fopen("Upload.txt", "r");
//$datavalue		=	file("Upload.txt");

if(count($arrayFileNames) == 0) {
	echo "nofile";
	exit;
}

foreach($arrayFileNames AS $filename) {
	$file			=	@fopen($directory.$filename, "r");
	$datavalue		=	file($directory.$filename);
	$datavalue_track=	file($directory.$filename);
	$linecount		=	count($datavalue);
	$linecountval	=	0;

	unset($new_Customer_Code_Created_Arr);
	$new_Customer_Code_Created_Arr			=	'';
	unset($arrayforsorting);
	$arrayforsorting						=	'';
	//pre($datavalue);
	//exit;

	// FOR NEW CUSTOMER STARTS HERE
	foreach($datavalue AS $dataeachval) {
		//echo $dataeachval;
		if($dataeachval[0] == '6') {
			//echo $line."<br>";
			
			$line		=	$dataeachval;
			$pos		=	strpos($line, "^");
			$index		=	substr($line, 0, $pos);

			if($linecount	==	$linecountval) {
				$line = substr($line, $pos + 1, -1);
			} else {
				$line = substr($line, $pos + 1, -3);
			}

			//$index	=	"27";
			
			//echo $line."<br>";
			
			$line = str_replace("^", "','", $line);
			//exit;

			$lineproduct				=	$line;
	
			$lineproductarr				=	explode("','",$lineproduct);

			$dev_cus_code				=	$lineproductarr[2];

			$DSR_name					=	getdbval($lineproductarr[1],'DSRName','DSR_Code','dsr');

			$lineproductarr[12]			=	$DSR_name;

			$query_city					=	"SELECT City,State,province,location,lga from customer WHERE DSR_Code='$lineproductarr[1]' AND route='$lineproductarr[10]'";
			//echo $query_customer;
			//exit;
			$result_city				=	mysql_query($query_city) or die(mysql_error());
			$rowcnt_city				=	mysql_num_rows($result_city);

			if($rowcnt_city		> 0) {
				$row_city				=	mysql_fetch_object($result_city);
				$city_name				=	$row_city->City;
				$state_name				=	$row_city->State;
				$province_name			=	$row_city->province;
				$location_name			=	$row_city->location;
				$lga_name				=	$row_city->lga;
				$lineproductarr[13]		=	$city_name;
				$lineproductarr[14]		=	$state_name;
				$lineproductarr[15]		=	$province_name;
				$lineproductarr[16]		=	$location_name;
				$lineproductarr[17]		=	$lga_name;
				$lineproductarr[18]		=	getdbval($KD_Code,'KD_Name','KD_Code','kd');

			} else {
				$lineproductarr[13]		=	'';
				$lineproductarr[14]		=	'';
				$lineproductarr[15]		=	'';
				$lineproductarr[16]		=	'';
				$lineproductarr[17]		=	'';
				$lineproductarr[18]		=	getdbval($KD_Code,'KD_Name','KD_Code','kd');
			}				

			if($new_Customer_Code_Created_Arr[$lineproductarr[2]] == '' || !isset($new_Customer_Code_Created_Arr[$lineproductarr[2]])) {

				$query_customer				=	"SELECT id from customer WHERE Customer_Name = '$lineproductarr[3]' AND contactnumber='$lineproductarr[9]'";
				//echo $query_customer;
				//exit;

				$result_customer			=	mysql_query($query_customer) or die(mysql_error());
				$rowcnt_customer			=	mysql_num_rows($result_customer);
				
				$query_oldcus				=	"SELECT customer_code,sequence_number FROM customer ORDER BY id DESC";
				$res_oldcus					=	mysql_query($query_oldcus) or die(mysql_error());
				$rowcnt_oldcus				=	mysql_num_rows($res_oldcus);
				//$rowcnt_oldcus			=	0; // comment if live
				if($rowcnt_oldcus > 0) {
					$row_oldcus				=	mysql_fetch_array($res_oldcus);
					$Old_cusnum				=	$row_oldcus['customer_code'];
					
					$query_oldseq				=	"SELECT max(sequence_number) AS SEQNO FROM customer WHERE route = '$lineproductarr[10]' GROUP BY route";
					$res_oldseq					=	mysql_query($query_oldseq) or die(mysql_error());
					$rowcnt_oldseq				=	mysql_num_rows($res_oldseq);
					//$rowcnt_oldseq			=	0; // comment if live
					if($rowcnt_oldseq > 0) {
						$row_oldseq				=	mysql_fetch_array($res_oldseq);
						$Old_seqnum				=	$row_oldseq['SEQNO'];
					} else {
						$Old_seqnum				=	"00";
					}
					
					$getcusno				=	abs(str_replace("CUS",'',strstr($Old_cusnum,"CUS")));
					$getcusno++;

					$getseqno				=	abs($Old_seqnum);
					$getseqno++;

					//echo $getcusno;
					//exit;
					if($getcusno < 10) {
						$createdcode		=	"00".$getcusno;
					} else if($getcusno < 100) {
						$createdcode		=	"0".$getcusno;
					} else {
						$createdcode		=	$getcusno;
					}
					
					if($getseqno < 10) {
						$createdseqcode		=	"0".$getseqno;
					} else if($getseqno > 99) {
						$createdseqcode		=	"01";
					} else {
						$createdseqcode		=	$getseqno;
					}

					$CustomerCode_num		=	"CUS".$createdcode;
					$lineproductarr[2]		=	$CustomerCode_num;
					$SeqCode_num			=	$createdseqcode;
					$lineproductarr[19]		=	$SeqCode_num;
				} else {
					$CustomerCode_num		=	"CUS001";
					$lineproductarr[2]		=	$CustomerCode_num;
					$SeqCode_num			=	"01";
					$lineproductarr[19]		=	$SeqCode_num;
				}								
				
				//$lineproductarr[2]	=	'CUS';

				if($lineproductarr[2] == 'CUS') {
					echo 'fail6';
					exit;
				}
				$new_Customer_Code_Created_Arr[$dev_cus_code]	=	$lineproductarr[2];
				
				//pre($lineproductarr);
				//exit;
				$line						=	implode("','",$lineproductarr);
				
				//echo $rowcnt_customer."HALL";
				//exit;

				if($rowcnt_customer == 0) {
					
					//echo $rowcnt_customer."HALL";
					//exit;

					$todaysdate				=	date('Y-m-d');			
					$query_newcus = "INSERT INTO new_cudtomer_details (device_customer_code,Date,new_customer_code) values('$dev_cus_code','$todaysdate','$CustomerCode_num')";
					$result_newcus = mysql_query($query_newcus) or die(mysql_error());

					$query = "INSERT INTO customer (KD_Code,DSR_Code,customer_code,Customer_Name,AddressLine1,AddressLine2,AddressLine3,PostCode,contactperson,contactnumber,route,customer_type,DSRName,City,State,province,location,lga,KD_Name,sequence_number) values('". $line ."')";
					$result = mysql_query($query) or die(mysql_error());

					if($result == true && $result_newcus == true)
						echo $index . "  success";
					else
						echo $index . "  fail";
					echo "<br>";
				}
				//echo $rowcnt_customer."LAMP";
				//exit;

			} else {
				$lineproductarr[2]			=	$new_Customer_Code_Created_Arr[$lineproductarr[2]];

				$query_oldseq				=	"SELECT max(sequence_number) AS SEQNO FROM customer WHERE route = '$lineproductarr[10]' GROUP BY route";
				//exit;
				$res_oldseq					=	mysql_query($query_oldseq) or die(mysql_error());
				$rowcnt_oldseq				=	mysql_num_rows($res_oldseq);
				//$rowcnt_oldseq			=	0; // comment if live
				if($rowcnt_oldseq > 0) {
					$row_oldseq				=	mysql_fetch_array($res_oldseq);
					$Old_seqnum				=	$row_oldseq['SEQNO'];
				} else {
					$Old_seqnum				=	"00";
				}
				
				$getseqno					=	abs($Old_seqnum);
				$getseqno++;

				if($getseqno < 10) {
					$createdseqcode			=	"0".$getseqno;
				} else if($getseqno > 99) {
					$createdseqcode			=	"01";
				} else {
					$createdseqcode			=	$getseqno;
				}
				
				if($lineproductarr[2] == 'CUS') {
					echo 'fail6';
					exit;
				}

				$query = "UPDATE customer SET Customer_Name='$lineproductarr[3]',AddressLine1='$lineproductarr[4]',AddressLine2='$lineproductarr[5]',AddressLine3='$lineproductarr[6]',PostCode='$lineproductarr[7]',contactperson='$lineproductarr[8]',contactnumber='$lineproductarr[9]',route='$lineproductarr[10]',customer_type='$lineproductarr[11]',City='$lineproductarr[13]',State='$lineproductarr[14]',province='$lineproductarr[15]',location='$lineproductarr[16]',lga='$lineproductarr[17]',sequence_number='$createdseqcode' WHERE customer_code = '$lineproductarr[2]'";
				//echo $query;
				//exit;
				$result = mysql_query($query) or die(mysql_error());
			}
		}
	}
	// FOR NEW CUSTOMER ENDS HERE
	
	//exit;
	
	foreach($datavalue_track AS $datasorttotimekey=>$datasorttotime) {
		
		if($datasorttotime[0].$datasorttotime[1] == '12') {
		
			//echo $datasorttotimekey."<br>";

			$line		=	$datasorttotime;
			$pos		=	strpos($line, "^");
			$index		=	substr($line, 0, $pos);

			if($linecount	==	$linecountval) {
				$line	=	substr($line, $pos + 1, -1);
			} else {
				$line	=	substr($line, $pos + 1, -3);
			}

			//$index	=	"27";
			
			//echo $line."<br>";
			
			$line		=	str_replace("^", "','", $line);
			//echo $line;
			//exit;

			$lineproduct	=	$line;
		
			$lineproductarr		=	explode("','",$lineproduct);

			$strtotimeforsort	=	strtotime($lineproductarr[2]." ".$lineproductarr[5]);
			$lineproductarr[10]	=	$strtotimeforsort;
			$arrayforsorting[$strtotimeforsort]	=	$lineproductarr;
			//pre($lineproductarr);
			//exit;
		}
	}
	//pre($arrayforsorting);

	if(count($arrayforsorting) > 0) { 
		$orderbycolumns     =   '10';
		$orderbysorting     =   'ASC';

		if($orderbysorting == 'DESC') {
			$dir        =   'arsort';               
		} else {
			$dir        =   'asort';   
		}
		$arrayforsorting	=	subval_sort($arrayforsorting,$orderbycolumns,$dir);
		//pre($arrayforsorting);
		//exit;
		//FOR CUSTOMER VISIT TRACKING STARTS HERE

		foreach($arrayforsorting AS $dataeachvaltrack) {
			//pre($dataeachvaltrack);
			//exit;

			//if($dataeachvaltrack[0].$dataeachvaltrack[1] == '12') {
				//echo $line."<br>";
				
			
			/*$line		=	$dataeachvaltrack;
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
			echo $line;
			exit;

			$lineproduct				=	$line;
		
			$lineproductarr				=	explode("','",$lineproduct);*/
			
			$date_cusvisit				=	$dataeachvaltrack[2];
	
			$dataeachvaltrack[2]		=	$dataeachvaltrack[2]." ".date('H:i:s');

			//$lineproductarr[2]		=	date('Y-m-d H:i:s');								
			
			if($new_Customer_Code_Created_Arr[$dataeachvaltrack[4]] == '' || !isset($new_Customer_Code_Created_Arr[$dataeachvaltrack[4]])) {
				

				// NEW CUSTOMER CREATION STARTS HERE FOR CUSTOMER VISIT TRACKING HDR STARTS HERE

				$query_customer				=	"SELECT id from customer WHERE customer_code =   '$dataeachvaltrack[4]'";
				//echo $query_customer;
				//exit;

				$result_customer			=	mysql_query($query_customer) or die(mysql_error());
				$rowcnt_customer			=	mysql_num_rows($result_customer);
				
				if($rowcnt_customer == 0) {			
					
					$dev_cus_code				=	$dataeachvaltrack[4];
					$lineproductarrCus[0]		=	$dataeachvaltrack[0];
					$lineproductarrCus[1]		=	$dataeachvaltrack[1];

					$query_oldcus				=	"SELECT customer_code FROM customer ORDER BY id DESC";
					$res_oldcus					=	mysql_query($query_oldcus) or die(mysql_error());
					$rowcnt_oldcus				=	mysql_num_rows($res_oldcus);
					//$rowcnt_oldcus			=	0; // comment if live
					if($rowcnt_oldcus > 0) {
						$row_oldcus				=	mysql_fetch_array($res_oldcus);
						$Old_cusnum				=	$row_oldcus['customer_code'];

						$getcusno				=	abs(str_replace("CUS",'',strstr($Old_cusnum,"CUS")));
						$getcusno++;
						//echo $getcusno;
						//exit;
						if($getcusno < 10) {
							$createdcode		=	"00".$getcusno;
						} else if($getcusno < 100) {
							$createdcode		=	"0".$getcusno;
						} else {
							$createdcode		=	$getcusno;
						}

						$CustomerCode_num		=	"CUS".$createdcode;
						$lineproductarr[4]	=	$CustomerCode_num;
					} else {
						$CustomerCode_num		=	"CUS001";
						$dataeachvaltrack[4]	=	$CustomerCode_num;
					}
				} else {
					$dataeachvaltrack[4]		=	$dataeachvaltrack[4];
				}

				if($dataeachvaltrack[4] == 'CUS') {
					echo 'fail12';
					exit;
				}
				// NEW CUSTOMER CREATION STARTS HERE FOR CUSTOMER VISIT TRACKING ENDS HERE


				$query_cusvisit				=	"SELECT id from " . $tables[$index] . " WHERE KD_Code='$dataeachvaltrack[0]' AND DSR_Code='$dataeachvaltrack[1]' AND Date LIKE '$date_cusvisit%' AND Sequence_Number = '$dataeachvaltrack[3]' AND Customer_Code='$dataeachvaltrack[4]' AND Check_In_time='$dataeachvaltrack[5]' AND Checkin_GPS='$dataeachvaltrack[6]' AND Check_Out_time='$dataeachvaltrack[7]' AND Checkout_GPS='$dataeachvaltrack[8]' AND check_out_id='$dataeachvaltrack[9]'";
				//echo $query_cusvisit;
				//exit;
				$result_cusvisit				=	mysql_query($query_cusvisit) or die(mysql_error());
				$rowcnt_cusvisit				=	mysql_num_rows($result_cusvisit);

				if($rowcnt_cusvisit == 0) {
					unset($dataeachvaltrack[10]);
					$lineagain					=	implode("','",$dataeachvaltrack);

					$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "',NOW())";
					//echo $query;
					//exit;
					$result = mysql_query($query) or die(mysql_error());

					if ($result == true)
						echo $index . "  success";
					else
						echo $index . "  fail";

					echo "<br>";
				}
			} else {
				$dataeachvaltrack[4]			=	$new_Customer_Code_Created_Arr[$dataeachvaltrack[4]];
				unset($dataeachvaltrack[10]);
				$lineagain						=	implode("','",$dataeachvaltrack);
				$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "',NOW())";
				//echo $query;
				//exit;
				$result							=	mysql_query($query) or die(mysql_error());

				if ($result == true)
					echo $index . "  success";
				else
					echo $index . "  fail";

				echo "<br>";

			}
			// }
		}

	}
	// FOR CUSTOMER VISIT TRACKING ENDS HERE

	//exit;







	//exit;

	if ($file) {
		while (($line = fgets($file, 4096)) !== false) {

			//pre($line);
			//exit;
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

				$cycleopenbal		=	0;
				$dayOpenBal			=	0;
				$dayBalDue			=	0;
				$lineproduct		=	$line;

				$lineproductarr		=	explode("','",$lineproduct);
				$dev_cus_code		=	$lineproductarr[6];
				//pre($lineproductarr);
				//exit;
				
				$query_transhdr = "SELECT id from " . $tables[$index] . " WHERE Date='$lineproductarr[3]' AND Time='$lineproductarr[4]' AND Transaction_type='$lineproductarr[7]' AND Transaction_Number='$lineproductarr[8]'";
				//exit;
				$result_transhdr = mysql_query($query_transhdr) or die(mysql_error());
				$rowcnt_transhdr = mysql_num_rows($result_transhdr);

				if($rowcnt_transhdr == 0){
									
					// NEW CUSTOMER CREATION STARTS HERE FOR TRANSACTION HDR STARTS HERE
					
					if($new_Customer_Code_Created_Arr[$lineproductarr[6]] == '' || !isset($new_Customer_Code_Created_Arr[$lineproductarr[6]])) {
						$query_customer				=	"SELECT id from customer WHERE customer_code =   '$lineproductarr[6]'";
						//echo $query_customer;
						//exit;

						$result_customer			=	mysql_query($query_customer) or die(mysql_error());
						$rowcnt_customer			=	mysql_num_rows($result_customer);
						
						if($rowcnt_customer == 0) {
														
							$lineproductarrCus[0]		=	$lineproductarr[0];
							$lineproductarrCus[1]		=	$lineproductarr[1];

							$query_oldcus				=	"SELECT customer_code,sequence_number FROM customer ORDER BY id DESC";
							$res_oldcus					=	mysql_query($query_oldcus) or die(mysql_error());
							$rowcnt_oldcus				=	mysql_num_rows($res_oldcus);
							//$rowcnt_oldcus			=	0; // comment if live
							if($rowcnt_oldcus > 0) {
								$row_oldcus				=	mysql_fetch_array($res_oldcus);
								$Old_cusnum				=	$row_oldcus['customer_code'];
								//$Old_seqnum			=	$row_oldcus['sequence_number'];

								$getcusno				=	abs(str_replace("CUS",'',strstr($Old_cusnum,"CUS")));
								$getcusno++;

								//echo $getcusno;
								//exit;
								if($getcusno < 10) {
									$createdcode		=	"00".$getcusno;
								} else if($getcusno < 100) {
									$createdcode		=	"0".$getcusno;
								} else {
									$createdcode		=	$getcusno;
								}

								if($getseqno < 10) {
									$createdseqcode		=	"0".$getseqno;
								} else if($getseqno > 99) {
									$createdseqcode		=	"01";
								}

								$CustomerCode_num		=	"CUS".$createdcode;
								$lineproductarrCus[2]	=	$CustomerCode_num;
								$SeqCode_num			=	$createdseqcode;
								//$lineproductarrCus[19]	=	$SeqCode_num;
							} else {
								$CustomerCode_num		=	"CUS001";
								$lineproductarrCus[2]	=	$CustomerCode_num;
								$SeqCode_num			=	"01";
								//$lineproductarrCus[19]	=	$SeqCode_num;
							}
							
							if($lineproductarrCus[2] == 'CUS') {
								echo 'fail1';
								exit;
							}

							$lineproductarrCus[3]		=	'';
							$lineproductarrCus[4]		=	'';
							$lineproductarrCus[5]		=	'';
							$lineproductarrCus[6]		=	'';
							$lineproductarrCus[7]		=	'';
							$lineproductarrCus[8]		=	'';
							$lineproductarrCus[9]		=	'';
							$lineproductarrCus[10]		=	'';
							$lineproductarrCus[11]		=	'';

							$DSR_name					=	getdbval($lineproductarr[1],'DSRName','DSR_Code','dsr');

							$lineproductarrCus[12]		=	$DSR_name;

							$lineproductarrCus[13]		=	'';
							$lineproductarrCus[14]		=	'';
							$lineproductarrCus[15]		=	'';
							$lineproductarrCus[16]		=	'';
							$lineproductarrCus[17]		=	'';
							$lineproductarrCus[18]		=	getdbval($KD_Code,'KD_Name','KD_Code','kd');
							//$lineproductarrCus[19]		=	$SeqCode_num;
							
							//pre($lineproductarrCus);
							//exit;

							$lineCus					=	implode("','",$lineproductarrCus);

							$todaysdate					=	date('Y-m-d');			
							$query_newcus				=	"INSERT INTO new_cudtomer_details (device_customer_code,Date,new_customer_code) values('$dev_cus_code','$todaysdate','$CustomerCode_num')";
							$result_newcus				=	mysql_query($query_newcus) or die(mysql_error());

							//$query						=	"INSERT INTO customer (KD_Code,DSR_Code,customer_code,Customer_Name,AddressLine1,AddressLine2,AddressLine3,PostCode,contactperson,contactnumber,route,customer_type,DSRName,City,State,province,location,lga,KD_Name,sequence_number) values('". $lineCus ."')";
							$query						=	"INSERT INTO customer (KD_Code,DSR_Code,customer_code,Customer_Name,AddressLine1,AddressLine2,AddressLine3,PostCode,contactperson,contactnumber,route,customer_type,DSRName,City,State,province,location,lga,KD_Name) values('". $lineCus."')"; 
							$result						=	mysql_query($query) or die(mysql_error());

							$new_Customer_Code_Created_Arr[$dev_cus_code]	=	$CustomerCode_num;
							$lineproductarr[6]								=	$new_Customer_Code_Created_Arr[$dev_cus_code];


							if($result == true && $result_newcus == true)
								echo $index . "  success";
							else
								echo $index . "  fail";
							echo "<br>";
							$lineproductarr[6]		=	$new_Customer_Code_Created_Arr[$dev_cus_code];
						} else {
							$lineproductarr[6]		=	$lineproductarr[6];
						}
						// NEW CUSTOMER CREATION STARTS HERE FOR TRANSACTION HDR ENDS HERE
					
						$line					=	implode("','",$lineproductarr);
						
						$query		=	"insert into " . $tables[$index] . " values ('','" . $line . "',NOW())";					
						$result		=	mysql_query($query) or die(mysql_error());
						//echo $query;
						//exit;				
				
					} else {
						$lineproductarr[6]		=	$new_Customer_Code_Created_Arr[$dev_cus_code];
						$line					=	implode("','",$lineproductarr);
						
						$query		=	"insert into " . $tables[$index] . " values ('','" . $line . "',NOW())";					
						$result		=	mysql_query($query) or die(mysql_error());
					}
				
				
				}
				

				// FOR CUSTOMER BALANCE DUE ADD STARTS HERE

				if($lineproductarr[7] == '2' || $lineproductarr[7] == '3' || $lineproductarr[7] == '5') {

					$todaydateval						=	date('Y-m-d');
					
					$query_cyclestart					=	"SELECT id from cycle_assignment WHERE flag_status='1' AND end_flag_status = '0' AND Date LIKE '$lineproductarr[3]%' ORDER BY id DESC";
					//exit;
					$res_cyclestart						=	mysql_query($query_cyclestart) or die(mysql_error());

					if(mysql_num_rows($res_cyclestart) > 0) {
						$query_cycleopenbal				=	"SELECT dayBalDue from customerbaldownload WHERE cycleStart='0' AND customerCode = '$lineproductarr[6]' ORDER BY id DESC";
						//exit;
						$res_cycleopenbal				=	mysql_query($query_cycleopenbal) or die(mysql_error());
						
						if(mysql_num_rows($res_cycleopenbal) > 0) {
							$row_cycleopenbal			=	mysql_fetch_array($res_cycleopenbal);
							$cycleopenbal				=	$row_cycleopenbal[dayBalDue];
						}

						$dayOpenBal						=	0;
						$cyclestart						=	'1';
					} else {
						$query_cycleopenbal				=	"SELECT cycleOpenBalDue from customerbaldownload WHERE cycleStart='1' AND customerCode = '$lineproductarr[6]' ORDER BY id DESC";
						//exit;
						$res_cycleopenbal				=	mysql_query($query_cycleopenbal) or die(mysql_error());
						
						if(mysql_num_rows($res_cycleopenbal) > 0) {
							$row_cycleopenbal			=	mysql_fetch_array($res_cycleopenbal);
							$cycleopenbal				=	$row_cycleopenbal[cycleOpenBalDue];
						}

						$query_dayOpenBal		=	"SELECT cycleOpenBalDue,dayBalDue from customerbaldownload WHERE customerCode = '$lineproductarr[6]' ORDER BY id DESC";
						//exit;
						$res_dayOpenBal			=	mysql_query($query_dayOpenBal) or die(mysql_error());

						if(mysql_num_rows($res_dayOpenBal) > 0) {
							$row_dayOpenBal		=	mysql_fetch_array($res_dayOpenBal);
							$dayOpenBal			=	($row_dayOpenBal[dayBalDue])-($row_dayOpenBal[cycleOpenBalDue]);
						}					
						$cyclestart				=	'0';
					}

					//echo $lineproductarr[17]."<bere>";

					if($lineproductarr[7] == '3') {
						$sale_minus_coll			=	($lineproductarr[17]);
						$dayBalDue					=	($cycleopenbal)+($dayOpenBal)-($sale_minus_coll);
					} if($lineproductarr[7] == '5') {
						$sale_minus_coll			=	($lineproductarr[16]);
						$dayBalDue					=	($cycleopenbal)+($dayOpenBal)-($sale_minus_coll);
					} else {
						$sale_minus_coll			=	($lineproductarr[17]);
						$dayBalDue					=	($cycleopenbal)+($dayOpenBal)+($sale_minus_coll);
					}

					$query_cusbaldow			=	"INSERT INTO customerbaldownload (customerCode,cycleStart,Transaction_Number,cycleOpenBalDue,dayOpenBalDue,daySaleValue,dayCollValue,dayBalDue,insertDateTime)  VALUES ('$lineproductarr[6]','$cyclestart','$lineproductarr[8]','$cycleopenbal','$dayOpenBal','$lineproductarr[15]','$lineproductarr[16]','$dayBalDue',NOW())";
					//echo $query_cusbaldow;
					
					$result_cusbaldow			=	 mysql_query($query_cusbaldow);
				}

				//FOR CUSTOMER BALANCE DUE ADD ENDS HERE
				//exit;



				if($todaydate >= 1 && $todaydate <= 10) {
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
					$transno_transhdr		=	array_unique($transno_transhdr);
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

					$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "',NOW())";
					//echo $query;
					//exit;
					$result = mysql_query($query) or die(mysql_error());
					
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

					$query_saleslist = "INSERT INTO ". $tables[14] ." SET KD_Code = '$KD_Code',DSR_Code = '$lineproductarr[1]',DateValue=NOW(),monthyear='$monthyear',route_id='$routeid',customer_id='$Customer_id',Product_code='$lineproductarr[5]',quantity='$lineproductarr[15]',rateval='$lineproductarr[16]',valueval='$lineproductarr[17]',transtype='$lineproductarr[2]',insertdatetime=NOW()";
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
					$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "',NOW())";
					$result = mysql_query($query) or die(mysql_error());

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

				$query_salescol				=	"SELECT id from " . $tables[$index] . " WHERE KD_Code='$lineproductarr[0]' AND DSR_Code='$lineproductarr[1]' AND device_code='$lineproductarr[2]' AND Vehicle_Code='$lineproductarr[3]' AND Date LIKE '$date_salescol%' AND total_sale_value='$lineproductarr[7]' AND total_collection_value='$lineproductarr[8]'";
				//echo $query_salescol;
				//exit;
				$result_salescol			=	mysql_query($query_salescol) or die(mysql_error());
				$rowcnt_salescol			=	mysql_num_rows($result_salescol);

				if($rowcnt_salescol == 0 || $rowcnt_salescol == '0') {
					
					//echo $rowcnt_salescol."HALL";
					//exit;

					$query = "insert into " . $tables[$index] . " values ('','" . $lineagain . "',NOW())";
					//exit;
					$result = mysql_query($query);

					if ($result == true)
						echo $index . "  success";
					else
						echo $index . "  fail";

					echo "<br>";
				}
				//echo $rowcnt_salescol."LAMP";
				//exit;
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
				
				//$query_metrics				=	"SELECT id from " . $tables[$index] . " WHERE KD_Code='$linenewarr[0]' AND DSR_Code='$linenewarr[1]' AND Device_Code='$linenewarr[2]' AND Date='$linenewarr[3]' AND visit_Count = '$linenewarr[4]' AND Invoice_Count='$linenewarr[5]' AND Invoice_Line_Count='$linenewarr[8]' AND Total_Sale_Value='$linenewarr[10]' AND Drop_Size_Value='$linenewarr[11]' AND Basket_Size_Value='$linenewarr[12]' AND targetSales='$linenewarr[13]' AND achievementPercent='$linenewarr[14]' AND effective_count='$linenewarr[6]' AND productive_count='$linenewarr[7]' AND prodIncentive='$linenewarr[15]' AND effIncentive='$linenewarr[16]'";

				$query_metrics				=	"SELECT id from " . $tables[$index] . " WHERE KD_Code='$linenewarr[0]' AND DSR_Code='$linenewarr[1]' AND Date='$linenewarr[3]'";
				//echo $query_metrics;
				//exit;
				$result_metrics			=	mysql_query($query_metrics) or die(mysql_error());
				$rowcnt_metrics			=	mysql_num_rows($result_metrics);

				if($rowcnt_metrics == '0' || $rowcnt_metrics == 0) {
					
					//echo $query_metrics;
					//echo $rowcnt_metrics."HALL";
					//exit;
					$query = "insert into " . $tables[$index] ."  ". $linecolumns . "  values ('" . $lineagain . "')";
					//exit;
					$result = mysql_query($query);

					if ($result == true)
						echo $index . "  success";
					else
						echo $index . "  fail";

					echo "<br>";
				}
				//echo $rowcnt_metrics."LAMP";
				//exit;
			}

			$line				=		'';
			$lineagain			=		'';	
		
			$linecountval++;
		}

		fclose($file);
	}
}
// Cycle through all source files
foreach ($arrayFileNames as $file) {
  if (in_array($directory.$file, array(".",".."))) continue;
  // If we copied this successfully, mark it for deletion
  if (copy($directory.$file, $uploadedDirectory.$file)) {
    $delete[] = $directory.$file;
  }
}
// Delete all successfully-copied files
foreach ($delete as $file) {
  unlink($file);
}
?>