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
	$qry="SELECT *,dre.AUDIT_DATE_TIME AS DevRegDate FROM `device_registration` AS dre LEFT JOIN cycle_assignment cyas ON cyas.device_code = dre.device_code LEFT JOIN dsr ON cyas.dsr_id = dsr.id WHERE dsr.DSRName like '%".$trimmed."%' GROUP BY dre.device_code";
}
else
{ 
	$qry="SELECT *,dre.AUDIT_DATE_TIME AS DevRegDate FROM `device_registration` AS dre LEFT JOIN cycle_assignment cyas ON cyas.device_code = dre.device_code LEFT JOIN dsr ON cyas.dsr_id = dsr.id GROUP BY dre.device_code";
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
		<th align="center">Device Id</th>
		<th align="center">Device Name</th>
		<th align="center" nowrap="nowrap">Device Serial No.</th>
		<th align="center">SR/DSR Code</th>
		<th class="rounded" onClick="devstatusajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');" >SR/DSR Name <img src="../images/sort.png" width="13" height="13" /></th>
		<th align="center" nowrap="nowrap">Reg. Date</th>
		<th align="center" >ASM</th>
		<th align="center" >RSM</th>
		<th align="center">Branch</th>
		<!-- <th align="right">Mod/Del</th> -->
	</tr>
	</thead>
	<tbody>
	<?php
	if(!empty($num_rows)){
	$c=0;$cc=1;
	while($fetch = mysql_fetch_array($results_dsr)) {
	if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
	
	$query_dsr					=	"SELECT DSRName,ASM,RSM FROM dsr WHERE id = '$fetch[dsr_id]'";			
	$res_dsr					=	mysql_query($query_dsr) or die(mysql_error());
	$row_dsr					=	mysql_fetch_array($res_dsr);
	$DSRName					=	upperstate($row_dsr['DSRName']);
	$ASM_id						=	$row_dsr['ASM'];
	$RSM_id						=	$row_dsr['RSM'];

	$ASMName					=	upperstate(getdbval($ASM_id,'DSRName','id','asm_sp'));

	$query_rsm					=	"SELECT DSRName,branch_id FROM rsm_sp WHERE id = '$RSM_id'";			
	$res_rsm					=	mysql_query($query_rsm) or die(mysql_error());
	$row_rsm					=	mysql_fetch_array($res_rsm);
	$RSMName					=	upperstate($row_rsm['DSRName']);
	$branch_id					=	$row_rsm['branch_id'];

	$branchName					=	upperstate(getdbval($branch_id,'branch','id','branch'));

	$query_devid			=	"SELECT device_description FROM device_master WHERE id = '$fetch[device_id]'";			
	$res_devid					=	mysql_query($query_devid) or die(mysql_error());
	$row_devid					=	mysql_fetch_array($res_devid);
	$device_description			=	$row_devid['device_description'];
	?>
	<tr>
		<td align="center"><?php echo $fetch[device_id];?></td>
		<td align="center"><?php echo $device_description;?></td>
		<td align="center"><?php echo $fetch[device_serial_number];?></td>
		<td align="center"><?php echo $fetch[dsr_code];?></td>
		<td align="center"><?php echo $DSRName;?></td>
		<td align="center"><?php $date_explode	=	explode(" ",$fetch[DevRegDate]);
		echo $date_explode[0]; ?></td>
		<td align="center"><?php echo $ASMName; ?></td>
		<td align="center" nowrap="nowrap"><?php echo $RSMName; ?></td>
		<td align="center" nowrap="nowrap"><?php echo $branchName; ?></td>
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
		rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'devstatusajax');   //need to uncomment
	} else { 
		echo "&nbsp;"; 
	} ?>      
	</th>
	</tr>
	</table>
  </div>
  <span id="printopen" style="padding-left:380px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printdevstaajax');"></span>
	<form id="printdevstaajax" target="_blank" action="printdevstaajax.php" method="post">
		<input type="hidden" name="DSR_name" id="DSR_name" value="<?php echo $DSR_name; ?>" />
		<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
		<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
		<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
	</form>
<?php exit(0); ?>