<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
error_reporting(0);
extract($_GET);
$params=$fromDate."&".$toDate;
$totalval=0;
if((isset($_GET[fromDate]) && $_GET[fromDate] !='') && (isset($_GET[toDate]) && $_GET[toDate] !='')) {
	$nextrecval		=	"WHERE Date BETWEEN '$fromDate' AND '$toDate' GROUP BY Product_code ORDER BY Date";
} else {
	echo "Invalid Query"; exit(0);
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	//$qry="SELECT * FROM `opening_stock_update` $where";
	$query_id	=	"SELECT MAX(id) AS pids FROM `opening_stock_update` $where";
	$result_id=mysql_query($query_id) or die(mysql_error());
	while($row_id= mysql_fetch_array($result_id)) {
		$pids[]		=	$row_id[pids];
	}

	$query_id	=	"SELECT MAX(id) AS pids FROM `opening_stock_update` $where";
	$result_id=mysql_query($query_id) or die(mysql_error());
	while($row_id= mysql_fetch_array($result_id)) {
		$pids[]		=	$row_id[pids];
	}

	$pidsarr		=	implode("','",$pids);
	
	$qry="SELECT * FROM `opening_stock_update` WHERE id in ('".$pidsarr."')";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr=mysql_query($qry) or die(mysql_error());
//$pager = new PS_Pagination($bd, $qry,5,5);
//$results = $pager->paginate();
$num_rows= mysql_num_rows($results_dsr);
while($row_dsr= mysql_fetch_array($results_dsr)) {
	$sel_price	=	"SELECT * FROM `price_master` WHERE Product_code = '$row_dsr[Product_code]'"; 
	$results_price=mysql_query($sel_price)  or die(mysql_error());
	$row_price= mysql_fetch_array($results_price);
	$totalval  +=	($row_dsr['TransactionQty'] * $row_price['Price']);
}

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
$qry.="  LIMIT $Page_Start , $Per_Page";
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>
<div class="con">
        <table id="sort" class="tablesorter" width="100%">
		<thead>
		<tr>
		<th class="rounded" nowrap="nowrap">Product Code<img src="../images/sort.png" width="13" height="13" /></th>
        <th>Product Name</th>
        <th>UOM</th>
		<th>Quantity</th>
		</tr>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results_dsr)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$id= $fetch['id'];

		$prv_qry	=	"select BalanceQty from opening_stock_update where id = (select max(id) from opening_stock_update where id < $id)"; 
		$prv_res	=	mysql_query($prv_qry) or die(mysql_error());
		if(mysql_num_rows($prv_res) > 0) {
			$prv_row	=	mysql_fetch_array($prv_res);
			$OpeningStock		=	$prv_row[BalanceQty];
		}
		?>
		<tr>
		<td><?php echo $fetch['Product_code'];?></td>
	    <td><?php $sel_prname	=	"SELECT *  FROM `product` WHERE Product_code = '$fetch[Product_code]'"; 
			$results_prname=mysql_query($sel_prname)  or die(mysql_error());
			$row_prname= mysql_fetch_array($results_prname); $pname = ucwords(strtolower($row_prname['Product_description1']));
		echo $pname; ?></td>
        <td><?php echo $row_prname['UOM1'];?></td>
        <td>
		<?php 
		$sel_receipt	=	"SELECT sum(quantity) as recqty FROM `stock_receipts` WHERE (Product_code = '$fetch[Product_code]') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_receipt	=	mysql_query($sel_receipt)  or die(mysql_error());
		$row_receipt		=	mysql_fetch_array($results_receipt);

		$sel_issue	=	"SELECT sum(issued_quantity) as issqty FROM `stock_issue` WHERE (Product_code = '$fetch[Product_code]') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_issue	=	mysql_query($sel_issue)  or die(mysql_error());
		$row_issue		=	mysql_fetch_array($results_issue);

		$sel_adjust	=	"SELECT sum(quantity) as adjqty FROM `stock_adjustment` WHERE (Product_code = '$fetch[Product_code]') AND (Date BETWEEN '$fromDate' AND '$toDate') GROUP BY Product_code";
		$results_adjust	=	mysql_query($sel_adjust)  or die(mysql_error());
		$row_adjust		=	mysql_fetch_array($results_adjust);
		
		?>
		<span style="cursor:pointer;cursor:hand;" onClick="bringPopup('<?php echo $fromDate; ?>','<?php echo $toDate; ?>','<?php echo $fetch[Product_code]; ?>','<?php echo $OpeningStock; ?>','<?php echo $row_receipt[recqty]; ?>','<?php echo $row_issue[issqty]; ?>','<?php echo $row_adjust[adjqty]; ?>','<?php echo $cc.$Page; ?>','<?php echo $pname; ?>')" ><?php 
				
		echo $fetch['BalanceQty'];?></span></td>
        </tr>
		<?php $c++; $cc++; }		 
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
			echo $pager->renderLast(); } else{ echo "&nbsp;"; } ?>  */ 
			rendering_pagination($Num_Pages,$Page,$Prev_Page,$Next_Page,$params);
		}
		?>
		</th>
		</tr>
        </table>
      </div>
<?php echo "~".$num_rows."~".$totalval;
//print_r($row_dsr);
exit(0); ?>