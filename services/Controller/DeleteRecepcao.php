<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once './ReceptionController.php';
include_once '../Models/Exceptions.php';

$user = AuthController::getUser();

if($user == null){
    header("Location: ../../pages/login.php");
    exit;
}

if($user['idRole'] != 1){
    header("Refresh: 2, url=../../pages/recepcao.php");
    $exc = new ExceptionAlert("Você não tem permissão para excluir recepções.");
    echo $exc->alerts("error", "Acesso negado");
    exit;
}

if(isset($_GET['id'])){

    $id = intval($_GET['id']);   

    $receptionC = new ReceptionController(); 
    $r = $receptionC->deleteReceptionById($id);

    if($r[0] == 'success'){
        header("Refresh: 2, url=../../pages/recepcao.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts();
    }else{
        header("Refresh: 3, url=../../pages/recepcao.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts("error", "Erro");
    }

}else{

    header("Refresh: 0, url=../../pages/recepcao.php");
    $exc = new ExceptionAlert("ID inválido.");
    echo $exc->alerts("error", "Erro");
}