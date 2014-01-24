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
if(isset($_GET[codeval]) && $_GET[codeval] !='') {
	$codevalstr		=	implode("','",$codeval);

	if($smval == 'asm_sp') {
		if($codevalstr != '') {			
			$nextrecvalasm		=	"DSR_Code IN ('".$codevalstr."')";
			$wherefordsrasm		=	"WHERE";
		} else {
			$nextrecvalasm			=	"";
			$wherefordsrasm		=	"";
		}
		if($nextrecvalasm != '') {
			$DSR_Codestr		=	findSR($wherefordsrasm,$nextrecvalasm);
			if($DSR_Codestr != '') {			
				$nextrecval		=	"DSR_Code IN ('".$DSR_Codestr."')";
				$wherefordsr	=	"WHERE";
			} else {
				$nextrecval		=	"";
				$wherefordsr	=	"";
			}
		} else {
			$nextrecval		=	"";
			$wherefordsr	=	"";
		}
	}

	if($smval == 'rsm_sp') {
		if($codevalstr != '') {			
			$nextrecvalrsm		=	"RSM IN ('".$codevalstr."')";
			$wherefordsrrsm		=	"WHERE";
		} else {
			$nextrecvalrsm		=	"";
			$wherefordsrrsm		=	"";
		}
		if($nextrecvalrsm != '') {
			$DSR_Codestr		=	findSR($wherefordsrrsm,$nextrecvalrsm);
			if($DSR_Codestr != '') {			
				$nextrecval		=	"DSR_Code IN ('".$DSR_Codestr."')";
				$wherefordsr	=	"WHERE";
			} else {
				$nextrecval		=	"";
				$wherefordsr	=	"";
			}
		} else {
			$nextrecval		=	"";
			$wherefordsr	=	"";
		}
	}

	if($smval == 'branch') {
		if($codevalstr != '') {
			$nextrecvalbr			=	"WHERE branch_id IN ('".$codevalstr."')";
		} else {
			$nextrecvalbr			=	"";
		}

		if($nextrecvalbr != '') {
			$query_asmnam 							=	"SELECT id,DSR_Code,DSRName FROM rsm_sp $nextrecvalbr";
			$res_asmnam 							=	mysql_query($query_asmnam) or die(mysql_error());
			while($info = mysql_fetch_assoc($res_asmnam)) {
				$rsm[]		=	$info[id];
			}

			$rsmuni								=	array_unique($rsm);
			$rsmstr								=	implode("','",$rsmuni);

			$query_rsmnam 						=	"SELECT id,DSR_Code,DSRName FROM asm_sp WHERE RSM IN ('".$rsmstr."')";
			$res_srnam 							=	mysql_query($query_rsmnam) or die(mysql_error());
			while($info = mysql_fetch_assoc($res_srnam)) {
				$asm_id[]		=	$info[id];
			}
			//debugerr($brand);
			$asmuni							=	array_unique($asm_id);
			//pre($branchuni);
			//debugerr($branduni);
			$asmstr							=	implode("','",$asmuni);
			
			if($asmstr != '') {
				$nextrecval		=	"ASM IN ('".$asmstr."')";
				$wherefordsr	=	"WHERE";
			} else {
				$nextrecval		=	"";
				$wherefordsr	=	"";
			}
		} else {
			$nextrecval		=	"";
			$wherefordsr	=	"";
		}
	}

	if($smval == 'dsr') {
		if(strstr($codevalstr,"',")) {

		} else {
			$codevalstr		=	str_replace(",","','",$codevalstr);
		}
		if($codevalstr != '') {			
			$nextrecval		=	"DSR_Code IN ('".$codevalstr."')";
			$wherefordsr	=	"WHERE";
		} else {
			$nextrecval		=	"";
			$wherefordsr	=	"";
		}
	}
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

//echo $nextrecval;

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `dsr` $wherefordsr $nextrecval";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr	=	mysql_query($qry) or die(mysql_error());
$num_rows		=	mysql_num_rows($results_dsr);

?>
<table align="center" width="100%">
<thead>
<tr>
<th align="center" style="width:30%">SR</th>
<th align="left" nowrap="nowrap">Coverage %
<br/><br/> <!-- Tgt Type : &nbsp;&nbsp; -->

<!-- <input type="radio" name="tgtTypeCov" id="tgtTypeCov" onClick="checkCovTgt('<?php echo $num_rows; ?>','cov_per_','visitcov','noton')" <?php if($tgtTypeCov == "0") echo "checked"; ?> value="0" /> T <sup>+</sup>&nbsp;&nbsp; 
<input type="radio" name="tgtTypeCov" id="tgtTypeCov" onClick="checkCovTgt('<?php echo $num_rows; ?>','cov_per_','visitcov','notoff')" <?php if($tgtTypeCov == "1") echo "checked"; ?> value="1" /> T <sup>C</sup> -->

<input type="radio" name="tgtTypeCov" id="tgtTypeCov" value="0" /> T <sup>+</sup>&nbsp;&nbsp; 
<input type="radio" name="tgtTypeCov" id="tgtTypeCov" value="1" /> T <sup>C</sup>

<br/><br/>

<!-- <input type="radio" value="5" name="visitcov" id="visitcov" onClick="checkCovVisit('<?php echo $num_rows; ?>','cov_per_','noton')" /> %&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" value="10" name="visitcov" id="visitcov" onClick="checkCovVisit('<?php echo $num_rows; ?>','cov_per_','notoff')" /> Visits -->

<input type="radio" value="5" name="visitcov" id="visitcov" /> %&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" value="10" name="visitcov" id="visitcov" /> Visits

</th>
<th align="left" nowrap="nowrap">Productivity %

<br/><br/> <!-- Tgt Type : &nbsp;&nbsp; -->

<!-- <input type="radio" name="tgtTypeProd" id="tgtTypeProd" onClick="checkCovTgt('<?php echo $num_rows; ?>','prod_per_','visitprod','noton')" <?php if($tgtTypeProd == "0") echo "checked"; ?> value="0" /> T <sup>+</sup>&nbsp;&nbsp; 
<input type="radio" name="tgtTypeProd" id="tgtTypeProd" onClick="checkCovTgt('<?php echo $num_rows; ?>','prod_per_','visitprod','notoff')" <?php if($tgtTypeProd == "1") echo "checked"; ?> value="1" /> T <sup>C</sup> --> 

<input type="radio" name="tgtTypeProd" id="tgtTypeProd" value="0" /> T <sup>+</sup>&nbsp;&nbsp; 
<input type="radio" name="tgtTypeProd" id="tgtTypeProd" value="1" /> T <sup>C</sup>

<br/><br/>

<!-- <input type="radio" value="5" name="visitprod" id="visitprod" onClick="checkCovVisit('<?php echo $num_rows; ?>','prod_per_','noton')" /> %&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" value="10" name="visitprod" id="visitprod" onClick="checkCovVisit('<?php echo $num_rows; ?>','prod_per_','notoff')" /> Visits -->

<input type="radio" value="5" name="visitprod" id="visitprod" /> %&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" value="10" name="visitprod" id="visitprod" /> Visits

</th>
<th align="left" nowrap="nowrap">Effective Cov. %

<br/><br/> <!-- Tgt Type : &nbsp;&nbsp; -->

<!-- <input type="radio" name="tgtTypeEff" id="tgtTypeEff" onClick="checkCovTgt('<?php echo $num_rows; ?>','eff_per_','visiteff','noton')" <?php if($tgtTypeEff == "0") echo "checked"; ?> value="0" /> T <sup>+</sup>&nbsp;&nbsp;
<input type="radio" name="tgtTypeEff" id="tgtTypeEff" onClick="checkCovTgt('<?php echo $num_rows; ?>','eff_per_','visiteff','notoff')" <?php if($tgtTypeEff == "1") echo "checked"; ?> value="1" /> T <sup>C</sup> -->

<input type="radio" name="tgtTypeEff" id="tgtTypeEff" value="0" /> T <sup>+</sup>&nbsp;&nbsp;
<input type="radio" name="tgtTypeEff" id="tgtTypeEff" value="1" /> T <sup>C</sup>

<br/><br/>

<!-- <input type="radio" value="5" name="visiteff" id="visiteff" onClick="checkCovVisit('<?php echo $num_rows; ?>','eff_per_','noton')" /> %&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" value="10" name="visiteff" id="visiteff" onClick="checkCovVisit('<?php echo $num_rows; ?>','eff_per_','notoff')" /> Visits -->

<input type="radio" value="5" name="visiteff" id="visiteff" /> %&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" value="10" name="visiteff" id="visiteff" /> Visits

</th>
<th align="center" >
	<table border="1">
	<tr>
	  <td align="center" colspan="3">INCENTIVE NAIRA</td>
	</tr>
	<tr>
	  <td align="center" nowrap="nowrap">Coverage Incentive</td>
	  <td align="center" nowrap="nowrap">Productivity Incentive</td>
	  <td align="center" nowrap="nowrap">Effective Cov. Incentive</td>
	</tr>
	</table>
</th>
<!-- <th align="center">Target Units</th>
<th align="center">Naira/Unit</th> -->
</tr>
</thead>
<tbody>
<?php
	//pre($product_codearr);
	//echo $rowcnt_srfind;
if($num_rows > 0) {

	if($nextrecval		!=	"") {


	while($row_srfind 					=	mysql_fetch_array($results_dsr)) {
		$sr_codearr[]					=	$row_srfind[DSR_Code];
	}
	//pre($sr_codearr);
	for($k=0; $k<$num_rows; $k++) {			
		//echo trim($sr_codearr[$k]);
	?>
	<tr>
		<td width="731" style="width:45%"><input type="hidden" autocomplete="off" name="srname_<?php echo $k; ?>" id="srname_<?php echo $k; ?>" value="<?php echo $sr_codearr[$k]; ?>" /><span><?php echo getdbval($sr_codearr[$k],'DSRName','DSR_Code','dsr'); //PARAMETERS ARE TABLE QUERY COLUMN VALUE, TABLE RESULT COLUMN, TABLE QUERY COLUMN NAME, TABLE NAME ?></span></td>
		<td align="center"><input type="text" size="5" maxlength="5" autocomplete="off" name="cov_per_<?php echo $k; ?>" id="cov_per_<?php echo $k; ?>" value="<?php echo $tgtsel[$k]["target_units"];?>" /></td>	
		<td align="center"><input type="text" size="5" maxlength="5" autocomplete="off" name="prod_per_<?php echo $k; ?>" id="prod_per_<?php echo $k; ?>" value="<?php echo $tgtsel[$k]["target_naira"]; ?>" /></td>
		<td align="center"><input type="text" size="5" maxlength="5" autocomplete="off" name="eff_per_<?php echo $k; ?>" id="eff_per_<?php echo $k; ?>" value="<?php echo $tgtsel[$k]["target_naira"]; ?>" /></td>
		<td align="center">
		<table>
		  <tr>
			 <td align="center" nowrap="nowrap"><input type="text" size="15" maxlength="5" autocomplete="off" name="cov_visit_<?php echo $k; ?>" id="cov_visit_<?php echo $k; ?>" onBlur="curformatreturntoid(this.value,'cov_visit_<?php echo $k; ?>');" value="<?php echo $tgtsel[$k]["target_units"];?>" />						
			</td>	
			<td  align="center" nowrap="nowrap"><input type="text" size="15" maxlength="5" autocomplete="off" name="prod_visit_<?php echo $k; ?>" id="prod_visit_<?php echo $k; ?>" onBlur="curformatreturntoid(this.value,'prod_visit_<?php echo $k; ?>');" value="<?php echo $tgtsel[$k]["target_naira"]; ?>" />
			</td>
			<td  align="center" nowrap="nowrap"><input type="text" size="15" maxlength="5" autocomplete="off" name="eff_visit_<?php echo $k; ?>" id="eff_visit_<?php echo $k; ?>" onBlur="curformatreturntoid(this.value,'eff_visit_<?php echo $k; ?>');" value="<?php echo $tgtsel[$k]["target_naira"]; ?>" />
			</td>
		 </tr>
	  </table>
	</tr>
	<?php 
	} 	//for loop ?>
	<input type="hidden" name="overall_rowcnt" id="overall_rowcnt" value="<?php echo $num_rows; ?>" />
	<?php if($rowcnt_tgtsel > 0) { ?>
	<input type="hidden" name="tgt_edit" id="tgt_edit" value="<?php 
	if($rowcnt_tgtsel > 0) {
		echo "edit";
	} ?>" />
<?php  } //edit if loop		

	} // if loop for no records 
	else { ?>
		<tr>
			<td align="center" colspan="7"><strong>NO RECORDS FOUND</strong></td>
		</tr>
	<?php }


} // if loop ?>


</tbody>
</table>
<?php exit(0);?>