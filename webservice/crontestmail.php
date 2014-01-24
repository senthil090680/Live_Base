<?php
//require_once("common/class.phpmailer.php");
//require_once("common/class.smtp.php");
require_once("include/config.php");

ini_set("display_errors",true);
error_reporting(E_ALL & ~E_NOTICE);

$current_timestamp		=	date('Y-m-d H:i:s');
$current_datealone		=	date('Y-m-d');
$KD_Code			=	"KD001";

if(!file_exists($current_datealone."_croncheck.txt")) {
	$filemode			=	fopen($current_datealone."_croncheck.txt","a+");
	chmod($current_datealone."_croncheck.txt", 0777);
	fwrite($filemode,"\n".$current_timestamp."_".$KD_Code);
} else {
	$filemode			=	fopen($current_datealone."_croncheck.txt","a+");
	fwrite($filemode,"\n".$current_timestamp."_".$KD_Code);
}
fclose($filemode);


$res_checkDeviceCode	= "insert into test (id) values ('5')";
mysql_query($res_checkDeviceCode) or die(mysql_error());
exit;

$cc					=	"";
$bcc				=	"";
$from				=	"noreply@wwcvl.com";
//$replyTo			=	'replyto@fmclgrp.com';

//Create a new PHPMailer instance
$mail				=	new PHPMailer();
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug	=	0;
//Ask for HTML-friendly debug output
$mail->Debugoutput	=	'html';
//Set the hostname of the mail server
$mail->Host			=	"mail.wwcvl.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port			=	26;
//Whether to use SMTP authentication
$mail->SMTPAuth		=	true;
//Username to use for SMTP authentication
$mail->Username		=	"noreply@wwcvl.com";
//Password to use for SMTP authentication
$mail->Password		=	"2pQm_lNU_}K1";


$mail->setFrom($from);

//Set an alternative reply-to address
//$mail->addReplyTo('replyto@fmclgrp.com', 'First Last');
//$mail->addReplyTo($replyTo);

//Set who the message is to be sent to
$mail->addAddress('senthil.kumar@kumanti.in', 'Senthil');
//$mail->addAddress($to);

//Set the subject line
$mail->Subject		=	"Test mail";
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

$mail->msgHTML("Test mail");

//Replace the plain text body with one created manually
$mail->AltBody		=	'This is a plain-text message body';
//Attach an image file

if (!$mail->send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
} else {
	echo "Message sent!";
}
?>