<?php
session_start();
ob_start();
require_once('../include/header.php');
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

if($_GET['submit']!='')
{
	$var = @$_GET['Product_name'] ;
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
$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>
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
<div id="mainareadaily">
<div class="mcf"></div>
<div><h2 align="center">STOCK RECEIPTS</h2></div> 

<div id="containerpr_str">
<span style="float:left;"><input type="button" name="kdproduct" value="Add Stock Receipts" class="buttonsbig" onclick="window.location='StockReceipts.php'"></span>
<span style="float:left;padding-left:20px"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

 <div id="search">
        <input type="text" name="Product_name" value="<?php echo $_GET['Product_name']; ?>" autocomplete='off' placeholder='Search By Product Name'/>
        <input type="button" onclick="searchreceiptsviewajax('<?php echo $Page; ?>');" class="buttonsg" value="GO"/>
 </div>
 <div class="clearfix"></div>
        <?php
		if($_POST['id']!=''){
			if($_POST['submit']=='ConfirmDelete'){
				$query = "DELETE FROM `stock_receipts` WHERE id = $id";
				//Run the query
				$result = mysql_query($query) or die(mysql_error());
				header("location:StockReceiptsview.php?no=3");
			}
		 }
		
		?>
		<div id="srviewajax">
			<div class="con_str">
			<table width="100%">
			<thead>
			<tr>
				<th width="5%">Sl. No.</th>
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
				<th width="20%" align="center">Product Name</th>
				<!--<th width="5%">UOM</th>-->
				<th width="5%" >Quantity</th>
				<!-- <th width="5%" align="right" nowrap="nowrap">Edit</th> -->
				<!-- <th width="5%" align="right">Edit/Del</th> -->
			</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($num_rows)){
				$slno	=	($Page-1)*$Per_Page + 1;
			$c=0;$cc=1;
			while($fetch = mysql_fetch_array($results_dsr)) {
				//echo $slno;
			if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
			$id= $fetch['id'];
			?>
			<tr>
			<td><?php echo $slno;?></td>
			<td><?php echo $fetch['Product_code'];?></td>
			<td><?php echo $fetch['Product_name'];?></td>
			<!--<td><?php echo $fetch['UOM'];?></td>-->
			<td align="right"><?php echo number_format($fetch['quantity']);?></td>
			<!-- <td align="right" nowrap="nowrap">
			<?php $todayDate		=	date('Y-m-d');
				//if($fetch['Date'] == $todayDate) {
			?>
			
			<a href="../StockManagement/StockReceipts.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
			
			<?php //} else { echo "No Edit";  } ?>
			
			<a href="../StockManagement/StockReceipts.php?id=<?php echo $fetch['id'];?>&del=del"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
			</td> -->
			</tr>		
			<?php $c++; $cc++;
			$slno++;
			 }		 
			}else{  ?> 
			
				<tr>
					<td align='center' colspan='13'><b>No records found</b></td>	
					<td style="display:none;" >Cust Name</td>
					<td style="display:none;" >Add Line1</td>
					<td style="display:none;" >Add Line2</td>
					<td style="display:none;" >Cust Name</td>
					<td style="display:none;" >Add Line1</td>
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
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'receiptsviewajax');   //need to uncomment
			}
			else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
		  </div>
			<span id="printopen" style="padding-left:470px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printreceiptsajax');"></span>
			<form id="printreceiptsajax" target="_blank" action="printreceiptsajax.php" method="post">
				<input type="hidden" name="Product_name" id="Product_name" value="<?php echo $Product_name; ?>" />
				<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
				<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
				<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
			</form>
	   </div>
     <div class="msg" align="center" <?php if($_GET['id']!='' && $_GET['del'] =='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='StockReceipts.php'"/>
      </form>
     </div>
   </div>
   <div class="mcf"></div>
    <?php include("../include/error.php"); ?>	
</div>
<?php require_once('../include/footer.php');?>