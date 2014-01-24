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

	$query_dsr 							=	"SELECT * FROM `branch`";
	$results_dsr						=	mysql_query($query_dsr) or die(mysql_error());
	?>
	<select class="dsrname" class="s9" name="branchcode" id="branchcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getbranchspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getbranchspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getbranchspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getbranchspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getbranchspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getbranchspecificposm();" <?php } ?> >
		<option value="">---BRANCH---</option>
		<option value="<?php echo $branchstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
		if(strstr($branchstr,$branchstrval)) { echo "selected"; } ?> > ALL</option>

		<?php while($info_branch = mysql_fetch_assoc($results_dsr)) { ?>
		<option value="<?php echo  $info_branch['id']; ?>" <?php if(!strstr($branchstr,$branchstrval)) { if(strstr($branchstr,$info_branch['id'])) { echo "selected"; } } if($branchcode == $info_branch['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_branch['branch']); ?></option>
		<?php } ?> 
	</select> 
	<?php exit(0);
}

if($_GET[codeval] == 'null') {
	//echo "goodfd";
	//exit;	
	$query_dsr 							=	"SELECT * FROM `branch`";
	$results_dsr						=	mysql_query($query_dsr) or die(mysql_error());
	?>
	<select class="dsrname" class="s9" name="branchcode" id="branchcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getbranchspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getbranchspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getbranchspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getbranchspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getbranchspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getbranchspecificposm();" <?php } ?> >
		<option value="">---BRANCH---</option>
		<option value="<?php echo $branchstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
		if(strstr($branchstr,$branchstrval)) { echo "selected"; } ?> > ALL</option>

		<?php while($info_branch = mysql_fetch_assoc($results_dsr)) { ?>
		<option value="<?php echo  $info_branch['id']; ?>" <?php if(!strstr($branchstr,$branchstrval)) { if(strstr($branchstr,$info_branch['id'])) { echo "selected"; } } if($branchcode == $info_branch['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_branch['branch']); ?></option>
		<?php } ?> 
	</select> 
	<?php exit(0);
}

// THIS IS WHEN THE SELECT BOX IS EMPTY WITHOUT ANY SELECTED OPTIONS, IT IS EITHER ARRAY WITH EMPTY VALUE IN O INDEX OR NULL VALUE ENDS HERE



if(isset($_GET[codeval]) && $_GET[codeval] !='') {
	$branchstrval							=	getdbstr('id','branch');

	$codevalstr		=	implode("','",$codeval);
	if($smval  == 'rsm_sp') {
		/*if($codevalstr != '') {			
			$nextrecval		=	"id IN ('".$codevalstr."')";
			$wherefordsr	=	"WHERE";
		} else {
			$nextrecval		=	"";
			$wherefordsr	=	"";
		}*/
		$branch_Codestr		=	finddbval("('".$codevalstr."')",'branch_id','id','rsm_sp');
		if($branch_Codestr != '') {			
			$nextrecval		=	"id IN ('".$branch_Codestr."')";
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
		$rsm_Codestr		=	finddbval("('".$codevalstr."')",'RSM','DSR_Code','asm_sp');
		$branch_Codestr		=	finddbval("('".$rsm_Codestr."')",'branch_id','id','rsm_sp');

		if($branch_Codestr != '') {			
			$nextrecval		=	"id IN ('".$branch_Codestr."')";
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
	$qry="SELECT * FROM `branch` $wherefordsr $nextrecval";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr	=	mysql_query($qry) or die(mysql_error());

while($info = mysql_fetch_assoc($results_dsr)) {
	$branch_id[]		=	$info[id];
}

$branch_iduni								=	array_unique($branch_id);
$branchstr									=	implode("','",$branch_iduni);

$qry="SELECT * FROM `branch`";
$results_dsr	=	mysql_query($qry) or die(mysql_error());


?>

<select class="dsrname" class="s9" name="branchcode" id="branchcode" multiple <?php if($srval == 'SRNOT') { ?> onChange="getbranchspecific();" <?php } elseif($srval == 'SRIN') { ?> onChange="getbranchspecificwithsr();" <?php } elseif($srval == 'SRINC') { ?> onChange="getbranchspecificsrsta();" <?php } elseif($srval == 'SRINCE') { ?> onChange="getbranchspecificsrinc();" <?php } elseif($srval == 'SRCOV') { ?> onChange="getbranchspecificsrpercent();" <?php } else if($srval == 'POSM') { ?> onChange="getbranchspecificposm();" <?php } ?> >
	<option value="">---BRANCH---</option>
	<option value="<?php echo $branchstrval; ?>" <?php //echo $asmstr. "==" . $asmidstrval;
	if(strstr($branchstr,$branchstrval)) { echo "selected"; } ?> > ALL</option>

	<?php while($info_branch = mysql_fetch_assoc($results_dsr)) { ?>
	<option value="<?php echo  $info_branch['id']; ?>" <?php if(!strstr($branchstr,$branchstrval)) { if(strstr($branchstr,$info_branch['id'])) { echo "selected"; } } if($branchcode == $info_branch['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_branch['branch']); ?></option>
	<?php } ?> 
</select> 
<?php exit(0);?>