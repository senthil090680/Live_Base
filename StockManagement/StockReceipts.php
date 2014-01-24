<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

//pre($_REQUEST);
//exit;
EXTRACT($_REQUEST);
$id=$_REQUEST['id'];
if($_REQUEST['id']!=''){
	if($_POST['submit']=='Save'){
		if($Date=='' || $Transaction_number==''  || $supplier_category=='' || $supplier_name=='' || $prodcnt =='')
		{
			header("location:StockReceipts.php?no=9&id=$id");exit;
		}
		else{
			$KD_Code=getKDCode();
			$TransactionType="Receipts";

			for($k=1; $k <= $prodcnt; $k++) { 

				$qty	=	'';
				$uom_coversion	=	1;
				$sno	=	$_POST["sno_".$k];
				$pcode	=	$_POST["pcode_".$k];
				
				/*$uom_coversion	=	getdbval($pcode,'UOM_Conversion','Product_code','product');
				if($uom_coversion == '' || $uom_coversion == null) {
					$uom_coversion		=	1;
				}*/

				$pname	=	$_POST["pname_".$k];
				$uom	=	$_POST["uom_".$k];
				//$qty	=	($uom_coversion) * ($_POST["qty_".$k]);
				$qty	=	$_POST["qty_".$k];
				
				if($qty=='')
				{
					header("location:StockReceipts.php?no=9&id=$id");exit;
				}

				$sel_upd="select id,quantity,opening_id,Date from stock_receipts where Product_code ='$pcode' AND id = '$id'";
				$res_upd=mysql_query($sel_upd) or die(mysql_error());
				if(mysql_num_rows($res_upd) > 0) {
					$row_upd=mysql_fetch_array($res_upd);
					$old_qty	=	$row_upd[quantity];
					$opening_id	=	$row_upd[opening_id];
					$opening_date	=	$row_upd['Date'];
				}
				
				//$sel="select id,quantity from opening_stock_update where id ='$opening_id'";
				//$sel_query=mysql_query($sel) or die(mysql_error());

				/*$prv_qry	=	"select id,BalanceQty from opening_stock_update where id = (select max(id) from opening_stock_update where Date < $opening_date AND Product_code = '$pcode')"; 
				$prv_res	=	mysql_query($prv_qry) or die(mysql_error());

				if(mysql_num_rows($prv_res) > 0) {
					$row_qty=mysql_fetch_array($prv_res);
					$open_id	=	$row_qty[id];
					
					echo $updated_qty	=	$row_qty[BalanceQty] + $qty;
					//exit;
					$sql_qty	=	"UPDATE opening_stock_update SET TransactionQty = '$qty',BalanceQty = '$updated_qty' WHERE id = '$opening_id'";
					mysql_query($sql_qty) or die(mysql_error());
				}*/

				$prv_qry	=	"select id,BalanceQty from opening_stock_update where id = (select max(id) from opening_stock_update where id < $opening_id AND Product_code = '$pcode')"; 
				$prv_res	=	mysql_query($prv_qry) or die(mysql_error());

				if(mysql_num_rows($prv_res) > 0) {
					$row_qty=mysql_fetch_array($prv_res);
					$open_id	=	$row_qty[id];
					/*echo $old_qty."</br>";
					echo $qty."</br>";
					echo $row_qty[quantity]."</br>";*/
					
					echo $updated_qty	=	$row_qty[BalanceQty] + $qty;
					//exit;
					$sql_qty	=	"UPDATE opening_stock_update SET TransactionQty = '$qty',BalanceQty = '$updated_qty' WHERE id = '$opening_id'";
					mysql_query($sql_qty) or die(mysql_error());
				}
				$sql	=	"UPDATE stock_receipts SET KD_Code= '$KD_Code', Transaction_number='$Transaction_number',supplier_inv_no='$supplier_inv_no',supplier_category='$supplier_category',supplier_name='$supplier_name',line_number='$sno',Product_code='$pcode',Product_name='$pname',UOM='$uom',quantity='$qty' WHERE id = '$id'";
			}					
			mysql_query( $sql) or die(mysql_error());
			header("location:StockReceiptsview.php?no=2");
		}
	}
}
elseif($_POST['submit']=='Save'){

	//pre($_REQUEST);
	//exit;
	//echo "goodfd";
	//exit;
	if($Date=='' || $Transaction_number==''  || $supplier_category=='' || $supplier_name=='' || $prodcnt =='') {
		//pre($_REQUEST);
		//exit;
		header("location:StockReceipts.php?no=9&id=$id");exit;
	}
	else{
		$sel="select * from stock_receipts where Date ='$Date' AND Transaction_number ='$Transaction_number'";
		$sel_query=mysql_query($sel) or die(mysql_error());
		if(mysql_num_rows($sel_query)=='0') {
			
			$KD_Code=getKDCode();
			$TransactionType="Receipts";
			$ins_val	=	'';
			for($k=1; $k <= $prodcnt; $k++) { 

				$qty	=	'';
				$uom_coversion	=	1;
				$sno	=	$_POST["sno_".$k];
				$pcode	=	$_POST["pcode_".$k];
				$pname	=	$_POST["pname_".$k];
				$uom	=	"PCS";
				//$uom	=	$_POST["uom_".$k];
				$mcsval	=	$_POST["mcs_".$k];

				if($mcsval == 'carton') {
					$uom_coversion	=	getdbval($pcode,'UOM_Conversion','Product_code','product');
					if($uom_coversion == '' || $uom_coversion == null) {
						$uom_coversion		=	1;
					}
					$qty	=	($uom_coversion) * ($_POST["qty_".$k]);
				} elseif($mcsval == 'PCS') {
					$qty	=	($_POST["qty_".$k]);
				}

				//$qty	=	$_POST["qty_".$k];

				if($qty=='')
				{
					header("location:StockReceipts.php?no=9&id=$id");exit;
				}

				$sel="select id,BalanceQty from opening_stock_update where Product_code ='$pcode' AND KD_Code = '$KD_Code' ORDER BY id desc";
				//echo $sel;
				//exit;
				$sel_query=mysql_query($sel) or die(mysql_error());
				if(mysql_num_rows($sel_query) > 0) {
				$row_qty=mysql_fetch_array($sel_query);
					$open_id	=	$row_qty[id];
					//echo $row_qty[BalanceQty];
					//echo $qty;
					$updated_qty	=	$row_qty[BalanceQty] + $qty;
					//echo $updated_qty;
					//exit;
					//$sql_qty	=	"UPDATE opening_stock_update SET quantity = '$updated_qty' WHERE id = '$open_id'";
					$sql_qty	=	"INSERT INTO opening_stock_update SET `Date`='$Date',`StockDateTime`=NOW(),Product_description='$pname',`TransactionType`='$TransactionType',`TransactionNo`='$Transaction_number',`UOM1`='$uom',`TransactionQty`='$qty',`BalanceQty`='$updated_qty',`AddedFirstTime`='Y',`Product_code`='$pcode',`KD_Code`='$KD_Code'";					
					mysql_query($sql_qty) or die(mysql_error());
					$last_inserted_id	=	mysql_insert_id();

				}
			
				if($k == $prodcnt) {
					$ins_val	.=	"('$Date','$KD_Code','$Transaction_number','$supplier_category','$supplier_inv_no','$supplier_name','$sno','$pcode','$pname','$uom','$qty','$last_inserted_id')";
				} else {
					$ins_val	.=	"('$Date','$KD_Code','$Transaction_number','$supplier_category','$supplier_inv_no','$supplier_name','$sno','$pcode','$pname','$uom','$qty','$last_inserted_id'),";
				}
			}
			//echo $ins_val;
			//exit;

			echo $sql="INSERT INTO `stock_receipts`(`Date`,`KD_Code`,`Transaction_number`,`supplier_category`,`supplier_inv_no`,`supplier_name`,`line_number`,`Product_code`,`Product_name`,`UOM`,`quantity`,`opening_id`) values $ins_val";
			mysql_query($sql) or die(mysql_error());
			header("location:StockReceiptsview.php?no=1");
		}
		else {
			header("location:StockReceipts.php?no=18");
		}
	}
}

$id=$_REQUEST['id'];
$list=mysql_query("select * from stock_receipts where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$Transaction_number = $row['Transaction_number'];
	$Date = $row['Date'];
	$supplier_category = $row['supplier_category'];
	$supplier_inv_no = $row['supplier_inv_no'];
	$supplier_name = $row['supplier_name'];
	$Product_code = $row['Product_code'];
	$Product_name = $row['Product_name'];
	$UOM = $row['UOM'];
	$quantity = $row['quantity'];
}
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainareastockstatic">
<div class="mcf"></div>
<div align="center" class="headingsgr">STOCK RECEIPTS</div>
<div id="mytableformreceipt" align="center">
<form action="" method="post" id="stockreceiptvalidation" onSubmit="return checkReceipts();">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Receipts</strong></legend>
  <table>
    <tr height="41">
		<td height="20" width="120">Date*</td>
		<td>
		<!-- <input type="text" name="Date" id="Date" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/> -->
		<input type="text" name="Date" id="Date" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" readonly maxlength="10" autocomplete='off' onChange="changeIndianDateFormat(this.value);"/>
		</td>
    </tr>
    <tr height="61">
     <td height="40" width="120">Receipt Number*</td>
	<?php
		 if(!isset($_GET[id]) && $_GET[id] == '') {
			$query_oldtranum					=	"SELECT Transaction_number FROM stock_receipts ORDER BY id DESC";			
			$res_oldtranum						=	mysql_query($query_oldtranum) or die(mysql_error());
			$rowcnt_oldtranum					=	mysql_num_rows($res_oldtranum);
			//$rowcnt_oldtranum					=	0; // comment if live
			if($rowcnt_oldtranum > 0) {
				$row_oldtranum					=	mysql_fetch_array($res_oldtranum);
				$Old_Transaction_number				=	$row_oldtranum['Transaction_number'];

				$gettxnno						=	abs(str_replace("STR",'',strstr($Old_Transaction_number,"STR")));
				$gettxnno++;
				if($gettxnno < 10) {
					$createdcode	=	"00".$gettxnno;
				} else if($gettxnno < 100) {
					$createdcode	=	"0".$gettxnno;
				} else {
					$createdcode	=	$gettxnno;
				}

				$Transaction_number				=	getKDCode()."STR".$createdcode;
			} else {
				$Transaction_number				=	getKDCode()."STR001";
			}
		}
	?>
     <td height="40"><input type="text" name="Transaction_number" id="Transaction_number" size="30" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "readonly"; } else { echo ""; } ?> readonly value="<?php echo $Transaction_number; ?>" maxlength="20" autocomplete='off'/></td>
	 </tr>
	
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
    <tr height="20">
    <td height="20" width="120">Category*</td>
    <td><select name="supplier_category" id="supplier_category">
	<option value="" >--Select--</option>
	<?php $sel_supp		=	"SELECT supplier_category from supplier_category GROUP BY supplier_category";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[supplier_category]; ?>" <?php if($supplier_category == $row_supp[supplier_category]) { echo "selected"; } ?> ><?php if($row_supp[supplier_category] == 'Fareast' || $row_supp[supplier_category] == 'FAREAST' || $row_supp[supplier_category] == 'fareast' || $row_supp[supplier_category] == 'FMCL' || $row_supp[supplier_category] == 'Fmcl' || $row_supp[supplier_category] == 'fmcl') { echo "FMCL"; } else echo $row_supp[supplier_category]; ?></option>
	<?php } ?>
	</select>&nbsp;<span id="supcaterr" style="display:none;color:red;">Choose Category</span>
	</td>
    </tr>
    
	<tr height="20">
     <td height="40" width="120">Name*</td>
     <td height="40"><input type="text" name="supplier_name" id="supplier_name" size="30" value="<?php echo $supplier_name; ?>" maxlength="20" autocomplete='off'/></td>
	</tr>
	<tr height="20">
     <td height="40" width="120">Supplier Invoice Number*</td>
     <td height="40"><input type="text"  <?php if(isset($_GET[id]) && $_GET[id] != '') { ?> readonly <?php } ?> name="supplier_inv_no" id="supplier_inv_no" size="30" value="<?php echo $supplier_inv_no; ?>" maxlength="20" autocomplete='off'/></td>
	</tr>
   </table>
 </fieldset>
   </td>
 </tr>
</table>

<!----------------------------------------------- Right Table End -------------------------------------->
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Product</strong></legend>
  <table>
    <tr  height="20">
    <td  width="120"><select <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> name="product_names" id="product_names">
	<option value="" >--Select Product--</option>
	<?php $sel_supp		=	"SELECT Product_code,Product_description from opening_stock_update WHERE (TransactionQty != '' AND BalanceQty != '' AND TransactionNo != '' AND TransactionType !='') GROUP BY Product_code";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[Product_code]; ?>" <?php if($Product_code == $row_supp[Product_code]) { echo "selected"; } ?> ><?php echo ucwords(strtolower($row_supp[Product_description])); ?></option>
	<?php } ?>
	</select></td>
    <td><button class="buttons" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> onClick="return addproduct();">Add</button></td>
    </tr>
	<tr>
		<td height="10"><span id="showerr" style="display:none;color:red;">Choose Product</span><input type="hidden" value="<?php if(isset($_GET[id]) && $_GET[id] != '') { ?> 1 <?php } ?>" name="prodcnt" id="prodcnt" /></td>
	</tr>
   </table>
 </fieldset>
   </td>
 </tr>
</table>

<!----------------------------------------------- last Table End -------------------------------------->
<table width="100%" align="left" id="productsadd" <?php if(!isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none" <?php } if(isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none" <?php } ?>>
 <tr>
  <td>
  <div class="condaily">
  <table>
  <thead>
	<tr>
		<th width="5%" align='center'>Sl. No.</th>
		<th width="5%" class='rounded' align='center'>Product Code</th>
		<th width="80%" align='center'>Product Name</th>
		<th width="5%" align='center'>UOM</th>
		<th width="5%" align='center'>Quantity</th>
	</tr>
  </thead>  
  <tbody id="productsadded">
	<?php $t = 1; if(isset($_GET[id]) && $_GET[id] != '') { ?> 
		<tr>
			<td align='center'><input type='hidden' value='<?php echo $t; ?>' name='sno_<?php echo $t; ?>' /><?php echo $t; ?></td>
			<td align='center'><input type='hidden' value='<?php echo $Product_code; ?>' name='pcode_<?php echo $t; ?>' /><?php echo $Product_code; ?></td>
			<td align='center'><input type='hidden' value='<?php echo $Product_name; ?>' name='pname_<?php echo $t; ?>' /><?php echo $Product_name; ?></td>
			<td align='center'><input type='hidden' value='<?php echo $UOM; ?>' name='uom_<?php echo $t; ?>' /><?php echo $UOM; ?></td>
			<td align='center'><input type='text' value='<?php echo $quantity; ?>' autocomplete='off' name='qty_<?php echo $t; ?>' id='qty_<?php echo $t; ?>' /></td>
		</tr>
	<?php } ?>
  </tbody>
   </table>
   </div>
   </td>
 </tr>
</table>

<table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td <?php if($_GET['del'] != 'del'){ ?>style="display:block;"<?php }else{?>style="display:none;"<?php }?> ><input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="button" name="View" value="View" class="buttons" onclick="window.location='StockReceiptsview.php'"/></td>
	 </td>
      </tr>
 </table>     
</form>

<div class="msg" align="center" <?php if($_GET['id']!='' && $_GET['del'] =='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
 <form action="StockReceiptsview.php" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="hidden" name="id" id="id" class="buttonsdel" value="<?php echo $_GET[id]; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='StockReceiptsview.php'"/>
 </form>
</div> 

</div>

<?php include("../include/error.php");?>
 
<div class="mcf"></div>        
	 <div id="errormsgdev" style="display:none;"><h3 align="center" class="myaligndev"></h3><button id="closebutton">Close</button></div>
   </div>
</div>
<?php include('../include/footer.php'); ?>