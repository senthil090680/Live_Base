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

$params			=	$Product_name."&".$sortorder."&".$ordercol;
		
if($_REQUEST['Product_name'] != '') {
	$var = @$_REQUEST['Product_name'];
	$trimmed = trim($var);	
	$qry="SELECT * FROM `opening_stock_update` where Product_description like '%".$trimmed."%'";
}
else
{ 
	$Product_name	=	'';
	$qry="SELECT *  FROM `opening_stock_update`";
}
$results=mysql_query($qry);

/*$pager = new PS_Pagination($bd, $qry,5,5);
$results = $pager->paginate();*/

$num_rows= mysql_num_rows($results);

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 15;   // Records Per Page

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
<title>Opening Stock Update</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>

<style type="text/css">
#containerdailysto {
	padding:0px;
	width:100%;	
	margin-left:auto;
	margin-right:auto;
}
.conitems {
	width:100%;
	height:100%
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}
.conitems th {
	font-weight:bold;
	font-size:13px;
	color:#000;
}
.conitems td {
	background:#fff;
	border-collapse:collapse;
	color: #000;
	font-size:13px;
}
.conitems tbody tr:hover td {
	background: #c1c1c1;
}
</style>

<table width="100%" align="center" border="1" style="border-collapse:collapse">
<thead>
<tr>
<th nowrap="nowrap" width="30%" align="center">Product Name</th>
<?php //echo $sortorderby;
if($sortorder == 'ASC') {
	$sortorderby = 'DESC';
} elseif($sortorder == 'DESC') {
	$sortorderby = 'ASC';
} else {
	$sortorderby = 'DESC';
}
$paramsval	=	$Product_name."&".$sortorderby."&Product_code"; ?>
<th scope="col" align="center" class="rounded" width="10%" onClick="openviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');">Product Code<img src="../images/sort.png" width="13" height="13" /></th>
<th nowrap="nowrap" align="center" width="7%">Date</th>
<!--<th nowrap="nowrap" align="center" width="5%">UOM</th>-->
<th nowrap="nowrap" align="center" width="10%">Trans. Type</th>
<th nowrap="nowrap" align="center" width="5%">Trans. Qty</th>
<th nowrap="nowrap" align="center" width="5%">Balance Qty</th>
<!-- <th nowrap="nowrap" align="right">Edit/Delete</th> -->
</tr>
</thead>
<tbody>
<?php
if(!empty($num_rows)){
$c=0;$cc=1;
while($fetch = mysql_fetch_array($results_dsr)) {
if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
$id= $fetch['id'];
?>
<tr>
<td nowrap="nowrap"><?php $sel_prname	=	"SELECT * FROM `product` WHERE Product_code = '$fetch[Product_code]'"; 
	$results_prname=mysql_query($sel_prname);
	$row_prname= mysql_fetch_array($results_prname);
echo ucwords(strtolower($row_prname['Product_description1'])); ?></td>
<td nowrap="nowrap"><?php echo $fetch['Product_code'];?></td>
<td nowrap="nowrap"><?php echo $fetch['Date']; ?></td>
<!--<td nowrap="nowrap" align="center"><?php echo $fetch['UOM1']; ?></td>-->
<td nowrap="nowrap"><?php echo $fetch['TransactionType']; ?></td>
<td nowrap="nowrap" align="right"><?php echo number_format($fetch['TransactionQty']); ?></td>
<td nowrap="nowrap" align="right"><?php echo number_format($fetch['BalanceQty']); ?></td>
<!-- <td align="right">
		<a href="openingstockedit.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="openingstockedit.php?id=<?php echo $fetch['id'];?>&del=del"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
		</td> -->
</tr>
<?php $c++; $cc++; }		 
}else{ ?>
	<tr>
		<td align='center' colspan="7"><b>No records found</b></td>
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
	echo $pager->renderLast();*/
	//rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'openviewajax');   //need to uncomment

} else { 
	echo "&nbsp;"; 
} ?>      
</th>
</tr>
</table>
<span id="printopen"<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0); ?>