<?php

//error_reporting(E_ALL);
//error_reporting(E_STRICT);

//date_default_timezone_set('America/Toronto');

include('classes/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

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

$mail->Subject    = "test";

$mail->Body    = "To view the messagexx"; // optional, comment out and test

//$mail->MsgHTML($body);

$address = "duminda@tekgeeks.net";
$mail->AddAddress($address);
//$mail->AddAddress($address, "John Doe");

//$mail->AddAttachment("images/phpmailer.gif");      // attachment
//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>