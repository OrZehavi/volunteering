<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Installed phpmailer using the command: composer require phpmailer/phpmailer 
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';

session_start();


//$user_mail = 'amnon.tetra@gmail.com';
//$user_name = 'Raya';

$user_mail = $_SESSION['association_email'];
$user_name = $_SESSION['association_name'];

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
    
    $mail->Port       = 25; 
    //587
    //$mail->Host       = "smtp.gmail.com";
    $mail->Host       = "zebra.mtacloud.co.il";
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
    $mail->AddAddress($user_mail, $user_mail);
    $mail->SetFrom("volunteeringprojectmta@gmail.com", "VolunteeringProjectMTA");
    $mail->Subject = "Welcome to 'Doing Good'  ";
    $content = "<b>Welcome $user_name, you were approved to use and publish volunteerings in Doing Good - Volunteering site.
    Let's start doing good!
    https://snirza.mtacloud.co.il";
    
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