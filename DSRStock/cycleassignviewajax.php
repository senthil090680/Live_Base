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

$Per_Page = 12;   // Records Per Page

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
$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/
?>
	<div class="con">
	<table width="100%">
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
		<th align="center" class="rounded" onClick="cyasviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');" >SR Name <img src="../images/sort.png" width="13" height="13" /></th>
		<th align="center">Device</th>
		<th align="center">Route</th>
		<th align="center">Location</th>
		<th align="center">Veh Reg No</th>
		<th align="center" nowrap="nowrap">Cycle Start flag</th>
		<th align="center" nowrap="nowrap">Cycle End flag</th>
		<th align="center" nowrap="nowrap">Date</th>
		<th align="right">Del</th>
		<!-- <th align="right">Mod/Del</th> -->
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
		<td align="right" nowrap="nowrap">
		<!-- <a href="../DSRStock/cycle_assignment.php?id=<?php echo $fetch['CYASID'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp; -->

		<?php $curdate		=	date('Y-m-d');
				//$curdate		=	"2013-08-22";
				$datearr		=	explode(' ',$fetch[DateCycle]);
			
				if($datearr[0] == $curdate) { 
			?>
		<a href="../DSRStock/cycle_assignment.php?id=<?php echo $fetch['CYASID'];?>&del=del"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>

		<?php } else {  echo "No Edit"; } ?>

		</td>
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
	 </div>   
	 <div class="paginationfile" align="center">
	 <table>
	 <tr>
	 <th class="pagination" scope="col">          
	<?php 
	if(!empty($num_rows)){	
		rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'cyasviewajax');   //need to uncomment
	} else { 
		echo "&nbsp;"; 
	} ?>      
	</th>
	</tr>
	</table>
  </div>
  <span id="printopen" style="padding-left:500px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printcyasajax');"></span>
	<form id="printcyasajax" target="_blank" action="printcyasajax.php" method="post">
		<input type="hidden" name="DSR_name" id="DSR_name" value="<?php echo $DSR_name; ?>" />
		<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
		<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
		<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
	</form>
<?php exit(0); ?>