<?php

require 'class.phpmailer.php';
require 'class.smtp.php';
require 'class.pop3.php';

function sendEmails($toAddress, $toName, $body, $subject, $filename = "", $path = "") {
    $mail = new PHPMailer(true);
    $mail->IsSMTP();

    try {
        $fromAddress = 'lpthushara@gmail.com';
        $fromName = "Ministry of Education";

        $mail->Host = "smtp.gmail.com";
        $mail->SMTPDebug = 2;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465; // 
        $mail->Username = "lpthushara@gmail.com";
        $mail->Password = "thuz11106926";

        foreach ($toAddress as $emailAdd) {
            $mail->AddAddress($emailAdd, $toName);
        }
        $mail->SetFrom($fromAddress, $fromName);
        $mail->Subject = $subject;
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';       
        $mail->MsgHTML($body);

        if(!empty($filename)){
            $file = "C:/wamp/www/EMIS/PDFGenerater/tempFile/" . $filename . ".pdf";
            $mail->AddAttachment($file);
        }
        if ($mail->Send())
            return true;
        else
            echo "Message Sent Fail.</p>\n";
    } catch (phpmailerException $e) {
        echo $e->errorMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
	
}

?>