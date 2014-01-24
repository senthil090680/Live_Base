<?php
session_start();
ob_start();
require_once('../include/header.php');
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
if($_REQUEST['UOM2']!='')
{
	$var = @$_REQUEST['UOM2'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `uom2` where UOM2 like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *  FROM `uom2`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$UOM2."&".$sortorder."&".$ordercol;

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

function uomsearch(page){  // For pagination and sorting of the SR search in view page
	var UOM2=$("input[name='UOM2']").val();
	$.ajax({
		url : "uomajax.php",
		type: "get",
		dataType: "text",
		data : { "UOM2" : UOM2, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#uomid").html(trimval);
		}
	});
}

function uomviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam		=	params.split("&");
	var UOM2         =	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "uomajax.php",
		type: "get",
		dataType: "text",
		data : { "UOM2" : UOM2, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
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
<div id="mainarea">
<div class="mcf"></div>
<div><h2 align="center">UOM2</h2></div> 
<div class="clearfix"></div>
<span style="float:left;padding-left:200px;">&nbsp;&nbsp;&nbsp;<input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>
<!--<span style="float:left;">&nbsp;&nbsp;<input type="button" value="Back" class="buttons" onclick="window.location='dsr.php'"></span>-->
 <div id="search" style="padding-right:200px">
        <input type="text" name="UOM2" value="<?php echo $_REQUEST['UOM2']; ?>" autocomplete='off' placeholder='Search By UOM2'/>
        <input type="button" class="buttonsg" onclick="uomsearch('<?php echo $Page; ?>');" value="GO"/>
 </div>
 <div class="clearfix"></div>
			<div id="uomid">
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
		    	$paramsval	=	$UOM2."&".$sortorderby."&UOM2"; ?>
            
<th nowrap="nowrap" class="rounded" onClick="uomviewajax('<?php echo $Page;?>','<?php echo $paramsval; ?>');">UOM2<img src="../images/sort.png" width="13" height="13" /></th>
            <th>UOM2 Description</th>
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
            <td><?php echo $fetch['UOM2'];?></td>
            <td><?php echo $fetch['UOM2_Description'];?></td>
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
	 </div>
  
   <div class="mcf"></div>
    <?php include("../include/error.php"); ?>
</div>
<?php require_once('../include/footer.php');?>