<?php

require 'assets/PHPMailer/src/Exception.php';
require 'assets/PHPMailer/src/PHPMailer.php';
require 'assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.mail.ru';
$mail->SMTPAuth = true;
$mail->Port = 465;
$mail->Username = 'no-reply-knit@mail.ru';  //sender
$mail->Password = 'qAWGveKGEh1k0f23sU0S';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->setFrom('no-reply-knit@mail.ru'); //sender
$mail->addAddress('shivamray07022002@gmail.com'); //receiver
$mail->isHTML(true);
$mail->Subject = 'Password Reset OTP'; 
$mail->Body = "FTS - KNIT | Your OTP for password reset is: ";

try {
    $mail->send();
    echo "successfull";

} catch (Exception $e) {
    echo $e;

}
?>