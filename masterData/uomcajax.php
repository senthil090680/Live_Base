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
//echo $id;
//exit;
if($_REQUEST['uom2']!='')
{
	$var = @$_REQUEST['uom2'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `uom_conversion` where uom2 like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *  FROM `uom_conversion`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$uom2."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];

$Per_Page = 8;   // Records Per Page

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
	$orderby	=	"ORDER BY id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>

<script type="text/javascript">

function uomcsearch(page){  // For pagination and sorting of the SR search in view page
	var uom2=$("input[name='uom2']").val();
	$.ajax({
		url : "uomcajax.php",
		type: "get",
		dataType: "text",
		data : { "uom2" : uom2, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#uomid").html(trimval);
		}
	});
}

function uomviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam		=	params.split("&");
	var uom2            =	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "uomcajax.php",
		type: "get",
		dataType: "text",
		data : { "uom2" : uom2, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#uomid").html(trimval);
		}
	});
}
</script>
<style type="text/css">
#containerpr {
	padding:0px;
	width:80%;
	margin-left:auto;
	margin-right:auto;
}


.con {
	width:60%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}
.con th,.con2 th,.con3 th {
	width:22%;
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:13px;
	color:#000;
}
.con td,.con2 td,.con3 td  {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	color: #000;
	font-size:13px;
}
.con tbody tr:hover td,.con2 tbody tr:hover td,.con3 tbody tr:hover td {
	background: #c1c1c1;
}
</style>
		<div class="con" width="100%">
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
		    	$paramsval	=	$uom2."&".$sortorderby."&uom2"; ?>
            
            <th>UOM1</th>
<th nowrap="nowrap" class="rounded" onClick="uomviewajax('<?php echo $Page;?>','<?php echo $paramsval; ?>');">UOM2<img src="../images/sort.png" width="13" height="13" /></th>
            <th>UOM Conversion<br />UOM2 - UOM1</th>
			</tr>
			</thead>
			<tbody>
			<?php
            if(!empty($num_rows)){
            $c=0;$cc=1;
            while($fetch = mysql_fetch_array($results_dsr)) {
            if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
            $id= $fetch['id'];
			$kd_category= $fetch['kd_category'];
            ?>
            <tr>
            <tr>
            <td><?php echo $fetch['uom'];?></td>
            <td><?php echo $fetch['uom2'];?></td>
            <td><?php echo $fetch['uom_conversion'];?></td>
            </tr>
			<?php $c++; $cc++; $slno++; }		 
			}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
			</tbody>
			</table>
			 </div>   
			 <div class="paginationfile" align="center">
			 <table>
			 <tr>
			 <th class="pagination" scope="col">          
			<?php 
			if(!empty($num_rows)){
				
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'uomviewajax');   //need to uncomment
				
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
		  </div>
