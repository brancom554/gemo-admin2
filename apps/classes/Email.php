<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require _WEB_PATH_PDF.'/autoload.php';

class Email
{
    public function SMTPinit()
    {
        $sender = 'info@mydko-sarl.com';
        $senderName = 'GEMO CPN';
        $recipient = 'info@mydko-sarl.com';
        $usernameSmtp = 'AKIAYVVWBFD4YB2RVU4P';
        $passwordSmtp = 'BEEb3lO9t3IodzHwkdM8NBTuUQGL00dQZvp2zZi6zD4Q'; 
        $configurationSet = 'ConfigSet';
        /*$message = "Test";
        $client = $_POST['name'];*/
        
        $host = 'email-smtp.eu-west-1.amazonaws.com';
        $port = 587;
        //$bodyText =  "SUJET DU MAIL ";

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
        
        
        
         //Server settings
          $mail->isSMTP();
        //$mail->SMTPDebug = 2;
         $mail->setFrom($sender, $senderName);
         $mail->Username   = $usernameSmtp;
         $mail->Password   = $passwordSmtp;
         $mail->Host       = $host;
         $mail->Port       = $port;
         $mail->SMTPAuth   = true;
         $mail->SMTPSecure = 'tls';
        
         // Specify the message recipients.
         $mail->addAddress($recipient);

         return $mail;
    }

    public function EnvoisDeEmail(string $title,$body)
    {
        try {
        $mail = $this->SMTPinit();
        
        // Set email format to HTML
        $mail->isHTML(true);
        $mail->Subject = $title;
        $mail->Body    = $body;
        $mail->AltBody = $body;
        //@$mail->send();
        if (@$mail->send()) {
             return true;
         }
        }catch (Exception $e) {
        $statusMessage = false;
         echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
        }
        
         /*if () {
             return true;
         }else {
             return $mail->errorMessage();
         }*/
    }
}
