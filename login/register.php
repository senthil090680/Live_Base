<?php 
ob_start();
//Error Message
if($_GET['no']!=''){
include "../include/config.php";
$error_sql="select * from  error_message where id=".$_GET['no'];
$error_exec=mysql_query($error_sql);
$error_fetch=mysql_fetch_array($error_exec);
}
if($_GET['no']==''||$_GET['no']=='9')
{
unset($_COOKIE['username_ret']);
unset($_COOKIE['email_ret']);
unset($_COOKIE['password_ret']);
unset($_COOKIE['confirmpassword_ret']);
}

 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Host System</title>
<link rel="stylesheet" href="../css/style.css" type="text/css" />
<link rel="stylesheet" href="../css/editbox.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css" />
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/facebox.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="../js/jconfirmaction.jquery.js"></script>
<script type="text/javascript">
function specialChar(e)
{
var k="<?php echo $param_fetch['specialchar']?>";
document.all ? k = e.keyCode : k = e.which;
return ((k != 48)&&(k != 49)&&(k != 50)&&(k != 51)&&(k != 52)&&(k != 53)&&(k != 54)&&(k != 55)&&(k != 56)&&(k != 57));
}
</script>
</head>

<body onLoad="document.getElementById('username').focus();">
<div id="wrapper">
 <!------------------------------- Header Start ---------------------------------------->
 <div id="header">
    <div id="logo">
      <div class="left"><img src="../images/logo_fmcl.png" width=60 height="70"/></div>
      <div class="left">
      <h1 align="center">BASE SYSTEM</h1>
      <h2 align="center"><?php 
      include "../include/config.php";
	  $list = mysql_query("select * from kd_information"); 
      $row = mysql_fetch_array($list);
	  echo $row['KD_Name'];?></h2></div>
      <div class="left"><img src="../images/logo_tts.png" width="60" height="72" style="padding-left:295px;"/></div> 
      </div>
 </div>
<!------------------------------- Form -------------------------------------------------->

<div id="mainarea">
  <div class="clearfix"></div>
   
<div align="center" class="headingsSGNIN">SIGN IN</div>
<div class="mytableformreg" align="center">
  <form method="post" action="registerAction.php" name="register">
  <table  width="90%" height="100%" cellpadding="0" cellspacing="0" border="0" style="padding:10px 0 10px 0">
  <tr>
    <td>
	<fieldset class="alignmentregister_new">
	<legend><strong>User</strong></legend>
	<table>
	<tr height="25">
	<td width="60">Name*</td>
	<td><input type="text" name="username"  id="username" value="<?php echo $_COOKIE['username_ret'];?>" maxlength="20" autocomplete="off" size="20" onkeypress="return specialChar(event)"></td>
	</tr>
	<tr  height="25">
	<td>Email*</td>
	<td><input type="text" name="email" value="<?php echo $_COOKIE['email_ret'];?>" maxlength="50" autocomplete="off" size="20"></td>
	</tr>
	</table>
	</fieldset>
	</td>
    <td>
	<fieldset class="alignmentregister_new">
	<legend><strong>Password</strong></legend>
	<table>
	<tr height="25">
	<td width="60">Password*</td>
	<td><input type="password" name="password" value="<?php echo $_COOKIE['password_ret'];?>" maxlength="20" autocomplete="off" size="20"></td>
	</tr>
	<tr  height="25">
	<td  width="128">Confirm Password*</td>
	<td><input type="password" name="confirmpassword" value="<?php echo $_COOKIE['confirmpassword_ret'];?>" maxlength="20" autocomplete="off" size="20"></td>
	</tr>
	</table>
	</fieldset>
	</td>
  </tr>
</table>
<table width="95%"  cellpadding="0" cellspacing="0" border="0" align="left" style="padding-left:34px;">
  <tr>
    <td>	<fieldset class="alignmentregister_new">
	<legend><strong>Parameters</strong></legend>
	<table>
	<tr height="25">
	<td width="60">Access*</td>
	<td><input type="text" name="access" value="General" readonly disabled>&nbsp;&nbsp;</td>
	<!-- <td  width="128" style="padding-left:54px;">Verification*</td>
	<td>
	<table width="100%"  border="0"  cellpadding="0" cellspacing="0">
	<tr>
	<td><input type="text" name="verify" size="10" autocomplete="off">&nbsp;&nbsp;</td>
	<td valign="top"><img src="getimage.php" alt="" /></td>
	</tr>
	</table>
	</td> -->
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</fieldset>
</td>
  </tr>
</table>
<table width="50%" style="clear:both">
<tr align="center" height="50px;">
<td><input type="submit" name="submit" value="Save" class="buttons"/>
&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="clear" value="Clear" id="clear" class="buttons" onclick="return valClear();" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../index.php'"/></td>
</tr>
</table> 

</form>
</div>
<div class="clearfix"></div>   
<!--Error Message -->  
<?php include("../include/error.php");?>

</div>
<!------------------------------- Footer ------------------------------------------------->

<div id="footer">
<div class="left"><a href="#">...a solution from TTS</a></div>
  <div class="right"><a href="#"><?php $time_now=mktime(date('g')+4,date('i')-30,date('s')); 
$time = date('d-M-Y / h:i A',$time_now); 
echo $time;
 ?></a></div>

</div>

<!------------------------------- Wrapper End ---------------------------------------->
</div>


</body>
</html>
