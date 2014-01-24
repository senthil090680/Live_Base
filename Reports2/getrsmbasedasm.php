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

$asmstrval									=	getdbstr('DSR_Code','asm_sp');
$asmidstrval								=	getdbstr('id','asm_sp');

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
}

if($_GET[codeval] == 'null') {
	//echo "goodfd";
	//exit;
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
}

// THIS IS WHEN THE SELECT BOX IS EMPTY WITHOUT ANY SELECTED OPTIONS, IT IS EITHER ARRAY WITH EMPTY VALUE IN O INDEX OR NULL VALUE ENDS HERE



if(isset($_GET[codeval]) && $_GET[codeval] !='') {
	$codevalstr		=	implode("','",$codeval);
	if($codevalstr != '') {
		$nextrecval		=	"WHERE RSM IN ('".$codevalstr."')";		
	}
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `asm_sp` $where GROUP BY RSM";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr = mysql_query($qry) or die(mysql_error());
$num_rows		=	mysql_num_rows($results_dsr);

while($info = mysql_fetch_assoc($results_dsr)) {
	$asm[]		=	$info[RSM];
}

$asmuni								=	array_unique($asm);
$asmstr								=	implode("','",$asmuni);

$qry="SELECT * FROM `asm_sp` GROUP BY RSM";
$results_dsr = mysql_query($qry) or die(mysql_error());


?>

<select class="dsrname" class="s9" name="asmcode" id="asmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getasmspecific();" <?php } else if($srval == 'SRIN') { ?> onChange="getasmspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getasmspecificposm();" <?php } ?> >
	<option value="">---ASM---</option>

	<option value="<?php echo $asmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
	if(strstr($asmstr,$asmidstrval)) { echo "selected"; } ?> > ALL</option>

	<?php while($info = mysql_fetch_assoc($results_dsr)) { ?>
	<option value="<?php echo  $info['DSR_Code']; ?>" <?php if(!strstr($asmstr,$asmidstrval)) { if(strstr($asmstr,$info['id'])) { echo "selected"; }  } if($asmcode == $info['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info['DSRName']); ?></option>
	<?php } ?> 
</select>  
<?php exit(0);?>