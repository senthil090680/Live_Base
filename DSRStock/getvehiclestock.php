<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_REQUEST);
$params=$DateVal."&".$DSR_Code."&".$sortorder."&".$ordercol;
if(isset($_REQUEST[DateVal]) && $_REQUEST[DateVal] !='') {
	$nextrecval		=	"WHERE VS.Date LIKE '$DateVal%' AND DSR_Code = '$DSR_Code' GROUP BY VS.Product_Code";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_REQUEST) && $_REQUEST !='')
{
	$qry="SELECT * FROM `vehicle_stock` VS LEFT JOIN product AS PROD ON VS.Product_code = PROD.Product_code $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr=mysql_query($qry);
$num_rows= mysql_num_rows($results_dsr);
//exit;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 7;   // Records Per Page

$Page = $strPage;
if(!$strPage)
{
$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($num_rows<=$Per_Page)
{
$Num_Pages =1;
}
else if(($num_rows % $Per_Page)==0)
{
$Num_Pages =($num_rows/$Per_Page) ;
}
else
{
$Num_Pages =($num_rows/$Per_Page)+1;
$Num_Pages = (int)$Num_Pages;
}
if($sortorder == "")
{
	$orderby	=	"ORDER BY VS.id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.="  $orderby LIMIT $Page_Start , $Per_Page";
//echo $qry;
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

$findvehdevqry					=	$qry;
$results_findvehdev				=	mysql_query($findvehdevqry);
$rowcnt_findvehdev				=	mysql_num_rows($results_findvehdev);
if($rowcnt_findvehdev > 0){
	$row_findvehdev				=	mysql_fetch_array($results_findvehdev);
	$vehicle_codeval			=	$row_findvehdev['Vehicle_Code'];
	$Device_Codeval				=	$row_findvehdev['Device_Code'];
	$device_name				=	getdeviceval($Device_Codeval,'device_description','device_code');
	$vehicle_name				=	getvehicleval($vehicle_codeval,'vehicle_desc','vehicle_code');
}
echo $DSR_Code."~".$vehicle_name."~".$device_name."~";
?>
<table width="100%" align="left" id="productsadd">
 <tr>
  <td>
  <div <?php if($num_rows == 0) { ?> class="condailynorec" <?php } else { ?> class="condaily_veh" <?php } ?> >
  <table>
  <thead>
	<tr>
		<th align='center' width="5%">SL. NO.</th>
		<th class='rounded' align='center' width="5%">Product Code</th>
		<?php //echo $sortorderby;
		if($sortorder == 'ASC') {
			$sortorderby = 'DESC';
		} elseif($sortorder == 'DESC') {
			$sortorderby = 'ASC';
		} else {
			$sortorderby = 'DESC';
		}
		$paramsval	=	$DateVal."&".$DSR_Code."&".$sortorderby."&PROD.Product_description1"; ?>
		<th align='center' width="60%" onClick="pag_vehstockajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');"><span style="cursor:hand;cursor:pointer;">Product Name <img src="../images/sort.png" width="13" height="13" /></span></th>
		<th align='center' width="5%">UOM</th>
		<th align='center' width="5%">Opening Stock</th>
		<th align='center' width="5%">Loaded Quantity</th>
		<th align='center' width="5%">Sold Quantity</th>
		<th align='center' width="5%">Customer Return Quantity</th>
		<th align='center' width="5%">Closing Stock</th>
	</tr>
</thead>  
  <tbody id="productsadded">
	<?php $t = 1; 
	if($num_rows >0) {
		$Closing_Stock				=	'';
		$Cal_Closing_Stock			=	0;
		$opn_Stock					=	0;
		
			while($row				=	mysql_fetch_array($results_dsr)){
			$KD_Code				=	$row['KD_Code'];
			$vehicle_code			=	$row['Vehicle_Code'];
			$Device_Code			=	$row['Device_Code'];
			$Date					=	$row['Date'];
			$DSR_Code				=	$row['DSR_Code'];
			$Product_code			=	$row['Product_Code'];
			/*$sel_pname				=	"SELECT Product_description1 from product WHERE Product_code = '$Product_code'";
			$res_pname				=	mysql_query($sel_pname) or die(mysql_error());	
			$row_pname				=	mysql_fetch_array($res_pname);

			$Product_name			=	$row_pname['Product_description1'];*/

			$Product_name			=	$row['Product_description1'];
			$Loaded_quantity		=	$row['Loaded_quantity'];
			$Sold_quantity			=	$row['Sold_quantity'];
			$Return_quantity		=	$row['Return_quantity'];
			$Stock_quantity			=	$row['Stock_quantity'];
			$Cycle_Start_Flag		=	$row['Cycle_Start_Flag'];
			$UOM					=	$row['UOM'];

			if($Cycle_Start_Flag == 0) {
				$DSR_Code_val					=	getdsrval($DSR_Code,'id','DSR_Code');
				$query_cycstartdate				=	"SELECT cycle_start_date from cycle_flag WHERE (cycle_start_flag = '1' AND cycle_end_flag = '0') AND dsr_id = '$DSR_Code_val'";
				$res_cycstartdate				=	mysql_query($query_cycstartdate) or die(mysql_error());	
				$row_cycstartdate				=	mysql_fetch_array($res_cycstartdate);
				$cycstartdatearr				=	explode(" ",$row_cycstartdate[cycle_start_date]);
				$cycstartdate					=	$cycstartdatearr[0];

				$previousdate					=	date("Y-m-d", strtotime($DateVal . "- 1 day"));
				//$previousdate					=	date("Y-m-d", strtotime($DateVal . "yesterday"));

				//$previousdate					=	date('Y-m-d', strtotime($DateVal . " - 1 day"));

				$sel_openingstk					=	"SELECT * FROM vehicle_stock WHERE (Date BETWEEN '$cycstartdate' AND '$previousdate') AND DSR_Code = '$DSR_Code' AND Product_Code = '$Product_code' ORDER BY id ASC";
				$res_openingstk					=	mysql_query($sel_openingstk) or die(mysql_error());
				$rowcnt_openingstk				=	mysql_num_rows($res_openingstk);
				if($rowcnt_openingstk > 0){
					while($row_openingstk		=	mysql_fetch_array($res_openingstk)){
						$Loaded_qty				=	$row_openingstk['Loaded_quantity'];
						$Sold_qty				=	$row_openingstk['Sold_quantity'];
						$Return_qty				=	$row_openingstk['Return_quantity'];
						$Stock_qty				=	$row_openingstk['Stock_quantity'];

						if($opn_Stock == '') {
							$opn_Stock	=	0;
						}
						$Cal_Closing_Stock		=	intval($opn_Stock + $Loaded_qty) - intval($Sold_qty) +  intval($Return_qty);
						$opn_Stock				=	$Cal_Closing_Stock;
						//echo $Cal_Closing_Stock."<br/>";
						//echo $opn_Stock."<br/>";	
						$Cal_Closing_Stock		=	0;
					}
				}
			}
			
			$Closing_Stock					=	$opn_Stock + $Loaded_quantity - $Sold_quantity +  $Return_quantity;
			?> 
			<tr>
				<td align='center'><input type='hidden' value='<?php echo $t; ?>' name='sno_<?php echo $t; ?>' /><?php echo $t; ?></td>
				<td align='center'><input type='hidden' value='<?php echo $Product_code; ?>' name='product_code_<?php echo $t; ?>' /><?php echo $Product_code; ?></td>
				<td align='left'><input type='hidden' value='<?php echo $Product_name; ?>' name='product_codename_<?php echo $t; ?>' /><?php echo $Product_name; ?></td>
				<td align='center'><input type='hidden' value='<?php echo $UOM; ?>' name='product_UOM_<?php echo $t; ?>' /><?php echo $UOM; ?></td>
				<td align='center'><input type='hidden' value='<?php echo $opn_Stock; ?>' name='Opening_Stock_<?php echo $t; ?>' /><?php echo $opn_Stock; ?></td>
				<td align='center'><a href="javascript:void(0);" onclick="getLoadedQuantity('<?php echo $Loaded_quantity; ?>','<?php echo $t; ?>','<?php echo $DateVal; ?>','<?php echo $DSR_Code; ?>','<?php echo $Product_code; ?>')"><input type='hidden' readonly value='<?php echo $Loaded_quantity; ?>' name='Loaded_Qty_<?php echo $t; ?>' /><?php echo $Loaded_quantity; ?></a></td>
				<td align='center'><a href="javascript:void(0);" onclick="getSoldQuantity('<?php echo $Loaded_quantity; ?>','<?php echo $t; ?>','<?php echo $DateVal; ?>','<?php echo $DSR_Code; ?>','<?php echo $Product_code; ?>')" ><input type='hidden' value='<?php echo $Sold_quantity; ?>' name='salequantity_<?php echo $t; ?>' /><?php echo $Sold_quantity; ?> </a></td>
				<td align='center'><a href="javascript:void(0);" onclick="getReturnedQuantity('<?php echo $Loaded_quantity; ?>','<?php echo $t; ?>','<?php echo $DateVal; ?>','<?php echo $DSR_Code; ?>','<?php echo $Product_code; ?>')" ><input type='hidden' readonly value='<?php echo $Return_quantity; ?>' name='returnqty_<?php echo $t; ?>' /><?php echo $Return_quantity; ?></a></td>
					<td align='center'><input type='hidden' readonly value='<?php echo $Closing_Stock; ?>' name='Closing_Stock_<?php echo $t; ?>' /><?php echo $Closing_Stock; ?></td>
			</tr>
	   <?php 
				$cycstartdate					=	'';
				$previousdate					=	'';
				$opn_Stock						=	0;
				$t++;
			} //while loop
	
	} else { ?>
		<tr>
			<td align='center' colspan="9">No Records Found.</td>		
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
			<td style="display:none;" >Add Line2</td>
			<td style="display:none;" >LGA</td>
			<td style="display:none;" >City</th>
			<td style="display:none;" >Contact Person</td>
			<td style="display:none;" >Contact Number</td>
			<td style="display:none;" >Contact Number</td>
			<td style="display:none;" >Contact Number</td>
		</tr>
		<?php } ?>
  </tbody>
   </table>
     </div>
   </td>
 </tr>
</table>

<div class="paginationfile" align="center">
 <table>
 <tr>
 <th class="pagination" scope="col">          
<?php 
if(!empty($num_rows)){ 
	rend_vehstockajaxpagination($Num_Pages,$Page,$Prev_Page,$Next_Page,$params);
} else {
	echo "&nbsp;";
}
?>
</th>
</tr>
</table>
</div>
<div id="errormsgcus" style="display:none;"><h3 align="center" class="myalign"></h3><button id="closebutton">Close</button></div>

</div>
<div style="clear:both"></div>
<span style="display:inline;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>">
<span id="printopen" style="padding-left:0px;" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printvehicleajax');"></span><span style="padding-left:20px;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>
</span>
<form id="printvehicleajax" target="_blank" action="printvehicleajax.php" method="post">
	<input type="hidden" name="DateVal" id="DateVal" value="<?php echo $DateVal; ?>" />
	<input type="hidden" name="DSR_Code" id="DSR_Code" value="<?php echo $DSR_Code; ?>" />
	<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
	<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
	<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
</form>
<?php exit(0);?>