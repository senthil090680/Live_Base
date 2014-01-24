<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_POST);
$id=$_REQUEST['id'];

$list=mysql_query("select * from stock_receipts where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$Transaction_number = $row['Transaction_number'];
	$Date = $row['Date'];
	$supplier_category = $row['supplier_category'];
	$supplier_name = $row['supplier_name'];
	$Product_code = $row['Product_code'];
	$Product_name = $row['Product_name'];
	$UOM = $row['UOM'];
	$quantity = $row['quantity'];
}

$fromofdate		=	date('Y-m-d');
//$fromofdate		=	'2013-06-01';
$toofdate		=	date('Y-m-d');
$paramform		=	$fromofdate."&".$toofdate;
?>
<!------------------------------- Form -------------------------------------------------->
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
<link href="../css/popup.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" >
	pagination_ajax('1','<?php echo $paramform; ?>');
</script>
<div id="mainareastock">
<div class="mcf"></div>
<div align="center" class="headingsgr">STOCK STATUS</div>
<div id="mytableformreceipt" align="center">
<form action="" method="post" id="stockvalidation">
 <fieldset align="left" class="alignmentstosta">
  <legend><strong>Stock Status</strong></legend>
<table width="100%" align="left">
 <tr>
  <td>
  <table>
    <tr height="20">
    <td width="120">From Date*</td>
    <td style="padding-left:84px;"><input type="text" name="fromDate" id="fromDate" size="15" value="<?php if(isset($fromDate) && $fromDate != '') { echo $fromDate; } else { echo $fromofdate; } ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/>
	</td>
    </tr>
   </table>
   </td>
	<td>
  <table>
    <tr height="20">
    <td width="120">To Date*</td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style="padding-left:40px;"><input type="text" name="toDate" id="toDate" size="15" value="<?php if(isset($toDate) && $toDate != '') { echo $toDate; } else { echo $toofdate; } ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/>
	
	<!-- <a href="javascript:void(0);" onClick="isValidDate('fromDate','toDate');" ><img src="../images/go2.png" /></a> -->
	<?php if($sortorder == 'ASC') {
			$sortorderby = 'DESC';
		} elseif($sortorder == 'DESC') {
			$sortorderby = 'ASC';
		} else {
			$sortorderby = 'DESC';
		}
	?>
	<input type="button" value="GO" onClick="isValidDate('fromDate','toDate');" class="buttons_new">
	</td>
    </tr>
   </table>
   </td>
 </tr>

 <tr>
  <td>
  <table>
    <tr height="40">
    <td width="120" class="wrapcut">Number of Products</td>
    <td style="padding-left:74px;"><input type="text" name="numprod" size="15" value="<?php echo $numprod; ?>" readonly maxlength="10" autocomplete='off'/></td>
    </tr>
   </table>
   </td>
	<td>
  <table>
    <tr height="20">
    <td width="120">Total Value</td>
	<td><img src='../images/currency.gif' width="15px" height="15px"/></td>
    <td style="padding-left:40px;"><input type="text" name="totalval" size="15" value="<?php echo $totalval; ?>" readonly maxlength="10" autocomplete='off'/></td>
    </tr>
   </table>
   </td>
 </tr>
</table>
</fieldset>

<!----------------------------------------------- last Table End -------------------------------------->

<table width="50%" style="clear:both">
      <tr align="center" height="25px;">
      <td>&nbsp;&nbsp;&nbsp;&nbsp;
     </td>
      </tr>
 </table>     
</form>
</div>

<!---- Form End ----->

<div class="mcf"></div>        
<div id="containerdailysto" style="display:none;">
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
	    <?php
		if($_GET['submit']!='')
		{
			$var = @$_GET['Product_name'] ;
			$trimmed = trim($var);	
			$qry="SELECT * FROM `stock_receipts` where Product_name like '%".$trimmed."%' order by id asc";
		}
		else
		{ 
			$qry="SELECT *  FROM `stock_receipts` order by id asc"; 
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="conitems">
        <table width="100%">
		<thead>
		<tr>
			<th width="5%" nowrap="nowrap">Serial Number</th>
			<th width="5%" nowrap="nowrap" class="rounded">Product Code<img src="../images/sort.png" width="13" height="13" /></th>
			<th width="75%" nowrap="nowrap">Product Name</th>
			<th width="5%" nowrap="nowrap">UOM</th>
			<th width="5%" nowrap="nowrap">Quantity</th>
			<th width="5%" nowrap="nowrap" align="right">Edit/Del</th>
		</tr>
		</thead>
		<tbody id="containerpr">
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$id= $fetch['id'];
		?>
		<tr>
		<td><?php echo $fetch['line_number'];?></td>
		<td><?php echo $fetch['Product_code'];?></td>
	    <td><?php echo $fetch['Product_name'];?></td>
        <td><?php echo $fetch['UOM'];?></td>
        <td><?php echo $fetch['quantity'];?></td>
       	<td align="right">
        <a href="../StockManagement/StockReceipts.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="../StockManagement/StockReceipts.php?id=<?php echo $fetch['id'];?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
        </td>
        </tr>
		<?php $c++; $cc++; }		 
		}else{  ?>
			<tr>
				<td align='center'><b>No records found</b></td>
				<td style="display:none;" >Cust Name</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line2</td>
				<td style="display:none;" >Cust Name</td>
				<td style="display:none;" >Add Line1</td>
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
		?>      
		</th>
		</tr>
        </table>
      </div> 
     <div class="msg" align="center" <?php if($_GET['id']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='StockReceipts.php'"/>
      </form>
     </div>
	</div>
   	<div class="mcf"></div>
   <div id="errormsgstock" style="display:none;"><h3 align="center" class="myalignstock"></h3><button id="closebutton">Close</button></div>
 </div>
</div>
<div id="backgroundChatPopup"></div>
<?php include('../include/footer.php'); ?>