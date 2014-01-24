<?php
session_start();
ob_start();
include('../include/header.php');
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
if($_GET['no']==''||$_GET['no']=='3'||$_GET['no']=='9')
{
unset($_COOKIE['email']);
unset($_COOKIE['old_pass']);
unset($_COOKIE['username']);
unset($_COOKIE['access']);
unset($_COOKIE['new_password']);
unset($_COOKIE['confirm_password']);
}
$user="select * from admin where username='".$_GET['username']."'";
$userqry=mysql_query($user);
$fetch_user=mysql_fetch_array($userqry);
//print_r($fetch_user);
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
   
<div align="center" class="headingsSGNIN">User Administration</div>
<div class="mytableformUA" align="center">
  <form method="post" action="userchangePasswordAction.php" name="register">
   <table  width="90%" height="100%" cellpadding="0" cellspacing="0" border="0" style="padding:10px 0 10px 0">
  <tr>
    <td valign="top">
	<fieldset class="alignmentregister_new">
	<legend><strong>User</strong></legend>
	<table>
	<tr height="25" >
	<td width="60">Name* <input type="hidden" name="user_id" value="" class="user_id"></td>
	<td><select  name="username" id="username" onchange="changePwd(this.value)"  autocomplete="off" style="width:100%;" autofocus class="username">
			<option value="">--- Select ---</option>
			<?php 
			$list=mysql_query("select * from  admin where username!='admin'"); 
			while($row=mysql_fetch_assoc($list)){
			?>
			<option value='<?php echo $row['username']; ?>' <?php if($_GET['username']==$row['username']){ echo 'selected'; }else{ if($_COOKIE['username']==$row['username']){ echo 'selected'; } }?>> <?php echo $row['username']; ?></option>
			<?php 
			// End while loop. 
			} 
			?>
			</select></td>
	</tr>
	<tr  height="25">
	<td  width="60">Email*</td>
	<td><input type="text" name="email" value="<?php if($_GET['username']==''){echo $_COOKIE['email'];}else{echo $fetch_user['email'];}?>" class="email" autocomplete="off" size="20" maxlength="20"></td>
	</tr>
	<tr height="25">
	<td width="60">Access*</td>
	<td><select name="access" id="access"  style="width:100%;" class="access">
			<option value="">--- Select ---</option>
			<option value="General" <?php if($fetch_user['access']=='General'){  echo 'selected';  }else{ if($_COOKIE['access']=='General'){ echo 'selected'; } }?>>General</option>
			<option value="Admin" <?php if($fetch_user['access']=='Admin'){  echo 'selected';  }else{ if($_COOKIE['access']=='Admin'){ echo 'selected'; } }?>>Admin</option>
			</select></td>
	</tr>
	</table>
	</fieldset>
	</td>
    <td>
	<fieldset class="alignmentregister_new">
	<legend><strong>Password</strong></legend>
	<table>
	<tr height="25">
	<td width="110">Old Password* <?php echo $fetch_user['old_pass'];?></td>
	<td><input type="password" name="old_pass" value="<?php if($_GET['username']==''){echo $_COOKIE['old_pass'];}else{echo $fetch_user['password'];}?>" size="20" maxlength="20" class="old_pass" readonly></td>
	</tr>
	<tr  height="25">
	<td  width="110">New Password*</td>
	<td><input type="password" name="new_password" value="<?php echo $_COOKIE['new_password'];?>" size="20" maxlength="20" /></td>
	</tr>
	<tr  height="25">
	<td  width="128">Confirm Password*</td>
	<td><input type="password" name="confirm_password" value="<?php echo $_COOKIE['confirm_password'];?>" size="20" maxlength="20" /></td>
	</tr>
	</table>
	</fieldset>
	</td>
  </tr>
  
</table>
<table width="50%" style="clear:both">
<tr align="center" height="50px;">
<td><input type="submit" name="save" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="clear" value="Clear" id="clear" class="buttons" onclick="return userPwd();" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="delete" id="submit_del" class="buttons" value="Delete"  />
<a href="../include/menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="cancel" id="cancel"  class="buttons" value="Cancel"/></a></td>
</tr>
</table> 

 <div class="msg" align="center" <?php if($_GET['username']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
	 <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='userchangePassword.php'"/>
     </div>
</form>

</div>
<?php include("../include/error.php");?>

</div>
<?php include("../include/footer.php");?>
