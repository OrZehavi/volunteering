<?php
    //PHPMailer for email sending after association is approved
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    // Installed phpmailer using the command: composer require phpmailer/phpmailer 
    require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
    require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';
    
    require_once "connectionToDB.php";
  
    session_start();  
    $user_email = $_SESSION['user_email'];
    $user_name = $_SESSION['user_name'];
    $associationid = $_POST["associationid"];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $sql = "UPDATE `users` SET `is_approved`= 1 WHERE `id`=$associationid";
        if (mysqli_query($conn, $sql))
        {
                echo '<script>
                alert("Association ID: '.$associationid.' , Name: '.$user_name.'  was approved succesfully");
                </script>';

                // passing true in constructor enables exceptions in PHPMailer
                $mail = new PHPMailer(true);
                
                try {
                    // Server settings
                    
                    $mail->isSMTP();
                    $mail->Mailer = "smtp";
                    $mail->SMTPDebug  = 1;  
                    $mail->SMTPAuth   = TRUE;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    //$mail->SMTPSecure = false;
                    //$mail->SMTPAutoTLS = false;
                    
                    $mail->Port       = 25; //587
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
                    $mail->AddAddress($user_email, $user_email);
                    $mail->SetFrom("volunteeringprojectmta@gmail.com", "VolunteeringProjectMTA");
                    $mail->Subject = "Welcome to 'Doing Good'  ";
                    $content = "<b>Welcome $user_name , you were approved to use and publish volunteerings in Doing Good - Volunteering site.<b>
                    Let's start doing good!<b>
                    https://snirza.mtacloud.co.il";
                    
                    $mail->MsgHTML($content); 
                    if(!$mail->Send()) {
                        echo '<script> alert("Error while sending Email to $user_email");  </script>';
                      var_dump($mail);
                    } else {
                      echo '<script> alert("Approval email was sent successfully to '.$user_email.' "); </script>';
                    }
                    
                    
                } catch (Exception $e) {
                    echo '<script> "Error in sending email. Mailer Error: {$mail->ErrorInfo}"; </script>';
                }
        } 
        else 
         {
             echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
         }
       echo '
            <script>
                window.location = "https://snirza.mtacloud.co.il/volunteering-project/includes/php/manage-associations.php";
            </script>';  

    }
 
?>    