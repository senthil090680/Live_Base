<?php
session_start();
ob_start();
	require_once "../include/header.php";
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
if(isset($_GET[nextrec]) && $_GET[nextrec] !='') {
	$nextrecval		=	"WHERE id = '$nextrec'";	
} else {
	$nextrecval		=	"ORDER BY id desc LIMIT 1";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `stock_issue` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr=mysql_query($qry);
$pager = new PS_Pagination($bd, $qry,5,5);
$results = $pager->paginate();
$num_rows= mysql_num_rows($results_dsr);
$row_dsr= mysql_fetch_array($results_dsr);

//print_r($row_dsr);
?>
	<div id="mainareastockcat">
	<div class="mcf"></div>
<div align="center" class="headingsgr">STOCK ISSUES</div>
<div id="mytableformreceipt" align="center">
<form action="" method="post" id="stockvalidation">

 <fieldset align="left" class="alignment">
  <legend><strong>Stock Issues</strong></legend>
<table width="50%" align="left">
 <tr>
  <td>
  <table>
    <tr height="20">
    <td width="120">Date : </td>
    <td style="padding-left:40px;" ><?php if($row_dsr['Date'] == '') { echo "-"; } else echo $row_dsr['Date']; ?></td>
    </tr>

  <tr height="20">
    <td width="120">DSR Code :</td>
    <td style="padding-left:40px;" ><?php if($row_dsr['DSR_Code'] == '') { echo "-"; } else echo $row_dsr[DSR_Code]; ?>
	</td>
    </tr>

   </table>
   </td>
 </tr>
</table>

<!----------------------------------------------- Left Table End -------------------------------------->
<table width="50%" align="left">
 <tr>
  <td>
  <table>
    
   <tr height="20">
     <td width="120">Issue Number </td>
     <td style="padding-left:75px;" ><?php if($row_dsr['Transaction_number'] == '') { echo "-"; } else echo $row_dsr['Transaction_number']; ?></td>
   </tr>

    <tr height="20">
     <td width="120">DSR Name </td>
     <td style="padding-left:75px;" ><?php 
	$sel="select id,DSRName from dsr where DSR_Code ='$row_dsr[DSR_Code]'";
	$sel_query=mysql_query($sel) or die(mysql_error());
	if(mysql_num_rows($sel_query) > 0) {
		$row_qty=mysql_fetch_array($sel_query);
		echo $row_qty[DSRName]; } else echo "-"; ?></td>
	</tr>
   </table>
   </td>
 </tr>
</table>

<!----------------------------------------------- Right Table End -------------------------------------->

<table width="50%" align="left">
 <tr>
  <td>
  <table>
    <tr height="20">
    <td width="120" class="wrapcut">Confirmation Flag </td>
    <td style="padding-left:40px;"><?php if($row_dsr['confirmation_flag'] == '') { echo "-"; } else echo $row_dsr[confirmation_flag]; ?>
	</td>
    </tr>
   </table>
   </td>
 </tr>
</table>
<table width="50%" align="left">
 <tr>
  <td>
  <table>
    <tr  height="20">
     <td width="120" class="wrapcut" >Confirmed Date & Time </td>
     <td style="padding-left:40px;"><?php if($row_dsr['confirmation_date_time'] == '') { echo "-"; } else echo $row_dsr[confirmation_date_time]; ?></td>
	 </tr>
   </table>
   </td>
 </tr>
</table>
</fieldset>
<!----------------------------------------------- Left Below Table End -------------------------------------->


<table width="50%" style="clear:both">
      <tr align="right" height="10px;">
      <td>&nbsp;</td>
      </tr>
 </table>     
</form>

</div>

<!---- Form End ----->

<?php include("../include/error.php");?>
<div class="mcf"></div>

<?php
if($num_rows > 0) { 
	$currentId	=	$row_dsr[id];
	$next_qry	=	"select * from stock_issue where id = (select min(id) from stock_issue where id > $currentId)"; 
	$next_res	=	mysql_query($next_qry) or die(mysql_error());
	if(mysql_num_rows($next_res) > 0) {
		$next_row	=	mysql_fetch_array($next_res);
		$nextId		=	$next_row[id];
	}
	$prv_qry	=	"select * from stock_issue where id = (select max(id) from stock_issue where id < $currentId)"; 
	$prv_res	=	mysql_query($prv_qry) or die(mysql_error());
	if(mysql_num_rows($prv_res) > 0) {
		$prv_row	=	mysql_fetch_array($prv_res);
		$prvId		=	$prv_row[id];
	}
}
?>

<?php if($prvId != '') { ?>
<span style="float:left;">	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0);" onclick="prvrecord('<?php echo $prvId; ?>','showisscontent.php');" ><img src="../images/back.png" width="20px" /></a></span>
<?php } ?>
<?php if($nextId != '') { ?>
<span style="float:right;"> <a href="javascript:void(0);" onclick="nextrecord('<?php echo $nextId; ?>','showisscontent.php');" ><img src="../images/front_play.png" width="20px" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
<?php } ?>
<div id="containerpr">
        <?php
		if($_GET['id']!=''){
			if($_POST['submit']=='ConfirmDelete'){
				$id = $_GET['id'];
				$query = "DELETE FROM `stock_issue` WHERE id = $id";
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
		<th nowrap="nowrap">Serial Number</th>
		<th nowrap="nowrap" class="rounded">Product Code</th>
        <th nowrap="nowrap">Product Name</th>
        <th>UOM</th>
		<th nowrap="nowrap">Issued Quantity</th>
		<th nowrap="nowrap">Confirmed Quantity</th>
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
        <td><?php echo str_replace("-","",$row_dsr['issued_quantity']); ?></td>
		 <td><?php echo str_replace("-","",$row_dsr['confirmed_quantity']); ?></td>
        </tr>
		<?php } else { ?><tr>
		<td align="center" colspan="6">No Records Found.</td>
        </tr>
		<?php } ?>
		</tbody>
		</table>
         </div>
     </div><br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 <!-- <span style="float:middle;"><a href="StockReceipts.php"><img src="../images/fileclose.png" /></a></span> -->
	 <span ><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>
	 </div>
<?php include('../include/footer.php'); ?>