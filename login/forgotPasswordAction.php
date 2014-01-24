<?php
include "../include/config.php";
EXTRACT($_POST);
if($save=='Send')
{
//Check Mandatory Fields
		if($userNameEmail=='')
		{
		header("location:forgotPassword.php?no=9");exit;
		}
		else
		{
		//echo "LPP"; exit;
			$sql="SELECT * FROM admin WHERE username='".$userNameEmail."'";
			$result=mysql_query($sql);
			$row=mysql_fetch_array($result);
			if(mysql_num_rows($result)>0)
			{
			
				$message = '
				<table width="600" border="0">
				<tr><td height="60">&nbsp;</td></tr>
				<tr>
				<td><p align="left" > Dear '.ucfirst($row['username']).',&nbsp;&nbsp;<br /> <br />Your account details are given below: <br /><br />
				<strong >User Name</strong> : '.$row['username'].'<br />
				<strong >Password</strong> : '.$row['password'].'<br />
				</p>
				<p>This is an auto generated mail. Kindly do not reply to it.<br></p>
				</td></tr>
				<tr>
				<td><p>Thank you,<br />
				</p></td>
				</tr>
				
				</table>
				';
				
				$message1 ="This is a multi-partmessage in
				
				MIME format.\n\n" .
				
				"--{}\n" .
				
				"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
				
				"Content-Transfer-Encoding: 7bit\n\n" .
				
				$message ."\n\n";
				
				$from='SatheeshS@tts-consulting.in';
				
				$subject = "Account Password - Details";
				
				$headers = "From:$from\r\nReply-to:$from";
				
				$headers .= "\nMIME-Version: 1.0\n" .
				
				"Content-Type: multipart/mixed;\n" .
				
				" boundary=\"{}\"";
				
				$to = $row['email'];
				
				//mail($to, $subject, $message1, $headers);
					//echo $to, $subject, $message1, $headers; exit;
				header("location:forgotPassword.php?no=14");
			}
			else
			{
					if($userNameEmail!=$row['username'])
					{
					header("location:forgotPassword.php?no=13");
					}
			}
		}
}
?>

