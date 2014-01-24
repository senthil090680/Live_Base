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

//pre($_GET);
//exit;

$rsmstrval								=	getdbstr('id','rsm_sp');
$asmstrval								=	getdbstr('DSR_Code','asm_sp');
$asmidstrval							=	getdbstr('id','asm_sp');
$branchstrval							=	getdbstr('id','branch');

// THIS IS WHEN THE SELECT BOX IS EMPTY WITHOUT ANY SELECTED OPTIONS, IT IS EITHER ARRAY WITH EMPTY VALUE IN O INDEX OR NULL VALUE STARTS HERE

//pre($_GET[codeval]);
//exit;
$arrsize		=	count($_GET[codeval]);
$arrvalue		=	1;
if($arrsize == '1') {
	//echo $arrsize;
	$arrvalue	=	$_GET[codeval][0];
}
//echo $arrvalue;
//exit;
if($arrvalue == '') {
	//echo "ldkd";
	//exit;

	if($smval	==	'rsm_sp') {
		$query_rsmnam 						=	"SELECT id,DSR_Code,DSRName FROM rsm_sp";
		$res_rsmnam = mysql_query($query_rsmnam) or die(mysql_error());
		?>
		<select class="dsrname" class="s9" name="rsmcode" id="rsmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getrsmspecific();" <?php } else if($srval == 'SRIN') { ?> onChange="getrsmspecificwithsr();" <?php } else if($srval == 'SRINC') { ?> onChange="getrsmspecificsrsta();" <?php } else if($srval == 'SRINCE') { ?> onChange="getrsmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getrsmspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getrsmspecificposm();" <?php } ?> >
			<option value="">---RSM---</option>
			<option value="<?php echo $rsmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
			if(strstr($rsmstr,$rsmstrval)) { echo "selected"; } ?> > ALL</option>

			<?php while($info_rsmname = mysql_fetch_assoc($res_rsmnam)) { ?>
			<option value="<?php echo  $info_rsmname['id']; ?>" <?php if(!strstr($rsmstr,$rsmstrval)) { if(strstr($rsmstr,$info_rsmname['id'])) { echo "selected"; }  } if($rsmcode == $info_rsmname['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_rsmname['DSRName']); ?></option>
			<?php } ?> 
		</select>  
		<?php exit(0);
	} elseif($smval	==	'asm_sp') {
		$query_asmnam 						=	"SELECT id,DSR_Code,DSRName FROM asm_sp";
		$res_asmnam							=	mysql_query($query_asmnam) or die(mysql_error());
		?>
		<select class="dsrname" class="s9" name="asmcode" id="asmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getasmspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getasmspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getasmspecificposm();" <?php } ?> >
			<option value="">---ASM---</option>

			<option value="<?php echo $asmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
			if(strstr($asmstr,$asmidstrval)) { echo "selected"; } ?> > ALL</option>

			<?php while($info_asm = mysql_fetch_assoc($res_asmnam)) { ?>
			<option value="<?php echo  $info_asm['DSR_Code']; ?>" <?php if(!strstr($asmstr,$asmidstrval)) { if(strstr($asmstr,$info_asm['id'])) { echo "selected"; } } if($asmcode == $info_asm['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_asm['DSRName']); ?></option>
			<?php } ?> 
		</select>  
		<?php exit(0);


	} elseif($smval	==	'branch') {
		$query_branch 						=	"SELECT id,branch FROM branch";
		$res_branch 						=	mysql_query($query_branch) or die(mysql_error());
		?>
		<select class="dsrname" class="s9" name="branchcode" id="branchcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getbranchspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getbranchspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getbranchspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getbranchspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getbranchspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getbranchspecificposm();" <?php } ?> >
			<option value="">---BRANCH---</option>
			<option value="<?php echo $branchstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
			if(strstr($branchstr,$branchstrval)) { echo "selected"; } ?> > ALL</option>
			<?php while($info_branch = mysql_fetch_assoc($res_branch)) { ?>
			<option value="<?php echo  $info_branch['id']; ?>" <?php if(!strstr($branchstr,$branchstrval)) { if(strstr($branchstr,$info_branch['id'])) { echo "selected"; } } if($branchcode == $info_branch['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_branch['branch']); ?></option>
			<?php } ?> 
		</select>
		<?php exit(0);
	} 
}

if($_GET[codeval] == 'null') {
	//echo "goodfd";
	//exit;
	if($smval	==	'rsm_sp') {
		$query_rsmnam 						=	"SELECT id,DSR_Code,DSRName FROM rsm_sp";
		$res_rsmnam = mysql_query($query_rsmnam) or die(mysql_error());
		?>
		<select class="dsrname" class="s9" name="rsmcode" id="rsmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getrsmspecific();" <?php } else if($srval == 'SRIN') { ?> onChange="getrsmspecificwithsr();" <?php } else if($srval == 'SRINC') { ?> onChange="getrsmspecificsrsta();" <?php } else if($srval == 'SRINCE') { ?> onChange="getrsmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getrsmspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getrsmspecificposm();" <?php } ?> >
			<option value="">---RSM---</option>
			<option value="<?php echo $rsmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
			if(strstr($rsmstr,$rsmstrval)) { echo "selected"; } ?> > ALL</option>

			<?php while($info_rsmname = mysql_fetch_assoc($res_rsmnam)) { ?>
			<option value="<?php echo  $info_rsmname['id']; ?>" <?php if(!strstr($rsmstr,$rsmstrval)) { if(strstr($rsmstr,$info_rsmname['id'])) { echo "selected"; }  } if($rsmcode == $info_rsmname['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_rsmname['DSRName']); ?></option>
			<?php } ?> 
		</select>  
		<?php exit(0);
	} elseif($smval	==	'asm_sp') {
		$query_asmnam 						=	"SELECT id,DSR_Code,DSRName FROM asm_sp";
		$res_asmnam							=	mysql_query($query_asmnam) or die(mysql_error());
		?>
		<select class="dsrname" class="s9" name="asmcode" id="asmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getasmspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getasmspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getasmspecificposm();" <?php } ?> >
			<option value="">---ASM---</option>

			<option value="<?php echo $asmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
			if(strstr($asmstr,$asmidstrval)) { echo "selected"; } ?> > ALL</option>

			<?php while($info_asm = mysql_fetch_assoc($res_asmnam)) { ?>
			<option value="<?php echo  $info_asm['DSR_Code']; ?>" <?php if(!strstr($asmstr,$asmidstrval)) { if(strstr($asmstr,$info_asm['id'])) { echo "selected"; } } if($asmcode == $info_asm['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_asm['DSRName']); ?></option>
			<?php } ?> 
		</select>  
		<?php exit(0);


	} elseif($smval	==	'branch') {
		$query_branch 						=	"SELECT id,branch FROM branch";
		$res_branch 						=	mysql_query($query_branch) or die(mysql_error());
		?>
		<select class="dsrname" class="s9" name="branchcode" id="branchcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getbranchspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getbranchspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getbranchspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getbranchspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getbranchspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getbranchspecificposm();" <?php } ?> >
			<option value="">---BRANCH---</option>
			<option value="<?php echo $branchstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
			if(strstr($branchstr,$branchstrval)) { echo "selected"; } ?> > ALL</option>
			<?php while($info_branch = mysql_fetch_assoc($res_branch)) { ?>
			<option value="<?php echo  $info_branch['id']; ?>" <?php if(!strstr($branchstr,$branchstrval)) { if(strstr($branchstr,$info_branch['id'])) { echo "selected"; } } if($branchcode == $info_branch['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_branch['branch']); ?></option>
			<?php } ?> 
		</select>
		<?php exit(0);
	}
}

// THIS IS WHEN THE SELECT BOX IS EMPTY WITHOUT ANY SELECTED OPTIONS, IT IS EITHER ARRAY WITH EMPTY VALUE IN O INDEX OR NULL VALUE ENDS HERE


if(isset($_GET[codeval]) && $_GET[codeval] !='') {
	$codevalstr		=	implode("','",$codeval);

	if(strstr($codevalstr,"',")) {

	} else {
		$codevalstr		=	str_replace(",","','",$codevalstr);
	}
	if($codevalstr != '') {
		$nextrecval			=	"WHERE DSR_Code IN ('".$codevalstr."')";
	} else {
		$nextrecval			=	"";
	}
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `dsr` $nextrecval";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr	=	mysql_query($qry) or die(mysql_error());
$num_rows		=	mysql_num_rows($results_dsr);

while($info = mysql_fetch_assoc($results_dsr)) {
	$asm[]		=	$info[ASM];
}
//debugerr($brand);
$asmuni								=	array_unique($asm);
//debugerr($branduni);
$asmstr								=	implode("','",$asmuni);
//$query_asmnam 						=	"SELECT id,DSR_Code,DSRName,RSM FROM asm_sp WHERE id IN ('".$asmstr."')";
$query_asmnam 						=	"SELECT id,DSR_Code,DSRName,RSM FROM asm_sp";
$res_asmnam 						=	mysql_query($query_asmnam) or die(mysql_error());
$rowcnt_asmnam						=	mysql_num_rows($res_asmnam);

if($smval	==	'rsm_sp' || $smval	==	'branch') {
	$query_asmnam 						=	"SELECT id,DSR_Code,DSRName,RSM FROM asm_sp WHERE id IN ('".$asmstr."')";
	$res_rsmid 							=	mysql_query($query_asmnam) or die(mysql_error());
	while($info = mysql_fetch_assoc($res_rsmid)) {
		$rsm[]		=	$info[RSM];
	}
	//debugerr($brand);
	$rsmuni								=	array_unique($rsm);
	//debugerr($branduni);
	$rsmstr								=	implode("','",$rsmuni);
	//echo $rsmstr;
	//$query_rsmnam 						=	"SELECT id,DSR_Code,DSRName,branch_id FROM rsm_sp WHERE id IN ('".$rsmstr."')";
	$query_rsmnam 						=	"SELECT id,DSR_Code,DSRName,branch_id FROM rsm_sp";
	$res_rsmnam 						=	mysql_query($query_rsmnam) or die(mysql_error());
	$rowcnt_rsmnam						=	mysql_num_rows($res_rsmnam); 
} if($smval	==	'rsm_sp') { ?>

	<select class="dsrname" class="s9" name="rsmcode" id="rsmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getrsmspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getrsmspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getrsmspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getrsmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getrsmspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getrsmspecificposm();" <?php } ?> >
	<option value="">---RSM---</option>

	<option value="<?php echo $rsmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
	if(strstr($rsmstr,$rsmstrval)) { echo "selected"; } ?> > ALL</option>
	<?php while($info_rsm = mysql_fetch_assoc($res_rsmnam)) { ?>
	<option value="<?php echo  $info_rsm['id']; ?>" <?php  
	if(!strstr($rsmstr,$rsmstrval)) { if(strstr($rsmstr,$info_rsm['id'])) { echo "selected"; }  }
	//if($rsmcode == $info_rsm['id']) { echo "selected"; } 
	?> > <?php echo  upperstate($info_rsm['DSRName']); ?></option>
	<?php } ?> 
</select>

<?php }

if($smval	==	'branch') {
	$query_rsmnam 						=	"SELECT id,DSR_Code,DSRName,branch_id FROM rsm_sp WHERE id IN ('".$rsmstr."')";
	$res_branchnam 						=	mysql_query($query_rsmnam) or die(mysql_error());
	while($info = mysql_fetch_assoc($res_branchnam)) {
		$branch_id[]		=	$info[branch_id];
	}
	//debugerr($brand);
	$branchuni								=	array_unique($branch_id);
	//pre($branchuni);
	//debugerr($branduni);
	$branchstr								=	implode("','",$branchuni);
	//$query_branch 						=	"SELECT id,branch FROM branch WHERE id IN ('".$branchstr."')";
	$query_branch 						=	"SELECT id,branch FROM branch";
	$res_branch 						=	mysql_query($query_branch) or die(mysql_error());
	$rowcnt_branch						=	mysql_num_rows($res_branch); ?>
	
	<select class="dsrname" class="s9" name="branchcode" id="branchcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getbranchspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getbranchspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getbranchspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getbranchspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getbranchspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getbranchspecificposm();" <?php } ?> >
	<option value="">---BRANCH---</option>
	<option value="<?php echo $branchstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
	if(strstr($branchstr,$branchstrval)) { echo "selected"; } ?> > ALL</option>
	<?php while($info_branch = mysql_fetch_assoc($res_branch)) { ?>
	<option value="<?php echo  $info_branch['id']; ?>" <?php if(!strstr($branchstr,$branchstrval)) { if(strstr($branchstr,$info_branch['id'])) { echo "selected"; } } if($branchcode == $info_branch['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_branch['branch']); ?></option>
	<?php } ?> 
</select>
<?php }

if($smval	==	'asm_sp') {
	?>
<select class="dsrname" class="s9" name="asmcode" id="asmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getasmspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getasmspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getasmspecificposm();" <?php } ?> >
	<option value="">---ASM---</option>
	<option value="<?php echo $asmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
	if(strstr($asmstr,$asmidstrval)) { echo "selected"; } ?> > ALL</option>
	<?php while($info_asm = mysql_fetch_assoc($res_asmnam)) { ?>
	<option value="<?php echo  $info_asm['DSR_Code']; ?>" <?php if(!strstr($asmstr,$asmidstrval)) { if(strstr($asmstr,$info_asm['id'])) { echo "selected"; }  } if($asmcode == $info_asm['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_asm['DSRName']); ?></option>
	<?php } ?> 
</select>
<?php } exit(0);?>