<?php
session_start();
ob_start();
error_reporting(0);
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
error_reporting(0);
extract($_REQUEST);
$params=$fromDate."&".$toDate;
$totalval=0;
$totalvalue		=	0;
//$query_allprod			=	"SELECT UOM1,Product_description1,Product_code FROM `product`";

$query_allprod			=	"SELECT UOM1,Product_description1,Product_code FROM `product` UNION SELECT UOM1,Product_description1,Product_code FROM customertype_product";
$result_allprod			=	mysql_query($query_allprod) or die(mysql_error());
$num_rows				=	mysql_num_rows($result_allprod);
$qry					=	$query_allprod;
while($row_allprod		=	mysql_fetch_array($result_allprod)) {
	$allprods[]			=	$row_allprod[Product_code];
}
$results_dsr=mysql_query($qry) or die(mysql_error());
//$pager = new PS_Pagination($bd, $qry,5,5);
//$results = $pager->paginate();
$num_rows= mysql_num_rows($results_dsr);

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
$Num_Pages =	1;
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
	$orderby	=	"ORDER BY Product_code";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
//$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
$qry.=" $orderby";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>
<title>Stock Status</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<link href="../css/edit.css" type="text/css" rel="stylesheet"/> 
<link href="../css/editbox.css" type="text/css" rel="stylesheet" /> 

<style type="text/css">
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
</style>

	<table width="100%" border="1" style="border-collapse:collapse">
	<thead>
	<tr>
	<th class="rounded" nowrap="nowrap">Product Code<img src="../images/sort.png" width="13" height="13" /></th>
	<th>Product Name</th>
	<!--<th>UOM</th>-->
	<th>Quantity</th>
	<th>Price</th>
	<th>Value</th>
	</tr>
	</tr>
	</thead>
	<tbody>
	<?php
	if(!empty($num_rows)){
		$OpeningStock			=		0;
		$c=0;$cc=1;$y=0;
		$OpeningStock = 0;
		while($fetch = mysql_fetch_array($results_dsr)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$Product_codeval			=		$fetch['Product_code'];
		$pname						=		$fetch['Product_description1'];
		$puom						=		$fetch['UOM1'];
		
		$qry_closing			=	"select BalanceQty from opening_stock_update where Product_code = '$Product_codeval' AND (Date BETWEEN '$fromDate' AND '$toDate' OR Date < '$fromDate') ORDER BY id DESC"; 
		$res_closing			=	mysql_query($qry_closing) or die(mysql_error());
		if(mysql_num_rows($res_closing) > 0) {
			$row_closing		=	mysql_fetch_array($res_closing);
			$closingstock		=	$row_closing[BalanceQty];
		}
		
		//$todayDate					=	date('Y-m-d');
		$qry_openingid			=	"select id from opening_stock_update where Product_code = '$Product_codeval' AND (Date BETWEEN '$fromDate' AND '$toDate') ORDER BY id ASC"; 
		$res_openingid			=	mysql_query($qry_openingid) or die(mysql_error());
		if(mysql_num_rows($res_openingid) > 0) {
			$row_openingid		=	mysql_fetch_array($res_openingid);
			$OpeningStockid		=	$row_openingid[id];
			$qry_opening			=	"select BalanceQty from opening_stock_update where Product_code = '$Product_codeval' AND (id < '$OpeningStockid') ORDER BY id DESC"; 
			$res_opening			=	mysql_query($qry_opening) or die(mysql_error());
			if(mysql_num_rows($res_opening) > 0) {
				$row_opening		=	mysql_fetch_array($res_opening);
				$OpeningStock		=	$row_opening[BalanceQty];
			} else {
				$qry_openingval			=	"select BalanceQty from opening_stock_update where Product_code = '$Product_codeval' AND (id = '$OpeningStockid') ORDER BY id DESC"; 
				$res_openingval			=	mysql_query($qry_openingval) or die(mysql_error());
				if(mysql_num_rows($res_openingval) > 0) {
					$row_openingval		=	mysql_fetch_array($res_openingval);
					$OpeningStock		=	$row_openingval[BalanceQty];
				}
			}
		} else {
			$qry_openingid			=	"select id AS ID from opening_stock_update where Product_code = '$Product_codeval' AND (Date < '$fromDate') ORDER BY id DESC"; 
			$res_openingid			=	mysql_query($qry_openingid) or die(mysql_error());
			if(mysql_num_rows($res_openingid) > 0) {
				$row_openingid		=	mysql_fetch_array($res_openingid);
				$OpeningStockid		=	$row_openingid[ID];
				
				$qry_openingval			=	"select BalanceQty from opening_stock_update where Product_code = '$Product_codeval' AND (id = '$OpeningStockid') ORDER BY id DESC"; 
				$res_openingval			=	mysql_query($qry_openingval) or die(mysql_error());
				if(mysql_num_rows($res_openingval) > 0) {
					$row_openingval		=	mysql_fetch_array($res_openingval);
					$OpeningStock		=	$row_openingval[BalanceQty];
				}
			}
		}

		if($OpeningStock == '') { 
			$OpeningStock		=	0;
			$closingstock		=	0;
		}
		
		//$OpeningStock					=	$fetch['BalanceQty'];
		?>
		<tr>
		<td align="center"><?php echo $fetch['Product_code'];?></td>
		<td><?php echo $pname; ?></td>
		<!--<td align="center"><?php echo $puom;?></td>-->
		<td align="right" >
		<?php 
		$sel_receipt	=	"SELECT sum(quantity) as recqty FROM `stock_receipts` WHERE (Product_code = '$Product_codeval') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_receipt	=	mysql_query($sel_receipt)  or die(mysql_error());
		$row_receipt		=	mysql_fetch_array($results_receipt);

		$sel_issue	=	"SELECT sum(issued_quantity) as issqty FROM `stock_issue` WHERE (Product_code = '$Product_codeval') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_issue	=	mysql_query($sel_issue)  or die(mysql_error());
		$row_issue		=	mysql_fetch_array($results_issue);
		
		//echo intval($row_receipt[recqty])."ydh";
		$sel_dsreturn	=	"SELECT sum(quantity) as dsrqty FROM `dsr_return` WHERE (Product_code = '$Product_codeval') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_dsreturn	=	mysql_query($sel_dsreturn)  or die(mysql_error());
		$row_dsreturn		=	mysql_fetch_array($results_dsreturn);

		$sel_cusreturn	=	"SELECT sum(quantity) as cusqty FROM `customer_return` WHERE (Product_code = '$Product_codeval') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_cusreturn	=	mysql_query($sel_cusreturn)  or die(mysql_error());
		$row_cusreturn		=	mysql_fetch_array($results_cusreturn);

		$sel_adjust	=	"SELECT sum(quantity) as adjqty FROM `stock_adjustment` WHERE (Product_code = '$Product_codeval') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_adjust	=	mysql_query($sel_adjust)  or die(mysql_error());
		$row_adjust		=	mysql_fetch_array($results_adjust);
		//echo $OpeningStock;		
		?>
		<!-- <span style="cursor:pointer;cursor:hand;color:#4285F4;" onClick="bringPopup('<?php echo $fromDate; ?>','<?php echo $toDate; ?>','<?php echo $fetch[Product_code]; ?>','<?php echo intval($OpeningStock); ?>','<?php echo (intval($row_receipt[recqty]) + intval($row_dsreturn[dsrqty]) + intval($row_cusreturn[cusqty])); ?>','<?php echo intval($row_issue[issqty]); ?>','<?php echo intval($row_adjust[adjqty]); ?>','<?php echo $cc.$Page; ?>','<?php echo $pname; ?>')" > -->

		<span>

		<?php //echo $fetch['BalanceQty'];
				
		echo number_format($closingstock); ?></span></td>
		
		<?php $sel_priceval			=	"SELECT Price FROM `price_master` WHERE Product_code = '$Product_codeval'"; 
			$results_priceval		=	mysql_query($sel_priceval)  or die(mysql_error());
			$row_priceval			=	mysql_fetch_array($results_priceval);
			$priceval				=	$row_priceval['Price'];
		?>

		 <td align="right"><?php echo number_format($priceval,2); ?></td>
		  <td align="right"><?php //$closingstock = intval($OpeningStock) + intval($row_receipt[recqty]) + intval($row_issue[issqty]) + intval($row_adjust[adjqty]) + intval($row_dsreturn[dsrqty]);
		  $totalvalue		+=	($priceval * $closingstock);
		 echo number_format(($priceval * $closingstock),2); ?></td>
		</tr>
		<?php $c++; $cc++; $y++; 
		$OpeningStock = 0;
		$closingstock = 0;
		$priceval = 0;
		
		
		}
		}else{ ?>
		<tr>
			<td align='center' colspan='13'><b>No records found</b></td>
			<th style="display:none;" >Cust Name</th>
			<th style="display:none;" >Add Line1</th>
			<th style="display:none;" >Add Line2</th>		
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
	}
	?>
	</th>
	</tr>
	</table>
  </div>	  
	<span id="printopen" style="padding-left:580px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0); ?>