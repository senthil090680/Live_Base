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
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/
EXTRACT($_REQUEST);
$id=$_REQUEST['id'];
//echo $id;
//exit;
if($_REQUEST['Customer_Name']!='')
{
	$var = @$_REQUEST['Customer_Name'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `customer` where Customer_Name like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT * FROM `customer`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$Customer_Name."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page =8;   // Records Per Page

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

function cussearch(page){  // For pagination and sorting of the SR search in view page
	var Customer_Name	=$("input[name='Customer_Name']").val();
	$.ajax({
		url : "cusajax.php",
		type: "get",
		dataType: "text",
		data : { "Customer_Name" : Customer_Name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#custid").html(trimval);
		}
	});
}

function cusviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam	=	params.split("&");
	var Customer_Name	    =	splitparam[0];
	var sortorder	        =	splitparam[1];
	var ordercol	        =	splitparam[2];
	$.ajax({
		url : "cusajax.php",
		type: "get",
		dataType: "text",
		data : {"Customer_Name" : Customer_Name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#custid").html(trimval);
		}
	});
}
</script>

<div id="mainarea">
<div class="mcf"></div>
<div><h2 align="center">Customer master</h2></div> 
<span style="float:left;">&nbsp;&nbsp;&nbsp;<input type="button" value="Back" class="buttons" onclick="window.location='customer.php'"></span>
<span style="float:left;">&nbsp;&nbsp;&nbsp;<input type="button" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

		<?php
		if($_GET['del']!=''){
			if($_POST['submit']=='ConfirmDelete'){
				$id = $_GET['del'];
				$query = "DELETE FROM `customer` WHERE id = $id";
				//Run the query
				$result = mysql_query($query) or die(mysql_error());
				header("location:customerview.php?no=3");
			}
		 }
		?>

 <div id="search">
        <input type="text" name="Customer_Name" value="<?php echo $_REQUEST['Customer_Name']; ?>" autocomplete='off' placeholder='Search By Customer Name'/>
        <input type="button" class="buttonsg" onclick="cussearch('<?php echo $Page; ?>');" value="GO"/>
 </div>
  <div class="clearfix"></div>
			<div id="custid">
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
			  $paramsval	=	$Customer_Name."&".$sortorderby."&Customer_Name"; ?>
             
            <th>KD Name</th>
            <th>KD Code</th> 
            <th nowrap="nowrap" class="rounded" onClick="cusviewajax('<?php echo $Page;?>','<?php echo $paramsval;?>');">Customer Name<img src="../images/sort.png" width="13" height="13" /></th>
            <th>DSR</th> 
            <th>LGA</th>
            <th>City</th>
            <th>Route</th>
            <th>Contact Person</th>
            <th>Contact Number</th>
          <!--  <th>Customer Type</th>-->
            <th align="right">Edit/Del</th>
			</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($num_rows)){
			$c=0;$cc=1;
			while($fetch = mysql_fetch_array($results_dsr)) {
			if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
			$id= $fetch['id'];
            $city= $fetch['City'];
			$lga= $fetch['lga'];
			$customertype= $fetch['customer_type'];
			?>
			<tr>
			<td><?php echo $fetch['KD_Name'];?></td>
            <td><?php echo $fetch['KD_Code'];?></td>
            <td><?php echo $fetch['Customer_Name'];?></td>
            <td><?php echo $fetch['DSRName'];?></td>
            <td>
            <?php
            $lg=mysql_query("select * from lga  where id= '$lga'");
            $row=mysql_fetch_array($lg);
            $lgid=$row['id'];
            $lgv=$row['lga'];
            if($lga=$lgv){echo $lgv;}?>
            </td>
            <td>
            <?php
            $cit=mysql_query("select * from city  where id= '$city'");
            $row=mysql_fetch_array($cit);
            $citid=$row['id'];
            $citv=$row['city'];
            if($city=$citv){echo $citv;}?>
            </td>
           	<td><?php echo $fetch['route'];?></td>
            <td><?php echo $fetch['contactperson'];?></td>
            <td><?php echo $fetch['contactnumber'];?></td>
          <!--  <td>-->
           <?php
//            $cut=mysql_query("select * from customer_type where id= '$customertype'");
//            $row=mysql_fetch_array($cut);
//            $cuid=$row['id'];
//            $cuv=$row['customer_type'];
//            if($customertype=$cuv){echo $cuv;}?>
            <!--</td>-->
            <td align="right">
        <a href="customer.php?id=<?php echo $fetch['id'];?>&page=<?php echo intval($_GET['page']);?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="customerview.php?del=<?php echo $fetch['id'];?>&id=<?php echo $fetch['id'];?>&page=<?php echo intval($_GET['page']);?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
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
    <div class="clearfix"></div>     
      
      
     <div class="msg" align="center" <?php if($_GET['del']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='customerview.php'"/>
      </form>
     </div>         
          
        </div>
 </div>
<?php require_once('../include/footer.php');?>