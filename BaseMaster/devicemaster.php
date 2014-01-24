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
	$qry="SELECT * FROM `device_master` where device_description like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT * FROM `device_master`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$device_description."&".$sortorder."&".$ordercol;

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


EXTRACT($_POST);
$page=intval($_REQUEST['page']);
$id=$_REQUEST['id'];
if($_GET['id']!=''){
if($_POST['submit']=='Save'){
if($device_code=='' || $device_description=='' || $device_serial_number==''  || $device_call_no=='')
{
header("location:devicemaster.php?no=9");exit;
}
else{

$sql=("UPDATE device_master SET 
          KD_Code= '$KD_Code', 
          KD_Name='$KD_Name', 
          device_code='$device_code',
		  device_description='$device_description',
		  device_serial_number='$device_serial_number',
		  device_serial_number='$device_serial_number',
		  device_call_no='$device_call_no'
		  WHERE id = '$id'");
mysql_query( $sql);
header("location:devicemaster.php?no=2&page=$page");
}
}
}

elseif($_POST['submit']=='Save'){?>
<form action="" method="post" id="resubmitform">
<input type="hidden" name="KD_Code" value="<?php echo $KD_Code; ?>" />
<input type="hidden" name="KD_Name" value="<?php echo $KD_Name; ?>" />
<input type="hidden" name="device_code" value="<?php echo $device_code; ?>" />
<input type="hidden" name="device_description" value="<?php echo $device_description; ?>" />
<input type="hidden" name="device_serial_number" value="<?php echo $device_serial_number; ?>" />
<input type="hidden" name="device_call_no" value="<?php echo $device_call_no; ?>" />
<input type="hidden" name="no" value="9" />
 
</form>
<form action="" method="post" id="dataexists">
<input type="hidden" name="KD_Code" value="<?php echo $KD_Code; ?>" />
<input type="hidden" name="KD_Name" value="<?php echo $KD_Name; ?>" />
<input type="hidden" name="device_code" value="<?php echo $device_code; ?>" />
<input type="hidden" name="device_description" value="<?php echo $device_description; ?>" />
<input type="hidden" name="device_serial_number" value="<?php echo $device_serial_number; ?>" />
<input type="hidden" name="device_call_no" value="<?php echo $device_call_no; ?>" />
<input type="hidden" name="no" value="18" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
</form>
<?php

if($device_code=='' || $device_description=='' || $device_serial_number==''  || $device_call_no=='')
{ ?>

<script type="text/javascript">
document.forms['resubmitform'].submit();
</script>
//header("location:devicemaster.php?no=9");exit;
<?php
}
else{
         $sel="select * from device_master where device_code ='$device_code'";
         $sel_query=mysql_query($sel);
		 if(mysql_num_rows($sel_query)=='0') {
		 echo $sql="INSERT INTO `device_master`(`KD_Code`,`KD_Name`, `device_code`,`device_description`,`device_serial_number`,`device_call_no`)
            values('$KD_Code','$KD_Name','$device_code','$device_description','$device_serial_number','$device_call_no')";
            mysql_query( $sql);

        header("location:devicemaster.php?no=1");
		}
		else { ?>
         <script type="text/javascript">
		document.forms['dataexists'].submit();
		</script>
        <?php 
		//header("location:devicemaster.php?no=18");
		}
}

}

$id=$_GET['id'];
$list=mysql_query("select * from device_master where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$KDName = $row['KDName'];
	$device_code = $row['device_code'];
	$device_description = $row['device_description'];
	$device_serial_number = $row['device_serial_number'];
	$device_call_no = $row['device_call_no'];
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
function  validatedev() {
	var SerialNumber				=	$('#device_serial_number').val();
	var Description			     	=	$('#device_description').val();
	var SimNumber                	=	$('#device_call_no').val();
		

	if(Description == ''){
		$('.myaligncol').html('ERR : Enter Description');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}

	if(SerialNumber == ''){
		$('.myaligncol').html('ERR : Enter Serial Number');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}
	
	
	if(SimNumber == ''){
		$('.myaligncol').html('ERR : Enter Sim Number');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}

 	$('#errormsgcol').css('display','none');
	//return false;
}


function devsearch(page){  // For pagination and sorting of the SR search in view page
	var device_description	=$("input[name='device_description']").val();
	$.ajax({
		url : "devajax.php",
		type: "get",
		dataType: "text",
		data : { "device_description" : device_description, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#devid").html(trimval);
		}
	});
}

function devviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam	=	params.split("&");
	var device_description	    =	splitparam[0];
	var sortorder	        =	splitparam[1];
	var ordercol	        =	splitparam[2];
	$.ajax({
		url : "devajax.php",
		type: "get",
		dataType: "text",
		data : {"device_description" : device_description, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#devid").html(trimval);
		}
	});
}

</script>



<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">Device</div>
<div id="mytableformgr" align="center">
<form action="" method="post" onsubmit="return validatedev();">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Device Data</strong></legend>
  <table>
  <tr height="30">
    <td width="120">KD Name*</td>
    <td>
    <input type="hidden" name="KD_Code" size="30" value="<?php echo $KD_Code; ?>" maxlength="20"/> 
    <input type="text" name="KD_Name" size="30" value="<?php echo $KD_Name; ?>" maxlength="20" autocomplete='off'/></td>
    </tr>
    <tr  height="30">
    <td  width="120" class="pclr">Device Code*</td>
    <?php
		 if(!isset($_GET[id]) && $_GET[id] == '') {
			$devid					=	"SELECT device_code FROM  device_master ORDER BY id DESC";
			$devold					=	mysql_query($devid) or die(mysql_error());
			$devcnt					=	mysql_num_rows($devold);
			//$devcnt					=	0; // comment if live
			if($devcnt > 0) {
				$row_dev					  =	 mysql_fetch_array($devold);
				$devnumber	  =	$row_dev['device_code'];

				$getdevno						=	abs(str_replace("DEV",'',strstr($devnumber,"DEV")));
				$getdevno++;
				if($getdevno < 10) {
					$createdcode	=	"00".$getdevno;
				} else if($getdevno < 100) {
					$createdcode	=	"0".$getdevno;
				} else {
					$createdcode	=	$getdevno;
				}

				$device_code				=	"DEV".$createdcode;
			} else {
				$device_code				=	"DEV001";
			}
		}
	?>
    <td><input type="text" name="device_code" size="10"  readonly="readonly" value="<?php echo $device_code; ?>" maxlength="10" autocomplete='off'/></td>
    </tr>
    <tr height="40">
     <td width="120">Description*</td>
     <td><input type="text" name="device_description" id="device_description" size="50" value="<?php echo $device_description; ?>" maxlength="20" autocomplete='off'/></td>
   </tr>

   </table>
 </fieldset>
   </td>
 </tr>
</table>
 <!----------------------------------------------- Left Table End -------------------------------------->
<table width="50%" align="right">
 <tr>
  <td>
   <fieldset class="alignment">
  <legend><strong>Device param</strong></legend>
  <table>
  <tr height="40">
     <td>Serial Number*</td>
     <td><input type="text" name="device_serial_number"  id="device_serial_number" size="30" value="<?php echo $device_serial_number; ?>" maxlength="20" autocomplete='off'/></td>
     </tr>
	 
     <tr height="40">
     <td>Sim Number*</td>
      <td><input type="text" name="device_call_no"  id="device_call_no" size="30" value="<?php echo $device_call_no; ?>" maxlength="20" autocomplete='off'/></td>
      </tr>
	   
      <tr>
      <td>&nbsp;</td> 
      <td>&nbsp;</td> 
      </tr>
      
          
     </table>
 </fieldset>
</td>
</tr>
</table>
 <!----------------------------------------------- Right Table End -------------------------------------->
<table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
      <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" name="reset" id="Clear"  class="buttons" value="Clear" onclick="return deviceclr()";/>&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'"/></td>
      </tr>
 </table>     
</form>
</div>

<div id="search">
        <input type="text" name="device_description" value="<?php echo $_REQUEST['device_description']; ?>" autocomplete='off' placeholder='Search By Device Desc'/>
        <input type="button" class="buttonsg" onclick="devsearch('<?php echo $Page; ?>');" value="GO"/>
 </div>

<!---- Form End ----->
<?php include("../include/error.php");?>
<div id="errormsgcol" style="display:none;clear:both;">
<h3 align="center" class="myaligncol"></h3><button id="closebutton">Close</button></div>
	   <?php
		if($_GET['delId']!=''){
		if($_POST['submit']=='ConfirmDelete'){
		$id = $_GET['delId'];
		$query = "DELETE FROM `device_master` WHERE id = $id";
        //Run the query
        $result = mysql_query($query) or die(mysql_error());
        header("location:devicemaster.php?no=3");
		}
		 }
		?> 
        <div class="clearfix"></div>
		<div id="devid">
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
			  $paramsval	=	$device_description."&".$sortorderby."&device_description"; ?>
        <th>KD Name</th>
        <th nowrap="nowrap" class="rounded" onClick="devviewajax('<?php echo $Page;?>','<?php echo $paramsval;?>');">Device Description<img src="../images/sort.png" width="13" height="13" /></th>
        <th>Device Code</th> 
        <th>Serial Number</th>
        <th>Sim Number</th>
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
        <td><?php echo $fetch['device_description'];?></td>
        <td><?php echo $fetch['device_code'];?></td>
        <td><?php echo $fetch['device_serial_number'];?>
        <td><?php echo $fetch['device_call_no'];?></td>
       	<td align="right">
        <a href="devicemaster.php?id=<?php echo $fetch['id'];?>&page=<?php echo intval($_GET['page']);?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="devicemaster.php?id=<?php echo $fetch['id'];?>&delId=<?php echo $fetch['id'];?>&page=<?php echo intval($_GET['page']);?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
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
        
        rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'devviewajax');   //need to uncomment
        
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
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='devicemaster.php'"/>
      </form>
         </div>
     </div>
</div>
<?php include('../include/footer.php'); ?>