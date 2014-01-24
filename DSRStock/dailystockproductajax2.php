<?php
session_start();
ob_start();
require_once('../include/config.php');
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
ini_set("display_errors",true);
error_reporting(E_ALL && ~ E_NOTICE);
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_REQUEST);

?>
<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeProductShow();"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor">

<table width="100%" align="left" >
 <tr>
  <td style="color:#000; font-size:14px;padding-left:820px;">
	<b>Total Value :</b> <input type='hidden' value='0' name='finaltotalval' id='finaltotalval' /><span id="totval">Nil</span>
   </td>
 </tr>

<tr>
  <td>
  <div class="condaily_prod">
   <table>
	<thead>
		<tr>
			<!-- <th align='center' width="4%"><a href="javascript:void(0);" onClick="selectall();" >All</a> / <a href="javascript:void(0);" onClick="selectnone();" >None</a></th> -->
			<th class='rounded' width="7%" align='center'>Product Code</th>
			<th align='center' width="41%">Product Name</th>
			<th align='center' width="4%">Loaded Qty. (Cartons)</th>
			<!-- <th align='center' width="4%">Suggested Order Provision</th> -->
			<th align='center' width="4%" >Loaded Qty. (PCS)</th>
			<th align='center' width="4%">Confirmed Quantity</th>
			<th align='center' width="4%">Price Per Carton</th>
			<th align='center' width="4%">Value</th>
			<th align='center' nowrap="nowrap" width="4%">Focus Flag</th>
			<th align='center' nowrap="nowrap" width="4%">Scheme Flag</th>
			<th align='center' nowrap="nowrap" width="4%">Product Type</th>
		</tr>
	</thead>  
  <tbody id="productsadded">
	<?php
	$todayDate						=	date('Y-m-d');
	$sel_getcyclestartdate			=	"SELECT Date,flag_status,end_flag_status from cycle_assignment WHERE flag_status = '1' AND end_flag_status = '0' ORDER BY id DESC";
	$res_getcyclestartdate			=	mysql_query($sel_getcyclestartdate) or die(mysql_error());
	$rowcnt_getcyclestartdate		=	mysql_num_rows($res_getcyclestartdate);

	if($rowcnt_getcyclestartdate > 0) {
		$row_getcyclestartdate		=	mysql_fetch_array($res_getcyclestartdate);
		$cyclestartdate				=	substr($row_getcyclestartdate['Date'],0,10);
	}

	//echo $cyclestartdate."-".$todayDate."<br>";
	//exit;

	//TODAY LOADING PRICE, VALUE & QUANTITY STARTS HERE
	$sel_supp		=	"SELECT Product_code,Product_description from opening_stock_update WHERE (TransactionQty != '' AND BalanceQty != '' AND TransactionNo != '' AND TransactionType !='') GROUP BY Product_code";
	$res_supp						=	mysql_query($sel_supp) or die(mysql_error());
	$rowcnt_supp					=	mysql_num_rows($res_supp);
	$t								=	1;
	if($rowcnt_supp > 0) {
	$w								=	0;					
	while($row_supp					=	mysql_fetch_array($res_supp)) {	
		$sel_isscheck				=	"SELECT max(id) AS MAX_ID from opening_stock_update WHERE Product_code = '$row_supp[Product_code]'";
		$res_isscheck				=	mysql_query($sel_isscheck) or die(mysql_error());
		$rowcnt_isscheck			=	mysql_num_rows($res_isscheck);
		$row_isscheck				=	mysql_fetch_array($res_isscheck);
		$max_id						=	$row_isscheck[MAX_ID];
		$sel_BalanceQty				=	"SELECT BalanceQty FROM opening_stock_update WHERE id = '$max_id'";
		$res_BalanceQty				=	mysql_query($sel_BalanceQty) or die(mysql_error());
		$row_BalanceQty				=	mysql_fetch_array($res_BalanceQty);
		$BalanceQty					=	$row_BalanceQty[BalanceQty];

		if($rowcnt_isscheck > 0) {
			$w++;
			$sel_prodtype			=	"SELECT product_type,UOM1,Focus from product WHERE Product_code = '$row_supp[Product_code]'";
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
			} else {
				$sel_prodtype			=	"SELECT id from customertype_product WHERE Product_code = '$row_supp[Product_code]'";
				$res_prodtype			=	mysql_query($sel_prodtype) or die(mysql_error());
				$nor_prodtype			=	mysql_num_rows($res_prodtype);
				if($nor_prodtype > 0) {
					$row_prodtype		=	mysql_fetch_array($res_prodtype);
					$prodtype			=	"POSM";
					$UOM_dis			=	"PCS";
					$focusval			=	0;
					$scheme_status		=	"Yes";
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
			<!-- <option value="<?php echo $row_supp[Product_code]."~".$UOM_dis."~".$prodtype."~".$focusval."~".$scheme_status; ?>" <?php if($Product_code == $row_supp[Product_code]) { echo "selected"; } ?> ><?php echo $row_supp[Product_description]; ?></option> -->		
	<?php } // if loop 
	
		if($_GET[id] == '' && !isset($_GET[id])) {

			$price_val	=	getdbval($row_supp[Product_code],'Price','Product_code','price_master');

			//echo $prodtype."deere<br>";
			//echo $price_val."deere<br>";
			if($prodtype	==	'POSM') {
				//$uom_coversion		=	getdbval($row_supp[Product_code],'UOM_Conversion','Product_code','customertype_product');
				if($uom_coversion == '') {
					$uom_coversion	=	1;
				} 
			} else {
				$uom_coversion		=	getdbval($row_supp[Product_code],'UOM_Conversion','Product_code','product');
				if($uom_coversion == '') {
					$uom_coversion	=	1;
				}
			}
			//echo $uom_coversion."deere<br>";
			$perCartonPrice = ($uom_coversion) * ($price_val); 
		?> 
		<tr>
			<!-- <td align='center' width="4%"><input type='hidden' value='<?php echo $t; ?>' name='sno_<?php echo $t; ?>' id='sno_<?php echo $t; ?>' /><input type="checkbox" name='cbox_<?php echo $t; ?>' id='cbox_<?php echo $t; ?>' value="<?php echo "cbox_".$t; ?>" /> </td> -->

			<td align='center' width="17%"><input type='hidden' value='<?php echo $row_supp[Product_code]; ?>' name='product_code_<?php echo $t; ?>' id='product_code_<?php echo $t; ?>' /><?php echo $row_supp[Product_code]; ?>
			<input type="hidden" value="<?php echo $row_BalanceQty[BalanceQty]; ?>" name="actual_qty_<?php echo $t; ?>" id="actual_qty_<?php echo $t; ?>" />			
			
			</td>

			<td align='left' width="41%"><input type='hidden' value='<?php echo $row_supp[Product_description]; ?>' name='product_codename_<?php echo $t; ?>' /><?php echo $row_supp[Product_description]; ?></td>

			<td align='center' width="14%">
			<input type='hidden' value='<?php echo $UOM_dis; ?>' name='product_UOM_<?php echo $t; ?>' />

			<input type='text' value='' style="width:50px;" onBlur="changeToPcs('<?php echo $t; ?>','<?php echo $perCartonPrice; ?>',this.value,'<?php echo $uom_coversion; ?>');" name='UOM_cartons_<?php echo $t; ?>' id='UOM_cartons_<?php echo $t; ?>' />
			
			<!-- <select name="uomval_<?php echo $t; ?>" id="uomval_<?php echo $t; ?>">
				<option value="PCS" >PCS</option>
				<option value="CARTONS" >Cartons</option>
			</select> -->
			</td>

			<!-- <td align='center'><span id="suggestedqtyid_<?php echo $t; ?>"><?php echo $Suggested_Qty; ?></span></td> -->

			<td align='center' width="14%"><input type='text' style="width:50px;" onBlur="changeToCartons('<?php echo $t; ?>','<?php echo $perCartonPrice; ?>',this.value,'<?php echo $uom_coversion; ?>');" value='<?php echo $Loaded_Qty; ?>' autocomplete='off' name='Loaded_Qty_<?php echo $t; ?>' id='Loaded_Qty_<?php echo $t; ?>' /></td>

			<td align='right' width="14%">			
			<!-- <input type='hidden' readonly value='<?php echo $Confirmed_Qty; ?>' name='Confirmed_Qty_<?php echo $t; ?>' /> -->
			</td>

			<td align='right' width="14%"><input type='hidden' value='<?php echo $perCartonPrice; ?>' name='price_carton_<?php echo $t; ?>' id='price_carton_<?php echo $t; ?>' /><?php echo $perCartonPrice; ?></td>
		
			<td align='right' width="14%"><input type='hidden' value='<?php $total_price_val	=	($Loaded_Qty * $price_val); echo $total_price_val; ?>' name='total_price_value_<?php echo $t; ?>' id='total_price_value_<?php echo $t; ?>' /><span id="value_<?php echo $t; ?>"><?php echo $total_price_val; ?></span></td>

			<td align='center' width="4%"><input type='hidden' value='<?php echo ucwords($focusval); ?>' name='focus_Flag_<?php echo $t; ?>' /><?php if($focusval == '1') { echo "Yes"; } elseif($focusval == '0') { echo "No"; }  ?> </td>

			<td align='center' width="4%"><input type='hidden' readonly value='<?php echo ucwords($scheme_status); ?>' name='scheme_Flag_<?php echo $t; ?>' /><?php echo ucwords($scheme_status); ?></td>

			<td align='center' width="4%"><input type='hidden' readonly value='<?php echo ucwords($prodtype); ?>' name='ProductType_<?php echo $t; ?>' /><?php echo ucwords($prodtype); ?>
			
			
			</td>
		</tr>
<?php } 
		$prodtype		=	'';
		$focusval		=	'';
		$scheme_status	=	'';
		$uom_coversion	=	'';
		$price_val		=	'';
		
		$t++; } //while loop		
	} // IF LOOP FOR NO OF PRODUCTS

	//TODAY LOADING PRICE, VALUE & QUANTITY ENDS HERE



	//PREVIOUS DAY LOADING PRICE, VALUE, QUANTITY STARTS HERE	
	
	if($cyclestartdate == $todayDate) {
		//exit;
	} else {
		$yesterdayDate				=	date('Y-m-d',strtotime("yesterday"));
		$sel_yesstock				=	"SELECT 
		Date,DSR_Code,vehicle_code,Product_code,UOM,Load_Sequence_No,Loaded_Qty,Confirmed_Qty,focus_Flag,scheme_Flag,ProductType from dailystockloading WHERE Date LIKE '$yesterdayDate%' AND DSR_Code = '$DSR_Code' ORDER BY id DESC";
		$res_yesstock				=	mysql_query($sel_yesstock) or die(mysql_error());
		$rowcnt_yesstock			=	mysql_num_rows($res_yesstock);
		$t							=	1;
		if($rowcnt_yesstock > 0) { 
			//$w							=	0;					
			while($row_yesstock				=	mysql_fetch_array($res_yesstock)) {	 
				$PrevDate					=	$row_yesstock['Date'];
				$PrevDSR_Code				=	$row_yesstock['DSR_Code'];
				$Prevvehicle_code			=	$row_yesstock['vehicle_code'];
				$PrevProduct_code			=	$row_yesstock['Product_code'];
				$PrevProductType			=	$row_yesstock['ProductType'];
				$PrevLoaded_Qty				=	$row_yesstock['Loaded_Qty'];

				if($PrevProductType	==	'POSM') {
					//$uom_coversion		=	getdbval($row_supp[Product_code],'UOM_Conversion','Product_code','customertype_product');
					if($uom_coversion == '') {
						$uom_coversion	=	1;						
					}
					$PrevUOM				=	round(($PrevLoaded_Qty/$uom_coversion),2);
				} else {
					$uom_coversion		=	getdbval($PrevProduct_code,'UOM_Conversion','Product_code','product');
					if($uom_coversion == '') {
						$uom_coversion	=	1;
					}
					$PrevUOM				=	round(($PrevLoaded_Qty/$uom_coversion),2);
				}
			
				$PrevLoad_Sequence_No		=	$row_yesstock['Load_Sequence_No'];
				
				$PrevConfirmed_Qty			=	$row_yesstock['Confirmed_Qty'];
				$Prevfocus_Flag				=	$row_yesstock['focus_Flag'];
				$Prevscheme_Flag			=	$row_yesstock['scheme_Flag'];
				?>
				<tr class="bgclass">
						<td align='center' width="17%"><?php echo $PrevProduct_code; ?></td>
						<td align='left' width="41%"><?php 
						$prod_name	=	getdbval($PrevProduct_code,'Product_description1','Product_code','product');
						if($prod_name	== '') {
							$prod_name	=	getdbval($PrevProduct_code,'Product_description1','Product_code','customertype_product');
						}
						echo $prod_name;
						?></td>
						<td align='center' width="14%"><?php echo $PrevUOM; ?></td>
						<!-- <td align='center'><?php echo ''; ?></td> -->
						<td align='center' width="4%"><?php echo $PrevLoaded_Qty; ?></td>
						<td align='right' width="4%"><?php echo $PrevConfirmed_Qty; ?></td>
						<td align='right' width="4%"><?php $Prevprice_val = getdbval($PrevProduct_code,'Price','Product_code','price_master'); 
						echo $Prevprice_val; ?></td>
						<td align='right' width="4%"><?php echo ($PrevLoaded_Qty) * ($Prevprice_val); ?></td>
						<td align='center' width="4%"><?php if($Prevfocus_Flag == '1') { echo "Yes"; } elseif($Prevfocus_Flag == '0') { echo "No"; } ?></td>
						<td align='center' width="4%"><?php echo $Prevscheme_Flag; ?></td>
						<td align='center' width="4%"><?php echo $PrevProductType; ?></td>
				</tr>
		<?php }
		}
	}
	
	//PREVIOUS DAY LOADING PRICE, VALUE, QUANTITY ENDS HERE


			if($w == 0) { ?>		
				<tr>
				  <td colspan="10" align="center"><strong>No Stock Quantity to Load</strong>
			<?php } if($_GET[id] == '' && !isset($_GET[id])) { ?>
			<input type="hidden" value="<?php echo $rowcnt_supp; ?>" name="prodcnt" id="prodcnt" />
			<?php } elseif(isset($_GET[id]) && $_GET[id] != '') { ?>
			<input type="hidden" value="<?php if(isset($_GET[id]) && $_GET[id] != '') { ?> 1 <?php } ?>" name="prodcnt" id="prodcnt" />
				  </td>
				</tr>
			<?php } ?>
	 </tbody>
   </table>   
     </div>
   </td>
 </tr>
</table>
	
</p>
<p align="center" style="clear:both;" height="50px;">
<input type="button" name="submitval" id="submitval" class="buttons" value="Save" onClick="return checkdailystock();" /><input type="hidden" name="submithidden" id="submithidden" class="buttons" value="Save" />

<!-- <input type="button" class="buttons" onClick="checkprodconfirm();" value="Save" /> -->

&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="cancel" value="Cancel" class="buttons" onclick="javascript:return closeProductShow();"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="buttons" onClick="printprodconfirm();" value="View" />
	 </p>
	 <div style="clear:both;"></div>
	 <div class="mcf"></div>
	 <div style="clear:both;"></div>
	 <div id="errormsgpopupprod" style="display:none;clear:both;"><h3 align="center" class="myalignprod"></h3><button id="closebutton" onClick="return nothingdo();" >Close</button></div>