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
if($_REQUEST['Product_code']!='')
{
	$var = @$_REQUEST['Product_code'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `product` where Product_code like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *  FROM `product`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$Product_code."&".$sortorder."&".$ordercol;

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

function productviewajaxsearch(page){  // For pagination and sorting of the SR search in view page
	var Product_code	=	$("input[name='Product_code']").val();
	$.ajax({
		url : "productviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_code" : Product_code, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#productid").html(trimval);
		}
	});
}

function productviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam		=	params.split("&");
	var Product_code	        =	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "productviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_code" : Product_code, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#productid").html(trimval);
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
			<?php
            $page=intval($_GET['page']);
            //Check whether product is assigned to any masters
            if($_GET['delID']!=''){
            $id = $_GET['delID'];
            //Check product is Assigned to kd product
            $p_sql="select a.*,b.* from product as a,kd_product as b where a.Product_code ='$Product_code' AND b.Product_code ='$id'";
            $resp=mysql_query($p_sql);
            $cnt=mysql_num_rows($resp);
            if($cnt=='1'){
            header("location:productMaster.php?no=43&page=$page"); 
            }
            elseif($_GET['delID']!=''){
            //Check product is Assigned to product scheme master
            $ps_sql="select c.*,d.* from product as c,product_scheme_master as d where c.Product_code='$Product_code' AND d.Header_Product_code='$id'";
            $resps=mysql_query($ps_sql);
            $pnt=mysql_num_rows($resps);
            if($pnt=='1'){
            header("location:productMaster.php?no=44&page=$page"); 
            }
            else{
            //Check product is Assigned to price master
            $pm_sql="select e.*,f.* from product as e, price_master as f where e.Product_code='$Product_code' AND f.Product_code='$id'";
            $respm=mysql_query($pm_sql);
            $snt=mysql_num_rows($respm);
            if($snt=='1'){
            header("location:productMaster.php?no=45&page=$page"); 
            }
            }
            }
            }		
            ?> 
            
            <?php
            if($_GET['id']!=''){
            if($_POST['submit']=='ConfirmDelete'){
            $id = $_GET['id'];
            $query = "DELETE FROM `product` WHERE id = $id";
            //Run the query
            $result = mysql_query($query) or die(mysql_error());
            header("location:productMaster.php?no=3&page=$page");
            }
            }
            ?> 
	
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
				$paramsval=$Product_code."&".$sortorderby."&Product_code"; ?>
<th nowrap="nowrap" class="rounded" onClick="productviewajax('<?php echo $Page;?>','<?php echo $paramsval; ?>');">Code<img src="../images/sort.png" width="13" height="13" /></th>
            <th>Description</th>
            <th>Product Type</th>
            <th>Brand</th>
            <th>Principal</th>
            <th>Batch Control</th>
            <th>Focus</th>
        	</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($num_rows)){
			$c=0;$cc=1;
			while($fetch = mysql_fetch_array($results_dsr)) {
			if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
			$id= $fetch['id'];
			$Focus=$fetch['Focus'];
			$brand=$fetch['brand'];
			$product_type=$fetch['product_type'];
			$principal=$fetch['principal'];
			?>
            <tr>
            <td><?php echo $fetch['Product_code'];?></td>
            <td><?php echo $fetch['Product_description1'];?></td>
            <td>
            <?php echo $fetch['product_type'];?>
            <td><?php
            $sambr=mysql_query("select * from  brand where id= '$brand'");
            $row=mysql_fetch_array($sambr);
            $branid=$row['id'];
            $brandv=$row['brand'];
            if($brand=$brandv){echo $brandv;}?>
            </td>
            <td>
            <?php
            $sambr=mysql_query("select * from  principal where id= '$principal'");
            $row=mysql_fetch_array($sambr);
            $branid=$row['id'];
            $principalv=$row['principal'];
            if($principal=$principalv){echo $principalv;}?>
            </td>
            <td><?php echo $fetch['batch_ctrl'];?></td>
            <td><?php if($Focus ==1) { echo "Yes";} elseif($Focus == 0) {echo "No";}?></td>
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
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'productviewajax');   //need to uncomment
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
		  </div>

        <div class="msg" align="center" <?php if($_GET['delID']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
        <form action="" method="post">
        <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='productMaster.php'"/>
        </form>
        </div>    
