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
}

if($_GET[codeval] == 'null') {
	//echo "goodfd";
	//exit;
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
}

// THIS IS WHEN THE SELECT BOX IS EMPTY WITHOUT ANY SELECTED OPTIONS, IT IS EITHER ARRAY WITH EMPTY VALUE IN O INDEX OR NULL VALUE ENDS HERE


if(isset($_GET[codeval]) && $_GET[codeval] !='') {
	$codevalstr		=	implode("','",$codeval);
	if($codevalstr != '') {
		$nextrecval		=	"WHERE DSR_Code IN ('".$codevalstr."')";		
	}
} else {
	
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `asm_sp` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr = mysql_query($qry) or die(mysql_error());
$num_rows		=	mysql_num_rows($results_dsr);

while($info = mysql_fetch_assoc($results_dsr)) {
	$rsm[]		=	$info[RSM];
}
//debugerr($brand);
$rsmuni								=	array_unique($rsm);
//debugerr($branduni);
$rsmstr								=	implode("','",$rsmuni);
//$query_rsmnam 						=	"SELECT id,DSR_Code,DSRName FROM rsm_sp WHERE id IN ('".$rsmstr."')";
$query_rsmnam 						=	"SELECT id,DSR_Code,DSRName FROM rsm_sp";
$res_rsmnam 						=	mysql_query($query_rsmnam) or die(mysql_error());
$rowcnt_rsmnam						=	mysql_num_rows($res_rsmnam);
?>

<select class="dsrname" class="s9" name="rsmcode" id="rsmcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getrsmspecific();" <?php } else if($srval == 'SRIN') { ?> onChange="getrsmspecificwithsr();" <?php } else if($srval == 'SRINC') { ?> onChange="getrsmspecificsrsta();" <?php } else if($srval == 'SRINCE') { ?> onChange="getrsmspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getrsmspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getrsmspecificposm();" <?php } ?> >
	<option value="">---RSM---</option>
	<option value="<?php echo $rsmstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
	if(strstr($rsmstr,$rsmstrval)) { echo "selected"; } ?> > ALL</option>

	<?php while($info_rsmname = mysql_fetch_assoc($res_rsmnam)) { ?>
	<option value="<?php echo  $info_rsmname['id']; ?>" <?php if(!strstr($rsmstr,$rsmstrval)) { if(strstr($rsmstr,$info_rsmname['id'])) { echo "selected"; }  } if($rsmcode == $info_rsmname['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_rsmname['DSRName']); ?></option>
	<?php } ?> 
</select>  
<?php exit(0);?>