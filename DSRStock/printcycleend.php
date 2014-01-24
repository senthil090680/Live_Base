<?php
session_start();
ob_start();
//require_once "../include/header.php";
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_POST);
//print_r($_POST);

$printvalstr			=	str_replace("~","','",$printval);

if(isset($_POST[printval]) && $_POST[printval] !='') {
	$nextrecval			=	"WHERE id in ('".$printvalstr."')";
} else {
	$nextrecval			=	"";
}
$where					=	"$nextrecval";

if(isset($_POST) && $_POST !='')
{
	$qry="SELECT * FROM `cycle_end_reconciliation` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
?>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="../js/jconfirmaction.jquery.js"></script>

<title>CYCLE END RECONCILIATION</title>
	<div id="mainareadaily">
<div class="mcf"></div>
 <span id="showviewbutton" ></span>
<div><h2 align="center">CYCLE END RECONCILIATION</h2></div> 
<div id="containerdaily">
	<div class="clearfix"></div>
	<?php		
	$results=mysql_query($qry);
	//$pager = new PS_Pagination($bd, $qry,15,15);
	//$results = $pager->paginate();
	$num_rows= mysql_num_rows($results);			
	?>
	<table border="1" width="100%" style="border-collapse:collapse;">
	<thead>
	<tr>
	<th align="center">Cycle Start Date</th>
	<th >Cycle End Date</th>
	<th >SR Name</th>
	<th >Vehicle Name</th>
	<th >Device Name</th>
	<th  >Product Name </th>
	<th >UOM</th>
	<th >Currency</th>
	<th >Cycle Start Loaded Quantity</th>
	<th >Daily Total Loaded Quantity</th>
	<th >Total Sold Quantity</th>
	<!-- <th >Total Sales Returned Quantity</th> -->
	<th>Total Cancelled Quantity</th>
	<th>Market Return Quantity</th>
	<th >SR Returned Quantity</th>
	<!-- <th >KD Returned Quantity</th> -->
	<th>KD Received Quantity</th>
	<th >Quantity Shortage</th>
	<!-- <th >Total Sales Value</th> -->
	<th >Product Sales Value</th>
	<!-- <th>Total Deposit Value</th> -->
	<!-- <th >KD Amount Received</th>
	<th >Amount Shortage</th> -->
	</tr>
	</tr>
	</thead>
	<tbody>
	<?php
	if(!empty($num_rows)){
	$c=0;$cc=1;
	while($fetch = mysql_fetch_array($results)) {
	if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
	$id= $fetch['id'];
	?>
	<tr>
	<td><?php if($fetch['cycle_start_date'] == '') { echo "&nbsp;"; } else { echo $fetch['cycle_start_date']; } ?></td>
	<td><?php if($fetch['cycle_end_date'] == '') { echo "&nbsp;"; } else {  echo $fetch['cycle_end_date']; } ?></td>
	<td><?php $sel_dsrname	=	"SELECT DSRName FROM `dsr` WHERE DSR_Code = '$fetch[DSR_Code]'"; 
		$results_dsrname=mysql_query($sel_dsrname);
		$row_dsrname= mysql_fetch_array($results_dsrname);
	if($row_dsrname['DSRName'] == '') { echo "&nbsp;"; } else {  echo ucwords(strtolower($row_dsrname['DSRName'])); } ?></td>

	<td><?php $sel_vehname	=	"SELECT vehicle_desc FROM `vehicle_master` WHERE vehicle_code = '$fetch[vehicle_code]'"; 
		$results_vehname=mysql_query($sel_vehname);
		$row_vehname= mysql_fetch_array($results_vehname);
	if($row_vehname['vehicle_desc'] == '') { echo "&nbsp;"; } else {  echo ucwords(strtolower($row_vehname['vehicle_desc'])); } ?></td>

	<td><?php $sel_devname	=	"SELECT device_description FROM `device_master` WHERE device_code = '$fetch[device_code]'"; 
		$results_devname=mysql_query($sel_devname);
		$row_devname= mysql_fetch_array($results_devname);
	if($row_devname['device_description'] == '') { echo "&nbsp;"; } else { echo ucwords(strtolower($row_devname['device_description'])); } ?></td>

	<td><?php $sel_prname	=	"SELECT Product_description1 FROM `product` WHERE Product_code = '$fetch[Product_code]'"; 
		$results_prname=mysql_query($sel_prname);
		$row_prname= mysql_fetch_array($results_prname);
	if($row_prname['Product_description1'] == '') { echo "&nbsp;"; } else {  echo ucwords(strtolower($row_prname['Product_description1'])); } ?></td>

	<td ><?php if($fetch['UOM'] == '') { echo "&nbsp;"; } else { echo $fetch['UOM']; } ?></td>
	<td ><?php if($fetch['currency'] == '') { echo "&nbsp;"; } else { echo $fetch['currency']; } ?></td>
	<td align="right"><?php if($fetch['cycle_start_loaded_qty'] == '') { echo "&nbsp;"; } else {  echo number_format($fetch['cycle_start_loaded_qty']); } ?></td>
	<td align="right"><?php if($fetch['daily_total_loaded_qty'] == '') { echo "&nbsp;"; } else {  echo number_format($fetch['daily_total_loaded_qty']); } ?></td>
	<td align="right"><?php if($fetch['total_sold_qty'] == '') { echo "&nbsp;"; } else {  echo number_format($fetch['total_sold_qty']); } ?></td>
	<td align="right"><?php if($fetch['total_cancelled_qty'] == '') { echo "&nbsp;"; } else {  echo number_format($fetch['total_cancelled_qty']); } ?></td>
	<td align="right"><?php if($fetch['total_sales_returned_qty'] == '') { echo "&nbsp;"; } else {  echo number_format($fetch['total_sales_returned_qty']); } ?></td>
	<td align="right"><?php if($fetch['DSR_returned_qty'] == '') { echo "&nbsp;"; } else {  echo number_format($fetch['DSR_returned_qty']); } ?></td>
	<td align="right"><?php if($fetch['KD_returned_qty'] == '') { echo "&nbsp;"; } else {  echo number_format($fetch['KD_returned_qty']); }?></td>
	<td align="right"><?php if($fetch['quantity_shortage'] == '') { echo "&nbsp;"; } else {  echo number_format($fetch['quantity_shortage']); } ?></td>
	<td align="right"><?php if($fetch['total_ind_prod_sale_value'] == '') { echo "&nbsp;"; } else {  echo number_format($fetch['total_ind_prod_sale_value'],2); } ?></td>
	<!-- <td><?php if($fetch['total_deposit_value'] == '') { echo "&nbsp;"; } else {  echo $fetch['total_deposit_value']; } ?></td>
	<td><?php if($fetch['amount_shortage'] == '') { echo "&nbsp;"; } else {  echo $fetch['amount_shortage']; } ?></td> -->
	<?php
	$total_sales_value		=		$fetch['total_sales_value'];
	$total_deposit_value	=		$fetch['total_deposit_value'];
	$amount_shortage		=		$fetch['amount_shortage'];
	
	?>
	</tr>


	<?php $c++; $cc++; }
	?>
	<tr>
		<td colspan="3">Total Sales Value</td><td colspan="3"><?php if($total_sales_value == '') { echo "&nbsp;"; } else { echo number_format($total_sales_value,2); } ?></td>
		<td colspan="3">KD Amount Received</td><td colspan="3"><?php if($total_deposit_value == '') { echo "&nbsp;"; } else { echo number_format($total_deposit_value,2); } ?></td>
		<td colspan="3">Amount Shortage</td><td colspan="3"><?php if($amount_shortage == '') { echo "&nbsp;"; } else { echo number_format($amount_shortage,2); } ?></td>
	</tr>

	<?php } else{  ?>
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
				<td style="display:none;" >Cust Name</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line2</td>
				<td style="display:none;" >LGA</td>
				<td style="display:none;" >City</td>
				<td style="display:none;" >Contact Person</td>
				<td style="display:none;" >Contact Number</td>
				<td style="display:none;" >City</td>
				<td style="display:none;" >City</td>
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

		/*
		//Display the link to first page: First
		echo $pager->renderFirst()."&nbsp; ";
		//Display the link to previous page: <<
		echo $pager->renderPrev();
		//Display page links: 1 2 3
		echo $pager->renderNav();
		//Display the link to next page: >>
		echo $pager->renderNext()."&nbsp; ";
		//Display the link to last page: Last
		echo $pager->renderLast(); 
		*/
	} 
	else{ 
		echo "&nbsp;"; 
	} ?>      
	</th>
	</tr>
	</table>
  
 <div class="msg" align="center">
 <form action="" method="post">

 <span id="printopen" style="padding-left:120px;padding-top:10px;" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>&nbsp;&nbsp;&nbsp;&nbsp;
 <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='cycleendreconciliation.php'"/>
  </form>
 </div>  
</div>
<?php exit(0);?>