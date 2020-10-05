<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Installed phpmailer using the command: composer require phpmailer/phpmailer 
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';


// passing true in constructor enables exceptions in PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    
    $mail->isSMTP();
    $mail->Mailer = "smtp";
    $mail->SMTPDebug  = 1;  
    $mail->SMTPAuth   = TRUE;
    //$mail->SMTPSecure = "tls";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //$mail->SMTPSecure = false;
    //$mail->SMTPAutoTLS = false;
    
    $mail->Port       = 587;
    $mail->Host       = "smtp.gmail.com";
    $mail->Username   ="snirza@zebra.mtacloud.co.il";
    $mail->Password   = "IaO&7sF3%&Pd";
    

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    

    // Sender and recipient settings
    
    $mail->IsHTML(true);
    $mail->AddAddress("raya.dimarski@gmail.com", " ");
    $mail->SetFrom("volunteeringprojectmta@gmail.com", "Volunteering Project MTA");
    $mail->Subject = "volunteering updates ";
    $content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";
    
    $mail->MsgHTML($content); 
    if(!$mail->Send()) {
      echo "Error while sending Email.";
      var_dump($mail);
    } else {
      echo "Email sent successfully";
    }
    
    
} catch (Exception $e) {
    echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
}

?>