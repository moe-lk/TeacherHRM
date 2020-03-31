<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
$_SESSION['SuccessContact']="";

include('../sendingmail/classes/class.phpmailer.php');

	$name = $_REQUEST['name'];
	$tel = $_REQUEST['telephone'];
	$subject = $_REQUEST['subject'];
	$email = $_REQUEST['email'];
	$inquiries = addslashes($_REQUEST['inquiries']);
	
	$msg = "<table width=\"800\" style=\"font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;\">
	   <tr>
		<td>Subject</td>
		<td width=\"5\">:</td>
		<td width=\"632\">$subject</td>
	  </tr>
	  <tr>
		<td width=142>Name</td>
		<td>:</td>
		<td>$name</td>
	  </tr>
	  <tr>
		<td>Contact Number</td>
		<td>:</td>
		<td>$tel</td>
	  </tr>
	  <tr>
		<td>Email Address</td>
		<td>:</td>
		<td>$email</td>
	  </tr>
	  
	  <tr>
		<td valign=top>Message</td>
		<td valign=top>:</td>
		<td valign=top>$inquiries</td>
	  </tr>
	</table>";
	/* echo $msg;
	exit(); */
	
	
	$mail= new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->SMTPDebug  = 1;   // enables SMTP debug information (for testing)
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure   = "ssl"; 
	$mail->Host       = "mail.moe.gov.lk"; // SMTP server //smtp.gmail.com
	$mail->Port       = 465;      // or 465              // set the SMTP port for the GMAIL server
	$mail->IsHTML(true);  
	$mail->Username   = "nemis@moe.gov.lk"; // SMTP account username
	$mail->Password   = "Nemisdmr29";        // SMTP account password
	$mail->SetFrom('nemis@moe.gov.lk');
	
	//$mail->AddReplyTo("name@yourdomain.com","First Last");
	
	$mail->Subject    = "Message from NEMIS - Contact us";
	$mail->Body    = $msg; // optional, comment out and test
	//$mail->MsgHTML($body);
	$address = "nemis@moe.gov.lk";
	$mail->AddAddress($address);
	//$mail->AddAddress($address, "John Doe");
	
	//$mail->AddAttachment("images/phpmailer.gif");      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
	
	if(!$mail->Send()) {
	  echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	  echo "Message sent!";
	}

	
	$_SESSION['SuccessContact']="Thank you for contacting with us. We will revert to you soon.";
	header("Location: contactUs-1.html");
	exit();
?>