<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
$params=$fromDate."&".$toDate."&".$Pcode."&".$tblname;
if((isset($_GET[fromDate]) && $_GET[fromDate] !='') && (isset($_GET[toDate]) && $_GET[toDate] !='') && (isset($_GET[Pcode]) && $_GET[Pcode] !='')) {
	$nextrecval		=	"WHERE Date BETWEEN '$fromDate' AND '$toDate' AND Product_code = '$Pcode' ORDER BY id DESC";	
} else {
	echo "Invalid Query"; exit(0);
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	if($tblname == 'stock_receipts') {
		$where_receipts	=	"WHERE Date BETWEEN '$fromDate' AND '$toDate' AND Product_code = '$Pcode'";
		$qry="SELECT Transaction_number,Product_code,quantity,supplier_name,id FROM dsr_return $where_receipts UNION ALL SELECT Transaction_number,Product_code,quantity,supplier_name,return_reason FROM customer_return $where_receipts UNION ALL SELECT Transaction_number,Product_code,quantity,supplier_name,supplier_category FROM stock_receipts $where_receipts";
	} else {
		$qry="SELECT * FROM $tblname $where";
	}
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
//$row_dsr= mysql_fetch_array($results_dsr); 



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
$qry.="  LIMIT $Page_Start , $Per_Page";
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>
<style type="text/css">
.confirmSecondSharedStock{
	margin:0 auto;
	display:none;
	background:#EEEEEE;
	color:#fff;
	width:820px;
	height:220px;
	position:fixed;
	left:250px;
	top:250px;
	border-bottom:2px solid #A09E9E;
	z-index:300;
	border-radius:2px 2px 2px 2px;
}
</style>
<div class="con">
	<table id="sort" class="tablesorter" width="100%">
	<thead>
	<tr>
		<th align="left" 
		<?php if($tblname == 'stock_receipts') { ?>
		colspan="5"
		<?php } ?>
		<?php if($tblname == 'stock_issue') { ?>
		colspan="4"
		<?php } ?>
		<?php if($tblname == 'stock_adjustment') { ?>
		colspan="4"
		<?php } ?> >
		<?php if($tblname == 'stock_receipts') { ?>
		<h2>Stock Receipts</h2>
		<?php } ?>
		<?php if($tblname == 'stock_issue') { ?>
		<h2>Stock Issues</h2>
		<?php } ?>
		<?php if($tblname == 'stock_adjustment') { ?>
		<h2>Stock Adjustment</h2>
		<?php } ?> 
		</th>
	</tr>
	
	<tr>
		<th>Transaction Number</th>
		<th>Product Name</th>
		<th>Quantity</th>

		<?php if($tblname == 'stock_receipts') { ?>
		<th>Category</th>
		<th>Name</th>
		<th>Return Reason</th>
		<?php } ?>
		<?php if($tblname == 'stock_issue') { ?>
		<th>Confirmed Quantity</th>
		<?php } ?>
		<?php if($tblname == 'stock_adjustment') { ?>
		<th>Reason</th>
		<?php } ?>
	</tr>
	</tr>
	</thead>
	<tbody>
	<?php
	//echo $num_rows."ere";
	if(!empty($num_rows)) {
	$c=0;$cc=1;$totalval=0;
	while($fetch = mysql_fetch_array($results_dsr)) {
	if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
	$id= $fetch['id'];
	?>
	<tr>
	<td><?php echo $fetch['Transaction_number'];?></td>
	<td><?php $prod_name	=	getdbval($fetch['Product_code'],'Product_description1','Product_code','product');
	if($prod_name == '') {
		$prod_name	=	getdbval($fetch['Product_code'],'Product_description1','Product_code','customertype_product');
	}
	echo $prod_name; ?></td>

	<?php if($tblname == 'stock_receipts' || $tblname == 'stock_adjustment') { ?>
	<td><?php echo abs($fetch['quantity']);?></td>
	<?php } ?>
	<?php if($tblname == 'stock_issue') { ?>
	<td><?php echo abs($fetch['issued_quantity']);?></td>
	<td><?php echo abs($fetch['confirmed_quantity']);?></td>
	<?php } ?>

	<?php if($tblname == 'stock_receipts') { ?>
	<td><?php 
		$diff_column	=	$fetch['id']; // SUPPLIER_CATEGORY FOR STOCK_RECEIPTS, RETURN_REASON FOR CUSTOMER_RETURN
		$diff_trans_no	=	$fetch['Transaction_number'];
		$first_3_letter	=	substr($diff_trans_no,0,2);
		if($first_3_letter == 'DS') {
			echo "Customer Return";
		} elseif ($first_3_letter == 'KD') {
			echo $fetch['id'];
		} else {
			echo "DSR Return";
		}
	?></td>
	<td><?php echo $fetch['supplier_name'];?></td>
	<td align="center"><?php 
		if($first_3_letter == 'DS') {
			echo getdbval($fetch['id'],'salereturn','id','salereturn');
		} elseif ($first_3_letter == 'KD') {
			echo "-";
		} else {
			echo "-";
		}
	?></td>
	<?php } ?>

	<?php if($tblname == 'stock_adjustment') { ?>
	<td><?php echo $fetch['reason'];?></td>
	<?php } ?>
	</tr>
	<?php $c++; $cc++; }	
	} else{  
		?>
		<tr>
			<td align='center' colspan='13'><b>No records found</b></td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
			<td style="display:none;" >Add Line2</td>	
			<td style="display:none;" >Add Line2</td>
			<td style="display:none;" >Add Line2</td>
			<td style="display:none;" >Add Line2</td>
		</tr>
	<?php 
	} //else loop
	?>
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
				echo $pager->renderLast(); } else{ echo "&nbsp;"; */				
				rend_pag_stock($Num_Pages,$Page,$Prev_Page,$Next_Page,$params);
			}				
			?>      
			</th>
		</tr>
	</table>
</div>
<?php //print_r($row_dsr);
exit(0); ?>