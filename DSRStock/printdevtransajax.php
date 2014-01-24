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
extract($_POST);
$params=$fromdate."&".$todate."&".$kd_id."&".$dsr_id."&".$sortorder."&".$ordercol;
if(isset($_POST[kd_id]) && $_POST[kd_id] !='') {
	$nextrecval		=	"WHERE (Date BETWEEN '$fromdate' AND '$todate') AND trhe.KD_Code = '$kd_id' AND trhe.DSR_Code = '$dsr_id'";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_POST) && $_POST !='')
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
//$pager		=	new PS_Pagination($bd, $qry,15,15);
//$results		=	$pager->paginate();

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page	=	4;   // Records Per Page

$Page = $strPage;
if(!$strPage)
{
$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($num_rows<=$Per_Page) {
	$Num_Pages =1;
}
else if(($num_rows % $Per_Page)==0) {
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
//$qry.="  $orderby LIMIT $Page_Start , $Per_Page";
$qry.="  $orderby ";
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination ends****************************/

?>
<title>DEVICE TRANSACTIONS</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="../js/jconfirmaction.jquery.js"></script>
<link type="text/css" rel="stylesheet" href="../css/popup.css" />

<style type="text/css">
#tablestr_dev {
	width:100%;
	margin-left:auto;
	margin-right:auto;
}

.con3_dev{
	width:100%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}
.con3_dev th {
	width:22%;
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:13px;
	color:#000;
}
.con3_dev td  {
	padding:2px 3px 0 3px;
	background:#fff;
	border-collapse:collapse;
	color: #000;
	font-size:13px;
}
.con3_dev tbody tr:hover td {
	background: #c1c1c1;
}

.buttons_new{
	-webkit-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	-moz-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	border-bottom-color:#333;
	border:1px solid #686868;
	background-color:#31859C;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	color:#000;
	font-family:Calibri;
	font-size:12px;
	padding:3px;
	cursor:pointer;
	width:50px;
	height:25px;
}
.headerdevice {
	margin-left:auto;
	margin-right:auto;
	width:99%;
	height:70px;
	padding:10px 0px 10px 0px;
	border-radius:10px;
	background:#C1C1C1;
}
.statictabledev {
	width:100%;
	float:left;
	padding-top:10px;
}

.conitems {
	width:100%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}
.conitems th {
	width:22%;
	/*padding:2px 5px 0 5px;*/
	font-weight:bold;
	font-size:13px;
	color:#000;
}
.conitems td {
	/*padding:2px 5px 0 5px;*/
	background:#fff;
	border-collapse:collapse;
	color: #000;
	font-size:13px;
}
.conitems tbody tr:hover td {
	background: #c1c1c1;
}

.confirmFirstDeviceTrans {
	top:150px;
	left:180px;
	width:74%;
	height:500px;
	background:#EEEEEE;
	position:fixed;
	margin:0 auto;
	display:none;
	border-bottom:2px solid #A09E9E;
	z-index:3;
	border-radius:2px 2px 2px 2px;
	color:#fff;
}
.confirmFirstDeviceImage {
	margin:0 auto;
	display:none;
	background:#EEEEEE;
	color:#fff;
	width:400px;
	height:300px;
	position:fixed;
	left:750px;
	top:250px;
	border-bottom:2px solid #A09E9E;
	z-index:3;
	border-radius:2px 2px 2px 2px;
}
.confirmFirstDeviceSig {
	margin:0 auto;
	display:none;
	background:#EEEEEE;
	color:#fff;
	width:400px;
	height:300px;
	position:fixed;
	left:750px;
	top:250px;
	border-bottom:2px solid #A09E9E;
	z-index:3;
	border-radius:2px 2px 2px 2px;
}
.confirmFirstDeviceFeed {
	margin:0 auto;
	display:none;
	background:#EEEEEE;
	color:#fff;
	width:400px;
	height:75px;
	position:fixed;
	left:750px;
	top:250px;
	border-bottom:2px solid #A09E9E;
	z-index:3;
	border-radius:2px 2px 2px 2px;
}
.confirmBatchControl {
	margin:0 auto;
	display:none;
	background:#EEEEEE;
	color:#fff;
	width:400px;
	height:75px;
	position:fixed;
	left:150px;
	top:380px;
	border-bottom:2px solid #A09E9E;
	z-index:4;
	border-radius:2px 2px 2px 2px;
}
.myaligndev {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}

#errormsgdev{
	width:40%;
	height:30px;
	background:#c1c1c1;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	padding-top:0px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	-ms-border-radius:10px;
	-o-border-radius:10px;
	text-align:center;
}
</style>
<div style="clear:both;"> </div>
<div class="statictabledev">
	
	 <table width="100%" border="1" style="border-collapse:collapse">
		<thead>
		<tr>
			<th align="left" colspan="15">
				<div >DEVICE TRANSACTIONS : &nbsp;&nbsp;&nbsp;&nbsp;FROM : <?php echo $fromdate; ?> &nbsp;&nbsp;&nbsp;&nbsp;TO : <?php echo $todate; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DSR : <?php echo $dsr_id; ?> &nbsp;&nbsp;&nbsp;&nbsp; PAGE : <?php if(isset($_REQUEST[page]) && $_REQUEST[page] !='') { echo $_REQUEST[page];  } else { echo "1";  }?> </div>
			</th>
		</tr>
		<tr>
		<th align='center' style="width:4%">Date</th>
		<th align='center' style="width:4%">Time</th>
		<th align='center' style="width:7%">GPS</th>
		<th align='center' style="width:8%">Customer Name</th>
		<th align='center' style="width:5%">Transaction Type</th>
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
		<td align="center" style="width:5%"><span style="cursor:pointer;cursor:hand;"><?php echo $devtransactionno; ?></span></td>

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
		<td align="right" style="width:8%"><?php echo number_format($fetch['Net_Sale_value'],2); ?></td>
		<td align="right" style="width:8%"><?php echo number_format($fetch['Collection_Value'],2); ?></td>
		<td align="right" style="width:8%"><?php echo number_format($fetch['Balance_Due_Value'],2); ?></td>
		<td align="center" style="width:8%">
		
		<?php if(($devcusimage != '') && (!is_null($devcusimage))) { ?> 			
		<span>Image Available</span>  
		
		<?php } else { ?>
			<span>No Customer Image</span> 
		<?php } ?>

		</td>
		<td align='center' style="width:8%">
		
		<?php if(($devsigimage != '') && (!is_null($devsigimage))) { ?> 			
		<span>Signature Available</span>  
		
		<?php } else { ?>
			<span>No Signature</span> 
		<?php } ?>

		</td>
		<td align='center' style="width:8%">		
			<?php if($rowcnt_feedback > 0) { ?> 			
			<span>Feedback Available</span>  
			
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
<div class="clearfix"></div>
	<div class="paginationfile" align="center">
		<?php 
		if(!empty($num_rows)){		
			//rendering_devajaxpagination($Num_Pages,$Page,$Prev_Page,$Next_Page,$params);
		} ?>      
	</div>
	<span id="printopen" style="padding-left:580px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0);?>