<?php
include("../include/config.php");
EXTRACT($_POST);
//Submit Action
if($_POST['submit']=='Save'){
$Effective_date=date("Y-M-d",strtotime($Effective_date));	
echo $sql=  ("UPDATE parameters set  
          `displaydateformat`='$displaydateformat',
		`currency`='$currency',
		`issue_no`='$issue_no',
		`Transfer_Frequency`='$Transfer_Frequency',
		`Start_time`='$Start_time',
		`End_time`='$End_time',
		`batchctrl`='$batchctrl',
		`Focus_item_stock`='$Focus_item_stock',
		`Customer_Sign`='$Customer_Sign',
		`Permit_Return`='$Permit_Return',
		`Trans_Reprint`='$Trans_Reprint',
		`Tran_copies`='$Tran_copies',
		`Data_Transfer`='$Data_Transfer', 
		`Data_sync_freq`='$Data_sync_freq'
	    where id = 1");	
mysql_query( $sql);
header("location:setupParam.php?no=2&page=$page");
}

?>