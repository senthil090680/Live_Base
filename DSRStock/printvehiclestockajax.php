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
	$nextrecval		=	"WHERE VS.Date LIKE '$DateVal%' AND DSR_Code = '$DSR_Code' AND VS.Product_code = '$Prod_Code'";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_REQUEST) && $_REQUEST !='')
{
	$qry="SELECT *,VS.Product_code AS DSLPC,VS.Date AS DSLDATE FROM `dailystockloading` VS LEFT JOIN product AS PROD ON VS.Product_code = PROD.Product_code $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
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
	$orderby	=	"ORDER BY VS.id ASC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.="  $orderby LIMIT $Page_Start , $Per_Page";
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/
?>
<title>VEHICLE STOCK</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<link href="../css/edit.css"  rel="stylesheet" type="text/css" />
<link href="../css/popup.css" rel="stylesheet" type="text/css" />

<style type"text/css">
.condaily_veh{
	width:100%;
	text-align:left;
	height:250px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}
.condaily_veh th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}

.condaily_veh td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	padding-left:10px;
	color:#000;
	font-size:14px;
}

.condaily_veh tbody tr:hover td {
	background: #c1c1c1;
}
.headingsgrdaily_veh{
	background:#a09e9e;
	width:100;
	margin-left:auto;
	margin-right:auto;
	height:25px;
	padding-top:5px;
	border-radius:6px;
	font-weight:bold;
	font-size:14px;
}

#headingsgrdaily_veh{
	background:#fff;
	width:100%;
	margin-left:auto;
	margin-right:auto;
	height:330px;
}
</style>

<span>
<table width="100%" align="left" id="productsadd">
 <tr>
  <td>
  <div <?php if($num_rows == 0) { ?> class="condailynorec" <?php } else { ?> class="condaily_veh" <?php } ?> >
  <table border="1">
  <thead>
	<tr>
		<?php //echo $sortorderby;
		if($sortorder == 'ASC') {
			$sortorderby = 'DESC';
		} elseif($sortorder == 'DESC') {
			$sortorderby = 'ASC';
		} else {
			$sortorderby = 'DESC';
		}
		$paramsval	=	$DateVal."&".$DSR_Code."&".$Prod_Code."&".$sortorderby."&Product_description1"; ?>

		<th nowrap="nowrap" class="rounded" width="61%" onClick="getspecificstockajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');">Product Name<img src="../images/sort.png" width="13" height="13" /></th>
		<th width="4%" align="center">Product Code</th>
		<th width="1%" align="center">UOM</th>
		<th width="4%" align="center">DSR</th>
		<th width="4%" align="center">Load Seq. No.</th>
		<th width="4%" align="center">Loaded Qty</th>
		<th width="2%" align="center">Confirmed Qty</th>
		<th width="4%" align="center">Focus Flag</th>
		<th width="4%" align="center">Scheme Flag</th>
		<th width="4%" align="center" nowrap="nowrap">Date</th>
		<th width="4%" align="center">Product Type</th>
	</tr>
</thead>  
  <tbody id="productsadded">
	<?php $t = 1; 
	if($num_rows >0) {
		while($fetch				=	mysql_fetch_array($results_dsr)) { ?>
		<tr>
			<td ><?php echo ucwords(strtolower($fetch['Product_description1'])); ?></td>
			<td align="center"><?php echo $fetch['DSLPC'];?></td>
			<td align="center"><?php echo $fetch['UOM']; ?></td>
			<td align="center"><?php echo $fetch['DSR_Code'];?></td>
			<td align="center"><?php echo $fetch['Load_Sequence_No']; ?></th>
			<td align="right"><?php echo number_format($fetch['Loaded_Qty']);?></td>
			<td align="right"><?php echo number_format($fetch['Confirmed_Qty']);?></td>
			<td align="center"><?php echo $fetch['focus_Flag'];?></td>
			<td align="center"><?php echo $fetch['scheme_Flag'];?></td>
			<td align="center" nowrap="nowrap"><?php echo $fetch['DSLDATE'];?></td>
			<td align="center"><?php echo $fetch['ProductType'];?></td>
		</tr>
	<?php 	} //while loop
	
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
<div class="paginationfile" align="center">
 <table>
 <tr>
 <th class="pagination" scope="col">          
<?php 
if(!empty($num_rows)){ 
	//rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'getspecificstockajax');
} else {
	echo "&nbsp;";
}
?>
</th>
</tr>
</table>
</div>
</span>
<div id="errormsgcus" style="display:none;"><h3 align="center" class="myalign"></h3><button id="closebutton">Close</button></div>

</div>
<div style="clear:both"></div>
<span id="printopen" style="padding-left:580px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0);?>