<?php

namespace App;

/**
 * Mail class
 *
 * PHPMailer Features :
 * SMTP
 *
 * Others Mail API:
 * None
 *
 * PHP version 7.0
 */
class Mail 
{    
    /**
     * Send an email
     * 
     * @param string $brandName The name of the Brand sending the email
     * @param string $to Recipient
     * @param string $username Recipient  -> Add the username ( optional )
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the email
     *
     * @return mixed
     */
    public static function send($brandName,$to,$username,$subject,$text,$html)
    {
        // SMTP needs accurate times, and the PHP time zone MUST be set
        // This should be done in your php.ini, but this is how to do it if you don't have access to that
        date_default_timezone_set('Etc/UTC');      

        $root = dirname(__DIR__);
        require_once($root."/vendor/phpmailer/phpmailer/PHPMailerAutoload.php");
        
        $mail = new \PHPMailer;                     // Create a new PHPMailer instance
        $mail->isSMTP();                            // Set Mailer to use SMTP
        $mail->Mailer = "smtp"; 
        $mail->Host = "authsmtp.sixteenleft.com";   // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = "dev@sixteenleft.com";    // Username to use for SMTP authentication
        $mail->Password = "T4ng0Ch4rl13";           // Password to use for SMTP authentication                   
        //$mail->SMTPSecure = 'tls';                // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;                           // TCP port to connect to
           
        $mail->setFrom("no-reply@sixteenleft.com", "Pass Recovery");        // Set who the message is to be sent FROM
        $mail->addAddress($to, $username);                                  // Add a recipient. Name is optional
        $mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);
        $mail->SingleTo = true;

        $mail->Subject = $subject;                                          // "Password Recovery Message from : {$brandName} "; 
        $mail->Body    = $html;                                             // 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = $text;                                             // 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->Send())
        {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            //return false;
        }
        else
        {
            echo 'Message has been sent';
            //return true;
        }
    }
}