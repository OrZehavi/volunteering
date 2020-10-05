<?php
$recipientEmail = "raya.dimarski@gmail.com";
$emailSubject = "PHP Mailing Function";
$emailContext = "Sending content using PHP mail function";

//$emailHeaders = "Cc: Replace email address" . "\r\n";
//$emailHeaders .= "Bcc: Replace email address" . "\r\n";

$fromAddress = "volunteeringprojectmta@gmail.com";
$emailStatus = mail($recipientEmail, $emailSubject, $emailContext, $emailHeaders, $fromAddress);
if($emailStatus) {
echo "EMail Sent Successfully!";
} else {
echo "No Email is sent";
}
?>