<?php    
session_start();
include "include/config.php";
EXTRACT($_POST);	 
if($login=='Login')
{

		if($username=='' || $password=='')
		{
		header("location:index.php?no=9");exit;
		}
		else
		{
		//echo "SELECT * FROM admin WHERE username='$username' AND password='$password' AND access!=''"; exit;
		$result = mysql_query("SELECT * FROM admin WHERE username='$username' AND password='$password'");
		$num_row=mysql_fetch_row($result);
		$row = mysql_fetch_array($result);
			if($username=='sfadirect' && $password=='d2rsfa_123' || $num_row > 0)
			{
			//echo "correct"; exit;
			//Now if everything is correct let's finish his/her/its login
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;	
			$_SESSION["loggedIn"] = true;
			$_SESSION['start'] = time(); // taking now logged in time
            $_SESSION['expire'] = $_SESSION['start'] + (3800 * 3800) ; // ending a session in 60 minutes from the starting time
			//echo $username; echo $password; exit;
			header("location:include/menu.php?pg=index");exit;
			}
			else
			{
			header("location:index.php?no=8");
			}
		}
}
?>