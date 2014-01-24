<?php
//Connect to database from here
include "../include/config.php";
EXTRACT($_POST);
	$sel="select editmasterpwd from parameters";
	$sel_query=mysql_query($sel);
	$row=mysql_fetch_array($sel_query);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />
<title>Host </title>
<script langauge="javascript">
function pwd()
{
var pwdval=$('.password').val();
var editmasterpwd=$('.editmasterpwd').val();

if(pwdval=='')
{
	alert("Please Enter The Password");
	return false;
}
else
{
	if(editmasterpwd!=pwdval)
	{
	alert("Password does not match");
	return false;
	}
	if(editmasterpwd==pwdval)
	{
	//alert("Password Match");
	document.passwordFrm.submit();
	//return false;
	}
}

}
</script>
</head>
<body topmargin="0" onLoad="focus()">
<h2 align="center">Enter Password To Edit</h2>
       <form action="" method="post" name="passwordFrm">
        <input hidden="text" name="popid" value="<?php echo $_GET['id']; ?> "/>
        <input type="text" name="password" value="" class="password" autocomplete="off" maxlength="10" />
		<input type="hidden" name="editmasterpwd" value="<?php echo $row['editmasterpwd'];?>" class="editmasterpwd"/>
        <input type="submit" name="submit"  value="Go" onclick="return pwd();" />
        </form>  </body>
</html>


