<?php 


include_once './UserController.php';
include_once '../Models/Exceptions.php';
include_once '../Models/ForgetPasswordMail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../Mailer/PHPMailer/src/PHPMailer.php';
require '../Mailer/PHPMailer/src/SMTP.php';

$userController = new UserController();
$email = addslashes($_POST['email-forg']);

$novasenha = uniqid();

$user = $userController->getByEmail($email);

if($user==null)
{
    header("Refresh: 2, url=../../pages/login.php");
    $exc = new ExceptionAlert("E-mail não encontrado.",2000);
    echo $exc->alerts("error", "Erro");
}else{
    
    $forgMail = new ForgetPasswordMail($novasenha);

    $text = $forgMail->content();


    $result = $userController->updatePasswordForget($email, $novasenha);
    // echo $mensagem;
    if($result[0]=='success'){
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = 'ssl'; 
        $phpmailer->Host = 'smtp.hostinger.com';
        $phpmailer->CharSet="UTF-8";
        $phpmailer->Port = "465";
        $phpmailer->Username = "sendemail@advbertoldi.com.br";
        $phpmailer->Password = 'Email@123';

        $orig='Sistema de Gerenciamento - Advocacia Bertoldi';
        $phpmailer->setFrom('sendemail@advbertoldi.com.br', $orig); //Origem
        $phpmailer->addAddress($user[0]['email'], $user[0]['name']);     //Destinatario      

        //Conteudo
        $phpmailer->isHTML(true);                                  
        $phpmailer->Subject = 'Recuperação de senha';
        $phpmailer->Body    = $text;
        
        $phpmailer->send();
       
        header("Refresh: 3, url=../../pages/login.php");
        $exc = new ExceptionAlert("Enviamos um email para você com sua nova senha.",3000);
        echo $exc->alerts("success", "Senha alterada");

    }else{
        header("Refresh: 2, url=../../pages/login.php");
        $exc = new ExceptionAlert("Erro ao alterar senha",2000);
        echo $exc->alerts("error", "Erro");

    }
    
}


?>

