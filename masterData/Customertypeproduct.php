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
if($_REQUEST['Product_description1']!='')
{
	$var = @$_REQUEST['Product_description1'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `customertype_product` where Product_description1 like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *  FROM `customertype_product`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$Product_description1."&".$sortorder."&".$ordercol;

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

function cussearch(page){  // For pagination and sorting of the SR search in view page
	var Product_description1=$("input[name='Product_description1']").val();
	$.ajax({
		url : "cuspajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_description1" : Product_description1, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#cusid").html(trimval);
		}
	});
}

function cusviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam		=	params.split("&");
	var Product_description1         =	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "cuspajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_description1" : Product_description1, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#cusid").html(trimval);
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
	width:80%;
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
<div><h2 align="center">Customer Type Product</h2></div> 
<div class="clearfix"></div>
<span style="float:left;padding-left:150px;">&nbsp;&nbsp;&nbsp;<input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>
<!--<span style="float:left;">&nbsp;&nbsp;<input type="button" value="Back" class="buttons" onclick="window.location='dsr.php'"></span>-->
 <div id="search" style="padding-right:150px;">
        <input type="text" name="Product_description1" value="<?php echo $_REQUEST['Product_description1']; ?>" autocomplete='off' placeholder='Search By Product Desc'/>
        <input type="button" class="buttonsg" onclick="cussearch('<?php echo $Page; ?>');" value="GO"/>
 </div>
 <div class="clearfix"></div>
			<div id="cusid">
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
		    	$paramsval	=	$Product_description1."&".$sortorderby."&Product_description1"; ?>
                
            <th>customer Type</th>
         <th nowrap="nowrap" class="rounded" onClick="cusviewajax('<?php echo $Page;?>','<?php echo $paramsval; ?>');">Product_description1<img src="../images/sort.png" width="13" height="13" /></th>
            <th>Product_code</th>
		    <th>UOM1</th>
          	</tr>
			</thead>
			<tbody>
			<?php
            if(!empty($num_rows)){
            $c=0;$cc=1;
            while($fetch = mysql_fetch_array($results_dsr)) {
            if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
            $id= $fetch['id'];
			$customer_types=$fetch['customer_type'];
            ?>
            <tr>
            <td>
            <?php 
            $cukd=mysql_query("select * from customer_type where id= '$customer_types'");
            $row=mysql_fetch_array($cukd);
            $cuskdid=$row['id'];
            $cuskdv=$row['customer_type'];
            if($customer_types=$cuskdv){echo $cuskdv;}?>
            </td>
            <td><?php echo $fetch['Product_description1'];?></td>
            <td><?php echo $fetch['Product_code'];?></td>
            <td><?php echo $fetch['UOM1'];?></td>
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
				
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'cusviewajax');   //need to uncomment
				
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