<?php
session_start();
ob_start();
require_once('../include/config.php');
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
error_reporting(E_ALL && ~ E_NOTICE);
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_REQUEST);
$id=$_REQUEST['id'];
if($_REQUEST['Product_name']!='')
{
	$var		=	@$_REQUEST['Product_name'] ;
	$trimmed	=	trim($var);	
	$qry="SELECT *,DSL.id AS DSLID, DSL.Date AS DSLDATE, DSL.Product_code AS DSLPC FROM `dailystockloading` AS DSL LEFT JOIN product AS prod ON DSL.Product_code = prod.Product_code WHERE Product_description1 LIKE '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *,DSL.id AS DSLID, DSL.Date AS DSLDATE, DSL.Product_code AS DSLPC FROM `dailystockloading` AS DSL LEFT JOIN product AS prod ON DSL.Product_code = prod.Product_code";
}
$results		=	mysql_query($qry);
$num_rows		=	mysql_num_rows($results);

$params			=	$Product_name."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 12;   // Records Per Page

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
	$orderby	=	"ORDER BY DSL.id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>
<div class="conitems">
<table width="100%">
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
	$paramsval	=	$Product_name."&".$sortorderby."&Product_description1"; ?>

	<th nowrap="nowrap" class="rounded" width="20%" onClick="dailyviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');">Product Name<img src="../images/sort.png" width="13" height="13" /></th>
	<th width="4%" align="center">Product Code</th>
<!--	<th width="1%" align="center">UOM</th>-->
	<th width="4%" align="center">SR</th>
	<th width="5%" align="center">Veh. Reg. No.</th>
	<th width="4%" align="center">Load Seq. No.</th>
	<th width="4%" align="center">Loaded Qty</th>
	<th width="2%" align="center">Confirmed Qty</th>
	<!-- <th width="4%" align="center">Focus Flag</th>
	<th width="4%" align="center">Scheme Flag</th> -->
	<th width="4%" align="center" nowrap="nowrap">Date</th>
	<!--<th width="4%" align="center">Product Type</th>-->
	<th width="4%" align="center">Edit/Del</th>
</tr>
</thead>
<tbody>
<?php
if(!empty($num_rows)){
$c=0;$cc=1;
while($fetch = mysql_fetch_array($results_dsr)) {
if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
$id= $fetch['DSLID'];
?>
<tr>
<td ><?php
$prod_name	=	$fetch['Product_description1'];
if($prod_name == '') {
	$prod_name	=	getdbval($fetch['DSLPC'],'Product_description1','Product_code','customertype_product');
}
echo ucwords(strtolower($prod_name)); ?></td>
<td align="center"><?php echo $fetch['DSLPC'];?></td>
<!--<td align="center"><?php echo $fetch['UOM']; ?></td>-->
<td align="center"><?php echo getdbval($fetch['DSR_Code'],'DSRName','DSR_Code','dsr');?></td>
<td align="center"><?php echo getdbval($fetch['vehicle_code'],'vehicle_reg_no','vehicle_code','vehicle_master');?></td>
<td align="center"><?php echo $fetch['Load_Sequence_No']; ?></th>
<td align="right"><?php echo number_format($fetch['Loaded_Qty']); ?></td>
<td align="right"><?php echo number_format($fetch['Confirmed_Qty']); ?></td>
<!-- <td align="center"><?php echo $fetch['focus_Flag'];?></td>
<td align="center"><?php echo $fetch['scheme_Flag'];?></td> -->
<td align="center" nowrap="nowrap"><?php echo $fetch['DSLDATE'];?></td>
<!--<td align="center"><?php echo $fetch['ProductType'];?></td>-->
<td align="right" align="center" nowrap="nowrap">

<?php $curdate		=	date('Y-m-d');
	//$curdate		=	"2013-08-22";
	$datearr		=	explode(' ',$fetch[DSLDATE]);

	if($datearr[0] == $curdate) { 
?>
<a href="../DSRStock/DailyStockLoading.php?id=<?php echo $fetch['DSLID'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../DSRStock/DailyStockLoading.php?id=<?php echo $fetch['DSLID'];?>&del=del"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>

<?php } else {  echo "No Edit/Del"; } ?>

</td>
</tr>
<?php $c++; $cc++; }		 
}else{  ?>
	<tr>
		<td align='center' colspan='13'><b>No records found</b></td>
		<td style="display:none;" >Cust Name</td>
		<td style="display:none;" >Add Line1</td>
		<td style="display:none;" >Add Line1</td>
		<td style="display:none;" >Add Line2</td>
		<td style="display:none;" >LGA</td>
		<td style="display:none;" >LGA</td>
		<td style="display:none;" >City</th>
		<td style="display:none;" >Contact Person</td>
		<td style="display:none;" >Contact Number</td>
		<td style="display:none;" >Contact Number</td>
	</tr>
<?php }  ?>
</tbody>
</table>
 </div>   
 <div class="paginationfile" align="center">
 <table>
 <tr>
 <th class="pagination" scope="col">          
<?php 
if(!empty($num_rows)){
	rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'dailyviewajax');   //need to uncomment
} else { 
	echo "&nbsp;"; 
} ?>      
</th>
</tr>
</table>
</div>
 <span id="printopen" style="padding-left:470px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printdailyajax');"></span>
<form id="printdailyajax" target="_blank" action="printdailyajax.php" method="post">
	<input type="hidden" name="Product_name" id="Product_name" value="<?php echo $Product_name; ?>" />
	<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
	<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
	<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
</form>
<?php exit(0);?>