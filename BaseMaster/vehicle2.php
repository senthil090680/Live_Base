<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}

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
    <input type="text" name="KD_Name" size="30" value="<?php echo $KD_Name; ?>" autocomplete='off' maxlength="50"/></td>
    
    <td width="120">Description*</td>
     <td><input type="text" name="vehicle_desc"  id="vehicle_desc" size="50" value="<?php echo $vehicle_desc; ?>" autocomplete='off' maxlength="50"/></td>
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
    <td><input type="text" name="vehicle_code"   readonly="readonly" size="10" value="<?php echo $vehicle_code; ?>"  autocomplete='off' maxlength="20"/></td>
      <td  width="120">Registration No*</td>
    <td><input type="text" name="vehicle_reg_no"  id="vehicle_reg_no" size="20" value="<?php echo $vehicle_reg_no; ?>" autocomplete='off' maxlength="50"/></td>
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

<!---- Form End ----->
<?php include("../include/error.php");?>
<div id="errormsgcol" style="display:none;clear:both;">
<h3 align="center" class="myaligncol"></h3><button id="closebutton">Close</button></div>

  <div id="search">
        <form action="" method="get">
        <input type="text" name="vehicle_code" value="<?php $_GET['vehicle_code']; ?>" autocomplete='off' placeholder='Search By Code'/>
        <input type="submit" name="submit" class="buttonsg" value="GO"/>
        </form>       
        </div>
<div class="mcf"></div>        
<div id="containerpr">
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
		<?php
		if($_GET['submit']!='')
		{
		$var = @$_GET['vehicle_code'] ;
        $trimmed = trim($var);	
	    $qry="SELECT * FROM `vehicle_master` where vehicle_code like '%".$trimmed."%' order by vehicle_desc asc";
		}
		else
		{ 
		$qry="SELECT *  FROM `vehicle_master` order by vehicle_desc asc"; 
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
          
        <div class="con">
        <table id="insured_list" class="tablesorter" width="100%">
		<thead>
		<tr>
		<th>KD Name</th>
		<th class="rounded">Vehicle Code<img src="../images/sort.png" width="13" height="13" /></th>
        <th>Description</th>
        <th>Reg No</th>
        <th align="right">Edit/Del</th>
		</tr>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$id= $fetch['id'];
		?>
		<tr>
		<td><?php echo $fetch['KD_Name'];?></td>
	    <td><?php echo $fetch['vehicle_code'];?></td>
        <td><?php echo $fetch['vehicle_desc'];?></td>
        <td><?php echo $fetch['vehicle_reg_no'];?></td>
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
      
       <!-- Start Page Sorting-->
 <table id="pager" class="pager" height="10">
 	  <form>
     <tr>
      <td>
		<img src="../images/first.png" class="first"/>
		<img src="../images/prev.png" class="prev"/>
		<input type="text" class="pagedisplay" size="5"/>
		<img src="../images/next.png" class="next"/>
		<img src="../images/last.png" class="last"/>
        </td>
        <td>
       		<select class="pagesize">
			<option value="">LIMIT</option>
			<option value="2">2 per page</option>
			<option value="5">5 per page</option>
			<option value="10">10 per page</option>
		</select>
       </td>
       </tr> 
     
	</form>
<script defer="defer">
	$(document).ready(function() 
    { 
        $("#insured_list")
		.tablesorter({widthFixed: true, widgets: ['zebra']})
		.tablesorterPager({container: $("#pager")}); 
    } 
	); 
</script>
</table><!-- End Page  Sorting-->


<div class="clearfix"></div>
    <div class="msg" align="center" <?php if($_GET['delId']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='vehicle.php'"/>
      </form>
     </div>     
      
   </div>
</div>

<?php include('../include/footer.php'); ?>