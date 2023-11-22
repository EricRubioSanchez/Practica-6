<?php
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarcorreuPHPMailer($correu,$text){

    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'ericsmtp2@gmail.com';                  //SMTP username
        $mail->Password   = 'hbup ndsk mkpl pqpo';                  //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('ericsmtp2@gmail.com', 'Eric Rubio');
        $mail->addAddress($correu);                           //Add a recipient

        //Content
        $mail->isHTML(true);                                        //Set email format to HTML
        $mail->Subject = 'Contrasenya practica PHP';
        $mail->Body    = $text;

        $mail->send();
        echo "<script> alert('ENVIAT CORRECTAMENT')</script>";
    } catch (Exception $e) {
        echo "<script> alert('El missatge no es pot enviar. Mailer Error: {$mail->ErrorInfo}')</script>";
    }
}
?>