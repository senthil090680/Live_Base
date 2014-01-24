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
if($_REQUEST['SR_Name']!='')
{
	$var = @$_REQUEST['SR_Name'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `sr` where SR_Name like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *  FROM `sr`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$SR_Name."&".$sortorder."&".$ordercol;

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

function srsearch(page){  // For pagination and sorting of the SR search in view page
	var SR_Name=$("input[name='SR_Name']").val();
	$.ajax({
		url : "srajax.php",
		type: "get",
		dataType: "text",
		data : { "SR_Name" : SR_Name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#rsmid").html(trimval);
		}
	});
}

function srviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam		=	params.split("&");
	var SR_Name         =	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "srajax.php",
		type: "get",
		dataType: "text",
		data : { "SR_Name" : SR_Name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#srid").html(trimval);
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
		    	$paramsval	=	$SR_Name."&".$sortorderby."&SR_Name"; ?>
            
<th nowrap="nowrap" class="rounded" onClick="srviewajax('<?php echo $Page;?>','<?php echo $paramsval; ?>');">Name<img src="../images/sort.png" width="13" height="13" /></th>
            <th>Code</th>
		    <th>Email Id</th>
            <th>Contact Number</th>
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
            <td><?php echo $fetch['SR_Name'];?></td>
            <td><?php echo $fetch['SR_Code'];?></td>
            <td><?php echo $fetch['email_id'];?></td>
            <td><?php echo $fetch['Contact_Number'];?></td>
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
				
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'srviewajax');   //need to uncomment
				
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
		  </div>
