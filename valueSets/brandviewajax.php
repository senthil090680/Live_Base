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
if($_REQUEST['brand']!='')
{
	$var = @$_REQUEST['brand'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `brand` where brand like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *  FROM `brand`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$brand."&".$sortorder."&".$ordercol;

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

function brandviewajaxsearch(page){  // For pagination and sorting of the SR search in view page
	var brand	=	$("input[name='brand']").val();
	$.ajax({
		url : "brandviewajax.php",
		type: "get",
		dataType: "text",
		data : { "brand" : brand, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#brandid").html(trimval);
		}
	});
}

function brandviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam		=	params.split("&");
	var brand	        =	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "brandviewajax.php",
		type: "get",
		dataType: "text",
		data : { "brand" : brand, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#brandid").html(trimval);
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
				$paramsval	=	$brand."&".$sortorderby."&brand"; ?>
				<th nowrap="nowrap" class="rounded" onClick="brandviewajax('<?php echo $Page;?>','<?php echo $paramsval; ?>');">Brand<img src="../images/sort.png" width="13" height="13" /></th>
			 <th>Principal</th>	
             <th>Product Category</th>		
 			</tr>
			</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($num_rows)){
			$c=0;$cc=1;
			while($fetch = mysql_fetch_array($results_dsr)) {
			if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
			$principal= $fetch['principal'];
			$product_category= $fetch['product_category'];
			?>
            <tr>
            <td><?php echo $fetch['brand'];?></td>
            <td>
            <?php
            $princi=mysql_query("select * from  principal where id='$principal'");
            $row=mysql_fetch_array($princi);
            $princid=$row['id'];
            $princv=$row['principal'];
            if($principal=$princv){echo $princv;}?>
            </td>
             <td>
            <?php
            $pro=mysql_query("select * from  product_category where id='$product_category'");
            $row=mysql_fetch_array($pro);
            $proid=$row['id'];
            $procv=$row['product_category'];
            if($product_category=$procv){echo $procv;}?>
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
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'brandviewajax');   //need to uncomment
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
            <br />
        <input type="button" name="close" class="buttons" value="Close" onclick="window.location='../include/empty.php'"/>
  </div>
