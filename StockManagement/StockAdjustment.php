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

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_POST);
$id=$_REQUEST['id'];
if($_REQUEST['id']!=''){
	if($_POST['submit']=='Save'){
		if($Date=='' || $Transaction_number==''  || $reason	=='' || $prodcnt =='')
		{
			header("location:StockAdjustment.php?no=9&id=$id");exit;
		}
		else{
			$KD_Code=getKDCode();
			$TransactionType="Adjustment";

			for($k=1; $k <= $prodcnt; $k++) { 

				$sno	=	$_POST["sno_".$k];
				$pcode	=	$_POST["pcode_".$k];
				$pname	=	$_POST["pname_".$k];
				$uom	=	$_POST["uom_".$k];
				$qty	=	$_POST["qty_".$k];

				if($qty=='')
				{
					header("location:StockAdjustment.php?no=9&id=$id");exit;
				}
				
				$sel_upd="select id,quantity,opening_id from stock_adjustment where Product_code ='$pcode' AND id = '$id'";
				$res_upd=mysql_query($sel_upd) or die(mysql_error());
				if(mysql_num_rows($res_upd) > 0) {
					$row_upd=mysql_fetch_array($res_upd);
					$old_qty	=	$row_upd[quantity];
					$opening_id	=	$row_upd[opening_id];
				}
				//$sel="select id,quantity from opening_stock_update where Product_code ='$pcode'";
				//$sel_query=mysql_query($sel) or die(mysql_error());

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
				$sql	=	"UPDATE stock_adjustment SET Date='$Date',KD_Code= '$KD_Code', Transaction_number='$Transaction_number',DSR_Code='$DSR_Code',reason='$reason',line_number='$sno',Product_code='$pcode',UOM='$uom',quantity='$qty' WHERE id = '$id'";
			}				
			mysql_query( $sql) or die(mysql_error());
			header("location:StockAdjustmentview.php?no=2");
		}
	}
}
elseif($_POST['submit']=='Save'){

	/*echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";*/

	//echo "goodfd";
	//exit;
	if($Date=='' || $Transaction_number==''  || $reason=='' || $product_names=='' || $prodcnt =='')
	{
		//exit;
		header("location:StockAdjustment.php?no=9&id=$id");exit;
	}
	else{
		$sel="select * from stock_adjustment where Date ='$Date' AND Transaction_number ='$Transaction_number'";
		$sel_query=mysql_query($sel) or die(mysql_error());
		if(mysql_num_rows($sel_query)=='0') {
			
			$KD_Code=getKDCode();
			$TransactionType="Adjustment";
			$ins_val	=	'';

			for($k=1; $k <= $prodcnt; $k++) { 
				
				$qty			=	'';
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
					header("location:StockAdjustment.php?no=9&id=$id");exit;
				}

				//$sel="select id,quantity from opening_stock_update where Product_code ='$pcode'";
				$sel="select id,BalanceQty from opening_stock_update where Product_code ='$pcode' AND KD_Code = '$KD_Code' ORDER BY id desc";
				$sel_query=mysql_query($sel) or die(mysql_error());
				if(mysql_num_rows($sel_query) > 0) {
				$row_qty=mysql_fetch_array($sel_query);
					$open_id	=	$row_qty[id];
					$updated_qty	=	$row_qty[BalanceQty] + $qty;

						//$sql_qty	=	"UPDATE opening_stock_update SET quantity = '$updated_qty' WHERE id = '$open_id'";
						$sql_qty	=	"INSERT INTO opening_stock_update SET `Date`='$Date',`StockDateTime`=NOW(),Product_description='$pname',`TransactionType`='$TransactionType',`TransactionNo`='$Transaction_number',`UOM1`='$uom',`TransactionQty`='$qty',`BalanceQty`='$updated_qty',`AddedFirstTime`='Y',`Product_code`='$pcode',`KD_Code`='$KD_Code'";
						mysql_query($sql_qty) or die(mysql_error());
						$last_inserted_id	=	mysql_insert_id();

				}
			
				if($k == $prodcnt) {
					$ins_val	.=	"('$Date','$KD_Code','$Transaction_number','$DSR_Code','$reason','$sno','$pcode','$uom','$qty','$last_inserted_id')";
				} else {
					$ins_val	.=	"('$Date','$KD_Code','$Transaction_number','$DSR_Code','$reason','$sno','$pcode','$uom','$qty','$last_inserted_id'),";
				}
			}
			//echo $ins_val;
			//exit;

			echo $sql="INSERT INTO `stock_adjustment`(`Date`,`KD_Code`,`Transaction_number`,`DSR_Code`,`reason`,`line_number`,`Product_code`,`UOM`,`quantity`,`opening_id`) values $ins_val";
			mysql_query($sql) or die(mysql_error());
			header("location:StockAdjustmentview.php?no=1");
		}
		else {
			header("location:StockAdjustment.php?no=18");
		}
	}
}

$id=$_REQUEST['id'];
$list=mysql_query("select * from stock_adjustment where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$Transaction_number = $row['Transaction_number'];
	$Date = $row['Date'];
	$DSR_Code = $row['DSR_Code'];
	$Product_code = $row['Product_code'];
	$sel_pname		=	"SELECT Product_description1 from product WHERE Product_code = '$Product_code'";
	$res_pname			=	mysql_query($sel_pname) or die(mysql_error());	
	$row_pname	= mysql_fetch_array($res_pname);


	$Product_name = $row_pname['Product_description1'];
	$reason = $row['reason'];
	$quantity = $row['quantity'];
	$UOM = $row['UOM'];
}
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainareadaily">
<div class="mcf"></div>
<div align="center" class="headingsgr">STOCK ADJUSTMENTS</div>
<div id="mytableformreceipt" align="center">
<form action="" method="post" id="stockvalidationadjust" onSubmit="return checkAdjustment();">

 <fieldset align="left" class="alignment">
  <legend><strong>Stock Adjustment</strong></legend>
<table width="50%" align="left">
 <tr>
  <td>
  <table>
    <tr height="30">
    <td width="120">Date*</td>
    <td>
	
	<!-- <input type="text" name="Date" id="Date" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/> -->

	<input type="text" name="Date" id="Date" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" readonly maxlength="10" autocomplete='off'/>
	</td>
    </tr>
    
	<!-- <tr height="40">
	     <td width="120" nowrap="nowrap">SR Name*</td>
	     <td>
	     	 
	     	 <select name="DSRName" id="DSRName" onChange="loadDSRAdj(this.value);">
	     	<option value="" >--Select--</option>
	     	<?php $sel_supp		=	"SELECT DSRName,DSR_Code from dsr GROUP BY DSRName";
	     	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	     	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	     	<option value="<?php echo $row_supp[DSR_Code]; ?>" <?php if($DSR_Code == $row_supp[DSR_Code]) { echo "selected"; } ?> ><?php echo $row_supp[DSRName]; ?></option>
	     	<?php } ?>
	    </select>&nbsp;</td>
	
	</tr> -->

	
   </table>
   </td>
 </tr>
</table>

<!----------------------------------------------- Right Table End -------------------------------------->

<table width="50%" align="left">
 <tr>
  <td>
  <table>    
	
  <tr height="30">
     <td width="170" nowrap="nowrap">Adjustment Number*</td>
	 <?php
		 if(!isset($_GET[id]) && $_GET[id] == '') {
			$query_oldtranum					=	"SELECT Transaction_number FROM stock_adjustment ORDER BY id DESC";			
			$res_oldtranum						=	mysql_query($query_oldtranum) or die(mysql_error());
			$rowcnt_oldtranum					=	mysql_num_rows($res_oldtranum);
			//$rowcnt_oldtranum					=	0; // comment if live
			if($rowcnt_oldtranum > 0) {
				$row_oldtranum					=	mysql_fetch_array($res_oldtranum);
				$Old_Transaction_number				=	$row_oldtranum['Transaction_number'];

				$gettxnno						=	abs(str_replace("ADJ",'',strstr($Old_Transaction_number,"ADJ")));
				$gettxnno++;
				if($gettxnno < 10) {
					$createdcode	=	"00".$gettxnno;
				} else if($gettxnno < 100) {
					$createdcode	=	"0".$gettxnno;
				} else {
					$createdcode	=	$gettxnno;
				}

				$Transaction_number				=	getKDCode()."ADJ".$createdcode;
			} else {
				$Transaction_number				=	getKDCode()."ADJ001";
			}
		}
	?>

     <td width="120"><input type="text" name="Transaction_number" id="Transaction_number" size="30" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "readonly"; } else {  echo ""; } ?> value="<?php echo $Transaction_number; ?>" readonly maxlength="20" autocomplete='off'/></td>
  </tr>
  
  <!-- <tr height="40">
    <td width="180">SR Code*</td>
    <td><input type="text" name="DSR_Code" id="DSR_Code" readonly size="30" value="<?php echo $DSR_Code; ?>" maxlength="20" autocomplete='off'/>&nbsp;
  	</td>
  </tr> -->
       </table>
       </td>
     </tr>
</table>

<!----------------------------------------------- Left Table End -------------------------------------->
<table width="50%" align="left">
 <tr>
  <td>
  <table>
    <tr height="25">
    <td width="120">Reason*</td>
    <td height="20" ><select name="reason" id="reason" >
	<option value="" >--Select--</option>
	<?php $sel_supp		=	"SELECT stock_adjustment_reason from stock_adjustment_reason GROUP BY stock_adjustment_reason";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[stock_adjustment_reason]; ?>" <?php if($reason == $row_supp[stock_adjustment_reason]) { echo "selected"; } ?> ><?php echo $row_supp[stock_adjustment_reason]; ?></option>
	<?php } ?>
	</select>&nbsp;
	</td>
    </tr>
<!-- 	<tr>
		<td >&nbsp;</td>
	</tr> 
	 -->       </table>
       </td>
     </tr> 
</table>

<!----------------------------------------------- Right Table End -------------------------------------->
<table width="50%" align="right">
 <tr>
  <td>
  <table>
    <tr height="20">
	<td width="180">Product*</td>
    <td width="120"><select <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> name="product_names" id="product_names">
	
	<option value="" >--Select Product--</option>
	<?php //$sel_supp		=	"SELECT product_code,Product_description1 from product";
	$sel_supp		=	"SELECT Product_code,Product_description from opening_stock_update WHERE (TransactionQty != '' AND BalanceQty != '' AND TransactionNo != '' AND TransactionType !='') GROUP BY Product_code";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	$w					=	0;					
	while($row_supp	= mysql_fetch_array($res_supp)){ 	
		//$sel_isscheck		=	"SELECT id from opening_stock_update WHERE TransactionType = 'Issues' AND Product_code = '$row_supp[Product_code]'";
		$sel_isscheck		=	"SELECT id from opening_stock_update WHERE Product_code = '$row_supp[Product_code]'";
		$res_isscheck			=	mysql_query($sel_isscheck) or die(mysql_error());
		$rowcnt_isscheck			=	mysql_num_rows($res_isscheck);		
		if($rowcnt_isscheck > 0){ 
			$w++;
			?>
			<option value="<?php echo $row_supp[Product_code]; ?>" <?php if($Product_code == $row_supp[Product_code]) { echo "selected"; } ?> ><?php echo $row_supp[Product_description]; ?></option>			
		<?php } // if loop
	} //while loop 
	if($w == 0) {
	?>			
	<option value="" >No Stock Issues to Adjust</option>
	<?php } ?>

	</select></td>
    <td><button class="buttons" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> onClick="return addproduct();">Add</button></td>
    </tr>
	<tr>
		<td height="10"><span id="showerr" style="display:none;color:#FF0000;"></span><input type="hidden" value="<?php if(isset($_GET[id]) && $_GET[id] != '') { ?> 1 <?php } ?>" name="prodcnt" id="prodcnt" /></td>
	</tr>
   </table>
   </td>
 </tr>
</table>
</fieldset>

<!----------------------------------------------- last Table End -------------------------------------->

<table width="100%" align="left" id="productsadd" <?php if(!isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none" <?php } if(isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none" <?php } ?> >
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
			<td align='left'><input type='hidden' value='<?php echo $Product_name; ?>' name='pname_<?php echo $t; ?>' /><?php echo $Product_name; ?></td>
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
	 <input type="button" name="View" value="View" class="buttons" onclick="window.location='StockAdjustmentview.php'"/></td>
      </tr>
 </table>     
</form>

<div class="msg" align="center" <?php if($_GET['id']!='' && $_GET['del'] =='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
 <form action="StockAdjustmentview.php" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="hidden" name="id" id="id" class="buttonsdel" value="<?php echo $_GET[id]; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='StockAdjustmentview.php'"/>
 </form>
</div>

</div>

<!---- Form End ----->

<?php include("../include/error.php");?>

<div class="mcf"></div>        
	<div id="errormsgdev" style="display:none;"><h3 align="center" class="myaligndev"></h3><button id="closebutton">Close</button></div>
   </div>
</div>
<?php include('../include/footer.php');?>