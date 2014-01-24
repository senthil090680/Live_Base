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
if($_REQUEST['DSRName']!='')
{
	$var = @$_REQUEST['DSRName'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `asm_sp` where DSRName like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *  FROM `asm_sp`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$DSRName."&".$sortorder."&".$ordercol;

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

function asmsearch(page){  // For pagination and sorting of the SR search in view page
	var DSRName=$("input[name='DSRName']").val();
	$.ajax({
		url : "asmajax.php",
		type: "get",
		dataType: "text",
		data : { "DSRName" : DSRName, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#asmid").html(trimval);
		}
	});
}

function asmviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam		=	params.split("&");
	var DSRName         =	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "asmajax.php",
		type: "get",
		dataType: "text",
		data : { "DSRName" : DSRName, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#asmid").html(trimval);
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
		    	$paramsval	=	$DSRName."&".$sortorderby."&DSRName"; ?>
            
<th nowrap="nowrap" class="rounded" onClick="asmviewajax('<?php echo $Page;?>','<?php echo $paramsval; ?>');">Name<img src="../images/sort.png" width="13" height="13" /></th>
            <th>Code</th>
		    <th>Email Id</th>
            <th>Contact Number</th>
            <th>RSM</th>
 			</tr>
			</thead>
			<tbody>
			<?php
            if(!empty($num_rows)){
            $c=0;$cc=1;
            while($fetch = mysql_fetch_array($results_dsr)) {
            if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
            $id= $fetch['id'];
			$RSM= $fetch['RSM'];
            ?>
            <tr>
            <tr>
            <td><?php echo $fetch['DSRName'];?></td>
            <td><?php echo $fetch['DSR_Code'];?></td>
            <td><?php echo $fetch['email_id'];?></td>
            <td><?php echo $fetch['Contact_Number'];?></td>
            <td><?php 
            $rsmname=mysql_query("select * from rsm_sp where id= '$RSM'"); 
            $row=mysql_fetch_array($rsmname);
            $rsmid=$row['id'];
            $rsmnam=$row['DSRName'];
            if($RSM = $rsmnam){echo $rsmnam;}?>
            </td>
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
				
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'asmviewajax');   //need to uncomment
				
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
		  </div>
