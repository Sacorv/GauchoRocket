<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/Exception.php";
require_once "PHPMailer/SMTP.php";

class EmailHelper

{
    private PHPMailer $mail;


    public function __construct()
    {
        $this->mail = new PHPMailer(true);

    }

    function enviarMail ( $mailUsuario , $idVerificacion ){
        try {
            //Server settings
            $this->mail->SMTPDebug = 0;                      //Enable verbose debug output
            $this->mail->isSMTP();                                            //Send using SMTP
            $this->mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mail->Username   = 'gauchorocket2022@gmail.com';                     //SMTP username
            $this->mail->Password   = 'bijywqoafqzwphzs';                               //SMTP password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $this->mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $this->mail->setFrom('gauchorocket2022@gmail.com', 'Gaucho Rocket');
            $this->mail->addAddress($mailUsuario);     //Add a recipient


            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $this->mail->isHTML(true);                                  //Set email format to HTML
            $this->mail->Subject = 'Confirmacion de registro en Gaucho Rocket';
            $this->mail->Body    = "Usted debe confirmar su registro en el siguiente link : http://localhost/user/register/?id=".$idVerificacion." 
                                       para poder loguearse correctamente";

            $this->mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }

    }



}