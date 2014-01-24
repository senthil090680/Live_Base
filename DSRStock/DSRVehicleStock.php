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
$id=$_REQUEST['id'];
if($_REQUEST['id']!=''){
	if($_POST['submit']=='Save'){
		$KD_Code=getKDCode();
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
		header("location:DailyStockLoading.php?no=2");
	}
}
elseif($_POST['submit']=='Save'){

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

//echo "goodfd";
exit;*/
		
	$KD_Code=getKDCode();
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


	$Product_name	=	$row_pname['Product_description1'];
	$Loaded_Qty		=	$row['Loaded_Qty'];
	$Confirmed_Qty	=	$row['Confirmed_Qty'];
	$focus_Flag		=	$row['focus_Flag'];
	$scheme_Flag	=	$row['scheme_Flag'];
	$ProductType	=	$row['ProductType'];
	$UOM			=	$row['UOM'];
}
?>
<style type"text/css">
#backgroundPopup {
	display:none;
	position:fixed;
	_position:absolute; /* hack for internet explorer 6*/
	height:100%;
	width:100%;
	top:0;
	left:0;
	border:1px solid #cecece;
	z-index:1;
}

.closepboxa {
	float:right;
	margin:-6px;
}
.condaily_veh{
	width:100%;
	text-align:left;
	height:250px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}
.condaily_veh th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}

.condaily_veh td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	padding-left:10px;
	color:#000;
	font-size:14px;
}

.condaily_veh tbody tr:hover td {
	background: #c1c1c1;
}
.headingsgrdaily_veh{
	background:#a09e9e;
	width:100;
	margin-left:auto;
	margin-right:auto;
	height:25px;
	padding-top:5px;
	border-radius:6px;
	font-weight:bold;
	font-size:14px;
}

#headingsgrdaily_veh{
	background:#fff;
	width:100%;
	margin-left:auto;
	margin-right:auto;
	height:330px;
}
</style>
<link href="../css/popup.css" rel="stylesheet" type="text/css" />
<div id="mainareadaily">
<div class="mcf"></div>
<div align="center" class="headingsgrdaily_veh">SR VEHICLE STOCK</div>
<div id="mytableformdaily_veh" align="center">
<!-- <form action="" method="post" id="dailystockvalidation"> -->

 <fieldset align="left" class="alignment">
  <legend><strong>Vehicle Stock</strong></legend>

<table width="100%">
 <tr>
  <td>
  <table>
    <tr height="25">
    <td width="120">Date*</td>
    <td><input type="text" name="Date" id="Date" onChange="loadDSRVehicleDate();" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/>
	</td>
    </tr>
       </table>
     </fieldset>
       </td>
     </tr> 
</table>

<table width="50%" align="left">
 <tr>
  <td>
  <table>    
    <tr height="30">
     <td width="120">SR Name*</td>
     <td><select name="DSRName" id="DSRName" onChange="loadDSRVehicle(this.value);">
	<option value="" >--Select--</option>
	<?php $sel_supp		=	"SELECT DSRName,DSR_Code from dsr GROUP BY DSRName";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[DSR_Code]; ?>" <?php if($DSR_Code == $row_supp[DSR_Code]) { echo "selected"; } ?> ><?php echo $row_supp[DSRName]; ?></option>
	<?php } ?>
	</select>&nbsp;</td>
	</tr>
		<tr height="45">
     <td width="120">Vehicle Name</td>
     <td><input type="text" name="vehicleName" id="vehicleName" readonly size="30" value="<?php echo $vehname; ?>" maxlength="20" autocomplete='off'/>&nbsp;</td>
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
    <td width="120">SR Code</td>
    <td><input type="text" name="DSR_Code" id="DSR_Code" readonly size="30" value="<?php echo $DSR_Code; ?>" maxlength="20" autocomplete='off'/>&nbsp;
	</td>
    </tr>

	
	<tr height="45">
    <td width="120">Device Name</td>
    <td><input type="text" name="deviceName" id="deviceName" readonly size="30" value="<?php echo $devname; ?>" maxlength="20" autocomplete='off'/>&nbsp;
	</td>
    </tr>
       </table>
       </td>
     </tr>
</table>
</fieldset>

<!----------------------------------------------- Right Table End -------------------------------------->

<div id="nodatetab">
<table width="100%" align="left" id="productsadd" <?php if(!isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none;" <?php } if(isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none;" <?php } ?> >
 <tr>
  <td>
  <div class="condaily">
  <table>
  <thead><tr><th align='center'>Serial Number</th><th class='rounded' align='center'>Product Code</th><th align='center'>Product Name</th><th align='center'>UOM</th><th align='center'>Loaded Quantity</th><th align='center'>Confirmed Quantity</th><th align='center'>Focus Flag</th><th align='center'>Scheme Flag</th><th align='center'>Product Type</th></tr></thead>  
  <tbody id="productsadded">
	<?php $t = 1; if(isset($_GET[id]) && $_GET[id] != '') { ?> 
		<tr><td align='center'><input type='hidden' value='<?php echo $t; ?>' name='sno_<?php echo $t; ?>' /><?php echo $t; ?></td><td align='center'><input type='hidden' value='<?php echo $Product_code; ?>' name='product_code_<?php echo $t; ?>' /><?php echo $Product_code; ?></td><td align='center'><input type='hidden' value='<?php echo $Product_name; ?>' name='product_codename_<?php echo $t; ?>' /><?php echo $Product_name; ?></td><td align='center'><input type='hidden' value='<?php echo $UOM; ?>' name='product_UOM_<?php echo $t; ?>' /><?php echo $UOM; ?></td><td align='center'><input type='text' value='<?php echo $Loaded_Qty; ?>' name='Loaded_Qty_<?php echo $t; ?>' /></td><td align='center'><input type='text' readonly value='<?php echo $Confirmed_Qty; ?>' name='Confirmed_Qty_<?php echo $t; ?>' /></td><td align='center'><input type='hidden' value='<?php echo $focus_Flag; ?>' name='focus_Flag_<?php echo $t; ?>' /><?php echo $focus_Flag; ?> </td><td align='center'><input type='hidden' readonly value='<?php echo $scheme_Flag; ?>' name='scheme_Flag_<?php echo $t; ?>' /><?php echo $scheme_Flag; ?></td><td align='center'><input type='hidden' readonly value='<?php echo $ProductType; ?>' name='ProductType_<?php echo $t; ?>' /><?php echo $ProductType; ?></td></tr>
	<?php } ?>
  </tbody>
   </table>
     </div>
   </td>
 </tr>
</table>
    
<!-- </form> -->


<table width="50%" style="clear:both">
	<tr align="center" height="50px;">
		<td><span ><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span></td>
	</tr>
 </table>

</div>

<div class="clearfix"></div>

<div id="errormsgveh" style="display:none;"><h3 align="center" class="myalignveh"></h3><button id="closebutton_blue">Close</button></div>

<div class="clearfix"></div>

</div>
<!---- Form End ----->
<div class="clearfix"></div>
<?php require_once("../include/error.php");?>
</div>
<div id="backgroundPopup"></div>
<?php require_once('../include/footer.php');?>