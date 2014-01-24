<?php
session_start();
ob_start();
require_once('../include/config.php');
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
error_reporting(E_ALL && ~ E_NOTICE);
//pre($_REQUEST);
//exit;
EXTRACT($_REQUEST);
$KD_Code=getKDCode();
//pre($_REQUEST);
//exit;
//$DSR_Code=getDSRid($DSR_Code);

?>

<title>DAILY STOCK LOADING</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>


<style type="text/css">
#containerdailysto {
	padding:0px;
	width:100%;
	margin-left:auto;
	margin-right:auto;
}
.conitems {
	width:100%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	/* border-radius:10px; */
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

<table border="1" style="border-collapse:collapse">
<thead>
<tr>
	<th colspan="11" class='rounded' width="7%" align='center'>DAILY STOCK LOADING CONFIRMATION </th>
</tr>

<tr>
	<th class='rounded' width="7%" align='left' style="padding-left:24px;" nowrap="nowrap">DSR Name : <?php echo upperstate(getdbval($DSR_Code,'DSRName','DSR_Code','dsr')); ?> </th>
	<th class='rounded' width="7%" align='left' nowrap="nowrap">Vehicle Name : <?php echo upperstate(getdbval($vehicle_code,'vehicle_desc','vehicle_code','vehicle_master')); ?></th>
	<th class='rounded' width="4%" align='left' nowrap="nowrap">Load Sequence No. : <?php echo $loadseqno; ?></th>
	<th class='rounded' width="4%" align='left' nowrap="nowrap">Date : <?php echo $Date; ?></th>
	<th class='rounded' width="4%" align='left' nowrap="nowrap">Total Value : <?php echo number_format($finaltotalval,2); ?></th>
	<th class='rounded' width="4%" align='center' nowrap="nowrap" colspan="6" ></th>
</tr>
</thead>
</table>
<table width="100%" style="border-collapse:collapse" border="1">
<thead>
<tr>
	<?php //echo $sortorderby;
	if($sortorder == 'ASC') {
		$sortorderby = 'DESC';
	} elseif($sortorder == 'DESC') {
		$sortorderby = 'ASC';
	} else {
		$sortorderby = 'DESC';
	}
	$paramsval	=	$Product_name."&".$sortorderby."&Product_description1"; ?>

	<th width="7%" align='center'>Product Code</th>
	<th align='center' width="31%">Product Name</th>
	<th align='center' width="4%">Loaded Qty. (Cartons)</th>
	<th align='center' nowrap="nowrap" width="4%">Loaded Qty. (PCS)</th>
	<th align='center' width="4%">Confirmed Quantity</th>
	<th align='center' width="4%">Price Per Carton</th>
	<th align='center' width="4%">Value</th>
	<th align='center' width="4%">Focus Flag</th>
	<th align='center' nowrap="nowrap" width="4%">Scheme Flag</th>
	<th align='center' nowrap="nowrap" width="4%">Product Type</th>
</tr>
</thead>
<tbody>
<?php
$ins_val	=	'';
$w			=	0;
for($k=1; $k <= $prodcnt; $k++) {		
	//if($_POST["cbox_".$k] != '' && isset($_POST['cbox_'.$k])) {
	if($_POST["Loaded_Qty_".$k] != '' && $_POST["Loaded_Qty_".$k] != '0' && isset($_POST["Loaded_Qty_".$k])) {
		$sno				=	$_POST["sno_".$k];
		$product_code		=	$_POST["product_code_".$k];
		$product_codename	=	$_POST["product_codename_".$k];
		$product_UOM		=	$_POST["product_UOM_".$k];
		$UOM_cartons		=	$_POST["UOM_cartons_".$k];
		$Loaded_Qty			=	$_POST["Loaded_Qty_".$k];
		$price_carton		=	$_POST["price_carton_".$k];
		$total_price_value	=	$_POST["total_price_value_".$k];
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

		?>
		<tr>
		<!-- <td align="center"><?php echo upperstate(getdbval($DSR_Code,'DSRName','DSR_Code','dsr')); ?></td> -->
		<td align="center"><?php echo $product_code; ?></td>
		<td align="left"><?php echo $product_codename; ?></td>
		<!-- <td align="center"><?php echo upperstate(getdbval($vehicle_code,'vehicle_desc','vehicle_code','vehicle_master')); ?></td> -->
		<td align="right"><?php echo number_format($UOM_cartons); ?></td>
		<td align="right"><?php echo number_format($Loaded_Qty); ?></td>
		<td align="center"><?php echo ''; ?></td>
		<td align="right"><?php echo number_format($price_carton,2); ?></td>
		<td align="right"><?php echo number_format($total_price_value,2); ?></td>

		<!-- <td align="center"><?php echo $loadseqno; ?></td> -->
		<!-- <td align="center"><?php echo date('Y-m-d'); ?></td> -->
		<td align="center"><?php if($focus_Flag == '0') { echo "No";  } else if($focus_Flag == '1') { echo "Yes";  }?></th>
		<td align="center"><?php echo $scheme_Flag;?></td>
		<td align="center"><?php echo $ProductType;?></td>
		</tr>
		<?php
		
	} else {
		continue;
	}
}
?>
</tbody>
</table>
 <div class="paginationfile" align="center">
 <table>
 <tr>
 <th class="pagination" scope="col">          
<?php 
if(!empty($num_rows)){
	
} else { 
	echo "&nbsp;"; 
} ?>
</th>
</tr>
</table>
</div>
 <span id="printopen" style="padding-left:580px;padding-top:10px;" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0);?>