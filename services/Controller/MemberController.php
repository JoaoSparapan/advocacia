<?php 
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../Mailer/PHPMailer/src/PHPMailer.php';
require '../Mailer/PHPMailer/src/SMTP.php';

include_once './UserController.php';
include_once '../Models/Exceptions.php';
$url_callback="../../pages/login.php";
$name = addslashes($_POST['nome']);
$email= addslashes($_POST['email']);
$password = addslashes($_POST['senha']);
$cpf=addslashes($_POST['cpf']);
$idRole= addslashes($_POST['role']);

if(AuthController::getUser()!=null){

    $url_callback="../../pages/users.php";
       
}else{
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert('Sem permissão para isso!');
    echo $exc->alerts("error", "Erro");
    exit;
}

$obj = new UserController();
$result = $obj->insertUser($name,$cpf,$password,$email, $idRole);

if($result[0]=='success'){
    
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert("Sucesso ao cadastrar usuário!");
    echo $exc->alerts();

}else{
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts("error", "Erro");
}
 

?>

