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
if($_REQUEST['KD_Name']!='')
{
	$var = @$_REQUEST['KD_Name'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `kd` where KD_Name like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT * FROM `kd`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$KD_Name."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page =6;   // Records Per Page

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

function kdviewajaxsearch(page){  // For pagination and sorting of the SR search in view page
	var KD_Name	=$("input[name='KD_Name']").val();
	$.ajax({
		url : "kdviewajax.php",
		type: "get",
		dataType: "text",
		data : { "KD_Name" : KD_Name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#kdid").html(trimval);
		}
	});
}

function kdviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam	=	params.split("&");
	var KD_Name	    =	splitparam[0];
	var sortorder	=	splitparam[1];
	var ordercol	=	splitparam[2];
	$.ajax({
		url : "kdviewajax.php",
		type: "get",
		dataType: "text",
		data : {"KD_Name" : KD_Name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#kdid").html(trimval);
		}
	});
}
</script>

<div id="mainarea">
<div class="mcf"></div>
<div><h2 align="center">Key Distributor</h2></div> 
<span style="float:left;">&nbsp;&nbsp;&nbsp;<input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

 <div id="search">
        <input type="text" name="KD_Name" value="<?php echo $_REQUEST['KD_Name']; ?>" autocomplete='off' placeholder='Search By KD Name'/>
        <input type="button" class="buttonsg" onclick="kdviewajaxsearch('<?php echo $Page; ?>');" value="GO"/>
 </div>
  <div class="clearfix"></div>
			<div id="kdid">
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
			  $paramsval	=	$KD_Name."&".$sortorderby."&KD_Name"; ?>
              
<th nowrap="nowrap" class="rounded" onClick="kdviewajax('<?php echo $Page;?>','<?php echo $paramsval;?>');">KD Name<img src="../images/sort.png" width="13" height="13" /></th>
<th>Contact Person</th>
<th>Contact Number</th>
<th>KD Product & Price Category</th>
			</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($num_rows)){
			$c=0;$cc=1;
			while($fetch = mysql_fetch_array($results_dsr)) {
			if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
			$id= $fetch['id'];
            $kd_cate= $fetch['kd_category'];
			?>
			<tr>
			<td><?php echo $fetch['KD_Name'];?></td>
            <td><?php echo $fetch['Contact_Person'];?></td>
            <td><?php echo $fetch['Contact_Number'];?></td>
            <td>
            <?php
            $kdc=mysql_query("select * from  kd_category  where id= ' $kd_cate'");
            $row=mysql_fetch_array($kdc);
            $kdcid=$row['id'];
            $kdcv=$row['kd_category'];
            if($kd_cate=$kdcv){echo $kdcv;}?>
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
				
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'kdviewajax');   //need to uncomment
				
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
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