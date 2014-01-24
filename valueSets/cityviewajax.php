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
if($_REQUEST['city']!='')
{
	$var = @$_REQUEST['city'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `city` where city like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *  FROM `city`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$city."&".$sortorder."&".$ordercol;
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

function cityviewajaxsearch(page){  // For pagination and sorting of the SR search in view page
	var city	=	$("input[name='city']").val();
	$.ajax({
		url : "cityviewajax.php",
		type: "get",
		dataType: "text",
		data : { "city" : city, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#cityid").html(trimval);
		}
	});
}

function cityviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam		=	params.split("&");
	var city	        =	splitparam[0];
	var state_id	    =	splitparam[1];
	var sortorder		=	splitparam[2];
	var ordercol		=	splitparam[3];
	$.ajax({
		url : "cityviewajax.php",
		type: "get",
		dataType: "text",
		data : { "city" : city, "state_id",state_id,"sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#cityid").html(trimval);
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
				$paramsval	=	$city."&".$sortorderby."&city";
				?>
				<th nowrap="nowrap" class="rounded" onClick="cityviewajax('<?php echo $Page;?>','<?php echo $paramsval; ?>');">City<img src="../images/sort.png" width="13" height="13" /></th>
				<th nowrap="nowrap">State</th>
 			</tr>
			</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($num_rows)){
			$c=0;$cc=1;
			while($fetch = mysql_fetch_array($results_dsr)) {
			if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
			$state_id= $fetch['state_id'];
			?>
			<tr>
			<td><?php echo $fetch['city'];?></td>
            <td>
			<?php
            $sql=mysql_query("select * from state where id = '$state_id'");
            $rowp = mysql_fetch_array($sql);
            $ids=$rowp['id'];
            $stat=$rowp['state'];
            
            if($state_id = $ids) {
            echo $stat;
            }
            ?>
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
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'cityviewajax');   //need to uncomment
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
             <br />
        <input type="button" name="close" class="buttons" value="Close" onclick="window.location='../include/empty.php'"/>
  </div>
