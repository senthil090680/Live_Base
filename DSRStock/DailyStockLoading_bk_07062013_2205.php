<?php
session_start();
ob_start();
require_once('../include/header.php');
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

error_reporting(E_ALL && ~ E_NOTICE);
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_POST);
//print_r($_POST);
$id=$_REQUEST['id'];
if($_REQUEST['id']!=''){
	if($_POST['submit']=='Save'){
		$KD_Code="KD001";
		for($k=1; $k <= $prodcnt; $k++) { 
			$sno				=	$_POST["sno_".$k];
			$product_code		=	$_POST["product_code_".$k];
			$product_codename	=	$_POST["product_codename_".$k];
			$product_UOM		=	$_POST["product_UOM_".$k];
			$Loaded_Qty			=	$_POST["Loaded_Qty_".$k];
			$focus_Flag			=	$_POST["focus_Flag_".$k];
			$scheme_Flag		=	$_POST["scheme_Flag_".$k];
			$ProductType		=	$_POST["ProductType_".$k];
		
			$sql	=	"UPDATE dailystockloading SET Date='$Date',KD_Code= '$KD_Code',DSR_Code='$DSR_Code',vehicle_code='$vehicle_code',Product_code='$product_code',UOM='$product_UOM',Loaded_Qty='$Loaded_Qty',focus_Flag='$focus_Flag',scheme_Flag='$scheme_Flag',ProductType='$ProductType' WHERE id = '$id'";
		}				
		mysql_query( $sql) or die(mysql_error());
		header("location:DailyStockLoadingview.php?no=2");
	}
}
elseif($_POST['submit']=='Save'){

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

//echo "goodfd";
exit;*/
		
	$KD_Code="KD001";
	$ins_val	=	'';
	for($k=1; $k <= $prodcnt; $k++) { 

		$sno				=	$_POST["sno_".$k];
		$product_code		=	$_POST["product_code_".$k];
		$product_codename	=	$_POST["product_codename_".$k];
		$product_UOM		=	$_POST["product_UOM_".$k];
		$Loaded_Qty			=	$_POST["Loaded_Qty_".$k];
		$focus_Flag			=	$_POST["focus_Flag_".$k];
		$scheme_Flag		=	$_POST["scheme_Flag_".$k];
		$ProductType		=	$_POST["ProductType_".$k];
		
		if($k == $prodcnt) {
			$ins_val	.=	"('$Date','$KD_Code','$DSR_Code','$vehicle_code','$product_code','$product_UOM','$Loaded_Qty','$focus_Flag','$scheme_Flag','$ProductType')";
		} else {
			$ins_val	.=	"('$Date','$KD_Code','$DSR_Code','$vehicle_code','$product_code','$product_UOM','$Loaded_Qty','$focus_Flag','$scheme_Flag','$ProductType'),";
		}
	}
	//echo $ins_val;
	//exit;

	echo $sql="INSERT INTO `dailystockloading`(`Date`,`KD_Code`,`DSR_Code`,`vehicle_code`,`Product_code`,`UOM`,`Loaded_Qty`,`focus_Flag`,`scheme_Flag`,`ProductType`) values $ins_val";
	mysql_query($sql) or die(mysql_error());
	header("location:DailyStockLoadingview.php?no=1");
}

$id=$_REQUEST['id'];
$list=mysql_query("select * from dailystockloading where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$vehicle_code = $row['vehicle_code'];
	$Date = $row['Date'];
	$DSR_Code = $row['DSR_Code'];
	$Product_code = $row['Product_code'];
	$sel_pname		=	"SELECT Product_description1 from product WHERE Product_code = '$Product_code'";
	$res_pname			=	mysql_query($sel_pname) or die(mysql_error());	
	$row_pname	= mysql_fetch_array($res_pname);


	$Product_name = $row_pname['Product_description1'];
	$Loaded_Qty = $row['Loaded_Qty'];
	$Confirmed_Qty = $row['Confirmed_Qty'];
	$focus_Flag = $row['focus_Flag'];
	$scheme_Flag = $row['scheme_Flag'];
	$ProductType = $row['ProductType'];
	$UOM = $row['UOM'];
}
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainareadaily">
<div class="mcf"></div>
<div align="center" class="headingsgrdaily">DSR DAILY STOCK LOADING</div>
<div id="mytableformdaily" align="center">
<form action="" method="post" id="dailystockvalidation" onSubmit="return checkdailystock();">
 <fieldset align="left" class="alignment">
  <legend><strong>Stock Loading</strong></legend>

<table width="100%">
 <tr>
  <td>
  <table>
    <tr  height="20">
    <td  width="120">Date*</td>
    <td>
	<!-- <input type="text" name="Date" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/> -->
	<input type="text" name="Date" id="Date" Size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" readonly maxlength="10" autocomplete='off'/>
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
    <tr height="30">
     <td width="120">DSR Name*</td>
     <td><select name="DSRName" onChange="loadDSR(this.value);">
	<option value="" >--Select--</option>
	<?php $sel_supp		=	"SELECT DSRName,DSR_Code from dsr GROUP BY DSRName";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[DSR_Code]; ?>" <?php if($DSR_Code == $row_supp[DSR_Code]) { echo "selected"; } ?> ><?php echo $row_supp[DSRName]; ?></option>
	<?php } ?>
	</select>&nbsp;</td>
	</tr>
	<tr height="40">
     <td width="120">Vehicle Name*</td>
     <td>
	 <input type="text" readonly name="vehicle_name" id="vehicle_name" value="<?php echo $vehicle_name; ?>"/>
	 
	 <!-- <select name="vehicle_name" onChange="loadvehicle(this.value);">
	 	<option value="" >--Select--</option>
	 	<?php $sel_supp		=	"SELECT vehicle_code,vehicle_desc from vehicle_master GROUP BY vehicle_desc";
	 	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	 	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	 	<option value="<?php echo $row_supp[vehicle_code]; ?>" <?php if($vehicle_code == $row_supp[vehicle_code]) { echo "selected"; } ?> ><?php echo $row_supp[vehicle_desc]; ?></option>
	 	<?php } ?>
	 	</select> -->
	
	&nbsp;</td>
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
   
   <tr height="30">
    <td width="120">DSR Code*</td>
    <td><input type="text" name="DSR_Code" readonly size="30" value="<?php echo $DSR_Code; ?>" maxlength="20" autocomplete='off'/>&nbsp;
	</td>
    </tr>	
	<tr height="40">
    <td width="120">Vehicle Code*</td>
    <td >
	<input type="text" name="vehicle_code" id="vehicle_code" readonly size="30" value="<?php echo $vehicle_code; ?>" maxlength="20" autocomplete='off'/>&nbsp;
	</td>
    </tr>
  </table>
       </td>
     </tr>
</table>
  </fieldset>
<!----------------------------------------------- Right Table End -------------------------------------->
 <fieldset align="left" class="alignment">
  <legend><strong>Product</strong></legend>
<table width="100%" align="right" style="clear:both">
 <tr>
  <td>
  <table>
    <tr height="20">
    <td width="120"><select <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> name="product_names" id="product_names" >
	<option value="" >--Select Product--</option>
	<?php $sel_supp		=	"SELECT UOM1,product_type,Focus,product_code,Product_description1 from product";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());

	while($row_supp	= mysql_fetch_array($res_supp)){ 
		
		$sel_scheme		=	"SELECT Scheme_code from product_scheme_master WHERE Header_Product_code = '$row_supp[product_code]' AND Scheme_code !='' AND Status = 'active'";
		$res_scheme		=	mysql_query($sel_scheme) or die(mysql_error());
		$nor_scheme		=	mysql_num_rows($res_scheme);
		if($nor_scheme > 0) {
			$scheme_status = "Yes";
		} else {
			$scheme_status = "No";
		}

		if($row_supp[UOM1] == 'pcs' || $row_supp[UOM1] == 'Pieces' || $row_supp[UOM1] == 'pieces') {
			$UOM_dis		=	"Pieces";
		}
		
	?>
	<option value="<?php echo $row_supp[Product_description1]."~".$UOM_dis."~".$row_supp[product_type]."~".$row_supp[Focus]."~".$scheme_status; ?>" <?php if($Product_code == $row_supp[product_code]) { echo "selected"; } ?> ><?php echo $row_supp[product_code]; ?></option>
	<?php } ?>
	</select></td>
    <td><button class="buttons" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> onClick="return addproduct_field();">Add</button></td>
    </tr>
	<tr>
		<td><span id="showerr" style="display:none;color:#FF0000;">Choose Product</span><input type="hidden" value="<?php if(isset($_GET[id]) && $_GET[id] != '') { ?> 1 <?php } ?>" name="prodcnt" id="prodcnt" /></td>
	</tr>
   </table>
   </td>
 </tr>
</table>
 </fieldset>

<!----------------------------------------------- last Table End -------------------------------------->

<table width="100%" align="left" id="productsadd" <?php if(!isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none;" <?php } if(isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none;" <?php } ?> >
 <tr>
  <td>
  <div class="condaily">
  <table>
  <thead><tr><th align='center'>Serial Number</th><th class='rounded' align='center'>Product Code</th><th align='center'>Product Name</th><th align='center'>UOM</th><th align='center'>Loaded Quantity</th><th align='center'>Confirmed Quantity</th><th align='center'>Focus Flag</th><th align='center'>Scheme Flag</th><th align='center'>Product Type</th></tr></thead>  
  <tbody id="productsadded">
	<?php $t = 1; if(isset($_GET[id]) && $_GET[id] != '') { ?> 
		<tr><td align='center'><input type='hidden' value='<?php echo $t; ?>' name='sno_<?php echo $t; ?>' /><?php echo $t; ?></td><td align='center'><input type='hidden' value='<?php echo $Product_code; ?>' name='product_code_<?php echo $t; ?>' id='product_code_<?php echo $t; ?>' /><?php echo $Product_code; ?></td><td align='center'><input type='hidden' value='<?php echo $Product_name; ?>' name='product_codename_<?php echo $t; ?>' /><?php echo $Product_name; ?></td><td align='center'><input type='hidden' value='<?php echo $UOM; ?>' name='product_UOM_<?php echo $t; ?>' /><?php echo $UOM; ?></td><td align='center'><input type='text' value='<?php echo $Loaded_Qty; ?>' name='Loaded_Qty_<?php echo $t; ?>' id='Loaded_Qty_<?php echo $t; ?>' /></td><td align='center'><input type='text' readonly value='<?php echo $Confirmed_Qty; ?>' name='Confirmed_Qty_<?php echo $t; ?>' /></td><td align='center'><input type='hidden' value='<?php echo $focus_Flag; ?>' name='focus_Flag_<?php echo $t; ?>' /><?php echo $focus_Flag; ?> </td><td align='center'><input type='hidden' readonly value='<?php echo $scheme_Flag; ?>' name='scheme_Flag_<?php echo $t; ?>' /><?php echo $scheme_Flag; ?></td><td align='center'><input type='hidden' readonly value='<?php echo $ProductType; ?>' name='ProductType_<?php echo $t; ?>' /><?php echo $ProductType; ?></td></tr>
	<?php } ?>
  </tbody>
   </table>
     </div>
   </td>
 </tr>
</table>

<?php if($_GET['del'] != 'del'){ ?>
<table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td><input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="button" name="View" value="View" class="buttons" onclick="window.location='DailyStockLoadingview.php'"/></td>
      </tr>
 </table> 
 <?php } ?>
</form>
 <div class="msg" align="center" <?php if($_GET['id']!='' && $_GET['del'] =='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="DailyStockLoadingview.php" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="hidden" name="id" id="id" class="buttonsdel" value="<?php echo $_GET[id]; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='DailyStockLoadingview.php'"/>
      </form>
     </div>  

<div id="errormsgcus" style="display:none;"><h3 align="center" class="myalign"></h3><button id="closebutton">Close</button></div>

</div>

<!---- Form End ----->
<div class="clearfix"></div>
<div class="clearfix"></div>

<?php require_once("../include/error.php");?>
</div>
<?php require_once('../include/footer.php');?>