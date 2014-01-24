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

$kdi=mysql_query("select * from kd_information");	
while($row = mysql_fetch_array($kdi)){ 
$KD_Code=$row['KD_Code'];
$KD_Name=$row['KD_Name'];
}	

$id=$_REQUEST['1'];
if(isset($_POST['submit'])=='Save'){
	
$device_code =$_POST['device_code'];


if($device_code=='' || $device_serial_number=='')
{
header("location:deviceReg.php?no=9");exit;
}
else{


$device_code_arr	=	explode("~",$device_code);

echo $dev = mysql_query("select * from cycle_assignment where device_code = '$device_code_arr[0]'");
while($row=mysql_fetch_array($dev)){
$dsr_code=$row['dsr_code'];
}

echo $sql=("INSERT INTO device_registration SET KD_Code='$KD_Code', KD_Name='$KD_Name', device_code='$device_code_arr[0]',device_serial_number='$device_serial_number',	device_call_no='$device_call_no',uid='$uid',pwd='$pwd',url='$url',dsr_code='$dsr_code'");

mysql_query($sql) or die(mysql_error());


$directory = "../../../Download/"; 
$downloadirectory = "../../../Register/";

if(!is_dir($downloadirectory)){
	$mode = 0777; 
    if(mkdir($downloadirectory,$mode,true)){
 }
}

$filename = ($KD_Code."_".$device_code_arr[0]."_"."Register".".txt");

$filenameA = ($KD_Code."_".$device_code_arr[0]."_"."Active".".txt");

//copy($directory.$filename, $downloadirectory.$filename);
//copy($directory.$filenameA, $downloadirectory.$filenameA);

$fileopen		=	fopen($directory.$filename,"w+");

$fileopenA		=	fopen($directory.$filenameA,"w+");

$filecontent	=	$KD_Code."^".$dsr_code."^".$device_code_arr[0]."^".$device_serial_number."^".$device_call_no."^".$uid."^".$pwd."^".$url."^";

$filecontentA	=	$KD_Code."^".$dsr_code."^".$device_code_arr[0]."^";

fwrite($fileopen,$filecontent);
fwrite($fileopenA,$filecontentA);

fclose($fileopen);
fclose($fileopenA);

header("location:deviceReg.php?no=1");
   }
 }

//Query to select  data
$id=$_GET['id'];
$list=mysql_query("select * from device_registration where id = 1"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$KDName = $row['KDName'];
	$device_code = $row['device_code'];
	$device_serial_number = $row['device_serial_number'];
	$device_call_no = $row['device_call_no'];
	}
	
//Query to select KD information



?>
<!------------------------------- Form -------------------------------------------------->
<style type="text/css">

#errormsgdevreg {
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

.myaligndevreg {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}

</style>
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">Device Registration</div>
<div id="mytableformgr" style="height:230px;" align="center">
<form action="" method="post" onSubmit="return deviceregister();">
<table width="100%" align="center">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Device Reg Data</strong></legend>
  <table width="100%">
    <tr height="30">
    <td width="120">KD Name*</td>
    <td>
    <input type="hidden" name="KD_Code" size="30" value="<?php echo $KD_Code; ?>" maxlength="20"/> 
    <input type="text" name="KD_Name" size="30" readonly value="<?php echo $KD_Name; ?>" autocomplete='off' maxlength="20"/></td>
    <td>Device Name*</td>
    <td>
    <input type="text" name="device_description" size="20" readonly value="" autocomplete='off' maxlength="15"/>
    </td>
    </tr>

    <tr  height="30">
    <td  width="120" class="pclr">Device Code*</td>
    <td>	
	<select name="device_code" onChange="loadDeviceDefault(this.value);">
	<option value="" >--Select--</option>
	<?php $sel_supp		=	"SELECT device_description,device_call_no,device_code,device_serial_number from device_master GROUP BY device_description";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[device_code]."~".$row_supp[device_serial_number]."~".$row_supp[device_call_no]."~".$row_supp[device_description]."~"; ?>" <?php if($DSR_Code == $row_supp[device_code]) { echo "selected"; } ?> ><?php echo $row_supp[device_code]; ?></option>
	<?php } ?>
	</select>
	</td>
    
   
    <td>Device Sim Number*</td>
    <td><input type="text" name="device_call_no" readonly size="20" value="" autocomplete='off' maxlength="15"/></td>
    </tr>
    
    <tr height="30">
    <td width="120">Device Serial No*</td>
    <td><input type="text" name="device_serial_number" readonly size="30" value="" autocomplete='off' maxlength="20"/></td>
	 <td width="120">URL*</td>
    <td><input type="text" name="url" size="50" value="http://sfa.fmclgrp.com" autocomplete='off' maxlength="50"/></td>
    </tr>

      
	<tr height="30">
    <td width="120">User Name*</td>
    <td><input type="text" name="uid" size="20" value="" autocomplete='off' maxlength="20"/></td>
    <td width="120">Password*</td>
    <td><input type="password" name="pwd" size="20" value="" autocomplete='off' maxlength="20"/></td>
    </tr>
     </table>
 </fieldset>
   </td>
 </tr>
</table>
 <!----------------------------------------------- Left Table End -------------------------------------->
<table width="50%" style="clear:both">
      <tr align="center" height="40px;">
      <td>
      <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'"/></td>
      </tr>
 </table>     
</form>
</div>

<div id="errormsgdevreg" style="display:none;"><h3 align="center" class="myaligndevreg"></h3><button id="closebutton">Close</button></div>

<!---- Form End ----->
<?php include("../include/error.php");?>
<div class="mcf"></div>        
</div>
<?php include('../include/footer.php'); ?>