<?php
session_start();
ob_start();
require_once('../include/config.php');
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

error_reporting(E_ALL && ~ E_NOTICE);
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_REQUEST);
$id=$_REQUEST['id'];
if($_REQUEST['DSR_name']!='')
{
	$var = @$_REQUEST['DSR_name'] ;
	$trimmed = trim($var);	
	$qry="SELECT *,cyas.id AS CYASID, cyas.Date AS DateCycle FROM `cycle_assignment` AS cyas LEFT JOIN dsr ON cyas.dsr_id = dsr.id WHERE dsr.DSRName like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *,cyas.id AS CYASID, cyas.Date AS DateCycle FROM `cycle_assignment` AS cyas LEFT JOIN dsr ON cyas.dsr_id = dsr.id";
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);

$params			=	$DSR_name."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 5;   // Records Per Page

$Page = $strPage;
if(!$strPage)
{
	$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($num_rows<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($num_rows % $Per_Page)==0)
{
	$Num_Pages =($num_rows/$Per_Page) ;
}
else
{
	$Num_Pages =($num_rows/$Per_Page)+1;
	$Num_Pages = (int)$Num_Pages;
}
if($sortorder == "")
{
	$orderby	=	"ORDER BY cyas.id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.=" $orderby";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/
?>
<title>CYCLE ASSIGNMENT</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>

<link rel="stylesheet" href="../css/edit.css" type="text/css"/>

<table width="100%" border="1" style="border-collapse:collapse">
<thead>
<tr>
	<?php //echo $sortorderby;
	if($sortorder == 'ASC') {
		$sortorderby = 'DESC';
	} elseif($sortorder == 'DESC') {
		$sortorderby = 'ASC';
	} else {
		$sortorderby = 'DESC';
	}
	$paramsval	=	$DSR_name."&".$sortorderby."&dsr.DSRName"; ?>
	<th class="rounded" onClick="cyasviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');" >SR Name <img src="../images/sort.png" width="13" height="13" /></th>
	<th>Device</th>
	<th>Route</th>
	<th>Location</th>
	<th>Veh Reg No</th>
	<th nowrap="nowrap">Cycle Start flag</th>
	<th nowrap="nowrap">Cycle End flag</th>
	<th nowrap="nowrap">Date</th>
</tr>
</thead>
<tbody>
<?php
if(!empty($num_rows)){
$c=0;$cc=1;
while($fetch = mysql_fetch_array($results_dsr)) {
if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
$id= $fetch['CYASID'];

$query_dsrid				=	"SELECT DSRName FROM dsr WHERE id = '$fetch[dsr_id]'";			
$res_dsrid					=	mysql_query($query_dsrid) or die(mysql_error());
$row_dsrid					=	mysql_fetch_array($res_dsrid);
$DSRName					=	$row_dsrid['DSRName'];

$query_devid				=	"SELECT device_description FROM device_master WHERE id = '$fetch[device_id]'";			
$res_devid					=	mysql_query($query_devid) or die(mysql_error());
$row_devid					=	mysql_fetch_array($res_devid);
$device_description			=	$row_devid['device_description'];

$query_routeid				=	"SELECT route_desc FROM route_master WHERE id = '$fetch[route_id]'";			
$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
$row_routeid				=	mysql_fetch_array($res_routeid);
$route_desc					=	$row_routeid['route_desc'];

$query_locid				=	"SELECT location FROM location WHERE id = '$fetch[location_id]'";			
$res_locid					=	mysql_query($query_locid) or die(mysql_error());
$row_locid					=	mysql_fetch_array($res_locid);
$location						=	$row_locid['location'];

$query_vehid				=	"SELECT vehicle_reg_no FROM vehicle_master WHERE id = '$fetch[vehicle_id]'";			
$res_vehid					=	mysql_query($query_vehid) or die(mysql_error());
$row_vehid					=	mysql_fetch_array($res_vehid);
$vehicle_reg_no				=	$row_vehid['vehicle_reg_no'];


?>
<tr>
	<td align="center"><?php echo $DSRName;?></td>
	<td align="center"><?php echo $device_description;?></td>
	<td align="center"><?php echo $route_desc;?></td>
	<td align="center"><?php echo $location;?></td>
	<td align="center"><?php echo $vehicle_reg_no;?></td>
	<td align="center"><?php if($fetch['flag_status'] == 1) { echo 'Yes'; } else { echo 'No'; } ?></td>
	<td align="center"><?php if($fetch['end_flag_status'] == 1) { echo 'Yes'; } else { echo 'No'; } ?></td>
	<td align="center" nowrap="nowrap"><?php echo $fetch['DateCycle'];?></td>
</tr>
<?php $c++; $cc++; }		 
}else { ?>
	<tr>
		<td align='center' colspan='9'><b>No records found</b></td>
		<td style="display:none;" >Cust Name</td>
		<td style="display:none;" >Add Line1</td>
		<td style="display:none;" >Add Line2</td>
		<td style="display:none;" >Cust Name</td>
		<td style="display:none;" >Add Line1</td>
		<td style="display:none;" >Add Line1</td>
		<td style="display:none;" >Add Line1</td>
	</tr>
<?php }  ?>
</tbody>
</table>
 <div class="paginationfile" align="center">
 <table>
 <tr>
 <th class="pagination" scope="col">          
<?php 
if(!empty($num_rows)){	
	//rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'cyasviewajax');   //need to uncomment
} else { 
	echo "&nbsp;"; 
} ?>      
</th>
</tr>
</table>
</div>
<span id="printopen" style="padding-left:580px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0); ?>