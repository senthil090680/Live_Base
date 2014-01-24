<?php 
//Error Message
if($_GET['no']!=''){
include "../include/config.php";
$error_sql="select * from  error_message where id=".$_GET['no'];
$error_exec=mysql_query($error_sql);
$error_fetch=mysql_fetch_array($error_exec);
}

$list=mysql_query("select * from kd_information"); 
$fetch=mysql_fetch_array($list);
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

</head>

<body onLoad="document.getElementById('userNameEmail').focus();">
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
   
<div align="center" class="headings">Forgot Password</div>
<div class="mytableFP" align="center">
  <form method="post" action="forgotPasswordAction.php" >
  <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr height="50px">
	<td width="40%" class="align">User Name*</td>
	<td width="20%"><input type="text" name="userNameEmail" id="userNameEmail"  autocomplete="off" size="20" maxlength="50" /></td>
	<td width="40%">&nbsp;</td>
	</tr>
	</table>
	<table width="50%" style="clear:both">
<tr align="center" height="50px;">
<td><input type="submit" name="save" value="Send" id="submit" class="buttons" />
&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="clear" value="Clear" id="clear" class="buttons" onclick="return forgot_clear();" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../index.php'"/></td>
</tr>
</table> 

</form>
</div>
<!--Error Message -->  
<?php include("../include/error.php");?>
<div class="clearfix"></div>   
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
