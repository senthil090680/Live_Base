<?php
ob_start();
session_start();

include "../include/config.php";
require_once('../login/challenge.php');

EXTRACT($_POST);	
//Check Mandatory Fields
		if($username==''||$password==''||$email==''||$confirmpassword=='')
		{
		header("location:register.php?no=9");exit;
		} 
			//Verification
			$CHALLENGE_FIELD_PARAM_NAME = "verify"; 
			// The following include is required in order to call 
			
			// the isChallengeAccepted() function.
			// If there was a form post, handle it 
			
			/*if(isset($_POST[$CHALLENGE_FIELD_PARAM_NAME])) {
			//echo $_POST[$CHALLENGE_FIELD_PARAM_NAME]; exit;
			// Check challenge string
		
			if(isChallengeAccepted($_POST[$CHALLENGE_FIELD_PARAM_NAME]) === FALSE) {
			//echo "GKO";exit;
			setcookie("username_ret", $_POST['username']);
			setcookie("email_ret", $_POST['email']);
			setcookie("password_ret", $_POST['password']);
			setcookie("confirmpassword_ret", $_POST['confirmpassword']);
			//$resultMessage = "Please Enter The Text in the Image Correctly.";
			header("Location:register.php?no=16");
			} */
			// VALID
			if( !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i",$email) )
			{
			setcookie("username_ret", $_POST['username']);
			setcookie("email_ret", $_POST['email']);
			setcookie("password_ret", $_POST['password']);
			setcookie("confirmpassword_ret", $_POST['confirmpassword']);
			header("location:register.php?no=11");
			}	
			//Pass & Cpassword
			else if($password!=$confirmpassword)
			{
			setcookie("username_ret", $_POST['username']);
			setcookie("email_ret", $_POST['email']);
			setcookie("password_ret", $_POST['password']);
			setcookie("confirmpassword_ret", $_POST['confirmpassword']);
			header("Location:register.php?no=7");
			}

		//Submit Action
else
{

//echo "KL"; exit;
		if($submit=='Save')
		{
		
				$result = mysql_num_rows(mysql_query("SELECT * FROM admin WHERE username = '$username'"));
				if($result > 0)
				{
				setcookie("username_ret", $_POST['username']);
				setcookie("email_ret", $_POST['email']);
				setcookie("password_ret", $_POST['password']);
				setcookie("confirmpassword_ret", $_POST['confirmpassword']);
				header("location:register.php?no=15");
				}
				else
				{
				$UN=strtoupper($username);
		//				echo "INSERT INTO admin (username,password,confirmpassword,email,access)VALUES('$UN','$password','$confirmpassword','$email','$access')"; exit;
						mysql_query("INSERT INTO admin (username,password,confirmpassword,email,access)VALUES('$UN','$password','$confirmpassword','$email','General')");
						//Session Variable
						$_SESSION['username']= $UN; 
		//Email 
						$subject="Registration";
						$message ="<table width=100% border=0 cellspacing=0 cellpadding=0 align=left>
						<tr>
						<td>
						<table cellpadding=0 cellspacing=0 width=450 border=0 align=center>
						<tr>
						<td colspan=3 height=10></td>
						</tr>
						<tr>
						<td colspan=3 height=10></td>
						</tr>
						<tr>
						<td width=344  colspan='3' align='left'>Hello $reguser_name ,</td>
						</tr>
						<tr>
						<td height=5 colspan=2></td>
						</tr>
						<tr>
						<td colspan=3 height=10></td>
						</tr>
						<tr>
						<td width=344  colspan='3' align='left'>Thank you </td>
						</tr>
						<tr>
						<td colspan=3 height=6></td>
						</tr>
						<tr>
						<td colspan=3 height=6></td>
						</tr>
						<tr>
						<td width=83><b>&nbsp;&nbsp;&nbsp;Password</b></td>
												 
						<td width=344 >: &nbsp;$password</td>
						</tr>
						<tr>
						<td colspan=3 height=6></td>
						</tr>
						<tr>
						<td colspan=3 height=10></td>
						</tr>	
						<tr>
						<td height=5 colspan=3>This is an auto generated mail. Kindly do not reply to it. </td>
						</tr>
						
						<tr>
						<td colspan=3 height=6></td>
						</tr>
						<tr>
						<td colspan=3 height=6></td>
						</tr>
						</table>
						</td>
						</tr>
						</table>";
						
		
						$email_address=$row_emailID['emailId'];
						$from='"Admin"<'.$emailId.'>';
						$to = $emailId;
						$message1 ="This is a multi-partmessage in
						MIME format.\n\n" .
						"--{}\n" .
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
						"Content-Transfer-Encoding: 7bit\n\n" .
						$message ."\n\n";
						$headers = "From:$from\r\nReply-to:$to";
						$headers .= "\nMIME-Version: 1.0\n" .
						"Content-Type: multipart/mixed;\n" .
						" boundary=\"{}\"";
						//echo $to, $subject, $message, $headers; 	exit;
						//mail($to, $subject, $message, $headers);
					header("location:register.php?no=17&pg=index");
					}
		}//End IF For ALL Entered Fields
}//End Of Else
//}

?>