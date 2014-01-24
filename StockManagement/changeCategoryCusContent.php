<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
if(isset($_GET[sup_cat]) && $_GET[sup_cat] !='') {
	$sup_cat	=	trim($_GET[sup_cat]);

	if(isset($_GET[nextrec]) && $_GET[nextrec] !='') {
		$nextrecval		=	$nextrec;	
	} else {
		$nextrecval		=	"";
	}
	$where		=	"$nextrecval ORDER BY id desc limit 1";
} 

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `customer_return` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr=mysql_query($qry) or die(mysql_error());
$pager = new PS_Pagination($bd, $qry,5,5);
$results = $pager->paginate();
$num_rows= mysql_num_rows($results_dsr);
$row_dsr= mysql_fetch_array($results_dsr);
?>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/facebox.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="../js/jconfirmaction.jquery.js"></script>
<div id="mainareastockcat">
	<div class="mcf"></div>
<div align="center" class="headingsgr">STOCK RECEIPTS</div>
<div id="mytableformreceipt" align="center">
<form action="" method="post" id="stockvalidation">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Receipts</strong></legend>
  <table>
    <tr  height="20">
    <td  width="120">Date</td>
    <td><?php if($row_dsr['Date'] == '') { echo "-"; } else echo $row_dsr['Date']; ?></td>
    </tr>
    <tr  height="20">
     <td width="120">Receipt Number</td>
     <td><?php if($row_dsr['Date'] == '') { echo "-"; } else echo $row_dsr['Transaction_number']; ?></td>
   </table>
 </fieldset>
   </td>
 </tr>
</table>

<!----------------------------------------------- Left Table End -------------------------------------->
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Supplier</strong></legend>
  <table>
    <tr  height="20">
    <td  width="120">Category</td>
    <td>Customer Return<?php //echo $sup_cat; ?>
	</td>
    </tr>
    <tr  height="20">
     <td width="120">Name</td>
     <td><?php if($row_dsr['supplier_name'] == '') { echo "-"; } else echo ucwords($row_dsr[supplier_name]); ?></td>
   </table>
 </fieldset>
   </td>
 </tr>
</table>

<!----------------------------------------------- Right Table End -------------------------------------->

<table width="50%" style="clear:both">
      <tr align="right" height="10px;">
      <td>&nbsp;</td>
      </tr>
 </table>     
</form>

</div>

<!---- Form End ----->

<?php include("../include/error.php");?>
 <!-- <div id="search">
        <form action="" method="get">
        <input type="text" name="Product_name" value="<?php $_GET['Product_name']; ?>" autocomplete='off' placeholder='Search By Product Name'/>
        <input type="submit" name="submit" class="buttonsg" value="GO"/>
        </form>       
        </div>-->
<div class="mcf"></div>

<?php 
if($num_rows > 0) {
	$currentId	=	$row_dsr[id];
	$next_qry	=	"select * from customer_return where id = (select min(id) from customer_return where id > $currentId)"; 
	$next_res	=	mysql_query($next_qry) or die(mysql_error());
	if(mysql_num_rows($next_res) > 0) {
		$next_row	=	mysql_fetch_array($next_res);
		$nextId		=	$next_row[id];
	}
	$prv_qry	=	"select * from customer_return where id = (select max(id) from customer_return where id < $currentId)"; 
	$prv_res	=	mysql_query($prv_qry) or die(mysql_error());
	if(mysql_num_rows($prv_res) > 0) {
		$prv_row	=	mysql_fetch_array($prv_res);
		$prvId		=	$prv_row[id];
	}
}
?>

<?php if($prvId != '') { ?>
<span style="float:left;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="prvrecord('<?php echo $prvId; ?>','showcuscontent.php');" ><img src="../images/back.png" width="20px" /></a></span>
<?php } ?>
<?php if($nextId != '') { ?>
<span style="float:right;"> <a href="javascript:void(0);" onclick="nextrecord('<?php echo $nextId; ?>','showcuscontent.php');" ><img src="../images/front_play.png" width="20px" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
<?php } ?>
<div id="containerpr">
        <?php
		if($_GET['id']!=''){
			if($_POST['submit']=='ConfirmDelete'){
				$id = $_GET['id'];
				$query = "DELETE FROM `stock_receipts` WHERE id = $id";
				//Run the query
				$result = mysql_query($query) or die(mysql_error());
				header("location:StockReceipts.php?no=3");
			}
		 }				
		?>
        <div class="con">
        <table id="sort" class="tablesorter" width="100%">
		<thead>
		<tr>
		<th>Serial Number</th>
		<th class="rounded">Product Code</th>
        <th>Product Name</th>
        <th>UOM</th>
		<th>Quantity</th>
		</tr>
		</tr>
		</thead>
		<tbody>
		<?php if($num_rows > 0) { ?>

		<tr>
		<td><?php echo $row_dsr['id'];?></td>
		<td><?php echo $row_dsr['Product_code'];?></td>
	    <td><?php $sel_prname	=	"SELECT *  FROM `product` WHERE Product_code = '$row_dsr[Product_code]'"; 
			$results_prname=mysql_query($sel_prname);
			$row_prname= mysql_fetch_array($results_prname);
		echo ucwords(strtolower($row_prname['Product_description1'])); ?></td>
        <td><?php echo $row_dsr['UOM'];?></td>
        <td><?php echo $row_dsr['quantity'];?></td>
        </tr>
		<?php } else { ?><tr>
		<td align="center" colspan="5">No Records Found.</td>
        </tr>
		<?php } ?>
		</tbody>
		</table>
         </div>
     </div><br/>
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <span ><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='StockReceipts.php'"></span>
	</div> 
<?php exit(0); ?>