<?php
    namespace Classes;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Email{
        public $email;
        public $nombre;
        public $token;

        public function __construct($email, $nombre, $token){
            $this->email=$email;
            $this->nombre=$nombre;
            $this->token=$token;
        }

        public function enviarConfirmacion(){  
            
            try{
                //crear en objeto de email
                $mail=new PHPMailer();
                
                $mail->isSMTP();
                $mail->Host=$_ENV['EMAIL_HOST'];                     //Set the SMTP server to send through
                
                $mail->SMTPAuth=true;                                   //Enable SMTP authentication
                $mail->Port=$_ENV['EMAIL_PORT']; 
                $mail->Username=$_ENV['EMAIL_USER'];                     //SMTP username
                $mail->Password=$_ENV['EMAIL_PASS'];                               //SMTP password
                
                $mail->setFrom('cuentas@appsalon.com');
                $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');     //Add a recipient
                $mail->Subject='Confirma tu cuenta';
                
                //set html
                $mail->isHTML(TRUE);
                $mail->CharSet='UTF-8';

                $contenido ="<html>";
                $contenido .="<p> <strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlace</p>";
                $contenido .="<p> Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
                $contenido .="<p> Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
                $contenido .="</html>";

                $mail->Body=$contenido;
                
                //ENVIAR EL EMAIL
                $mail->send();
            }catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        public function enviarInstrucciones(){          
            try{
                $mail=new PHPMailer();
                
                $mail->isSMTP();
                $mail->Host=$_ENV['EMAIL_HOST'];                     //Set the SMTP server to send through
                
                $mail->SMTPAuth=true;                                   //Enable SMTP authentication
                $mail->Port=$_ENV['EMAIL_PORT']; 
                $mail->Username=$_ENV['EMAIL_USER'];                     //SMTP username
                $mail->Password=$_ENV['EMAIL_PASS'];                                  //SMTP password
                
                $mail->setFrom('cuentas@appsalon.com');
                $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');     //Add a recipient
                $mail->Subject='Restablecer tu password';
                
                //set html
                $mail->isHTML(TRUE);
                $mail->CharSet='UTF-8';

                $contenido ="<html>";
                $contenido .="<p> <strong>Hola " . $this->nombre . "</strong> Has solicitado restablecer tu password, sigue el siguiente enlace para hacerlo</p>";
                $contenido .="<p> Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>Restablecer password</a></p>";
                $contenido .="<p> Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
                $contenido .="</html>";

                $mail->Body=$contenido;
                
                //ENVIAR EL EMAIL
                $mail->send();
            }catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }