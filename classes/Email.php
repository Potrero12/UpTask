<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token; 

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        // create a new object
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 25;
        $mail->Username = '55c108f1d94678';
        $mail->Password = '0cfe2db0701d84';
    
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'UpTask.com');
        $mail->Subject = 'Confirma tu Cuenta';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has Creado tu cuenta en UpTask, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a>";        
        $contenido .= "<p>Si tu no create esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        //Enviar el mail
        $mail->send();

   }

   public function enviarInstrucciones() {

    // create a new object
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Port = 25;
    $mail->Username = '55c108f1d94678';
    $mail->Password = '0cfe2db0701d84';

    $mail->setFrom('cuentas@uptask.com');
    $mail->addAddress('cuentas@uptask.com', 'UpTask.com');
    $mail->Subject = 'Reestablece tu Password';

    // Set HTML
    $mail->isHTML(TRUE);
    $mail->CharSet = 'UTF-8';

    $contenido = '<html>';
    $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Parece que has olvidado tu password, sigue el siguiente enlace para recuperlo</p>";
    $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/reestablecer-password?token=" . $this->token . "'>Reestablecer Password</a>";        
    $contenido .= "<p>Si no solicitaste este cambio, ignora este correo</p>";
    $contenido .= '</html>';
    $mail->Body = $contenido;

    //Enviar el mail
    $mail->send();

}

}