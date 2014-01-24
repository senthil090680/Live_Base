<?php
session_start();
ob_start();
require_once('../include/config.php');
require_once "../include/ps_pagination.php";
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
	$qry="SELECT *,rpl.id AS RPLID FROM `routemonthplan` rpl LEFT JOIN dsr ON rpl.DSR_Code = dsr.DSR_Code where DSRName like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *,rpl.id AS RPLID FROM `routemonthplan` rpl LEFT JOIN dsr ON rpl.DSR_Code = dsr.DSR_Code";
}
$results	=	mysql_query($qry) or die(mysql_error());
//$pager		=	new PS_Pagination($bd, $qry,5,5);
//$results	=	$pager->paginate();
$num_rows	=	mysql_num_rows($results);	

$params			=	$DSR_name."&".$sortorder."&".$ordercol;

$curmonthnum		=	ltrim(date('m'),0);
$curyear			=	date('Y');
$list=array();
	$num_of_days = date('t');
	for($d=1; $d<=$num_of_days; $d++)
	{
		$time=mktime(12, 0, 0, date('m'), $d, date('Y'));
		if (date('m', $time)==date('m'))
			$daysval[ltrim(date('d', $time),0)]		=	date('D', $time);
	}

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
	$orderby	=	"ORDER BY rpl.id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry			.=	" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//echo $qry;
//exit;
$results_dsr	=	mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>
	<div class="con">
	<table id="sort" class="tablesorter" width="100%">
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
		<th nowrap="nowrap" onClick="routemonthviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');">DSR Name <img src="../images/sort.png" width="13" height="13" /></th>
		<th>Monday</th>
		<th>Tuesday</th>
		<th>Wednesday</th>
		<th>Thursday</th>
		<th>Friday</th>
		<th>Saturday</th>
		<th nowrap="nowrap">Month</th>
		<th nowrap="nowrap">Year</th>
		<th>Edit</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if(!empty($num_rows)){
	$c=0;$cc=1;
	while($fetch = mysql_fetch_array($results_dsr)) {
	if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
	$id= $fetch['RPLID'];

	/*
		$query_dsrid				=	"SELECT DSRName FROM dsr WHERE DSR_Code = '$fetch[DSR_Code]'";			
		$res_dsrid					=	mysql_query($query_dsrid) or die(mysql_error());
		$row_dsrid					=	mysql_fetch_array($res_dsrid);
	*/
	$DSRName					=	$fetch['DSRName'];

	$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_mon]'";			
	$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
	$row_routeid				=	mysql_fetch_array($res_routeid);
	$route_mon					=	$row_routeid['route_desc'];

	$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_tue]'";			
	$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
	$row_routeid				=	mysql_fetch_array($res_routeid);
	$route_tue					=	$row_routeid['route_desc'];

	$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_wed]'";			
	$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
	$row_routeid				=	mysql_fetch_array($res_routeid);
	$route_wed					=	$row_routeid['route_desc'];

	$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_thu]'";			
	$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
	$row_routeid				=	mysql_fetch_array($res_routeid);
	$route_thu					=	$row_routeid['route_desc'];

	$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_fri]'";			
	$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
	$row_routeid				=	mysql_fetch_array($res_routeid);
	$route_fri					=	$row_routeid['route_desc'];

	$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_sat]'";			
	$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
	$row_routeid				=	mysql_fetch_array($res_routeid);
	$route_sat					=	$row_routeid['route_desc'];


	//echo $fetch['routemonth'];
	?>
	<tr>
	<td><?php echo $DSRName;?></td>
	<td><?php echo $route_mon;?></td>
	<td><?php echo $route_tue;?></td>
	<td><?php echo $route_wed;?></td>
	<td><?php echo $route_thu;?></td>
	<td><?php echo $route_fri; ?></td>
	<td><?php echo $route_sat; ?></td>
	<td nowrap="nowrap"><?php echo date('F',mktime(0,0,0,$fetch['routemonth'],10));?></td>
	<td nowrap="nowrap"><?php echo $fetch['routeyear'];?></td>
	<td align="right" nowrap="nowrap">
	
	<?php if($curmonthnum == $fetch[routemonth] && $curyear == $fetch[routeyear]) { ?>
	<a href="../BaseMaster/routemonthlyplan.php?idvalnum=<?php echo $fetch['RPLID'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php } else { echo "No Edit"; } ?>
	</td>
	</tr>
	<?php $c++; $cc++; }		 
	}else { ?>
		<tr>
			<td align='center' colspan='13'><b>No records found</b></td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
			<td style="display:none;" >Add Line2</td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
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
		rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'routemonthviewajax');   //NEED TO UNCOMMENT
	} else	{ 
		echo "&nbsp;"; 
	} ?>      
	</th>
	</tr>
	</table>
  </div>