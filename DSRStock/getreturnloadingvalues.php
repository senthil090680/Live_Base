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
	$nextrecval		=	"WHERE Date = '$DateVal' AND DSR_Code = '$DSR_Code' AND Transaction_type = '4'";	
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
	$transno_transhdr[]											=   $row_transhdr[Transaction_Number];
}
 
//pre($transno_transhdr);
//pre($transaction_Reference_Number_cancel);
//pre($transno_cancel_number);
//exit;
$transno_transhdr		=	array_unique($transno_transhdr);
$transno_Total			=	implode("','",$transno_transhdr);
//exit;

$qry									=   "SELECT *,VS.id AS TLID, DSR_Code,Transaction_Number,VS.Product_code AS DSLPC,UOM,Reurn_quantity FROM transaction_return_line VS LEFT JOIN product PROD ON VS.Product_code = PROD.Product_code WHERE Transaction_Number IN ('".$transno_Total."') AND VS.Product_code = '$Prod_Code'";
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

$Per_Page = 1;   // Records Per Page

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
	height:360px;
	position:fixed;
	left:50px;
	top:0px;
	border-bottom:2px solid #A09E9E;
	z-index:3;
	border-radius:2px 2px 2px 2px;
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
  <div <?php if($num_rows == 0) { ?> class="condailynorec" <?php } else { ?> class="condaily_veh" <?php } ?> >
  <table border="1">
  <thead>
	<tr>
		<th align="center" colspan="11"><h2>Return Product Line Items</h2></th>
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
		<th align="center" style="width:3%">Return Quantity</th>
	</tr>
</thead>  
  <tbody id="productsadded" style="font-weight:lighter;">
	<?php $t = 1; 
	if($num_rows >0) {
		$slno	=	($Page-1)*$Per_Page + 1;
		while($fetch					=	mysql_fetch_array($results_dsr)) { 
			$deidj[]	=	$fetch;
			$devtransactionid			=		$fetch['TLID'];
			$Transaction_Number			=		$fetch['Transaction_Number'];
			$Transaction_Line_Number	=		$fetch['Transaction_Line_Number'];
			$Product_code				=		$fetch['Product_code'];
			$UOM						=		$fetch['UOM'];
			$Return_quantity			=		$fetch['Reurn_quantity'];
			?>
		<tr>
	<td align="center" style="width:2%"><?php echo $slno; ?></td>
	<td align="center" style="width:8%"><span <?php if($BatchCount != '') { ?> onclick="getbatchcontrol('<?php echo $Transaction_Number; ?>','<?php echo $Transaction_Line_Number; ?>');" style="cursor:pointer;cursor:hand;" <?php } ?> ><?php echo $fetch['Product_code']; ?></span></td>
	<td align="center" style="width:62%" ><span <?php if($BatchCount != '') { ?> onclick="getbatchcontrol('<?php echo $Transaction_Number; ?>','<?php echo $Transaction_Line_Number; ?>');" style="cursor:pointer;cursor:hand;" <?php } ?> ><?php $sel_prname	=	"SELECT *  FROM `product` WHERE Product_code = '$Product_code'"; 
		$results_prname=mysql_query($sel_prname);
		$row_prname= mysql_fetch_array($results_prname);
		echo ucwords(strtolower($row_prname['Product_description1'])); ?></span></td>
	<td align="center" style="width:5%"><?php echo $UOM;?></td>
	<td align="center" style="width:3%"><?php echo $Return_quantity; ?></td>
	</tr>
	<?php $slno++;	} //while loop

	//pre($deidj);
	
	} else { ?>
		<tr>
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
	rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'getspecificreturnajax');
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
<span id="printopen" style="padding-left:590px;" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printvehiclereturnajax<?php echo $DSR_Code.$Prod_Code; ?>');"></span><span style="padding-left:20px;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="closePopupBox(this,'<?php echo $Prod_Code.$DSR_Code; ?>','0','0');" ></span>
</span>
<form id="printvehiclereturnajax<?php echo $DSR_Code.$Prod_Code; ?>" target="_blank" action="printvehiclereturnajax.php" method="post">
	<input type="hidden" name="DateVal" id="DateVal" value="<?php echo $DateVal; ?>" />
	<input type="hidden" name="DSR_Code" id="DSR_Code" value="<?php echo $DSR_Code; ?>" />
	<input type="hidden" name="Prod_Code" id="Prod_Code" value="<?php echo $Prod_Code; ?>" />
	<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
	<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
	<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
</form>
<?php exit(0);?>