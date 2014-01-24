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
	$qry="SELECT *,srinc.id AS SRINCID, srinc.DSR_Code AS DSR_CodeVal FROM `srbrand_incentive` AS srinc LEFT JOIN dsr ON srinc.DSR_Code = dsr.DSR_Code WHERE dsr.DSRName like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *,srinc.id AS SRINCID, srinc.DSR_Code AS DSR_CodeVal FROM `srbrand_incentive` AS srinc LEFT JOIN dsr ON srinc.DSR_Code = dsr.DSR_Code";
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
	$orderby	=	"ORDER BY srinc.id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/
?>
<title>SR BRAND INCENTIVE TARGET</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>

<link rel="stylesheet" href="../css/edit.css" type="text/css"/>

<!-- <div class="con"> -->
<table width="100%" border="1" style="border-collapse:collapse;">
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
	<th style="width:18%" class="rounded" onClick="srincviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');" >DSR Name </th>
	<th style="width:18%" align="center">Month & Year</th>
	<th style="width:35%" align="center" nowrap="nowrap">Brand Name</th>
	<th style="width:5%" align="center" nowrap="nowrap">Target Units</th>
	<th style="width:5%" align="center" nowrap="nowrap">Target Naira</th>
	<th style="width:18%" align="center" nowrap="nowrap">Date</th>
</tr>
</thead>
<tbody>
<?php
if(!empty($num_rows)){
$c=0;$cc=1;
while($fetch = mysql_fetch_array($results_dsr)) {
	if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
	$id= $fetch['SRINCID'];
	$DSRName					=	getdbval($fetch[DSR_CodeVal],'DSRName','DSR_Code','dsr');
	$BrandName					=	getdbval($fetch['Brand_id'],'brand','id','brand');
	?>
	<tr>
		<td align="left"><?php echo $DSRName; ?></td>
		<td align="left"><?php echo date('F', mktime(0, 0, 0, $fetch[monthval])). " & " .$fetch[yearval]; ?></td>
		<td align="left"><?php echo $BrandName; ?></td>
		<td align="right"><?php echo number_format($fetch[target_units]); ?></td>
		<td align="right"><?php echo number_format($fetch[target_naira],2); ?></td>
		<td align="left"><?php echo trim($fetch[insertdatetime],' '); ?></td>		
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
<!--  </div>    -->
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