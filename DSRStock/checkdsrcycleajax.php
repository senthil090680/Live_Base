<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
if(isset($DSR_Code) && $DSR_Code !='') {

	$curdate				=	date('Y-m-d');
	$sel_dsrid				=	"SELECT id from dsr WHERE DSR_Code = '$DSR_Code'";
	$res_dsrid				=	mysql_query($sel_dsrid) or die(mysql_error());
	$rowcnt_dsrid			=	mysql_num_rows($res_dsrid);		
	if($rowcnt_dsrid > 0){
		$row_dsrid			=	mysql_fetch_array($res_dsrid);
		$dsrid				=	$row_dsrid[id];
	}
	$nextrecval				=	"WHERE ((flag_status = '1' AND end_flag_status = '0') OR (flag_status = '0' AND end_flag_status = '0')) AND dsr_id = '$dsrid' AND Date LIKE '%$curdate%' ORDER BY id DESC";

} else {
	$nextrecval				=	"";
}
$where						=	"$nextrecval";

if(isset($nextrecval) && $nextrecval !='')
{
	$qry_checkcyc			=	"SELECT device_id,route_id,vehicle_id FROM `cycle_assignment` $where";
}
else
{
	echo "Invalid Access";
	exit;
}
$res_checkcyc				=	mysql_query($qry_checkcyc) or die(mysql_error());
$nor_checkcyc				=	mysql_num_rows($res_checkcyc);
$suggestedQty				=	'';
$suggestedQtyfirst			=	0;
$suggestedQtysecond			=	0;
$suggestedQtythird			=	0;
$routemonth					=	trim(date('m'),0);
$routeyear					=	date('Y');
$monthyearfirst				=	($routemonth - 1)."-".$routeyear;
$monthyearsecond			=	($routemonth - 2)."-".$routeyear;
$monthyearthird				=	($routemonth - 3)."-".$routeyear;

if($nor_checkcyc > 0) {
	$row_checkcyc			=	mysql_fetch_array($res_checkcyc);
	$device_id				=	$row_checkcyc['device_id'];
	$route_id				=	$row_checkcyc['route_id'];
	$vehicle_id				=	$row_checkcyc['vehicle_id'];

	$qry_devcode			=	"SELECT device_description,device_code FROM `device_master` WHERE id = '$device_id'";
	$res_devcode			=	mysql_query($qry_devcode) or die(mysql_error());	
	$row_devcode			=	mysql_fetch_array($res_devcode);
	$devcode				=	$row_devcode['device_code'];
	$device_name			=	$row_devcode['device_description'];

	$qry_vehcode			=	"SELECT vehicle_reg_no,vehicle_code,vehicle_desc FROM `vehicle_master` WHERE id = '$vehicle_id'";
	$res_vehcode			=	mysql_query($qry_vehcode) or die(mysql_error());	
	$row_vehcode			=	mysql_fetch_array($res_vehcode);
	$vehcode				=	$row_vehcode['vehicle_code'];
	$vehicle_name			=	$row_vehcode['vehicle_desc'];
	$vehicle_regno			=	$row_vehcode['vehicle_reg_no'];

	$qry_rotcode			=	"SELECT route_code,route_desc FROM `route_master` WHERE id = '$route_id'";
	$res_rotcode			=	mysql_query($qry_rotcode) or die(mysql_error());	
	$row_rotcode			=	mysql_fetch_array($res_rotcode);
	$rotcode				=	$row_rotcode['route_code'];
	$route_name				=	$row_rotcode['route_desc'];

	$TodayDate					=	date('Y-m-d');
	
	$sel_loadseqno				=	"SELECT Load_Sequence_No FROM dailystockloading WHERE (Load_Sequence_No != '' AND Load_Sequence_No IS NOT NULL) AND DSR_Code = '$DSR_Code' AND Date LIKE '$TodayDate%' ORDER BY id DESC";
	$res_loadseqno				=	mysql_query($sel_loadseqno) or die(mysql_error());
	$rowcnt_loadseqno			=	mysql_num_rows($res_loadseqno);
	if($rowcnt_loadseqno > 0) { 
		$row_loadseqno			=	mysql_fetch_array($res_loadseqno);
		$loadseqno				=	$row_loadseqno[Load_Sequence_No] + 1;
	} else {
		$loadseqno				=	1;
	}
	

	//THIS IS OLD ONE FOR SEQUENCE ORDER STARTS FROM 1 WHEN CYCLE IS STARTED AND CONTINUES UNTIL THE CYCLE ENDS, STARTS HERE
	/* 
	$qry_checkassignment	=	"SELECT flag_status,end_flag_status FROM `cycle_assignment` WHERE dsr_id = '$dsrid' ORDER BY id DESC";
	//exit;
	$res_checkassignment	=	mysql_query($qry_checkassignment) or die(mysql_error());
	$rowcnt_checkassignment	=	mysql_num_rows($res_checkassignment);
	if($rowcnt_checkassignment > 0) {
		$row_checkassignment			=	mysql_fetch_array($res_checkassignment);
		$flag_status					=	$row_checkassignment[flag_status];
		$end_flag_status				=	$row_checkassignment[end_flag_status];
		if($flag_status == '1' && $end_flag_status == '0') {
			$sel_loadseqno				=	"SELECT Load_Sequence_No FROM dailystockloading WHERE (Load_Sequence_No != '' AND Load_Sequence_No IS NOT NULL) AND DSR_Code = '$DSR_Code' ORDER BY id DESC";
			$res_loadseqno				=	mysql_query($sel_loadseqno) or die(mysql_error());
			$rowcnt_loadseqno			=	mysql_num_rows($res_loadseqno);
			if($rowcnt_loadseqno > 0) { 
				$row_loadseqno			=	mysql_fetch_array($res_loadseqno);
				$loadseqno				=	$row_loadseqno[Load_Sequence_No] + 1;
			} else {
				$loadseqno				=	1;
			}
		} else {
			$sel_loadseqno				=	"SELECT Load_Sequence_No FROM dailystockloading WHERE (Load_Sequence_No != '' AND Load_Sequence_No IS NOT NULL) AND DSR_Code = '$DSR_Code' ORDER BY id DESC";
			$res_loadseqno				=	mysql_query($sel_loadseqno) or die(mysql_error());
			$rowcnt_loadseqno			=	mysql_num_rows($res_loadseqno);
			if($rowcnt_loadseqno > 0) { 
				$row_loadseqno			=	mysql_fetch_array($res_loadseqno);
				$loadseqno				=	$row_loadseqno[Load_Sequence_No] + 1;
			} else {
				$loadseqno				=	1;
			}
		}
	}
	*/
	//THIS IS OLD ONE FOR SEQUENCE ORDER STARTS FROM 1 WHEN CYCLE IS STARTED AND CONTINUES UNTIL THE CYCLE ENDS, ENDS HERE
	
	$sel_supp		=	"SELECT Product_code,Product_description from opening_stock_update WHERE (TransactionQty != '' AND BalanceQty != '' AND TransactionNo != '' AND TransactionType !='') GROUP BY Product_code";
	$res_supp						=	mysql_query($sel_supp) or die(mysql_error());
	$rowcnt_supp					=	mysql_num_rows($res_supp);
	$t								=	1;
	if($rowcnt_supp > 0) { 
		$w								=	0;
		$g								=	0;
		while($row_supp					=	mysql_fetch_array($res_supp)){	
			
			$suggestedQtyfirst			=	0;
			$suggestedQtysecond			=	0;
			$suggestedQtythird			=	0;

			$qry_avgsalesfirst			=	"SELECT transtype,quantity FROM `sales_list` WHERE route_id = '$route_id' AND DSR_Code = '$DSR_Code' AND Product_code = '$row_supp[Product_code]' AND monthyear = '$monthyearfirst'";
			$res_avgsalesfirst			=	mysql_query($qry_avgsalesfirst) or die(mysql_error());	
			
			while($row_avgsalesfirst			=	mysql_fetch_array($res_avgsalesfirst)) {
				$transtype				=	$row_avgsalesfirst[transtype];
				//exit;
				if($transtype == 2) {
					$suggestedQtyfirst	+=	$row_avgsalesfirst['quantity'];
				} elseif ($transtype == 3 || $transtype == 4) {
					$suggestedQtyfirst	-=	$row_avgsalesfirst['quantity'];
				}
			}
			
			$qry_avgsalessec			=	"SELECT transtype,quantity FROM `sales_list` WHERE route_id = '$route_id' AND DSR_Code = '$DSR_Code' AND Product_code = '$row_supp[Product_code]' AND monthyear = '$monthyearsecond'";
			$res_avgsalessec			=	mysql_query($qry_avgsalessec) or die(mysql_error());	
			
			while($row_avgsalessec			=	mysql_fetch_array($res_avgsalessec)) {
				$transtype				=	$row_avgsalessec[transtype];
				if($transtype == 2) {
					$suggestedQtysecond	+=	$row_avgsalessec['quantity'];
				} elseif ($transtype == 3 || $transtype == 4) {
					$suggestedQtysecond	-=	$row_avgsalessec['quantity'];
				}
			}

			$qry_avgsalesthird			=	"SELECT transtype,quantity FROM `sales_list` WHERE route_id = '$route_id' AND DSR_Code = '$DSR_Code' AND Product_code = '$row_supp[Product_code]' AND monthyear = '$monthyearthird'";
			$res_avgsalesthird			=	mysql_query($qry_avgsalesthird) or die(mysql_error());	
			
			while($row_avgsalesthird	=	mysql_fetch_array($res_avgsalesthird)) {
				$transtype				=	$row_avgsalesthird[transtype];
				if($transtype == 2) {
					$suggestedQtythird	+=	$row_avgsalesthird['quantity'];
				} elseif ($transtype == 3 || $transtype == 4) {
					$suggestedQtythird	-=	$row_avgsalesthird['quantity'];
				}
			}
			
			$firstoptionbefore				=	'';
			$firstoption					=	'';
			$secondoptionbefore				=	'';
			$secondoption					=	'';
			$thirdoptionbefore				=	'';
			$thirdoption					=	'';
			$fourthoptionbefore				=	'';
			$fourthoption					=	'';
			$fifthoptionbefore				=	'';
			$fifthoption					=	'';
			$sixthoptionbefore				=	'';
			$sixthoption					=	'';
			$seventhoptionbefore			=	'';
			$seventhoption					=	'';
			
			/*echo $suggestedQtyfirst."<br/>";
			echo $suggestedQtysecond."<br/>";
			echo $suggestedQtythird."<br/>";*/

			if($suggestedQtythird != 0 && $suggestedQtysecond != 0 && $suggestedQtyfirst != 0) { //first
				if($suggestedQty == '') {
					$firstoptionbefore		=	(($suggestedQtythird) + ($suggestedQtysecond) + ($suggestedQtyfirst));
					if($firstoptionbefore > 0) {
						$firstoption			=	ceil($firstoptionbefore/3);
						$suggestedQty			.=	$firstoption;
					} else {
						$suggestedQty			.=	0;
					}
				} else {
					$firstoptionbefore		=	(($suggestedQtythird) + ($suggestedQtysecond) + ($suggestedQtyfirst));
					if($firstoptionbefore > 0) {
						$firstoption			=	"&".ceil($firstoptionbefore/3);
						$suggestedQty			.=	$firstoption;
					} else {
						$suggestedQty			.=	"&0";
					}					
				}
			} else if(($suggestedQtythird == 0) && ($suggestedQtysecond != 0 && $suggestedQtyfirst != 0)) {  //second
				if($suggestedQty == '') {
					$secondoptionbefore		=	(($suggestedQtysecond) + ($suggestedQtyfirst));
					if($secondoptionbefore > 0) {
						$secondoption			=	ceil($secondoptionbefore/2);
						$suggestedQty			.=	$secondoption;
					} else {
						$suggestedQty			.=	0;
					}					
				} else {
					$secondoptionbefore		=	(($suggestedQtysecond) + ($suggestedQtyfirst));
					if($secondoptionbefore > 0) {
						$secondoption			=	"&".ceil($secondoptionbefore/2);
						$suggestedQty			.=	$secondoption;
					} else {
						$suggestedQty			.=	"&0";
					}					
				}
			} else if(($suggestedQtysecond == 0) && ($suggestedQtythird != 0 && $suggestedQtyfirst != 0)) { //third
				if($suggestedQty == '') {
					$thirdoptionbefore		=	(($suggestedQtythird) + ($suggestedQtyfirst));
					if($thirdoptionbefore > 0) {
						$thirdoption			=	ceil($thirdoptionbefore/2);
						$suggestedQty			.=	$thirdoption;
					} else {
						$suggestedQty			.=	0;
					}					
				} else {
					$thirdoptionbefore		=	(($suggestedQtythird) + ($suggestedQtyfirst));
					if($thirdoptionbefore > 0) {
						$thirdoption			=	"&".ceil($thirdoptionbefore/2);
						$suggestedQty			.=	$thirdoption;
					} else {
						$suggestedQty			.=	"&0";
					}
				}
			} else if(($suggestedQtyfirst == 0) && ($suggestedQtythird != 0 && $suggestedQtysecond != 0)) {  //fourth
				if($suggestedQty == '') {
					$fourthoptionbefore		=	(($suggestedQtythird) + ($suggestedQtysecond));
					if($fourthoptionbefore > 0) {
						$fourthoption			=	ceil($fourthoptionbefore/2);
						$suggestedQty			.=	$fourthoption;
					} else {
						$suggestedQty			.=	0;
					}
				} else {
					$fourthoptionbefore		=	(($suggestedQtythird) + ($suggestedQtysecond));
					if($fourthoptionbefore > 0) {
						$fourthoption			=	"&".ceil($fourthoptionbefore/2);
						$suggestedQty			.=	$fourthoption;
					} else {
						$suggestedQty			.=	"&0";
					}
				}
			} else if(($suggestedQtyfirst == 0 && $suggestedQtythird == 0) && ($suggestedQtysecond != 0)) { //fifth
				if($suggestedQty == '') {
					$fifthoptionbefore		=	($suggestedQtysecond);
					if($fifthoptionbefore > 0) {
						$fifthoption			=	ceil($fifthoptionbefore);
						$suggestedQty			.=	$fifthoption;
					} else {
						$suggestedQty			.=	0;
					}
				} else {
					$fifthoptionbefore		=	($suggestedQtysecond);
					if($fifthoptionbefore > 0) {
						$fifthoption			=	"&".ceil($fifthoptionbefore);
						$suggestedQty			.=	$fifthoption;
					} else {
						$suggestedQty			.=	"&0";
					}
				}
			} else if(($suggestedQtyfirst == 0 && $suggestedQtysecond == 0) && ($suggestedQtythird != 0)) {  //sixth
				if($suggestedQty == '') {
					$sixthoptionbefore		=	($suggestedQtythird);
					if($sixthoptionbefore > 0) {
						$sixthoption			=	ceil($sixthoptionbefore);
						$suggestedQty			.=	$sixthoption;
					} else {
						$suggestedQty			.=	0;
					}
				} else {
					$sixthoptionbefore		=	($suggestedQtythird);
					if($sixthoptionbefore > 0) {
						$sixthoption			=	"&".ceil($sixthoptionbefore);
						$suggestedQty			.=	$sixthoption;
					} else {
						$suggestedQty			.=	"&0";
					}
				}
			} else if(($suggestedQtysecond == 0 && $suggestedQtythird == 0) && ($suggestedQtyfirst != 0)) {  //seventh
				if($suggestedQty == '') {
					$seventhoptionbefore		=	($suggestedQtyfirst);
					if($seventhoptionbefore > 0) {
						$seventhoption			=	ceil($seventhoptionbefore);
						$suggestedQty			.=	$seventhoption;
					} else {
						$suggestedQty			.=	0;
					}
				} else {
					$seventhoptionbefore		=	($suggestedQtyfirst);
					if($seventhoptionbefore > 0) {
						$seventhoption			=	"&".ceil($seventhoptionbefore);
						$suggestedQty			.=	$seventhoption;
					} else {
						$suggestedQty			.=	"&0";
					}
				}
			}
			
		$w++;
		}
	}
	echo $device_name."~".$vehicle_regno."~".$route_name."~".$devcode."~".$vehcode."~".$rotcode."~".$suggestedQty."~".$loadseqno;	
} else {
	echo "ASSNO";
}
exit(0);?>