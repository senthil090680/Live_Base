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

	$query_dsr 							=	"SELECT * FROM `dsr`";
	$results_dsr						=	mysql_query($query_dsr) or die(mysql_error());
	?>
	<select class="dsrname" class="s9" name="srcode[]" id="srcode" multiple <?php if($srval == 'SRIN') { ?> onChange="getsrspecific();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmrsmsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmrsmsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmrsmsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getsrspecificposm();" <?php } ?> >
		<option value="">---SR---</option>

		<option value="<?php echo $dsrcodestrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
		if(strstr($srcodestr,$dsrstrval)) { echo "selected"; } ?> > ALL</option>

		<?php while($info_srname = mysql_fetch_assoc($results_dsr)) { ?>
		<option value="<?php echo  $info_srname['DSR_Code']; ?>" <?php if(!strstr($srcodestr,$dsrstrval)) { if(strstr($srcodestr,$info_srname['id'])) { echo "selected"; }  }  if($srcode == $info_srname['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_srname['DSRName']); ?></option>
		<?php } ?> 
	</select> 
	<?php exit(0);
}

if($_GET[codeval] == 'null') {
	//echo "goodfd";
	//exit;	
	$query_dsr 							=	"SELECT * FROM `dsr`";
	$results_dsr						=	mysql_query($query_dsr) or die(mysql_error());
	?>
	<select class="dsrname" class="s9" name="srcode[]" id="srcode" multiple <?php if($srval == 'SRIN') { ?> onChange="getsrspecific();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmrsmsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmrsmsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmrsmsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getsrspecificposm();" <?php } ?> >
		<option value="">---SR---</option>

		<option value="<?php echo $dsrcodestrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
		if(strstr($srcodestr,$dsrstrval)) { echo "selected"; } ?> > ALL</option>

		<?php while($info_srname = mysql_fetch_assoc($results_dsr)) { ?>
		<option value="<?php echo  $info_srname['DSR_Code']; ?>" <?php if(!strstr($srcodestr,$dsrstrval)) { if(strstr($srcodestr,$info_srname['id'])) { echo "selected"; }  }  if($srcode == $info_srname['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_srname['DSRName']); ?></option>
		<?php } ?> 
	</select> 
	<?php exit(0);
}

// THIS IS WHEN THE SELECT BOX IS EMPTY WITHOUT ANY SELECTED OPTIONS, IT IS EITHER ARRAY WITH EMPTY VALUE IN O INDEX OR NULL VALUE ENDS HERE


if(isset($_GET[codeval]) && $_GET[codeval] !='') {
	$dsrstrval								=	getdbstr('id','dsr');
	$codevalstr		=	implode("','",$codeval);
	if($smval  == 'rsm_sp') {
		if($codevalstr != '') {			
			$nextrecval		=	"RSM IN ('".$codevalstr."')";
			$wherefordsr	=	"WHERE";
		} else {
			$nextrecval		=	"";
			$wherefordsr	=	"";
		}
		$DSR_Codestr		=	findSR($wherefordsr,$nextrecval);
		if($DSR_Codestr != '') {			
			$nextrecval		=	"DSR_Code IN ('".$DSR_Codestr."')";
			$wherefordsr	=	"WHERE";
		} else {
			$nextrecval		=	"";
			$wherefordsr	=	"";
		}
	} elseif($smval  == 'asm_sp') {
		if($codevalstr != '') {			
			$nextrecval		=	"DSR_Code IN ('".$codevalstr."')";
			$wherefordsr	=	"WHERE";
		} else {
			$nextrecval		=	"";
			$wherefordsr	=	"";
		}		
		$DSR_Codestr		=	findSR($wherefordsr,$nextrecval);
		if($DSR_Codestr != '') {			
			$nextrecval		=	"DSR_Code IN ('".$DSR_Codestr."')";
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

while($info = mysql_fetch_assoc($results_dsr)) {
	$srstr[]		=	$info[id];
}

$srstruni								=	array_unique($srstr);
$srcodestr								=	implode("','",$srstruni);

$qry="SELECT * FROM `dsr`";
$results_dsr	=	mysql_query($qry) or die(mysql_error());


?>

<select class="dsrname" class="s9" name="srcode[]" id="srcode" multiple <?php if($srval == 'SRIN') { ?> onChange="getsrspecific();" <?php } elseif($srval == 'SRINC') { ?> onChange="getasmrsmsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getasmrsmsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getasmrsmsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getsrspecificposm();" <?php } ?> >
	<option value="">---SR---</option>

	<option value="<?php echo $dsrcodestrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
	if(strstr($srcodestr,$dsrstrval)) { echo "selected"; } ?> > ALL</option>

	<?php while($info_srname = mysql_fetch_assoc($results_dsr)) { ?>
	<option value="<?php echo  $info_srname['DSR_Code']; ?>" <?php if(!strstr($srcodestr,$dsrstrval)) { if(strstr($srcodestr,$info_srname['id'])) { echo "selected"; }  }  if($srcode == $info_srname['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_srname['DSRName']); ?></option>
	<?php } ?> 
</select>  
<?php exit(0);?>