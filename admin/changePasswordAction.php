<?php
session_start();
ob_start();
include "../include/config.php";
EXTRACT($_POST);	 
//Submit Action
if($submit=='Save')
{
//Check Mandatory Fields
		if($old_paswd==''||$paswd==''||$conf_paswd=='')
		{
		header("location:changePassword.php?no=9");exit;
		}
		else
		{
				$sql="SELECT * FROM admin where `username`='".$_SESSION['username']."'";
				$result=mysql_query($sql);
				$row=mysql_fetch_array($result);
				$num_rows=mysql_num_rows($result);
				//Old Password check in DB
				if($row['password']!=$old_paswd){ 
				header("location:changePassword.php?no=6");
				}
				//Pass & Cpassword
				else if($paswd!=$conf_paswd)
				{
				setcookie("old_paswd", $_POST['old_paswd']);
				header("Location:changePassword.php?no=7");
				}
				
				else 
				{
					if(!empty($_POST['paswd'])) 
					{
						//echo "update `admin` set password= '".$_POST['paswd']."',confirmpassword= '".$_POST['conf_paswd']."' where `username`='".$_SESSION['username']."'"; 
							$updt_qry = mysql_query("update `admin` set password= '".$_POST['paswd']."',confirmpassword= '".$_POST['conf_paswd']."' where `username`='".$_SESSION['username']."'");
							 $message = '
							<table width="600" border="0">
							<tr><td height="60">&nbsp;</td></tr>
							
							  <tr>
								<td>Dear&nbsp;<strong>'.ucwords($_SESSION['username']).',</strong> </td>
							  </tr>
							  <tr height="10">
							  </tr>
							  <tr>
								<td><p >Your password has been changed. The account details are as follows.<br><br>
								<strong>User Id :</strong>&nbsp;&nbsp;'.$_SESSION['username'].'<br><br>
								<strong>Password :</strong>&nbsp;&nbsp;'.$_POST['paswd'].'<br><br>
								</p>
								</th>
							  </tr>
								<tr>
								<td><p>This is an auto generated mail. Kindly do not reply to it. </p></th>
							  </tr>
							  <tr>
								<td><p>Thank you,<br />
							</p></td>
							  </tr>
							</table>';
							$message1 ="This is a multi-partmessage in
							MIME format.\n\n" .
								"--{}\n" .
								"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
								"Content-Transfer-Encoding: 7bit\n\n" .
								$message ."\n\n";
								$from='admin';
							$subject = "Change of Password Notification";
							$headers = "From:$from\r\nReply-to:$from";
							$headers .= "\nMIME-Version: 1.0\n" .
								"Content-Type: multipart/mixed;\n" .
								" boundary=\"{}\"";
							$to = $row->email;
										// mail($to, $subject, $message1, $headers);
							header("location:changePassword.php?no=29");
		
					}
				}
		}
		
}

?>