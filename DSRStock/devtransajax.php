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
$params=$fromdate."&".$todate."&".$kd_id."&".$dsr_id."&".$sortorder."&".$ordercol;
if(isset($_REQUEST[kd_id]) && $_REQUEST[kd_id] !='') {
	$nextrecval		=	"WHERE (Date BETWEEN '$fromdate' AND '$todate') AND trhe.KD_Code = '$kd_id' AND trhe.DSR_Code = '$dsr_id'";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_REQUEST) && $_REQUEST !='')
{
	$qry="SELECT *,trhe.id AS TRHEID,trhe.GPS AS GPSVAL FROM trans_type AS TT RIGHT JOIN `transaction_hdr` AS trhe ON TT.id = trhe.Transaction_type LEFT JOIN `customer` AS custable ON trhe.Customer_code = custable.customer_code $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results		=	mysql_query($qry);
$num_rows		=	mysql_num_rows($results);			

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 4;   // Records Per Page

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
	$orderby	=	"ORDER BY trhe.id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.="  $orderby LIMIT $Page_Start , $Per_Page";
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination ends****************************/

?>
<div class="statictabledev">
	<div class="con3_dev">
	 <table width="100%">
		<thead>
		<tr>
		<th align='center' style="width:4%">Date</th>
		<th align='center' style="width:4%">Time</th>
		<th align='center' style="width:7%">GPS</th>
		<?php //echo $sortorderby;
		if($sortorder == 'ASC') {
			$sortorderby = 'DESC';
		} elseif($sortorder == 'DESC') {
			$sortorderby = 'ASC';
		} else {
			$sortorderby = 'DESC';
		}
		$paramsval	=	$fromdate."&".$todate."&".$kd_id."&".$dsr_id."&".$sortorderby."&Customer_Name"; ?>
		<th align='center' style="width:8%" onClick="pag_devajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');" ><span style="cursor:hand;cursor:pointer;">Customer Name<img src="../images/sort.png" width="13" height="13" /></span></th>
		<?php //echo $sortorderby;
		if($sortorder == 'ASC') {
			$sortordertype = 'DESC';
		} elseif($sortorder == 'DESC') {
			$sortordertype = 'ASC';
		} else {
			$sortordertype = 'DESC';
		}
		$paramsval	=	$fromdate."&".$todate."&".$kd_id."&".$dsr_id."&".$sortordertype."&trans_type"; ?>
		<th align='center' style="width:5%" onClick="pag_devajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');" ><span style="cursor:hand;cursor:pointer;">Transaction Type<img src="../images/sort.png" width="13" height="13" /></span></th>
		<th align='center' style="width:5%">Transaction No</th>		
		<th align='center' style="width:5%">Reference No</th>
		<th align='center' style="width:10%"><table>
			<tr>
				<td align="center" colspan="2">No of Products</td>
			</tr>
			<tr>
				<td>Standard</td><td>POSM</td>
			</tr>
		</table>
		</th>
		<th align='center' style="width:4%">Currency</th>
		<th align='center' style="width:8%">Sale Value</th>
		<th align='center' style="width:8%">Collection Value</th>
		<th align='center' style="width:8%">Balance Due</th>
		<th align='center' style="width:8%">Customer Image</th>
		<th align='center' style="width:8%">Customer Signature</th>
		<th align='center' style="width:8%">Feedback</th>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results_dsr)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls ="class='odd'"; }
		$devtransactionid		=		$fetch['TRHEID'];
		$devtransactionno		=		$fetch['Transaction_Number'];
		$devtransactiontype		=		$fetch['Transaction_type'];
		$devcusimage			=		$fetch['Shop_Image'];
		$devsigimage			=		$fetch['Signature_Image'];
		$Product_Line_count		=		$fetch['Product_Line_count'];		
			
		$sel_posmcount			=	"SELECT count(*) as PosmCount FROM `transaction_line` WHERE (Transaction_Number = '$devtransactionno' AND POSM_Flag = 1)";
		$results_posmcount		=	mysql_query($sel_posmcount);
		$rowcnt_posmcount		=	mysql_num_rows($results_posmcount);
		$row_posmcount			=	mysql_fetch_array($results_posmcount);
		if($rowcnt_posmcount > 0) {
			$Posmcnt			=	$row_posmcount['PosmCount'];
		}
		
		$sel_feedback			=	"SELECT id FROM `feedback` WHERE Transaction_Number	= '$fetch[Transaction_Number]'";
		$results_feedback		=	mysql_query($sel_feedback);
		$rowcnt_feedback		=	mysql_num_rows($results_feedback);

		$Other_Product_count	=	$Product_Line_count - $Posmcnt;
		?>		
		<tr>
		<td align="center" style="width:4%"><span><?php echo $fetch['Date'];?></span></td>
		<td align="center" style="width:4%"><span ><?php echo $fetch['Time'];?></span></td>
		<td align="center" style="width:7%"><span ><?php echo $fetch['GPSVAL'];?></span></td>
		<td align="center" style="width:8%"><span ><?php echo $fetch[Customer_Name]; ?></span></td>
		<td align="center" style="width:5%"><span ><?php 
			echo $fetch[trans_type];			
			/*if($devtransactiontype == 1) {
				echo "No Sales";
			} else if($devtransactiontype == 2) {
				echo "Sales";
			} else if($devtransactiontype == 3) {
				echo "Cancelled";
			} else if($devtransactiontype == 4) {
				echo "Return";
			} else if($devtransactiontype == 5) {
				echo "Receipt";
			}*/ 			
		?></span></td>
		<td align="center" style="width:5%"><span <?php if($devtransactiontype == 3) { ?> onclick="getlineitems('<?php echo $fetch['transaction_Reference_Number']; ?>','<?php echo $devtransactionid; ?>','<?php echo $devtransactiontype; ?>','<?php echo $fromdate; ?>','<?php echo $todate; ?>','<?php echo $kd_id; ?>','<?php echo $dsr_id; ?>');" <?php } elseif($devtransactiontype == 2) { ?> onclick="getlineitems('<?php echo $devtransactionno; ?>','<?php echo $devtransactionid; ?>','<?php echo $devtransactiontype; ?>','<?php echo $fromdate; ?>','<?php echo $todate; ?>','<?php echo $kd_id; ?>','<?php echo $dsr_id; ?>');" <?php } elseif($devtransactiontype == 4) { ?> onclick="getlineitems('<?php echo $devtransactionno; ?>','<?php echo $devtransactionid; ?>','<?php echo $devtransactiontype; ?>','<?php echo $fromdate; ?>','<?php echo $todate; ?>','<?php echo $kd_id; ?>','<?php echo $dsr_id; ?>');" <?php }  ?> style="cursor:pointer;cursor:hand;"><a style="color:blue;" ><?php echo $devtransactionno; ?></a></span></td>

		<td align="center" style="width:5%">
			<?php if($devtransactiontype == 3) { 
				echo $fetch['transaction_Reference_Number'];
			} else {				
				echo "-";
			}?>
		</td>
		<td align="center" style="width:10%">
			<table border="0" width="100%">
			<tr>
				<td align="center"><?php echo $Other_Product_count;?></td><td align="center"><?php echo $Posmcnt; ?></td>
			</tr>
			</table>
		</td>
		<td align="center" style="width:4%"><?php echo $fetch['currency'];?></td>
		<td align="center" style="width:8%"><?php echo $fetch['Net_Sale_value'];?></td>
		<td align="center" style="width:8%"><?php echo $fetch['Collection_Value'];?></td>
		<td align="center" style="width:8%"><?php echo $fetch['Balance_Due_Value'];?></td>
		<td align="center" style="width:8%">
		
		<?php if(($devcusimage != '') && (!is_null($devcusimage))) { ?> 			
		<span><a href="javascript:void(0);" onclick="getcustomerimage('<?php echo $devtransactionno; ?>');" style="cursor:pointer;cursor:hand;">Image Available</a></span>  
		
		<?php } else { ?>
			<span>No Customer Image</span> 
		<?php } ?>

		</td>
		<td align='center' style="width:8%">
		
		<?php if(($devsigimage != '') && (!is_null($devsigimage))) { ?> 			
		<span><a href="javascript:void(0);" onclick="getcustomersig('<?php echo $devtransactionno; ?>');" style="cursor:pointer;cursor:hand;">Signature Available</a></span>  
		
		<?php } else { ?>
			<span>No Signature</span> 
		<?php } ?>

		</td>
		<td align='center' style="width:8%">		
			<?php if($rowcnt_feedback > 0) { ?> 			
			<span><a href="javascript:void(0);" onclick="getfeedback('<?php echo $devtransactionno; ?>');" style="cursor:pointer;cursor:hand;">Feedback Available</a></span>  
			
			<?php } else { ?>
				<span>No Feedback</span> 
			<?php } ?>
		</td>

		</tr>
		<?php $c++; $cc++; }		 
		}else{  ?>
				<tr>
					<td align='center' colspan='15'><b>No records found</b></td>
					<td style="display:none;" >Cust Name</td>
					<td style="display:none;" >Add Line1</td>
					<td style="display:none;" >Add Line1</td>
					<td style="display:none;" >Add Line2</td>
					<td style="display:none;" >LGA</td>
					<td style="display:none;" >City</th>
					<td style="display:none;" >Contact Person</td>
					<td style="display:none;" >Contact Number</td>
					<td style="display:none;" >Contact Number</td>
					<td style="display:none;" >Contact Person</td>
					<td style="display:none;" >Contact Number</td>
					<td style="display:none;" >Contact Number</td>
					<td style="display:none;" >Contact Person</td>
					<td style="display:none;" >Contact Number</td>
				</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
</div>   
<div class="clearfix"></div>
	<div class="paginationfile" align="center">
		<?php 
		if(!empty($num_rows)){		
			rendering_devajaxpagination($Num_Pages,$Page,$Prev_Page,$Next_Page,$params);
		} ?>      
	</div>
	<?php if(!empty($num_rows)) { ?>
	 <div style="padding-left:450px;padding-top:5px;"><span ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printdevajax');" ></span>&nbsp;&nbsp;&nbsp;<span ><input type="button" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span></div>
	<form id="printdevajax" target="_blank" action="printdevtransajax.php" method="post">
		<input type="hidden" name="fromdate" id="fromdate" value="<?php echo $fromdate; ?>" />
		<input type="hidden" name="todate" id="todate" value="<?php echo $todate; ?>" />
		<input type="hidden" name="kd_id" id="kd_id" value="<?php echo $kd_id; ?>" />
		<input type="hidden" name="dsr_id" id="dsr_id" value="<?php echo $dsr_id; ?>" />
		<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
		<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
		<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
	</form>
	<?php } else { ?>
		<span style="padding-left:450px;padding-top:5px;"><input type="button" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>
	<?php } 
exit(0);?>