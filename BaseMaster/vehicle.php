<?php
session_start();
ob_start();
include('../include/header.php');
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
if($_REQUEST['device_description']!='')
{
	$var = @$_REQUEST['device_description'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `vehicle_master` where vehicle_desc like '%".$trimmed."%' order by vehicle_desc asc";
}
else
{ 
	$qry="SELECT * FROM `vehicle_master`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$vehicle_desc."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page =5;   // Records Per Page

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

EXTRACT($_POST);
$page=intval($_REQUEST['page']);
$id=$_REQUEST['id'];
if($_GET['id']!=''){
if($_POST['submit']=='Save'){
if($vehicle_code=='' || $vehicle_desc=='' || $vehicle_reg_no=='')
{
header("location:vehicle.php?no=9");exit;
}
else{
$sql=("UPDATE vehicle_master SET 
          KD_Code= '$KD_Code', 
          KD_Name='$KD_Name', 
          vehicle_code='$vehicle_code',
		  vehicle_desc='$vehicle_desc',
		  vehicle_reg_no='$vehicle_reg_no'
		  WHERE id = '$id'");
mysql_query( $sql);
header("location:vehicle.php?no=2&page=$page");
   }
 }
}
elseif($_POST['submit']=='Save'){?>
<form action="" method="post" id="resubmitform">
<input type="hidden" name="KD_Code" value="<?php echo $KD_Code; ?>" />
<input type="hidden" name="KD_Name" value="<?php echo $KD_Name; ?>" />
<input type="hidden" name="vehicle_code" value="<?php echo $vehicle_code; ?>" />
<input type="hidden" name="vehicle_desc" value="<?php echo $vehicle_desc; ?>" />
<input type="hidden" name="vehicle_reg_no" value="<?php echo $vehicle_reg_no; ?>" />
<input type="hidden" name="no" value="9" />
 
</form>
<form action="" method="post" id="dataexists">
<input type="hidden" name="KD_Code" value="<?php echo $KD_Code; ?>" />
<input type="hidden" name="KD_Name" value="<?php echo $KD_Name; ?>" />
<input type="hidden" name="vehicle_code" value="<?php echo $vehicle_code; ?>" />
<input type="hidden" name="vehicle_desc" value="<?php echo $vehicle_desc; ?>" />
<input type="hidden" name="vehicle_reg_no" value="<?php echo $vehicle_reg_no; ?>" />
<input type="hidden" name="no" value="18" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
</form>
<?php

if($vehicle_code=='' || $vehicle_desc=='' || $vehicle_reg_no=='')
{?>
<script type="text/javascript">
document.forms['resubmitform'].submit();
</script>
<?php 
//header("location:vehicle.php?no=9");exit;
}
else{
$sel="select * from vehicle_master where vehicle_code ='$vehicle_code'";
$sel_query=mysql_query($sel);
		if(mysql_num_rows($sel_query)=='0') {
		$sql="INSERT INTO `vehicle_master`(`KD_Code`,`KD_Name`, `vehicle_code`,`vehicle_desc`,`vehicle_reg_no`)
              values('$KD_Code','$KD_Name','$vehicle_code','$vehicle_desc','$vehicle_reg_no')";
              mysql_query( $sql);
        header("location:vehicle.php?no=1&page=$page");
		}
		else {?>
        <script type="text/javascript">
		document.forms['dataexists'].submit();
		</script>
		<?php //header("location:vehicle.php?no=18");
		}
}

 }
//Query to select  data
$id=$_GET['id'];
$list=mysql_query("select * from vehicle_master where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$KD_Name = $row['KD_Name'];
	$vehicle_code = $row['vehicle_code'];
	$vehicle_desc = $row['vehicle_desc'];
	$vehicle_reg_no = $row['vehicle_reg_no'];
	}
	
//Query to select Device ip
$dr=mysql_query("select * from device_registration");	
while($row = mysql_fetch_array($dr)){ 
$KD_public_ip=$row['KD_public_ip'];
$KD_private_ip=$row['KD_private_ip'];
}

//Query to select KD information

$kdi=mysql_query("select * from kd_information");	
while($row = mysql_fetch_array($kdi)){ 
$KD_Code=$row['KD_Code'];
$KD_Name=$row['KD_Name'];
}	

?>
<!------------------------------- Form -------------------------------------------------->
<style type="text/css">
#errormsgcol {
	display:none;
	width:40%;
	height:30px;
	background:#c1c1c1;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	padding-top:0px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	-ms-border-radius:10px;
	-o-border-radius:10px;
	text-align:center;
}

.myaligncol {
	 clear:both;
	padding-top:10px;
	margin:0 auto;
	color:#FF0000;
}

#closebutton {
  position:relative;
  top:-35px;
  right:-190px;
  border:none;
  background:url(../images/close_pop.png) no-repeat;
  color:transparent;
  }
 
</style>


<script type="text/javascript">
function  validateveh() {
	var Description				=	$('#vehicle_desc').val();
	var RegistrationNo         	=	$('#vehicle_reg_no').val();
		

	if(Description == ''){
		$('.myaligncol').html('ERR : Enter Description');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}

	if(RegistrationNo == ''){
		$('.myaligncol').html('ERR : Enter Location');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}

 	$('#errormsgcol').css('display','none');
	//return false;
}

function vehsearch(page){  // For pagination and sorting of the SR search in view page
	var vehicle_desc	=$("input[name='vehicle_desc']").val();
	$.ajax({
		url : "vehajax.php",
		type: "get",
		dataType: "text",
		data : { "vehicle_desc" : vehicle_desc, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#vehid").html(trimval);
		}
	});
}

function vehviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam	=	params.split("&");
	var vehicle_desc   	    =	splitparam[0];
	var sortorder	        =	splitparam[1];
	var ordercol	        =	splitparam[2];
	$.ajax({
		url : "vehajax.php",
		type: "get",
		dataType: "text",
		data : {"vehicle_desc" : vehicle_desc, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#vehid").html(trimval);
		}
	});
}

</script>

<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">Vehicle</div>
<div id="mytableformgr" align="center">
<form action="" method="post" onsubmit="return validateveh()">
<table width="100%" align="center">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Vehicle Data</strong></legend>
  <table width="100%">
  <tr height="40">
    <td width="120">KD Name*</td>
    <td>
    <input type="hidden" name="KD_Code" size="30" value="<?php echo $KD_Code; ?>" maxlength="20"/> 
    <input type="text" name="KD_Name" size="30" value="<?php echo $KD_Name; ?>" autocomplete='off' maxlength="20"/></td>
    <td width="120">Description*</td>
     <td><input type="text" name="vehicle_desc"  id="vehicle_desc" size="50" value="<?php echo $vehicle_desc; ?>" autocomplete='off' maxlength="20"/></td>
    </tr>
    
    <tr  height="40">
    <td  width="120" class="pclr">Vehicle Code*</td>
       <?php
		 if(!isset($_GET[id]) && $_GET[id] == '') {
			$vehid					=	"SELECT vehicle_code FROM  vehicle_master ORDER BY id DESC";			
			$vehold					=	mysql_query($vehid) or die(mysql_error());
			$vehcnt					=	mysql_num_rows($vehold);
			//$vehcnt					=	0; // comment if live
			if($vehcnt > 0) {
				$row_veh					  =	 mysql_fetch_array($vehold);
				$vehnumber	  =	$row_veh['vehicle_code'];

				$getvhno						=	abs(str_replace("VEH",'',strstr($vehnumber,"VEH")));
				$getvhno++;
				if($getvhno < 10) {
					$createdcode	=	"00".$getvhno;
				} else if($getvhno < 100) {
					$createdcode	=	"0".$getvhno;
				} else {
					$createdcode	=	$getvhno;
				}

				$vehicle_code				=	"VEH".$createdcode;
			} else {
				$vehicle_code				=	"VEH001";
			}
		}
	?>
    <td><input type="text" name="vehicle_code" size="10"  readonly="readonly" value="<?php echo $vehicle_code; ?>"  autocomplete='off' maxlength="10"/></td>
      <td  width="120">Registration No*</td>
    <td><input type="text" name="vehicle_reg_no" id="vehicle_reg_no" size="20" value="<?php echo $vehicle_reg_no; ?>" autocomplete='off' maxlength="15"/></td>
    </tr>
   </table>
 </fieldset>
   </td>
 </tr>
</table>
 <!----------------------------------------------- Left Table End -------------------------------------->
<table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
      <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" name="reset" id="Clear"  class="buttons" value="Clear" onclick="return vehicleclr()";/>&nbsp;&nbsp;&nbsp;&nbsp;
       <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'"/></td>
      </tr>
 </table>     
</form>
</div>

 <div id="search">
        <input type="text" name="vehicle_desc" value="<?php echo $_REQUEST['vehicle_desc']; ?>" autocomplete='off' placeholder='Search By Vehicle Desc'/>
        <input type="button" class="buttonsg" onclick="vehsearch('<?php echo $Page; ?>');" value="GO"/>
 </div>


<!---- Form End ----->
<?php include("../include/error.php");?>
<div id="errormsgcol" style="display:none;clear:both;">
<h3 align="center" class="myaligncol"></h3><button id="closebutton">Close</button></div>

	   	<?php
		if($_GET['id']!=''){
		if($_POST['submit']=='ConfirmDelete'){
		$id = $_GET['id'];
		$query = "DELETE FROM `vehicle_master` WHERE id = $id";
        //Run the query
        $result = mysql_query($query) or die(mysql_error());
        header("location:vehicle.php?no=3");
		}
		 }
		?>
		 <div class="clearfix"></div>
		 <div id="vehid">
        <div class="con">
        <table id="sort" class="tablesorter" width="100%">
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
			  $paramsval	=	$vehicle_desc."&".$sortorderby."&vehicle_desc"; ?>
        <th>KD Name</th>
        <th nowrap="nowrap" class="rounded" onClick="vehviewajax('<?php echo $Page;?>','<?php echo $paramsval;?>');">Vehicle Description<img src="../images/sort.png" width="13" height="13" /></th>
        <th>Vehicle Code</th> 
        <th>Registration Number</th>
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
        ?>
		<tr>
        <td><?php echo $fetch['KD_Name'];?></td>
        <td><?php echo $fetch['vehicle_desc'];?></td>
        <td><?php echo $fetch['vehicle_code'];?></td>
        <td><?php echo $fetch['vehicle_reg_no'];?>
       	<td align="right">
        <a href="vehicle.php?id=<?php echo $fetch['id'];?>&page=<?php echo intval($_REQUEST['page']);?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="vehicle.php?id=<?php echo $fetch['id'];?>&delId=<?php echo $fetch['id'];?>&page=<?php echo intval($_REQUEST['page']);?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
        </td>
        </tr>
		<?php $c++; $cc++; }		 
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
        
        rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'vehviewajax');   //need to uncomment
        
        } else { 
        echo "&nbsp;"; 
        } ?>      
        </th>
        </tr>
        </table>
        </div> 
    <div class="msg" align="center" <?php if($_GET['delId']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='vehicle.php'"/>
      </form>
     </div>     
      
   </div>
</div>
<?php include('../include/footer.php'); ?>