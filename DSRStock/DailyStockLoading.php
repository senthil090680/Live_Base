<?php
session_start();
ob_start();
require_once('../include/header.php');
require_once "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

//ini_set("display_errors",true);
//ini_set("max_input_vars",10000);
//echo ini_get("max_input_vars");
error_reporting(E_ALL && ~ E_NOTICE);
//pre($_REQUEST);
//exit;
EXTRACT($_REQUEST);
//print_r($_POST);
$id=$_REQUEST['id'];
if($_REQUEST['id']!=''){

//pre($_REQUEST);
//exit;

	if($_POST['submithidden']=='Save'){
		//pre($_REQUEST);

		$KD_Code=getKDCode();
		//echo $prodcnt;
		for($k=1; $k <= $prodcnt; $k++) { 
			$sno				=	$_POST["sno_".$k];
			$product_code		=	$_POST["product_code_".$k];
			$product_codename	=	$_POST["product_codename_".$k];
			$product_UOM		=	$_POST["product_UOM_".$k];
			$Loaded_Qty			=	$_POST["Loaded_Qty_".$k];
			$focus_Flag			=	$_POST["focus_Flag_".$k];
			$scheme_Flag		=	$_POST["scheme_Flag_".$k];
			$ProductType		=	$_POST["ProductType_".$k];
		
			echo $sql	=	"UPDATE dailystockloading SET Date=NOW(),KD_Code= '$KD_Code',DSR_Code='$DSR_Code',vehicle_code='$vehicle_code',Product_code='$product_code',UOM='$product_UOM',Loaded_Qty='$Loaded_Qty',focus_Flag='$focus_Flag',scheme_Flag='$scheme_Flag',ProductType='$ProductType' WHERE id = '$id'";
			//exit;
		}				
		mysql_query( $sql) or die(mysql_error());
		header("location:DailyStockLoadingview.php?no=2");
	}
}
elseif($_POST['submithidden']=='Save'){

/*
echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

//echo "goodfd";
exit;

*/
		
	$KD_Code=getKDCode();
	//pre($_REQUEST);
	//$DSR_Code=getDSRid($DSR_Code);
	$ins_val	=	'';
	$w			=	0;
	for($k=1; $k <= $prodcnt; $k++) {		
		//if($_POST["cbox_".$k] != '' && isset($_POST['cbox_'.$k])) {
		if($_POST["Loaded_Qty_".$k] != '' && $_POST["Loaded_Qty_".$k] != '0' && isset($_POST["Loaded_Qty_".$k])) {
			$uom_coversion		=	1;
			$sno				=	$_POST["sno_".$k];
			$product_code		=	$_POST["product_code_".$k];
			$product_codename	=	$_POST["product_codename_".$k];
			$product_UOM		=	$_POST["product_UOM_".$k];
			$Loaded_Qty			=	$_POST["Loaded_Qty_".$k];
			$focus_Flag			=	$_POST["focus_Flag_".$k];
			$scheme_Flag		=	$_POST["scheme_Flag_".$k];
			$ProductType		=	$_POST["ProductType_".$k];
			$uomvalue			=	$_POST["uomval_".$k];

			/*if($uomvalue == 'CARTONS') {
				$uom_coversion	=	getdbval($product_code,'UOM_Conversion','Product_code','product');
				if($uom_coversion == '' || $uom_coversion == null) {
					$uom_coversion		=	1;
				}
				$Loaded_Qty			=	($uom_coversion) * ($Loaded_Qty);
			} elseif($uomvalue == 'PCS') {
				$Loaded_Qty			=	$_POST["Loaded_Qty_".$k];
			}*/
			
			if($w == 0) {
				$ins_val	.=	"(NOW(),'$KD_Code','$DSR_Code','$vehicle_code','$product_code','$product_UOM','$loadseqno','$Loaded_Qty','$focus_Flag','$scheme_Flag','$ProductType')";
				$w++;
			} else {
				$ins_val	.=	",(NOW(),'$KD_Code','$DSR_Code','$vehicle_code','$product_code','$product_UOM','$loadseqno','$Loaded_Qty','$focus_Flag','$scheme_Flag','$ProductType')";
			}
		} else {
			continue;
		}
	}
	echo $ins_val;
	//exit;

	echo $sql="INSERT INTO `dailystockloading` (`Date`,`KD_Code`,`DSR_Code`,`vehicle_code`,`Product_code`,`UOM`,`Load_Sequence_No`,`Loaded_Qty`,`focus_Flag`,`scheme_Flag`,`ProductType`) values $ins_val";
	mysql_query($sql) or die(mysql_error());
	header("location:DailyStockLoadingview.php?no=1");
}

$id=$_REQUEST['id'];
$list=mysql_query("select * from dailystockloading where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$vehicle_code		=	$row['vehicle_code'];
	$DateValue			=	explode(" ",$row['Date']);
	$Date				=	$DateValue[0];
	$DSR_Code			=	$row['DSR_Code'];
	$Product_code		=	$row['Product_code'];
	$sel_pname			=	"SELECT Product_description1 from product WHERE Product_code = '$Product_code'";
	$res_pname			=	mysql_query($sel_pname) or die(mysql_error());	
	$row_pname			=	mysql_fetch_array($res_pname);
	$Product_name		=	$row_pname['Product_description1'];

	$sel_vname			=	"SELECT vehicle_desc from vehicle_master WHERE vehicle_code = '$vehicle_code'";
	$res_vname			=	mysql_query($sel_vname) or die(mysql_error());	
	$row_vname			=	mysql_fetch_array($res_vname);

	$vehicle_name		=	$row_vname['vehicle_desc'];
	$Loaded_Qty			=	$row['Loaded_Qty'];
	$loadseqno			=	$row['Load_Sequence_No'];
	$Confirmed_Qty		=	$row['Confirmed_Qty'];
	$focus_Flag			=	$row['focus_Flag'];
	$scheme_Flag		=	$row['scheme_Flag'];
	$ProductType		=	$row['ProductType'];
	$UOM				=	$row['UOM'];
}
?>
<!------------------------------- Form -------------------------------------------------->
<link type="text/css" rel="stylesheet" href="../css/popup.css" />
<style type="text/css">
.loadingstyle {
	display:none;
	position:absolute;
	top:250px;
	left:470px;
	z-index:3;
}
.buttons_prod{
	-webkit-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	-moz-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	border-bottom-color:#333;
	border:1px solid #686868;
	background-color:#c1c1c1;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	color:#000;
	font-family:Calibri;
	font-size:12px;
	padding:3px;
	cursor:pointer;
	width:200px;
	height:25px;
}
.displayprod {
	margin:0 auto;
	display:none;
	background:#FFFFFF;
	color:#fff;
	width:1000px;
	height:550px;
	position:fixed;
	left:172px;
	top:100px;
	border:1px solid #EEEEEE;
	z-index:2;
	border-radius:5px 5px 5px 5px;
}
.condaily_prod{
	width:100%;
	text-align:left;
	height:420px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	overflow:scroll;
	overflow-x:hidden;
}
.condaily_prod th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}

.condaily_prod td {
	padding:2px 5px 0 5px;
	background:#fff; !important;
	border-collapse:collapse;
	padding-left:10px;
	color:#000; 
	font-size:14px;
}

.condaily_prod {
	background: #c1c1c1;
}
#errormsgpopupprod{
	width:38%;
	height:30px;
	background:#c1c1c1;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	padding-top:0px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	-ms-border-radius:10px;
	-o-border-radius:10px;
	text-align:center;
}
.myalignprod {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}
#closebutton{
	min-height:100%;
}
#closebutton_cus {
  position:relative;
  top:-35px;
  color:transparent;
  right:-190px;
  border:none;
  clear:both;
  height:100%;
  min-height:100%;
  background:url(../images/close_pop.png) no-repeat;
}
.bgclass td{
	background-color:#faf9f9;
}
</style>
<div id="mainareadaily">
<div class="mcf"></div>
<div align="center" class="headingsgrdaily">DSR DAILY STOCK LOADING</div>
<div id="mytableformdaily" align="center">
<form action="" method="post" id="dailystockvalidation" 
<?php //if($_GET['id']!='' && isset($_GET['id'])) { onSubmit="return checkdailystockedit();" <?php } 
//elseif($_GET['id']=='' && !isset($_GET['id'])) {  onSubmit="return checkdailystock();"  }
?> >
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
	<input type="text" name="Date" id="Date" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" readonly maxlength="10" autocomplete='off'/>
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
     <td width="120">SR Name*</td>
     <td><select name="DSRName" id="DSRName" onChange="loadDSR(this.value);">
	<option value="" >--Select--</option>
	<?php $sel_supp		=	"SELECT DSRName,DSR_Code from dsr GROUP BY DSRName";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[DSR_Code]; ?>" <?php if($DSR_Code == $row_supp[DSR_Code]) { echo "selected"; } ?> ><?php echo $row_supp[DSRName]; ?></option>
	<?php } ?>
	</select>&nbsp;</td>
	</tr>
	<tr height="40">
     <td width="120">Vehicle Reg. No.*</td>
     <td>
	 <input type="text" readonly name="vehicle_name" id="vehicle_name" autocomplete="off" value="<?php echo $vehicle_name; ?>"/>
	 
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
    <td width="120">SR Code*</td>
    <td><input type="text" name="DSR_Code" id="DSR_Code" readonly size="30" value="<?php echo $DSR_Code; ?>" maxlength="20" autocomplete='off'/>&nbsp;
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

<table width="50%" align="left">
 <tr>
  <td>
  <table>   
   <tr height="30">
    <td width="120">Load Seq. No.*</td>
    <td><input type="text" name="loadseqno" id="loadseqno" readonly size="1" value="<?php echo $loadseqno; ?>" maxlength="20" autocomplete='off'/>&nbsp;
	</td>
    </tr>	
	
  </table>
       </td>
     </tr>
</table>

  </fieldset>
<!----------------------------------------------- Right Table End -------------------------------------->
 
 
 <?php if(!isset($_GET[id]) && $_GET[id] == '') { ?> 
 <fieldset align="left" class="alignment">
  <legend><strong>Product</strong></legend>
<table width="100%" align="right" style="clear:both">
 <tr>
  <td>
  <table>
    <tr height="20">
   <!-- <td width="120">
	
	
	
	 <select <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> name="product_names" id="product_names" >
	<option value="" >--Select Product--</option>
	<?php
	
	//$sel_supp		=	"SELECT UOM1,product_type,Focus,Product_code,Product_description1 from product";
	$sel_supp		=	"SELECT Product_code,Product_description from opening_stock_update WHERE (TransactionQty != '' AND BalanceQty != '' AND TransactionNo != '' AND TransactionType !='') GROUP BY Product_code";
	$res_supp						=	mysql_query($sel_supp) or die(mysql_error());
	$w								=	0;					
	while($row_supp	= mysql_fetch_array($res_supp)){ 	
		$sel_isscheck				=	"SELECT id from opening_stock_update WHERE Product_code = '$row_supp[Product_code]'";
		$res_isscheck				=	mysql_query($sel_isscheck) or die(mysql_error());
		$rowcnt_isscheck			=	mysql_num_rows($res_isscheck);		
		if($rowcnt_isscheck > 0) { 
			$w++;
			echo $sel_prodtype			=	"SELECT product_type,UOM1,Focus from product WHERE Product_code = '$row_supp[Product_code]'";
			$res_prodtype			=	mysql_query($sel_prodtype) or die(mysql_error());
			$nor_prodtype			=	mysql_num_rows($res_prodtype);
			if($nor_prodtype > 0) {
				$row_prodtype		=	mysql_fetch_array($res_prodtype);
				$prodtype			=	$row_prodtype[product_type];
				$uomval				=	$row_prodtype[UOM1];
				$focusval			=	$row_prodtype[Focus];
				$scheme_status = "Yes";
				if($uomval == 'pcs' || $uomval == 'Pieces' || $uomval == 'pieces' || $uomval == 'PCS' || $uomval == 'Pcs') {
					$UOM_dis		=	"PCS";
				}
			}
	
			$sel_scheme				=	"SELECT Scheme_code from product_scheme_master WHERE Header_Product_code = '$row_supp[Product_code]' AND Scheme_code !=''";
			$res_scheme				=	mysql_query($sel_scheme) or die(mysql_error());
			$nor_scheme				=	mysql_num_rows($res_scheme);
			if($nor_scheme > 0) {
				$scheme_status		=	"Yes";
			} else {
				$scheme_status		=	"No";
			}			
			?>
			<option value="<?php echo $row_supp[Product_code]."~".$UOM_dis."~".$prodtype."~".$focusval."~".$scheme_status; ?>" <?php if($Product_code == $row_supp[Product_code]) { echo "selected"; } ?> ><?php echo $row_supp[Product_description]; ?></option>		
	<?php } // if loop
	} //while loop 
			if($w == 0) { ?>			
				<option value="" >No Stock Receipts to Load</option>
			<?php } ?>
	</select> 
	
	
	</td>-->
    <td colspan="2" width="250">
		<input type="button" value="Show Products" class="buttons_prod"  onClick="return addproduct_field();" />
		
		<!-- <button class="buttons" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> onClick="return addproduct_field();">Add</button> -->
	</td>
    </tr>
	<tr>
		<td><span id="showerr" style="display:none;color:#FF0000;"></span><!-- <input type="text" value="<?php if(isset($_GET[id]) && $_GET[id] != '') { ?> 1 <?php } ?>" name="prodcnt" id="prodcnt" /> --></td>
	</tr>
   </table>
   </td>
 </tr>
</table>
 </fieldset>
 <?php } ?>

<!----------------------------------------------- last Table End -------------------------------------->

<table width="100%" align="left" id="productsadd" <?php if(!isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none;" <?php } if(isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none;" <?php } ?> >
 <tr>
  <td>
  <div class="condaily">
  <table>
  <thead>
	<tr>
		<th align='center'>Serial Number</th>
		<th class='rounded' align='center'>Product Code</th>
		<th align='center'>Product Name</th>
		<th align='center'>UOM</th>
		<th align='center'>Loaded Quantity</th>
		<th align='center'>Confirmed Quantity</th>
		<th align='center'>Focus Flag</th>
		<th align='center'>Scheme Flag</th>
		<th align='center'>Product Type</th>
	</tr>
</thead>  
  <tbody id="productsadded">
	<?php $t = 1; if(isset($_GET[id]) && $_GET[id] != '') { ?> 
		<tr><td align='center'><input type='hidden' value='<?php echo $t; ?>' name='sno_<?php echo $t; ?>' /><?php echo $t; ?></td><td align='center'><input type='hidden' value='<?php echo $Product_code; ?>' name='product_code_<?php echo $t; ?>' id='product_code_<?php echo $t; ?>' /><?php echo $Product_code; ?></td><td align='center'><input type='hidden' value='<?php echo $Product_name; ?>' name='product_codename_<?php echo $t; ?>' /><?php echo $Product_name; ?></td><td align='center'><input type='hidden' value='<?php echo $UOM; ?>' name='product_UOM_<?php echo $t; ?>' /><?php echo $UOM; ?></td><td align='center'><input type='text' value='<?php echo $Loaded_Qty; ?>' autocomplete='off' name='Loaded_Qty_<?php echo $t; ?>' id='Loaded_Qty_<?php echo $t; ?>' /></td><td align='center'><input type='text' readonly value='<?php echo $Confirmed_Qty; ?>' name='Confirmed_Qty_<?php echo $t; ?>' /></td><td align='center'><input type='hidden' value='<?php echo $focus_Flag; ?>' name='focus_Flag_<?php echo $t; ?>' /><?php echo $focus_Flag; ?> </td><td align='center'><input type='hidden' readonly value='<?php echo $scheme_Flag; ?>' name='scheme_Flag_<?php echo $t; ?>' /><?php echo $scheme_Flag; ?></td><td align='center'><input type='hidden' readonly value='<?php echo $ProductType; ?>' name='ProductType_<?php echo $t; ?>' /><?php echo $ProductType; ?></td></tr>
	<?php } ?>
  </tbody>
   </table>
     </div>
   </td>
 </tr>
</table>

<div id="productshow" class="displayprod">

</div>
<div style="clear:both;"></div>	
<div class="mcf"></div>
<?php if($_GET['del'] != 'del'){ ?>
<table width="50%" style="clear:both;">
      <tr align="center" height="50px;">
      <td>
	  <?php //if($_GET['id']=='' && !isset($_GET['id'])) {  onClick="return checkdailystock();" <?php  } ?>
	  <?php if($_GET['id']!='' && isset($_GET['id'])) { ?> <input type="button" name="submitval" id="submitval" class="buttons" value="Save" onClick="return checkdailystockedit();" /><input type="hidden" name="submithidden" id="submithidden" class="buttons" value="Save" />
	  <input type="hidden" value="1" name="prodcnt" id="prodcnt" />
	  <?php } ?>
	  
	  
	  &nbsp;&nbsp;&nbsp;&nbsp;
     <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="button" name="View" value="View" class="buttons" onclick="window.location='DailyStockLoadingview.php'"/></td>
      </tr>
 </table> 
 <?php } ?>
</form>

<div class="msg" align="center" <?php if($_GET['id']!='' && $_GET['del'] =='del'){?> style="display:block;"<?php } else { ?> style="display:none;" <?php }?>>
	<form action="DailyStockLoadingview.php" method="post">
		<input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" name="id" id="id" class="buttonsdel" value="<?php echo $_GET[id]; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='DailyStockLoadingview.php'" />
	</form>
</div>
	
	 <div style="clear:both;"></div>
	 <div class="mcf"></div>
	 <div id="errormsgcus" style="display:none;clear:both;"><h3 align="center" class="myalign"></h3><button id="closebutton_cus">Close</button></div>
<?php require_once("../include/error.php");?>
</div>

<!---- Form End ----->
<div class="mcf"></div>
<div class="mcf"></div>
</div>
<div id="backgroundChatPopup"></div>
<?php require_once('../include/footer.php');?>