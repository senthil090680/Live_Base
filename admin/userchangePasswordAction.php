<?php 
include "../include/config.php";
EXTRACT($_POST);
session_start();
if($username!='')
{
if($submit=='ConfirmDelete')
{
		$query = "DELETE FROM admin WHERE username = '$username'";
		mysql_query($query) or die(mysql_error());
		header("location:userchangePassword.php?no=3");exit;
}
}
if($delete=='Delete')
{
		if($username=='')
		{
		setcookie("username", $_POST['username']);
		//echo "PP";
		header("location:userchangePassword.php?no=9");
		}
		else
		{
		//echo "PPPPP".$user_id;  exit;
		header("location:userchangePassword.php?username=$username");
		}
}
if($save=='Save')
{
//Check Mandatory Fields
		if($username==''||$email==''||$access==''||old_pass==''||$new_password==''||$confirm_password=='')
		{
		setcookie("email", $_POST['email']);
		setcookie("old_pass", $_POST['old_pass']);
		setcookie("username", $_POST['username']);
		setcookie("access", $_POST['access']);
		setcookie("new_password", $_POST['new_password']);
		setcookie("confirm_password", $_POST['confirm_password']);
		header("location:userchangePassword.php?no=9");exit;
		}
		
        // VALID Email ID
		if (strpos($userNameEmail, "@")!== false)
		{
			// VALID
			if( !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $userNameEmail) )
			{
					$Invalid = "Invalid email ID";
					header("location:userchangePassword.php?no=11");exit;
			}
   		}
		 $sql="SELECT * FROM admin where `username`='".$username."'"; 
				$result=mysql_query($sql);
				$row=mysql_fetch_array($result);
				$num_rows=mysql_num_rows($result);
				//Old Password check in DB
				if($row['password']!=$old_pass){ 
				header("location:userchangePassword.php?no=6");
				}
				//Pass & Cpassword
				else if($new_password!=$confirm_password)
				{
				setcookie("email", $_POST['email']);
				setcookie("old_pass", $_POST['old_pass']);
				setcookie("username", $_POST['username']);
				setcookie("access", $_POST['access']);
				header("Location:userchangePassword.php?no=7");
				}
		else
		{
		setcookie("email", $_POST['email']);
		setcookie("old_pass", $_POST['old_pass']);
		setcookie("username", $_POST['username']);
		setcookie("access", $_POST['access']);
		setcookie("new_password", $_POST['new_password']);
		setcookie("confirm_password", $_POST['confirm_password']);
		mysql_query("UPDATE admin SET 
          username='$username', 
          password='$new_password',
		  email='$email',confirmpassword='$confirm_password',access='$access'
		  WHERE username = '".$_POST['username']."'");
		  header("location:userchangePassword.php?no=29");
		}
}

?>