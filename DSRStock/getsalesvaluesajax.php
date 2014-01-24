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
$params=$DateVal."&".$DSR_Code."&".$Prod_Code."&".$sortorder."&".$ordercol;
if(isset($_REQUEST[DateVal]) && $_REQUEST[DateVal] !='') {
	$nextrecval		=	"WHERE Date = '$DateVal' AND DSR_Code = '$DSR_Code'";	
	$prod_code		=	"AND VS.Product_code = '$Prod_Code'";
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_REQUEST) && $_REQUEST !='')
{
	$qry_trans="SELECT * FROM `transaction_hdr` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}

$res_transhdr													=   mysql_query($qry_trans);
$transno_transhdr												=	array();
while($row_transhdr												=   mysql_fetch_assoc($res_transhdr)) {		
	$Transaction_Number											=	$row_transhdr[Transaction_Number];	
	if($row_transhdr[transaction_Reference_Number] !='' && $row_transhdr[transaction_Reference_Number] != '0') {
		$transaction_Reference_Number_cancel[]					=   $row_transhdr[transaction_Reference_Number];
		$transno_cancel_number[]								=   $row_transhdr[Transaction_Number];
	}
	$transhdr_result[]									=   $row_transhdr;
	$transhdrInfo[$row_transhdr[Transaction_Number]]	=   $row_transhdr;
	$transno_transhdr[]									=   $row_transhdr[Transaction_Number];
}
 
//pre($transno_transhdr);
//pre($transaction_Reference_Number_cancel);
//pre($transno_cancel_number);

foreach($transaction_Reference_Number_cancel AS $REFVAL){
	if(array_search($REFVAL,$transno_transhdr)) {
		$arraysearchval		=	array_search($REFVAL,$transno_transhdr);
		unset($transno_transhdr[$arraysearchval]);
		unset($transhdr_result[$arraysearchval]);
	}
}

//pre($transno_transhdr);
foreach($transno_cancel_number AS $REFVAL){
	if(array_search($REFVAL,$transno_transhdr)) {
		$arraysearchval		=	array_search($REFVAL,$transno_transhdr);
		unset($transno_transhdr[$arraysearchval]);
		unset($transhdr_result[$arraysearchval]);
		//array_splice($transno_transhdr, $arraysearchval, 1);
	}
}

//pre($transhdr_result);
//pre($transno_transhdr);

//exit;
$transno_transhdr		=	array_unique($transno_transhdr);
$transno_Total			=	implode("','",$transno_transhdr);
//exit;

$qry									=   "SELECT *,VS.id AS TLID, DSR_Code,Transaction_Number,Transaction_type,VS.Product_code AS DSLPC,UOM,Focus_Flag,POSM_Flag,Scheme_Flag,Sold_quantity,Price,Value FROM transaction_line VS LEFT JOIN product PROD ON VS.Product_code = PROD.Product_code WHERE Transaction_Number IN ('".$transno_Total."') AND VS.Product_code = '$Prod_Code'";
//echo $qry;
//exit;

$results_qry=mysql_query($qry);
$num_rows= mysql_num_rows($results_qry);
//exit;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 3;   // Records Per Page

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
	$orderby	=	"ORDER BY Transaction_Number ASC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.="  $orderby LIMIT $Page_Start , $Per_Page";

$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/
?>
<style type="text/css">
.popupOpenVehicle {
	margin:0 auto;
	display:none;
	background:#EEEEEE;
	color:#fff;
	width:1290px;
	height:460px;
	position:fixed;
	left:50px;
	top:0px;
	border-bottom:2px solid #A09E9E;
	z-index:3;
	border-radius:2px 2px 2px 2px;
}
.condailynorecords{
	width:100%;
	text-align:left;
	height:150px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}

.condailynorecords th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}

.condailynorecords td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	padding-left:10px;
	color:#000;
	font-size:14px;
}

.condailynorecords tbody tr:hover td {
	background: #c1c1c1;
}


</style>
<?php 
//echo $qry;
//exit;
?>
<div style="clear:both"></div>
<div>
<table width="100%" align="left" id="productsadd">
 <tr>
  <td>
  <div <?php if($num_rows == 0) { ?> class="condailynorecords" <?php } else { ?> class="condaily_veh" <?php } ?> >
  <table border="1">
  <thead>
	<tr>
		<th align="center" colspan="11"><h2>Sales Product Line Items</h2></th>
	</tr>
	<tr>
		<th align="center" style="width:2%" >Ln. Num.</th>
		<th align="center" style="width:8%" >Product Code</th>
		<?php //echo $sortorderby;
		if($sortorder == 'ASC') {
			$sortorderby = 'DESC';
		} elseif($sortorder == 'DESC') {
			$sortorderby = 'ASC';
		} else {
			$sortorderby = 'DESC';
		}
		$paramsval	=	$DateVal."&".$DSR_Code."&".$Prod_Code."&".$sortorderby."&Product_description1"; ?>
		<th align="center" style="width:62%" ><span style="cursor:hand;cursor:pointer;">Product Name <img src="../images/sort.png" width="13" height="13" /></span></th>
		<th align="center" style="width:5%" >UOM</th>

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
	</tr>
</thead>  
  <tbody id="productsadded" style="font-weight:lighter;">
	<?php $t = 1; 
	if($num_rows >0) {
		$slno	=	($Page-1)*$Per_Page + 1;
		while($fetch				=	mysql_fetch_array($results_dsr)) { 
			$deidj[]	=	$fetch;
			$devtransactionid		=		$fetch['TLID'];
			$Transaction_Number		=		$fetch['Transaction_Number'];
			$Transaction_Line_Number=		$fetch['Transaction_Line_Number'];
			$Focus_Flag				=		$fetch['Focus_Flag'];
			$POSM_Flag				=		$fetch['POSM_Flag'];
			$Scheme_Flag			=		$fetch['Scheme_Flag'];
			$Product_Scheme_Flag	=		$fetch['Product_Scheme_Flag'];
			$Product_code			=		$fetch['Product_code'];
			$UOM					=		$fetch['UOM'];
			$Customer_Stock_quantity=		$fetch['Customer_Stock_quantity'];
			$Customer_Stock_Check	=		$fetch['Customer_Stock_Check'];
			$Order_quantity			=		$fetch['Order_quantity'];
			$Sold_quantity			=		$fetch['Sold_quantity'];
			$priceval				=		$fetch['Price'];
			$valueval				=		$fetch['Value'];
			?>
	<tr>
		<td align="center" style="width:2%"><?php echo $slno; ?></td>
		<td align="center" style="width:8%"><span <?php if($BatchCount != '') { ?> onclick="getbatchcontrol('<?php echo $Transaction_Number; ?>','<?php echo $Transaction_Line_Number; ?>');" style="cursor:pointer;cursor:hand;" <?php } ?> ><?php echo $fetch['Product_code']; ?></span></td>
		<td align="center" style="width:62%" ><span <?php if($BatchCount != '') { ?> onclick="getbatchcontrol('<?php echo $Transaction_Number; ?>','<?php echo $Transaction_Line_Number; ?>');" style="cursor:pointer;cursor:hand;" <?php } ?> ><?php $sel_prname	=	"SELECT *  FROM `product` WHERE Product_code = '$Product_code'"; 
			$results_prname=mysql_query($sel_prname);
			$row_prname= mysql_fetch_array($results_prname);
			echo ucwords(strtolower($row_prname['Product_description1'])); ?></span></td>
		<td align="center" style="width:5%"><?php echo $UOM;?></td>

		<td align="center" style="width:3%"><?php if($Focus_Flag == 1) { echo "Yes"; } else { echo "No"; } ?></td>
		<td align="center" style="width:3%"><?php if($Scheme_Flag == 1) { echo "Yes"; } else { echo "No"; }  ?></td>
		<td align="center" style="width:3%"><?php echo $fetch['Customer_Stock_quantity']; ?></td>
		<td align="center" style="width:3%"><?php echo $Product_type; ?></td>
		<td style="width:5%"><table border="0" width="100%">
				<tr>
					<td align="center"><?php if($fetch['Order_quantity'] != '') { echo $fetch['Order_quantity']; } else { echo "0"; } ?></td><td align="center"><?php if($fetch['Sold_quantity'] != '') { echo $fetch['Sold_quantity']; } else { echo "0"; } ?></td><td align="center"><?php if($Product_Scheme_Flag == 1 && $Scheme_Flag == 0) { echo "1"; } else { echo "0"; } ?></td>
				</tr>
				</table></td>
		<td align="center" style="width:3%"><?php echo $priceval; ?></td>
		<td align="center" style="width:3%"><?php echo $valueval; ?></td>
	</tr>
	<?php $slno++;	} //while loop

	//pre($deidj);
	
	} else { ?>
		<tr style="background:white;">
			<td align='center' colspan="11">No Records Found.</td>		
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
			<td style="display:none;" >Add Line2</td>
			<td style="display:none;" >LGA</td>
			<td style="display:none;" >City</th>
			<td style="display:none;" >Contact Person</td>
			<td style="display:none;" >Contact Number</td>
			<td style="display:none;" >Contact Number</td>
			<td style="display:none;" >Contact Number</td>
		</tr>
		<?php } ?>
  </tbody>
   </table>
     </div>
   </td>
 </tr>
</table>
</div>
<div style="clear:both"></div>
<div class="paginationfile" align="center">
 <table>
 <tr>
 <th class="pagination" scope="col">          
<?php 
if(!empty($num_rows)){ 
	rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'getspecificsalesajax');
} else {
	echo "&nbsp;";
}
?>
</th>
</tr>
</table>
</div>

<div id="errormsgcus" style="display:none;"><h3 align="center" class="myalign"></h3><button id="closebutton">Close</button></div>

</div>
<div style="clear:both"></div>
<span style="display:inline;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>">
<span id="printopen" style="padding-left:590px;" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printvehiclesalesajax<?php echo $DSR_Code.$Prod_Code; ?>');"></span><span style="padding-left:20px;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="closePopupBox(this,'<?php echo $Prod_Code.$DSR_Code; ?>','0','0');" ></span>
</span>
<form id="printvehiclesalesajax<?php echo $DSR_Code.$Prod_Code; ?>" target="_blank" action="printvehiclesalesajax.php" method="post">
	<input type="hidden" name="DateVal" id="DateVal" value="<?php echo $DateVal; ?>" />
	<input type="hidden" name="DSR_Code" id="DSR_Code" value="<?php echo $DSR_Code; ?>" />
	<input type="hidden" name="Prod_Code" id="Prod_Code" value="<?php echo $Prod_Code; ?>" />
	<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
	<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
	<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
</form>
<?php exit(0);?>