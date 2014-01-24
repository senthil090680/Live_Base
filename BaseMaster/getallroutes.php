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
if(isset($_GET[DSR_Code]) && $_GET[DSR_Code] !='') {
	$nextrecval		=	"WHERE (DSR_Code = '$DSR_Code')";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `customer` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results									=	mysql_query($qry);
$num_rows									=	mysql_num_rows($results);
$alldayscnt									=	0;
$saturdaychecked							=	0;
$repeatchecked								=	0;
$route_mon									=	'';
$route_tue									=	'';
$route_wed									=	'';
$route_thu									=	'';
$route_fri									=	'';
$route_sat									=	'';
if($num_rows > 0 ){
	while($row_routes						=	mysql_fetch_array($results)) {
		$allroutes[]						=	$row_routes[route];
	}
	$allroutesStr							=	implode("','",$allroutes);
	$route_sql								=	"select id,location,route_desc,route_code from route_master WHERE route_code in ('".$allroutesStr."')";
	$route									=	mysql_query($route_sql) or die(mysql_error());

	$query_routemas							=	"select * from routemasterplan where DSR_Code = '$DSR_Code'";
	$list_routemas							=	mysql_query($query_routemas) or die(mysql_error());
	$rowcnt_routemas						=	mysql_num_rows($list_routemas);	
	if($rowcnt_routemas > 0) {
		$row_routemas						=	mysql_fetch_array($list_routemas);
		$route_mon							=	$row_routemas['route_mon'];
		$route_tue							=	$row_routemas['route_tue'];
		$route_wed							=	$row_routemas['route_wed'];
		$route_thu							=	$row_routemas['route_thu'];
		$route_fri							=	$row_routemas['route_fri'];
		$route_sat							=	$row_routemas['route_sat'];

		if($route_sat != '' && (!is_null($route_sat))) {
			$saturdaychecked				=	1;
		}
		
		if($route_tue == $route_mon) {				
			$alldayscnt++;
		}
		if($route_wed == $route_mon) {
			$alldayscnt++;
		}
		if($route_wed == $route_tue) {
			$alldayscnt++;
		}
		if($route_thu == $route_mon) {
			$alldayscnt++;
		}
		if($route_thu == $route_tue) {
			$alldayscnt++;
		}
		if($route_thu == $route_wed) {
			$alldayscnt++;
		}
		if($route_fri == $route_mon) {
			$alldayscnt++;
		}
		if($route_fri == $route_tue) {
			$alldayscnt++;
		}
		if($route_fri == $route_wed) {
			$alldayscnt++;
		}
		if($route_fri == $route_thu) {
			$alldayscnt++;
		}
		if($route_sat == $route_mon) {
			$alldayscnt++;
		}
		if($route_sat == $route_tue) {
			$alldayscnt++;
		}
		if($route_sat == $route_wed) {
			$alldayscnt++;
		}
		if($route_sat == $route_thu) {
			$alldayscnt++;
		}
		if($route_sat == $route_fri) {
			$alldayscnt++;
		}
		if($alldayscnt > 0) {
			$repeatchecked			=	1;
		}

	}
/*$daysval = array();
$num_of_days = date('t');
for($i = 1; $i <= $num_of_days; $i++) {	
    $daysval[ltrim(date("d", strtotime('+'. $i .' days')),0)] = date("D", strtotime('+'. $i .' days'));
}

$list=array();
$num_of_days = date('t');
for($d=1; $d<=$num_of_days; $d++)
{
    $time=mktime(12, 0, 0, date('m'), $d, date('Y'));
    if (date('m', $time)==date('m'))
        $daysval[ltrim(date('d', $time),0)]		=	date('D', $time);
}

$num_of_days = date('t');    
for( $i=1; $i<= $num_of_days; $i++)
 $daysval[ltrim(str_pad($i,2,'0', STR_PAD_LEFT),0)]		=	date('Y') . "-". date('m'). "-". str_pad($i,2,'0', STR_PAD_LEFT); 
*/

//debugerr($daysval);
?>
<select class="location" name="route_mon" id="route_mon" style="width:100px" onChange="bringcustomers(this.value,'mon','route_mon');">
	<option value="">---Select---</option>
	<?php while($results = mysql_fetch_assoc($route)){?>
	<option value="<?php echo $results['route_code']; ?>" <?php if($route_mon == $results['route_code']) { echo "selected"; } ?> ><?php echo  $results['route_desc'] ?></option>
	<?php }	?> 
</select>
<?php echo "~"; ?>
<select class="location" name="route_tue" id="route_tue" style="width:100px" onChange="bringcustomers(this.value,'tue','route_tue');">
	<option value="">---Select---</option>
	<?php 
	$route_sql							=	"select id,location,route_desc,route_code from route_master WHERE route_code in ('".$allroutesStr."')";
	$route								=	mysql_query($route_sql) or die(mysql_error());
	while($results = mysql_fetch_assoc($route)){?>
	<option value="<?php echo $results['route_code']; ?>" <?php if($route_tue == $results['route_code']) { echo "selected"; } ?> ><?php echo  $results['route_desc'] ?></option>
	<?php }	?> 
</select>
<?php echo "~"; ?>
<select class="location" name="route_wed" id="route_wed" style="width:100px" onChange="bringcustomers(this.value,'wed','route_wed');">
	<option value="">---Select---</option>
	<?php 
	$route_sql							=	"select id,location,route_desc,route_code from route_master WHERE route_code in ('".$allroutesStr."')";
	$route								=	mysql_query($route_sql) or die(mysql_error());
	while($results = mysql_fetch_assoc($route)){?>
	<option value="<?php echo $results['route_code']; ?>" <?php if($route_wed == $results['route_code']) { echo "selected"; } ?> ><?php echo  $results['route_desc'] ?></option>
	<?php }	?> 
</select>
<?php echo "~"; ?>
<select class="location" name="route_thu" id="route_thu" style="width:100px" onChange="bringcustomers(this.value,'thu','route_thu');">
	<option value="">---Select---</option>
	<?php
	$route_sql							=	"select id,location,route_desc,route_code from route_master WHERE route_code in ('".$allroutesStr."')";
	$route								=	mysql_query($route_sql) or die(mysql_error());
	while($results = mysql_fetch_assoc($route)){?>
	<option value="<?php echo $results['route_code']; ?>" <?php if($route_thu == $results['route_code']) { echo "selected"; } ?> ><?php echo  $results['route_desc'] ?></option>
	<?php }	?> 
</select>
<?php echo "~"; ?>
<select class="location" name="route_fri" id="route_fri" style="width:100px" onChange="bringcustomers(this.value,'fri','route_fri');">
	<option value="">---Select---</option>
	<?php
	$route_sql							=	"select id,location,route_desc,route_code from route_master WHERE route_code in ('".$allroutesStr."')";
	$route								=	mysql_query($route_sql) or die(mysql_error());
	while($results = mysql_fetch_assoc($route)){?>
	<option value="<?php echo $results['route_code']; ?>" <?php if($route_fri == $results['route_code']) { echo "selected"; } ?> ><?php echo  $results['route_desc'] ?></option>
	<?php }	?> 
</select>
<?php echo "~"; ?>
<select class="location" name="route_sat" id="route_sat" style="width:100px" <?php if($saturdaychecked == 0) { ?> disabled <?php } ?> onChange="bringcustomers(this.value,'sat','route_sat');">		
	<option value="">---Select---</option>
	<?php
	$route_sql							=	"select id,location,route_desc,route_code from route_master WHERE route_code in ('".$allroutesStr."')";
	$route								=	mysql_query($route_sql) or die(mysql_error());
	while($results = mysql_fetch_assoc($route)){?>
	<option value="<?php echo $results['route_code']; ?>" <?php if($route_sat == $results['route_code']) { echo "selected"; } ?> ><?php echo  $results['route_desc'] ?></option>
	<?php }	?> 
</select>
<?php echo "~".$saturdaychecked."~".$repeatchecked."~".$route_mon."~".$route_tue."~".$route_wed."~".$route_thu."~".$route_fri."~".$route_sat; } else {
	echo "NOROUTE~"; ?>
<select class="location" name="route_mon" id="route_mon" style="width:100px" onChange="bringcustomers(this.value,'mon','route_mon');">
	<option value="">---Select---</option>
</select>
<?php echo "~"; ?>
<select class="location" name="route_tue" id="route_tue" style="width:100px" onChange="bringcustomers(this.value,'tue','route_tue');">
	<option value="">---Select---</option>
</select>
<?php echo "~"; ?>
<select class="location" name="route_wed" id="route_wed" style="width:100px" onChange="bringcustomers(this.value,'wed','route_wed');">
	<option value="">---Select---</option>
</select>
<?php echo "~"; ?>
<select class="location" name="route_thu" id="route_thu" style="width:100px" onChange="bringcustomers(this.value,'thu','route_thu');">
	<option value="">---Select---</option>
</select>
<?php echo "~"; ?>
<select class="location" name="route_fri" id="route_fri" style="width:100px" onChange="bringcustomers(this.value,'fri','route_fri');">
	<option value="">---Select---</option>
</select>
<?php echo "~"; ?>
<select class="location" name="route_sat" id="route_sat" style="width:100px" disabled onChange="bringcustomers(this.value,'sat','route_sat');">		
	<option value="">---Select---</option>
</select>
<?php } exit(0);?>