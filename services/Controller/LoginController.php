<?php
session_start();
include_once './AuthController.php';
include_once '../Models/Exceptions.php';
include_once '../Models/TwoFactorMail.php';

use PHPMailer\PHPMailer\PHPMailer;

require '../Mailer/PHPMailer/src/PHPMailer.php';
require '../Mailer/PHPMailer/src/SMTP.php';

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

$auth = new AuthController();
$auth->cleanupExpiredTrustedDevices();
$user = $auth->verifyUser($email, $senha);

if ($user != null) {

    $user_id = $user['idUser'];

    if (isset($_COOKIE['trusted_device'])) {

        $token = $_COOKIE['trusted_device'];

        if ($auth->isTrustedDevice($user_id, $token)) {
            AuthController::setUser($user);
            header("Location: ../../");
            exit;
        }
    }

    $codigo = rand(100000, 999999);
    $auth->createVerificationSession($user_id, $codigo);

    $mailTemplate = new TwoFactorMail($codigo, $user['name']);
    $body = $mailTemplate->content();

    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->SMTPAuth = true;
    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->Host = 'smtp.hostinger.com';
    $phpmailer->Port = 465;
    $phpmailer->CharSet = 'UTF-8';
    $phpmailer->Username = 'sendemail@advbertoldi.com.br';
    $phpmailer->Password = 'Email@123';
    $phpmailer->setFrom('sendemail@advbertoldi.com.br', 'Sistema de Gerenciamento - Advocacia Bertoldi');
    $phpmailer->addAddress($user['email'], $user['name']);
    $phpmailer->isHTML(true);
    $phpmailer->Subject = 'Seu código de verificação';
    $phpmailer->Body = $body;

    if ($phpmailer->send()) {

        $_SESSION['pending_user_id'] = $user_id;

        header("Refresh: 1, url=../../pages/verify_code.php");
        $exc = new ExceptionAlert("Enviamos um código de verificação para o seu e-mail.", 3000);
        echo $exc->alerts("success", "Código enviado");
    } else {
        header("Refresh: 2, url=../../pages/login.php");
        $exc = new ExceptionAlert("Erro ao enviar o código.", 2000);
        echo $exc->alerts("error", "Erro");
    }
} else {
    header("Refresh: 2, url=../../pages/login.php");
    $exc = new ExceptionAlert("Usuário ou senha incorretos!", 2000);
    echo $exc->alerts("error", "Erro");
}
?>