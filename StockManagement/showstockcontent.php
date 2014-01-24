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
$params=$fromDate."&".$toDate."&".$sortorder."&".$ordercol."&".$Product_name;
$totalval		=	0;
$totalvalue		=	0;
//$query_allprod			=	"SELECT UOM1,Product_description1,Product_code FROM `product`";

if($_REQUEST['Product_name'] != '') {
	$var = @$_REQUEST['Product_name'];
	$trimmed = trim($var);	
	//$qry="SELECT * FROM `opening_stock_update` where Product_description like '%".$trimmed."%'";
	$query_allprod			=	"SELECT prod.UOM1,prod.Product_description1,prod.Product_code FROM `product` prod WHERE Product_description1 like '%".$trimmed."%' UNION
SELECT cp.UOM1,cp.Product_description1,cp.Product_code FROM `customertype_product` cp WHERE Product_description1 like '%".$trimmed."%'";
	//echo $query_allprod;
	//exit;
} else {
	$query_allprod			=	"SELECT prod.UOM1,prod.Product_description1,prod.Product_code FROM `product` prod UNION
SELECT cp.UOM1,cp.Product_description1,cp.Product_code FROM `customertype_product` cp";
}

$result_allprod			=	mysql_query($query_allprod) or die(mysql_error());
$num_rows				=	mysql_num_rows($result_allprod);
$qry					=	$query_allprod;
$qry_totval				=	$query_allprod;
while($row_allprod		=	mysql_fetch_array($result_allprod)) {
	$allprods[]			=	$row_allprod[Product_code];
}
	
$results_dsr=mysql_query($qry) or die(mysql_error());
$num_rows= mysql_num_rows($results_dsr);

$results_totval=mysql_query($qry_totval) or die(mysql_error());

$totalval_withprice		=	0;	
while($row_totval	=	mysql_fetch_array($results_totval)) {
	$Product_codetotval			=		$row_totval['Product_code'];

	$qry_closing_totval			=	"select BalanceQty from opening_stock_update where Product_code = '$Product_codetotval' AND (Date BETWEEN '$fromDate' AND '$toDate' OR Date < '$fromDate') ORDER BY id DESC"; 
	$res_closing_totval			=	mysql_query($qry_closing_totval) or die(mysql_error());
	if(mysql_num_rows($res_closing_totval) > 0) {
		$row_closing_totval		=	mysql_fetch_array($res_closing_totval);
		$closingstock_totval=	$row_closing_totval[BalanceQty];

		$sel_pricetotval			=	"SELECT Price FROM `price_master` WHERE Product_code = '$Product_codetotval'"; 
		$results_pricetotval		=	mysql_query($sel_pricetotval)  or die(mysql_error());
		$row_pricetotval			=	mysql_fetch_array($results_pricetotval);
		$pricetotval				=	$row_pricetotval['Price'];
	} else {
		$closingstock_totval	=	0;
		$pricetotval			=	0;
	}
	$totalval_withprice			+=	($pricetotval * $closingstock_totval);

}

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 9;   // Records Per Page

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
	$orderby	=	"ORDER BY Product_code";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//echo $qry;
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>
 <div id="search">
	<input type="text" name="Product_name" value="<?php echo $_REQUEST['Product_name']; ?>" autocomplete='off' style="width:110px;" placeholder='Search By Pro. Name'/>
    <input type="button" class="buttonsg" onclick="searchstostaajax('<?php echo $Page; ?>');" value="GO"/>
 </div>
 <div class="clearfix"></div>
 <div id="searchstat">
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
			$paramsval	=	$fromDate."&".$toDate."&".$sortorderby."&Product_code&".$Product_name; ?>
			<th width="5%" class="rounded" nowrap="nowrap" align="center" onClick="pagination_ajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');">Product Code<img src="../images/sort.png" width="13" height="13" /></th>
			<th width="75%">Product Name</th>
			<th width="5%" nowrap="nowrap" align="center">UOM</th>
			<th width="5%" nowrap="nowrap" align="center">Quantity</th>
			<th width="5%" nowrap="nowrap" align="center">Price</th>
			<th width="5%" nowrap="nowrap" align="center">Value</th>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
			//echo "hi";
		$OpeningStock			=		0;
		$c=0;$cc=1;$y=0;
		$OpeningStock = 0;
		while($fetch = mysql_fetch_array($results_dsr)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$Product_codeval			=		$fetch['Product_code'];
		$pname						=		$fetch['Product_description1'];
		$puom						=		$fetch['UOM1'];
		
		$qry_closing			=	"select BalanceQty from opening_stock_update where Product_code = '$Product_codeval' AND (Date BETWEEN '$fromDate' AND '$toDate' OR Date < '$fromDate') ORDER BY id DESC"; 
		//echo $qry_closing;
		//exit;
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
		<td align="center"><?php echo $puom;?></td>
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
		<span style="cursor:pointer;cursor:hand;color:#4285F4;" onClick="bringPopup('<?php echo $fromDate; ?>','<?php echo $toDate; ?>','<?php echo $fetch[Product_code]; ?>','<?php echo intval($OpeningStock); ?>','<?php echo (intval($row_receipt[recqty]) + intval($row_dsreturn[dsrqty]) + intval($row_cusreturn[cusqty])); ?>','<?php echo intval($row_issue[issqty]); ?>','<?php echo intval($row_adjust[adjqty]); ?>','<?php echo $cc.$Page; ?>','<?php echo $pname; ?>')" ><?php 
				
		//echo $fetch['BalanceQty'];
				
		echo number_format($closingstock); ?></span></td>
		
		<?php $sel_priceval			=	"SELECT Price FROM `price_master` WHERE Product_code = '$Product_codeval'"; 
			$results_priceval		=	mysql_query($sel_priceval)  or die(mysql_error());
			$row_priceval			=	mysql_fetch_array($results_priceval);
			$priceval				=	$row_priceval['Price'];
		?>

		 <td align="right"><?php echo number_format($priceval,2); ?></td>
		  <td align="right"><?php //$closingstock = intval($OpeningStock) + intval($row_receipt[recqty]) + intval($row_issue[issqty]) + intval($row_adjust[adjqty]) + intval($row_dsreturn[dsrqty]);
		  $totalvalue		+=	($priceval * $closingstock);
		 echo number_format($priceval * $closingstock,2); ?></td>
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
		</tr><?php } ?>
		</tbody>
		</table>
		 </div>   
		 <div class="paginationfile" align="center">
		 <table>
		 <tr>
		 <th class="pagination" scope="col">          
		<?php 
		if(!empty($num_rows)){ 
			rendering_pagination($Num_Pages,$Page,$Prev_Page,$Next_Page,$params);   //need to uncomment
		}
		?>
		</th>
		</tr>
		</table>
	  </div>
	  <span id="printopen" style="padding-left:400px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printstostaajax');"> &nbsp;&nbsp;&nbsp;<a style="width:17%" class="buttons" href="javascript:void(0);" onclick="javascript:window.location='downloadstostatus.php?fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&sortorder=<?php echo $sortorder; ?>&ordercol=<?php echo $ordercol; ?> '" >Download Excel</a></span>
		<form id="printstostaajax" target="_blank" action="printstostaajax.php" method="post">
			<input type="hidden" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" />
			<input type="hidden" name="toDate" id="toDate" value="<?php echo $toDate; ?>" />
			<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
			<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
			<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
		</form>
<?php //echo "~".$num_rows."~".number_format($totalvalue,2);
echo "~".$num_rows."~".number_format($totalval_withprice,2);
//print_r($row_dsr);

exit(0); ?>