<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_REQUEST);
$params=$DateVal."&".$DSR_Code;
if(isset($_REQUEST[DateVal]) && $_REQUEST[DateVal] !='') {
	$nextrecval		=	"WHERE Date LIKE '$DateVal%' AND DSR_Code = '$DSR_Code'";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_REQUEST) && $_REQUEST !='')
{
	$qry="SELECT * FROM `sale_and_collection` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr=mysql_query($qry);
$num_rows= mysql_num_rows($results_dsr);
//exit;

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
$qry.="  LIMIT $Page_Start , $Per_Page";
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

$findvehdevqry					=	$qry;
$results_findvehdev				=	mysql_query($findvehdevqry);
$rowcnt_findvehdev				=	mysql_num_rows($results_findvehdev);
if($rowcnt_findvehdev > 0){
	$row_findvehdev				=	mysql_fetch_array($results_findvehdev);
	$vehicle_codeval			=	$row_findvehdev['Vehicle_Code'];
	$Device_Codeval				=	$row_findvehdev['device_code'];
	$vehicle_name				=	getvehicleval($vehicle_codeval,'vehicle_desc','vehicle_code');
}
?>
<title>SALES & COLLECTION</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<link href="../css/edit.css"  rel="stylesheet" type="text/css" />
<link href="../css/popup.css" rel="stylesheet" type="text/css" />
<style type"text/css">
.condaily_veh{
	width:100%;
	text-align:left;
	height:250px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}
.condaily_veh th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}

.condaily_veh td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	padding-left:10px;
	color:#000;
	font-size:14px;
}

.condaily_veh tbody tr:hover td {
	background: #c1c1c1;
}
.headingsgrdaily_veh{
	background:#a09e9e;
	width:100;
	margin-left:auto;
	margin-right:auto;
	height:25px;
	padding-top:5px;
	border-radius:6px;
	font-weight:bold;
	font-size:14px;
}

#headingsgrdaily_veh{
	background:#fff;
	width:100%;
	margin-left:auto;
	margin-right:auto;
	height:330px;
}
</style>


<table width="100%" align="left" id="productsadd">
 <tr>
  <td>
  <!--<div <?php if($num_rows == 0) { ?> class="condailynorec" <?php } else { ?> class="condaily" <?php } ?> >-->
  <table width="100%" border="1" style="border-collapse:collapse">
  <thead>
	<tr>		
		<th class='rounded' align='center'>Total Sales Value</th>
		<th align='center'>Total Collection Value</th>
		<th align='center'>Balance Due</th>
	</tr>
</thead>  
  <tbody id="productsadded">
	<?php $t = 1; 
	//if(isset($_GET[DateVal]) && $_GET[DateVal] != '') { 

	if($num_rows >0) {
		while($row_salcolval				=	mysql_fetch_array($results_dsr)) {
			$currency						=	$row_salcolval['currency'];
			$total_sale_value				=	$row_salcolval['total_sale_value'];
			$total_collection_value			=	$row_salcolval['total_collection_value'];
			$device_code					=	$row_salcolval['device_code'];
			$Vehicle_Code					=	$row_salcolval['Vehicle_Code'];
			$BalDue	=	($total_sale_value) - ($total_collection_value);
	?> 
		<tr>
			<td align='right'><?php echo number_format($total_sale_value,2); ?></td>
			<td align='right'><?php echo number_format($total_collection_value,2); ?></td>
			<td align='right'><?php echo number_format($BalDue,2); ?></td>
		</tr>
	<?php } // WHILE LOOP
	} // IF LOOP
	else { ?>
		<tr>
			<td align='center' colspan="3">No Records Found.</td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>		
		</tr>
		<?php } ?>
  </tbody>
   </table>
     </div>
   </td>
 </tr>
</table>

<div class="paginationfile" align="center">
 <table>
 <tr>
 <th class="pagination" scope="col">          
<?php 
if(!empty($num_rows)){ 
	//rend_vehstockajaxpagination($Num_Pages,$Page,$Prev_Page,$Next_Page,$params);
} else {
	echo "&nbsp;";
}
?>
</th>
</tr>
</table>
<!--</div>-->
<div id="errormsgcus" style="display:none;"><h3 align="center" class="myalign"></h3><button id="closebutton">Close</button></div>
</div>
<div style="clear:both"></div>
<span id="printopen" style="padding-left:580px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0);?>