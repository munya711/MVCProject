<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendRegistrationEmail($userEmail) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
       // $mail->SMTPDebug = 2; // Enables verbose debug output
        $mail->isSMTP();
        $mail->Host       = 'mail.clearfacetech.com'; // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'cleartech@clearfacetech.com'; // SMTP username
        $mail->Password   = 'Matipa2011$$'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable STARTTLS encryption
        $mail->Port       = 587; // TCP port for STARTTLS

        //Recipients
        $mail->setFrom('cleartech@clearfacetech.com', 'ClearTech');
        $mail->addAddress($userEmail); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Registration Confirmation';
        $mail->Body    = 'Thank you for registering with our service. Your email has been successfully registered.';

        $mail->send();
        header('Location: login.php');
        exit;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
