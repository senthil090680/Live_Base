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
if($_REQUEST['Scheme_code']!='')
{
	$var = @$_REQUEST['Scheme_code'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `product_scheme_master` where Scheme_code like '%".$trimmed."%'";
}
else
{ 
	$qry = "SELECT Scheme_code,Scheme_Description,Effective_from,Effective_to,rebate,rebateunits,rebatevalue,Header_Product_code,line_Product_Code,SchemeType,
			GROUP_CONCAT(Header_Product_description1 SEPARATOR '<br>') AS Header_Product_description1,
			GROUP_CONCAT(DISTINCT Header_Product_code  SEPARATOR '<br>') AS Header_Product_code,
	       
			GROUP_CONCAT(Header_Quantity SEPARATOR '<br>') AS Header_Quantity,
			GROUP_CONCAT(line_Product_Name SEPARATOR '<br>') AS line_Product_Name,
			GROUP_CONCAT(DISTINCT line_Product_Code SEPARATOR '<br>') AS line_Product_Code,
			GROUP_CONCAT(DISTINCT line_Product_quantity SEPARATOR '<br>') AS line_Product_quantity
			FROM product_scheme_master GROUP BY Scheme_Description";
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$Scheme_code."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 6;   // Records Per Page

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

function productscviewajaxsearch(page){  // For pagination and sorting of the SR search in view page
	var Scheme_code=$("input[name='Scheme_code']").val();
	$.ajax({
		url : "productscviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Scheme_code" : Scheme_code, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#productscid").html(trimval);
		}
	});
}

function productscviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam		=	params.split("&");
	var Scheme_code    =	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "productscviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Scheme_code" : Scheme_code, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#productscid").html(trimval);
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
				$paramsval	=	$Scheme_code."&".$sortorderby."&Scheme_code"; ?>
		<th>&nbsp;Scheme Description</th>
		<th nowrap="nowrap" class="rounded" onClick="productscviewajax('<?php echo $Page;?>','<?php echo $paramsval; ?>');">Scheme Code<img src="../images/sort.png" width="13" height="13" /></th>
        <th nowrap="nowrap">&nbsp;Header<br /> ProductCode</th>
       	<th nowrap="nowrap">&nbsp;Header<br /> Quantity</th>
        <th nowrap="nowrap">Scheme Type</th>
  		<th nowrap="nowrap">&nbsp;&nbsp;Line<br />  ProductCode</th>
        <th nowrap="nowrap">&nbsp;Line<br /> Quantity</th>
        <th nowrap="nowrap">Effective From</th>
        <th nowrap="nowrap">Effective To</th>
        <th nowrap="nowrap">Rebate</th>
        <th nowrap="nowrap">Rebate Units</th>
        <th nowrap="nowrap">Rebate Value</th>
        </tr>
		</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($num_rows)){
			$c=0;$cc=1;
			while($fetch = mysql_fetch_array($results_dsr)) {
			if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
			?>
            <tr>
            <td><?php echo $fetch['Scheme_Description'];?></td>
            <td><?php echo $fetch['Scheme_code'];?></td>
            <td><?php echo $fetch['Header_Product_code'];?></td>
            <td><?php echo $fetch['Header_Quantity'];?></td>
            <td><?php echo $fetch['SchemeType'];?></td>
            <td><?php echo $fetch['line_Product_Code'];?></td>
            <td><?php echo $fetch['line_Product_quantity'];?></td>
            <td><?php echo $fetch['Effective_from'];?></td>
            <td><?php echo $fetch['Effective_to'];?></td>
            <td><?php echo $fetch['rebate'];?></td>
            <td><?php echo $fetch['rebateunits'];?></td> 
            <td><?php echo $fetch['rebatevalue'];?></td>
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
				
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'productscviewajax');   //need to uncomment
				
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
	  </div>
