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

	/*echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";

	echo "<pre>";
	print_r($_FILES);
	echo "</pre>";

	exit;*/
$KD_Code	=	getKDCode();
EXTRACT($_POST);
$id=$_REQUEST['id'];
if($_REQUEST['id']!=''){
	if($_POST['submit']=='Save'){
		if($Date=='' || $_FILES[excelfile][name]=='')
		{
			header("location:openingstockins.php?no=9&id=$id");exit;
		}
		else{
			$KD_Code=getKDCode();

			$file=$_FILES['excelfile']['tmp_name'];

			$filename=$_FILES['excelfile']['name'];

			$fname=explode(".",$filename);
			$ext=$fname[1];

			if($ext=="csv") {
				$handle=fopen($file,"r");
			}
			for($k=1; $k <= $prodcnt; $k++) { 

				$sno	=	$_POST["sno_".$k];
				$pcode	=	$_POST["pcode_".$k];
				$pname	=	$_POST["pname_".$k];
				$uom	=	$_POST["uom_".$k];
				$qty	=	$_POST["qty_".$k];

				if($qty=='')
				{
					header("location:openingstockins.php?no=9&id=$id");exit;
				}
				
				$sel_upd="select id,quantity from opening_stock_update where Product_code ='$pcode' AND id = '$id'";
				$res_upd=mysql_query($sel_upd) or die(mysql_error());
				if(mysql_num_rows($res_upd) > 0) {
					$row_upd=mysql_fetch_array($res_upd);
					$old_qty	=	$row_upd[quantity];
				}
				$sel="select id,quantity from opening_stock_update where Product_code ='$pcode'";
				$sel_query=mysql_query($sel) or die(mysql_error());
				if(mysql_num_rows($sel_query) > 0) {
					$row_qty=mysql_fetch_array($sel_query);
					$open_id	=	$row_qty[id];
					/*echo $old_qty."</br>";
					echo $qty."</br>";
					echo $row_qty[quantity]."</br>";*/
					
					echo $updated_qty	=	($row_qty[quantity] + $qty) - $old_qty ;
					//exit;
					$sql_qty	=	"UPDATE opening_stock_update SET quantity = '$updated_qty' WHERE id = '$open_id'";
					mysql_query($sql_qty) or die(mysql_error());
				}
				$sql	=	"UPDATE opening_stock_update SET Date='$Date',KD_Code= '$KD_Code', Transaction_number='$Transaction_number',DSR_Code='$DSR_Code',reason='$reason',line_number='$sno',Product_code='$pcode',UOM='$uom',quantity='$qty' WHERE id = '$id'";
			}				
			mysql_query( $sql) or die(mysql_error());
			header("location:openingstockins.php?no=2");
		}
	}
}
elseif($_POST['submit']=='Update'){

	/*echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";

	echo "<pre>";
	print_r($_FILES);
	echo "</pre>";

	exit;*/

	if($Date=='' || $_FILES[excelfile][name]=='')
	{
		//exit;
		header("location:openingstockins.php?no=9&id=$id");exit;
	}
	else {			
		
		$KD_Code=getKDCode();
		$TransactionType="OpeningStock";
		$TransactionNo="10001";
		$ins_val		=	'';
		$error_show		=	'';

		$file=$_FILES['excelfile']['tmp_name'];

		$filename=$_FILES['excelfile']['name'];

		$fname=explode(".",$filename);
		$ext=$fname[1];
		
		$w	=	0;
		if($ext=="csv") {
			$handle=fopen($file,"r");

			while(($fileop=fgetcsv($handle,10000,",")) !== FALSE)
			{	
				
				if($w == 0){
					$w++;
					continue;
				}
				//print_r($fileop);
				//exit;
				$KD_Code		=	$fileop[0];
				$Product_code	=	$fileop[1];
				
				$sel_checkproduct="SELECT id from opening_stock_update WHERE Product_code = '$Product_code'";
				$res_checkproduct=mysql_query($sel_checkproduct) or die(mysql_error());
				if(mysql_num_rows($res_checkproduct) == 0) {
					if($error_show	== '') {
						$error_show		.=	$fileop[1];
					} else {
						$error_show		.=	"~".$fileop[1];
					}
				}

				$sel_checkqty="SELECT id from opening_stock_update WHERE Product_code ='$Product_code' AND (TransactionQty !='') AND (BalanceQty !='') AND AddedFirstTime in ('D') ";
				$res_checkqty=mysql_query($sel_checkqty) or die(mysql_error());
				if(mysql_num_rows($res_checkqty) > 0) {
					continue;
				}

				$UOM			=	$fileop[2];
				$quantity		=	$fileop[3];

				$sql="UPDATE `opening_stock_update` SET `Date`='$Date',`StockDateTime`=NOW(),`TransactionType`='$TransactionType',`TransactionNo`='$TransactionNo',`UOM1`='$UOM',`TransactionQty`='$quantity',`BalanceQty`='$quantity',`AddedFirstTime`='D',KD_Code='$KD_Code' WHERE Product_code = '$Product_code'";
				mysql_query($sql) or die(mysql_error());

				$KD_Code		=	'';
				$Product_code	=	'';
				$UOM			=	'';
				$quantity		=	'';
			}
			setcookie('errorshow',$error_show,time()+3600);
			//exit;
		}
		header("location:openingstockview.php?no=2&dis=err");
	}
}

$id=$_REQUEST['id'];
$list=mysql_query("select * from opening_stock_update where id= '$id'"); 
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
<link href="../css/popup.css" rel="stylesheet" type="text/css" />
<div id="mainareastockstatic">
<div class="mcf"></div>
<div align="center" class="headingopening">OPENING STOCK UPDATE</div>
<div id="mytableformopening" align="center">
<form action="" method="post" id="dailystockvalidation" onSubmit="return checkFile();" enctype="multipart/form-data">
<table width="100%" align="left">
 <tr>
  <td>
  <table style="padding-left:10px;">
    <tr height="54">
    <td width="120"><span style="font-family:Verdana;font-size:12px;">Date*</span></td>
    <td>
	<!-- <input type="text" name="Date" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/> -->

	<input type="text" name="Date" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" readonly maxlength="10" autocomplete='off'/>
	</td>
    </tr>
	 <tr  height="20">
    <td  width="120" nowrap="nowrap"><span style="font-family:Verdana;font-size:12px;">Upload File*</span></td>
    <td><input type="file" name="excelfile" id="excelfile" />&nbsp;
	</td>
    </tr>
    <tr height="20">
     <td width="120">&nbsp;</td>
     <td nowrap="nowrap" >
	 <a href="sample_csv.php"><span style="font-size:11px;">Download Sample XLS file and see the format (XLS)</span></a>
	 <!-- <input type="button" name="cancel" style="cursor:hand;cursor:pointer;font-size:11px;" value="Download Sample XLS file and see the format (XLS)" onclick="window.location='sample_csv.php'"/> -->
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
   
       </table>
       </td>
     </tr> 
</table>

<table width="100%" style="clear:both">
      <tr align="center" height="50px;">
      <td><input type="submit" name="submit" id="submit" class="buttons" value="Update" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
	 <!-- <a href="javascript:void(0);" class="buttonsStock" onclick="stockAddPopup();">Individual Stock Update</a> -->
	 <input type="button" name="individual" value="Individual Stock Update" class="buttonsStock" onclick="stockAddPopup();"/>
	 &nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="button" name="View" value="View" class="buttons" onclick="window.location='openingstockview.php'"/>
	 </td>
      </tr>
 </table>     
</form>
</div>

<?php include("../include/error.php");?>
<div class="mcf"></div>

<div id="indStockUpdate" class="conind openingPopup">
<div>
<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeindstock();"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor">
<form action="" method="post" id="stockreceiptvalidation" >
<table width="100%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Individual Stock Update</strong></legend>
  <table>
    <tr height="20">
    <td height="20" width="120">Product Code*</td>
    <td  width="120">
	<select <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> name="product_code" id="product_code" onchange="checkstockqty('product_code',this.value,'<?php echo getKDCode(); ?>');">
		<option value="" >--Select Product--</option>
	
	<?php $umo = 0; $sel_allprod		=	"SELECT Product_code from product";
	$res_allprod			=	mysql_query($sel_allprod) or die(mysql_error());	
	while($row_allprod	= mysql_fetch_array($res_allprod)){ 
		$product_all		=	$row_allprod[Product_code]; 
		$sel_supp			=	"SELECT TransactionQty,Product_code,Product_description from opening_stock_update WHERE Product_code = '$product_all'";
		$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
		$noofrows			=	mysql_num_rows($res_supp);	
		if($noofrows == 1) {
			$umo++;
			$row_supp	= mysql_fetch_array($res_supp); ?>
			<option value="<?php echo $row_supp[Product_code]."~".$row_supp[TransactionQty]; ?>" <?php if($Product_code == $row_supp[Product_code]) { echo "selected"; } ?> ><?php echo $row_supp[Product_code]; ?></option>
		<?php }
		$noofrows			=	'';
		$product_all		=	''; 
	}	
	?>
	</select>
	</td>
    </tr>
	<tr height="20">
    <td height="20" width="120">Product Description*</td>
    <td  width="220"><select <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> name="product_name" id="product_name" onchange="checkstockqty('product_name',this.value,'<?php echo getKDCode(); ?>');" style="width:400px;">
	<option value="" >--Select Product--</option>
	
	<?php $sel_allprod		=	"SELECT Product_code from product";
	$res_allprod			=	mysql_query($sel_allprod) or die(mysql_error());	
	while($row_allprod	= mysql_fetch_array($res_allprod)){ 
		$product_all		=	$row_allprod[Product_code]; 
		echo $sel_supp			=	"SELECT Product_code,Product_description from opening_stock_update WHERE Product_code = '$product_all'";
		$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
		$noofrows			=	mysql_num_rows($res_supp);	
		if($noofrows == 1) {
			$row_supp	= mysql_fetch_array($res_supp); ?>
			<option value="<?php echo $row_supp[Product_code]; ?>" <?php if($Product_code == $row_supp[Product_code]) { echo "selected"; } ?> ><?php echo $row_supp[Product_description]; ?></option>
		<?php }
		$noofrows			=	'';
		$product_all		=	''; 
	}	
	?>

	</select>
	</td>
    </tr>

    <tr height="20">
     <td height="40" width="120">UOM*</td>
     <td height="40"><input type="text" name="UOM" id="UOM" size="5" value="PCS" readonly maxlength="20" autocomplete='off'/></td>
	 </tr>
	<tr height="20">
     <td height="40" width="120">Quantity*</td>
     <td height="40"><input type="text" name="TransactionQty" id="TransactionQty" size="10" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "readonly"; } else { echo ""; } ?> value="<?php echo $TransactionQty; ?>" maxlength="10" autocomplete='off'/></td>
	 </tr>
	 <tr height="20">
     <td height="40" width="120">Date*</td>
     <td height="40"><input type="text" name="Date" id="Date" size="10" value="<?php echo date('Y-m-d'); ?>" maxlength="10" autocomplete='off'/></td>
	 </tr>
   </table>
 </fieldset>
   </td>
 </tr>
</table>

<table width="100%" style="clear:both">
      <tr align="center" height="50px;">
		 <td align="center" <?php if($_GET['del'] != 'del'){ ?>style="display:block;"<?php }else{?>style="display:none;"<?php }?> ><input type="button" name="submit" id="submit" class="buttons" value="Save" onclick="return checkindstock();" />&nbsp;&nbsp;&nbsp;&nbsp;
		 <input type="hidden" name="umo" id="umo" size="30" value="<?php echo $umo; ?>" readonly maxlength="20" autocomplete='off'/>
		 <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
		 <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='openingstockins.php'"/>
		 </td>
     </tr>	 
 </table>     
</form>
<div class="mcf"></div>
	</p>
	<label id="errormsgindopen" <?php if($umo == 0) { ?> style="display:block"; <?php } else { ?> style="display:none;" <?php } ?> ><h3 align="center" class="myalignindopen"><?php if($umo == 0) echo "No Stocks for Update"; ?> </h3><button id="closebutton">Close</button></label>
	<div class="clearfix"></div>
	</div>
</div>


<div class="mcf"></div>        

<div id="errormsgopen" style="display:none;"><h3 align="center" class="myalignopen"></h3><button id="closebutton">Close</button></div>

<div class="clearfix"></div>
</div>
<?php include('../include/footer.php');?>