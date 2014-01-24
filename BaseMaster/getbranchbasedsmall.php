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

$rsmstrval								=	getdbstr('id','rsm_sp');
$asmstrval								=	getdbstr('DSR_Code','asm_sp');
$asmidstrval							=	getdbstr('id','asm_sp');
$dsrstrval								=	getdbstr('id','dsr');
$dsrcodestrval							=	getdbstr('DSR_Code','dsr');

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
		<select class="dsrname" class="s9" name="rsmcode" id="rsmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getrsmspecific();" <?php } else if($srval == 'SRIN') { ?> onChange="getrsmspecificwithsr();" <?php } else if($srval == 'SRINC') { ?> onChange="getrsmspecificsrsta();" <?php } else if($srval == 'SRINCE') { ?> onChange="getrsmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getrsmspecificsrpercent();" <?php } ?> >
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
		<select class="dsrname" class="s9" name="asmcode" id="asmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getasmspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getasmspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmspecificsrpercent();" <?php } ?> >
			<option value="">---ASM---</option>

			<option value="<?php echo $asmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
			if(strstr($asmstr,$asmidstrval)) { echo "selected"; } ?> > ALL</option>

			<?php while($info_asm = mysql_fetch_assoc($res_asmnam)) { ?>
			<option value="<?php echo  $info_asm['DSR_Code']; ?>" <?php if(!strstr($asmstr,$asmidstrval)) { if(strstr($asmstr,$info_asm['id'])) { echo "selected"; } } if($asmcode == $info_asm['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_asm['DSRName']); ?></option>
			<?php } ?> 
		</select>  
		<?php exit(0);


	} elseif($smval	==	'dsr') {
		$query_dsr 							=	"SELECT id,DSR_Code,DSRName FROM dsr";
		$res_dsr							=	mysql_query($query_dsr) or die(mysql_error());
		?>
		<select class="dsrname" class="s9" name="srcode" id="srcode" multiple <?php if($srval == 'SRIN') { ?> onChange="getsrspecific();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmrsmsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmrsmsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmrsmsrpercent();" <?php } ?> >
			<option value="">---SR---</option>

			<option value="<?php echo $dsrcodestrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
			if(strstr($srcodestr,$dsrstrval)) { echo "selected"; } ?> > ALL</option>

			<?php while($info_srname = mysql_fetch_assoc($res_dsr)) { ?>

			<option value="<?php echo  $info_srname['DSR_Code']; ?>" <?php if(!strstr($srcodestr,$dsrstrval)) { if(strstr($srcodestr,$info_srname['id'])) { echo "selected"; }  } if($srcode == $info_srname['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_srname['DSRName']); ?></option>
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
		<select class="dsrname" class="s9" name="rsmcode" id="rsmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getrsmspecific();" <?php } else if($srval == 'SRIN') { ?> onChange="getrsmspecificwithsr();" <?php } else if($srval == 'SRINC') { ?> onChange="getrsmspecificsrsta();" <?php } else if($srval == 'SRINCE') { ?> onChange="getrsmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getrsmspecificsrpercent();" <?php } ?> >
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
		<select class="dsrname" class="s9" name="asmcode" id="asmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getasmspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getasmspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmspecificsrpercent();" <?php } ?> >
			<option value="">---ASM---</option>

			<option value="<?php echo $asmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
			if(strstr($asmstr,$asmidstrval)) { echo "selected"; } ?> > ALL</option>

			<?php while($info_asm = mysql_fetch_assoc($res_asmnam)) { ?>
			<option value="<?php echo  $info_asm['DSR_Code']; ?>" <?php if(!strstr($asmstr,$asmidstrval)) { if(strstr($asmstr,$info_asm['id'])) { echo "selected"; } } if($asmcode == $info_asm['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_asm['DSRName']); ?></option>
			<?php } ?> 
		</select>  
		<?php exit(0);


	} elseif($smval	==	'dsr') {
		$query_dsr 							=	"SELECT id,DSR_Code,DSRName FROM dsr";
		$res_dsr							=	mysql_query($query_dsr) or die(mysql_error());
		?>
		<select class="dsrname" class="s9" name="srcode" id="srcode" multiple <?php if($srval == 'SRIN') { ?> onChange="getsrspecific();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmrsmsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmrsmsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmrsmsrpercent();" <?php } ?> >
			<option value="">---SR---</option>

			<option value="<?php echo $dsrcodestrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
			if(strstr($srcodestr,$dsrstrval)) { echo "selected"; } ?> > ALL</option>

			<?php while($info_srname = mysql_fetch_assoc($res_dsr)) { ?>

			<option value="<?php echo  $info_srname['DSR_Code']; ?>" <?php if(!strstr($srcodestr,$dsrstrval)) { if(strstr($srcodestr,$info_srname['id'])) { echo "selected"; }  } if($srcode == $info_srname['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_srname['DSRName']); ?></option>
			<?php } ?> 
		</select> 
		<?php exit(0);
	}
}

// THIS IS WHEN THE SELECT BOX IS EMPTY WITHOUT ANY SELECTED OPTIONS, IT IS EITHER ARRAY WITH EMPTY VALUE IN O INDEX OR NULL VALUE ENDS HERE


if(isset($_GET[codeval]) && $_GET[codeval] !='') {
	$codevalstr		=	implode("','",$codeval);
	if($codevalstr != '') {
		$nextrecval			=	"WHERE id IN ('".$codevalstr."')";
	} else {
		$nextrecval			=	"";
	}
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `branch` $nextrecval";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr	=	mysql_query($qry) or die(mysql_error());
$num_rows		=	mysql_num_rows($results_dsr);

while($info = mysql_fetch_assoc($results_dsr)) {
	$branch[]		=	$info[id];
}
//debugerr($brand);
$branchuni								=	array_unique($branch);
//debugerr($branduni);
$branchstr								=	implode("','",$branchuni);
//$query_asmnam 						=	"SELECT id,DSR_Code,DSRName FROM rsm_sp WHERE branch_id IN ('".$branchstr."')";
$query_asmnam 							=	"SELECT id,DSR_Code,DSRName FROM rsm_sp";
//$res_asmnam 							=	mysql_query($query_asmnam) or die(mysql_error());
$res_rsmid 								=	mysql_query($query_asmnam) or die(mysql_error());
//$rowcnt_asmnam							=	mysql_num_rows($res_asmnam);

if($smval	==	'asm_sp' || $smval	==	'dsr') {
	$query_asmnam 							=	"SELECT id,DSR_Code,DSRName FROM rsm_sp WHERE branch_id IN ('".$branchstr."')";
	$res_asmnam 							=	mysql_query($query_asmnam) or die(mysql_error());
	while($info = mysql_fetch_assoc($res_asmnam)) {
		$rsm[]		=	$info[id];
	}
	//debugerr($brand);
	$rsmuni								=	array_unique($rsm);
	//debugerr($branduni);
	$rsmstr								=	implode("','",$rsmuni);
	//$query_rsmnam 					=	"SELECT id,DSR_Code,DSRName FROM asm_sp WHERE RSM IN ('".$rsmstr."')";
	$query_rsmnam 						=	"SELECT id,DSR_Code,DSRName FROM asm_sp";
	$res_asmnam 						=	mysql_query($query_rsmnam) or die(mysql_error());
	$res_srnam 							=	mysql_query($query_rsmnam) or die(mysql_error());
	//$rowcnt_rsmnam						=	mysql_num_rows($res_srnam); 
} if($smval	==	'rsm_sp') {
	$query_asmnam 							=	"SELECT id,DSR_Code,DSRName FROM rsm_sp WHERE branch_id IN ('".$branchstr."')";
	$res_asmnam 							=	mysql_query($query_asmnam) or die(mysql_error());
	while($info = mysql_fetch_assoc($res_asmnam)) {
		$rsm[]		=	$info[id];
	}
	
	$rsmuni								=	array_unique($rsm);
	$rsmstr								=	implode("','",$rsmuni);

	?>

	<select class="dsrname" class="s9" name="rsmcode" id="rsmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getrsmspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getrsmspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getrsmspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getrsmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getrsmspecificsrpercent();" <?php } ?> >
	<option value="">---RSM---</option>

	<option value="<?php echo $rsmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
	if(strstr($rsmstr,$rsmstrval)) { echo "selected"; } ?> > ALL</option>

	<?php while($info_rsm = mysql_fetch_assoc($res_rsmid)) { ?>
	<option value="<?php echo  $info_rsm['id']; ?>" <?php if(!strstr($rsmstr,$rsmstrval)) { if(strstr($rsmstr,$info_rsm[id])) { echo "selected"; }  } if($rsmcode == $info_rsm['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_rsm['DSRName']); ?></option>
	<?php } ?> 
</select>
<?php }

if($smval	==	'dsr') {
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
	//$query_sr 					=	"SELECT id,DSR_Code,DSRName FROM dsr WHERE ASM IN ('".$asmstr."')";
	$query_sr 						=	"SELECT id,DSR_Code,DSRName FROM dsr";
	$res_sr 						=	mysql_query($query_sr) or die(mysql_error());
	$rowcnt_sr						=	mysql_num_rows($res_sr);
	
	$query_srcode 					=	"SELECT id,DSR_Code,DSRName FROM dsr WHERE ASM IN ('".$asmstr."')";
	$res_srcode 					=	mysql_query($query_srcode) or die(mysql_error());
	while($info_srcode				=	mysql_fetch_assoc($res_srcode)) {
		$sr_code[]					=	$info_srcode[id];
	}
	//debugerr($brand);
	$sr_codeuni							=	array_unique($sr_code);
	//pre($branchuni);
	//debugerr($branduni);
	$srcodestr							=	implode("','",$sr_codeuni);
		
	?>
	
	<select class="dsrname" class="s9" name="srcode" id="srcode" multiple <?php if($srval == 'SRIN') { ?> onChange="getsrspecific();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmrsmsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmrsmsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmrsmsrpercent();" <?php } ?> >
	<option value="">---SR---</option>

	<option value="<?php echo $dsrcodestrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
	if(strstr($srcodestr,$dsrstrval)) { echo "selected"; } ?> > ALL</option>

	<?php while($info_srname = mysql_fetch_assoc($res_sr)) { ?>

	<option value="<?php echo  $info_srname['DSR_Code']; ?>" <?php if(!strstr($srcodestr,$dsrstrval)) { if(strstr($srcodestr,$info_srname['id'])) { echo "selected"; }  } if($srcode == $info_srname['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_srname['DSRName']); ?></option>
	<?php } ?> 
</select> 
<?php }

if($smval	==	'asm_sp') { 
	
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

	//echo $asmstr. "==" . $asmidstrval."<br>";
	
	?>
<select class="dsrname" class="s9" name="asmcode" id="asmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getasmspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getasmspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmspecificsrpercent();" <?php } ?> >
	<option value="">---ASM---</option>

	<option value="<?php echo $asmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
	if(strstr($asmstr,$asmidstrval)) { echo "selected"; } ?> > ALL</option>

	<?php while($info_asm = mysql_fetch_assoc($res_asmnam)) { ?>
	<option value="<?php echo  $info_asm['DSR_Code']; ?>" <?php if(!strstr($asmstr,$asmidstrval)) { if(strstr($asmstr,$info_asm['id'])) { echo "selected"; } } if($asmcode == $info_asm['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_asm['DSRName']); ?></option>
	<?php } ?> 
</select>
<?php } exit(0); ?>