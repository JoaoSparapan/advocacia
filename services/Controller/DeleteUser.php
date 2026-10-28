<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './UserController.php';
require_once '../Models/Exceptions.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $userCont = new UserController();
    $user = $userCont->getById($id);

    if(!$user){
        die("Usuário não encontrado");
    }

    $r = $userCont->deleteUser($id);

    if(!is_array($r)){
        die("Erro inesperado ao deletar");
    }

    if($r[0]=='success'){
        header("Refresh: 2, url=../../pages/users.php");
        echo (new ExceptionAlert($r[1]))->alerts();
    }else{
        header("Refresh: 3, url=../../pages/users.php");
        echo (new ExceptionAlert($r[1]))->alerts("error", "Erro");
    }

}else{
    header("Location: ../../pages/users.php");
    exit;
}