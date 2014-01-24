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
//echo $id;
//exit;

if($_REQUEST['Product_name']!='')
{
	$var = @$_REQUEST['Product_name'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `stock_receipts` where Product_name like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *  FROM `stock_receipts`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);
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
	$orderby	=	"ORDER BY id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.=" $orderby";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/
?>
<title>STOCK RECEIPTS</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>

<style type="text/css">
	.con_str {
		width:100%;
		text-align:left;
		border-collapse:collapse;
		background:#a09e9e;
		margin-left:auto;
		margin-right:auto;
		border-radius:10px;
	}
	.con_str th {
		padding:2px 5px 0 5px;
		font-weight:bold;
		font-size:13px;
		color:#000;
	}
	.con_str td {
		padding:2px 5px 0 5px;
		background:#fff;
		border-collapse:collapse;
		color: #000;
		font-size:13px;
	}
	.con_str tbody tr:hover td {
		background: #c1c1c1;
	}
	#containerpr_str {
		padding:0px;
		width:100%;
		margin-left:auto;
		margin-right:auto;
	}
</style>

<table width="100%" border="1" style="border-collapse:collapse;">
<thead>
<tr>
	<th width="5%" align="center">Sl. No.</th>
	<?php //echo $sortorderby;
	if($sortorder == 'ASC') {
		$sortorderby = 'DESC';
	} elseif($sortorder == 'DESC') {
		$sortorderby = 'ASC';
	} else {
		$sortorderby = 'DESC';
	}
	$paramsval	=	$Product_name."&".$sortorderby."&Product_code"; ?>
	<th width="5%" class="rounded" onClick="receiptsviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');">Pro. Code<img src="../images/sort.png" width="13" height="13" /></th>
	<th width="30%" align="center">Product Name</th>
	<!--<th width="5%" align="center">UOM</th>-->
	<th width="5%" align="center">Quantity</th>	
</tr>
</thead>
<tbody>
<?php
if(!empty($num_rows)){
$c=0;$cc=1;                   
$slno	=	($Page-1)*$Per_Page + 1; //for serial number
while($fetch = mysql_fetch_array($results_dsr)) {
if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
$id= $fetch['id'];
?>
<tr>
<td align="center"><?php echo $slno; ?></td>  <!--for serial number -->
<td><?php echo $fetch['Product_code'];?></td>
<td><?php echo $fetch['Product_name'];?></td>
<!--<td align="center"><?php echo $fetch['UOM'];?></td>-->
<td align="right"><?php echo number_format($fetch['quantity']); ?></td>
</tr>		
<?php $c++; $cc++; 
	$slno++;  //for serial number
  }		 
}else{  ?> 

	<tr>
		<td align='center' colspan='6'><b>No records found</b></td>	
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
	//rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'receiptsviewajax');   //need to uncomment
}
else { 
	echo "&nbsp;"; 
} ?>      
</th>
</tr>
</table>
<span id="printopen"<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0); ?>