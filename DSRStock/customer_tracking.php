<?php
session_start();
ob_start();
require_once('../include/header.php');
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

//error_reporting(E_ALL && ~ E_NOTICE);
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

?>

<link href="../css/popup.css" rel="stylesheet" type="text/css" />
<div id="mainareadaily">
<div class="mcf"></div>
<div align="center" class="headingsgrdaily">CUSTOMER VISIT TRACKING</div>
<div id="mytableformdaily" align="center">
<form action="" method="post" id="dailystockvalidation">
<table width="100%">
 <tr>
  <td>
 <fieldset class="alignment">
 
  <table width="100%">
    <tr height="20">
    <td width="120">Date*</td>
    <td><input type="text" name="DSRDate" id="DSRDate" onChange="loadCustomerTrackDate(this.value);" size="15" value="<?php if(isset($DSRDate) && $DSRDate != '') { echo $DSRDate; } else { echo date('Y-m-d'); } ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/>
	</td>
	<td width="120">SR Name*</td>
     <td><select name="DSR_Code" id="DSR_Code" onChange="loadCustomerTrack(this.value);">
	<option value="" >--Select--</option>
	<?php $sel_supp		=	"SELECT DSRName,DSR_Code from dsr GROUP BY DSRName";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[DSR_Code]; ?>" <?php if($DSR_Code == $row_supp[DSR_Code]) { echo "selected"; } ?> ><?php echo $row_supp[DSRName]; ?></option>
	<?php } ?>
	</select>&nbsp;</td>
    </tr>
    <!-- <tr  height="20">
     <td width="120">Name*</td>
     <td><input type="text" name="supplier_name" size="30" value="<?php echo $supplier_name; ?>" maxlength="20" autocomplete='off'/></td>
	 </tr>-->
       </table>
     </fieldset>
       </td>
     </tr> 
</table>
</form>

<div id="nodatetab" style="display:none;">

<!----------------------------------------------- Left Table End -------------------------------------->

<table width="100%" align="left" id="productsadd">
 <tr>
  <td>
  <div class="condailytrack">
  <table>
  <?php $query_track		=	"SELECT id, DSR_Code, Date, Sequence_Number, Customer_Code, Check_In_Time, Checkin_GPS, Check_Out_Time, Checkout_GPS, check_out_id  FROM customer_visit_tracking WHERE (Date = '$DateVal' AND DSR_Code = '$DSR_Code')";

	$res_track			=	mysql_query($query_track)or die(mysql_error()); ?>
  <thead>
  <tr><th align='center'>Serial Number</th><th class='rounded' align='center'>Sequence Number</th><th align='center'>Customer Code</th><th align='center'>Customer Name</th><th align='center'>Check In Time</th><th align='center'>Check In GPS</th><th align='center'>Check Out Time</th><th align='center'>Check Out GPS</th><th align='center'>Check Out Reason</th></tr></thead>  
  <tbody id="productsadded">
   <?php while($row_track	=	mysql_fetch_array($res_track)) { ?>
		<tr><td align='center'><?php echo $row_track[id]; ?></td><td align='center'><?php echo $row_track[Sequence_Number]; ?></td><td align='center'><?php echo $row_track[Customer_Code]; 
		$query_CustomerName			=	"SELECT Customer_Name FROM customer WHERE customer_code = '$row_track[Customer_Code]'";			
		$res_CustomerName			=	mysql_query($query_CustomerName) or die(mysql_error());
		$row_CustomerName			=	mysql_fetch_array($res_CustomerName);
		$CustomerName				=	$row_CustomerName['Customer_Name'];
	
		?></td><td align='center'><?php echo $CustomerName; ?></td><td align='center'><?php echo $row_track[Check_In_Time]; ?></td><td align='center'><?php echo $row_track[Checkin_GPS]; ?></td><td align='center'><?php echo $row_track[Check_Out_Time]; ?> </td><td align='center'><?php echo $row_track[Checkout_GPS]; ?></td><td align='center'><?php 
		$query_reason				=	"SELECT reason FROM check_out_reason WHERE check_out_id = '$row_track[check_out_id]'";			
		$res_reason					=	mysql_query($query_reason) or die(mysql_error());
		$row_reason					=	mysql_fetch_array($res_reason);
		$reason						=	$row_reason['reason'];		
		echo $reason; ?></td></tr>
	<?php } ?>
  </tbody>
   </table>
     </div>
   </td>
 </tr>
</table>

<table width="50%" style="clear:both">
	<tr align="center" height="50px;">
		<td>&nbsp;&nbsp;&nbsp;</td>
	</tr>
 </table>     
</div>

<div style="padding-top:10px;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></div>
<?php require_once("../include/error.php");?>
<!-- <div id="errormsgcus" style="display:none;"><h3 align="center" class="myalign"></h3><button id="closebutton">Close</button></div> -->
<div id="errormsgcustra" ><h3 align="center" class="myaligncustra"></h3><button id="closebutton_blue">Close</button></div>
</div>
<!---- Form End ----->
<div class="clearfix"></div>
<div class="clearfix"></div>

</div>
<?php require_once('../include/footer.php'); ?>