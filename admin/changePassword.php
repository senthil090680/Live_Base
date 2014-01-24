<?php
session_start();
ob_start();
include('../include/header.php');
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
if($_GET['no']!='7'||$_GET['no']=='')
{
unset($_COOKIE['old_paswd']);
}
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headings">Change Password</div>
<div class="mytableCP" align="center">
	<form name="account" id="account" method="post" action="changePasswordAction.php">
	<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr height="50px">
	<td width="45%" class="align">Old Password*</td>
	<td width="18%"><input type="password" name="old_paswd" value="<?php echo $_COOKIE['old_paswd'];?>"  autocomplete="off" size="20" maxlength="20" autofocus /></td>
	<td width="40%">&nbsp;</td>
	</tr>
	<tr height="50px">
	<td class="align">New Password*</td>
	<td><input type="password" name="paswd"  autocomplete="off" size="20" maxlength="20" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr height="50px">
	<td class="align">Confirm Password*</td>
	<td><input type="password" name="conf_paswd"  autocomplete="off" size="20" maxlength="20" /></td>
	<td>&nbsp;</td>
	</tr>
	</table>
	<table width="50%" style="clear:both">
	<tr align="center" height="50px;">
	<td ><input type="submit" name="submit" id="submit" class="buttons" value="Save" />
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" name="clear"  class="buttons" value="Clear" id="clear" onclick="return changePWD_clear();"/>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'" /></td>
	</tr>
	</table> 
</form>
</div>
<?php include("../include/error.php");?>
</div>
<?php include('../include/footer.php'); ?>