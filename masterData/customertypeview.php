<?php
session_start();
ob_start();
require_once('../include/header.php');
include("../include/ajax_pagination.php");
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}


error_reporting(E_ALL && ~ E_NOTICE);

EXTRACT($_REQUEST);
$id=$_REQUEST['id'];
if(isset($_POST['Delete'])){
for($i=0;$i<count($_POST['checkbox']);$i++){
$del_id=$_POST['checkbox'][$i];
$sql = "DELETE FROM customertype_product WHERE id='$del_id'";
$result = mysql_query($sql);
header("location:customertypeview.php?no=3");exit;
}
}

if($_REQUEST['customer_type']!='')
{
	$var = @$_REQUEST['customer_type'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `customertype_product` where customer_type like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT * FROM `customertype_product`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$customer_type."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];

$Per_Page =12; 

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
<style type="text/css">
#containerpr {
	padding:0px;
	width:80%;
	margin-left:auto;
	margin-right:auto;
}
</style>

<script type="text/javascript">

function cusviewajaxsearch(page){  // For pagination and sorting of the SR search in view page
	var customer_type	= $("input[name='customer_type']").val();
	$.ajax({
		url : "cusviewajax.php",
		type: "get",
		dataType: "text",
		data : { "customer_type" : customer_type, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#cusid").html(trimval);
		}
	});
}

function cusviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam	=	params.split("&");
	var customer_type	    =	splitparam[0];
	var sortorder	        =	splitparam[1];
	var ordercol	        =	splitparam[2];
	$.ajax({
		url : "cusviewajax.php",
		type: "get",
		dataType: "text",
		data : {"customer_type" : customer_type, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#cusid").html(trimval);
		}
	});
}

$(function(){
 
    // add multiple select / deselect functionality
    $("#selectall").click(function () {
          $('.case').attr('checked', this.checked);
    });
 
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $(".case").click(function(){
 
        if($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }
 
    });
});
</script>

<div id="mainarea">
<div class="mcf"></div>
<div><h2 align="center">Customer Type product</h2></div> 
<span style="float:left;">&nbsp;&nbsp;&nbsp;<input type="button" name="customer_type" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>
<span style="float:left;">&nbsp;&nbsp;<input type="button" value="Back" class="buttons" onclick="window.location='customertypeproduct.php'"></span>


 <div id="search">
        <input type="text" name="customer_type" value="<?php echo $_REQUEST['customer_type']; ?>" autocomplete='off' placeholder='Search By Cus Type'/>
        <input type="button" class="buttonsg" onclick="cusviewajaxsearch('<?php echo $Page; ?>');" value="GO"/>
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
			  $paramsval	=	$customer_type."&".$sortorderby."&customer_type"; ?>
            <th align="center"><input type="checkbox" id="selectall"/></th>
            <th nowrap="nowrap" class="rounded" onClick="cusviewajax('<?php echo $Page;?>','<?php echo $paramsval;?>');">Customer Type<img src="../images/sort.png" width="13" height="13" /></th>
            <th>Product</th>
            <th>Product Description</th>
            <th>UOM</th>
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
            <td width="5" align="center"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $fetch['id']; ?>" class="case"></td>
            <td><input type="hidden" name="customer_type" value="<?php echo $fetch['customer_type'];?>">
            <?php 
            $cukd=mysql_query("select * from customer_type where id= '$customer_types'");
            $row=mysql_fetch_array($cukd);
            $cuskdid=$row['id'];
            $cuskdv=$row['customer_type'];
            if($customer_types=$cuskdv){echo $cuskdv;}?>
            </td>
            <td><input type="hidden" name="Product_code" value="<?php echo $fetch['Product_code'];?>"><?php echo $fetch['Product_code'];?></td>
            <td><input type="hidden" name="Product_description1" value="<?php echo $fetch['Product_description1'];?>"><?php echo $fetch['Product_description1'];?></td>
            <td><input type="hidden" name="UOM1" value="<?php echo $fetch['UOM1']?>" autocomplete="off" size="20" maxlength="20"><?php echo $fetch['UOM1'];?></td>
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
            <table width="100%" style="clear:both" align="center">
<tr align="center" height="50px;">
<td colspan="10">
<input type="submit" name="Delete" id="Delete" class="buttons" value="Delete" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="clear" value="Clear" id="clear" class="buttons" onclick="window.location='customertypeview.php'" />&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../include/empty.php" style="text-decoration:none"><input type="button" name="cancel" id="cancel"  class="buttons" value="Cancel"/></a></td>
</tr>
</table>   
	  </div>
        </div>
     
<div class="msg" align="center" <?php if($_GET['delID']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
<form action="" method="post">
<input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='kd.php'"/>
</form> 
</div>    
  <div class="mcf"></div>
      <?php include("../include/error.php"); ?>
</div>
<?php require_once('../include/footer.php');?>