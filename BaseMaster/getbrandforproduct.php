<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
//require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
$params=$kdcode;

if($codeval	==	'' || $codeval == 'null') {
	$nextrecval		=	"WHERE product_type != 'POSM' AND Product_code = ''";
	//$target_query		=	"";
} elseif($codeval	!=	'') {
	//pre($kdcode);
	$brandcodestr			=	implode("','",$codeval);
	if(is_array($codeval)) {
		$brandcodeprint		=	$brandcodestr;
	} else {
		$brandcodeprint		=	$codeval;		
	}
	$nextrecval		=	" WHERE brand IN ('".$brandcodeprint."') AND product_type != 'POSM'";
} 

$where		=	"$nextrecval";

if(isset($_REQUEST) && $_REQUEST !='')
{
	$qry="SELECT * FROM `product` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr		=	mysql_query($qry) or die(mysql_error());
$rowcnt_prod		=	mysql_num_rows($results_dsr);
while($row_prod 					=	mysql_fetch_array($results_dsr)) {
	$product_codearr[]				=	$row_prod[Product_code];
}

?>
<table align="center" width="100%">
        <thead>
        <tr>
        <th align="center" style="width:70%">Product</th>
        <th align="center">Target<br/>Units/Month</th>
        <th  align="center">Naira/Unit</th>
        </tr>
        </thead>
        <tbody>
        <?php
			//pre($product_codearr);
			//echo $rowcnt_prod;
		if($rowcnt_prod > 0) {
			for($k=0; $k<$rowcnt_prod; $k++) {			
			?>
			<tr>
			<td width="731" style="width:70%"><input type="hidden" autocomplete="off" name="productname_<?php echo $k; ?>" id="productname_<?php echo $k; ?>" value="<?php echo getdbval($product_codearr[$k],'id','Product_code','product'); ?>" /><span><?php echo getdbval($product_codearr[$k],'Product_description1','Product_code','product'); //PARAMETERS ARE TABLE QUERY COLUMN VALUE, TABLE RESULT COLUMN, TABLE QUERY COLUMN NAME, TABLE NAME ?></span></td>

			<td align="center"><input type="text" size="5" maxlength="10" <?php if($rowcnt_tgtsel > 0) { if($targetFlag == "1") { echo "disabled"; }  } ?> autocomplete="off" name="tgt_units_<?php echo $k; ?>" id="tgt_units_<?php echo $k; ?>" onBlur="qtyformatreturntoid(this.value,'tgt_units_<?php echo $k; ?>');" value="<?php if($rowcnt_tgtsel > 0) { if($targetFlag == "0") { echo $tgtsel[$k]["target_units"]; }  } ?>" /></td>	
			<td align="center"><input type="text" size="5" maxlength="10" autocomplete="off" name="tgt_naira_<?php echo $k; ?>" id="tgt_naira_<?php echo $k; ?>" onBlur="curformatreturntoid(this.value,'tgt_naira_<?php echo $k; ?>');" value="<?php echo $tgtsel[$k]["target_naira"]; ?>" /></td>
			</tr>
			<?php 
			} 	//for loop ?>
			<input type="hidden" name="overall_rowcnt" id="overall_rowcnt" value="<?php echo $rowcnt_prod; ?>" />
			<?php if($rowcnt_tgtsel > 0) { ?>
			<input type="hidden" name="tgt_edit" id="tgt_edit" value="<?php 
			if($rowcnt_tgtsel > 0) {
				echo "edit";
			} ?>" />
		<?php  }  else { ?>
				<input type="hidden" name="tgt_edit" id="tgt_edit" value="0" /> 		
			<?php } //edit if loop
		
			} // if loop 
			 else { ?>
				<tr>
					<td width="731" align="center" colspan="3">NO RECORDS FOUND
						<input type="hidden" name="overall_rowcnt" id="overall_rowcnt" value="<?php echo $rowcnt_prod; ?>" />
							<?php if($rowcnt_tgtsel > 0) { ?>
							<input type="hidden" name="tgt_edit" id="tgt_edit" value="<?php 
							if($rowcnt_tgtsel > 0) {
								echo "edit";
							} ?>" />
							<?php  }  else { ?>
							 <input type="hidden" name="tgt_edit" id="tgt_edit" value="0" /> 		
								<?php } //edit if loop
						?>
					</td>
				</tr>
			 <?php } ?>
        </tbody>
       
</table>
<?php exit(0);?>