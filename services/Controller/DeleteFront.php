<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once './FrontdeskController.php';
include_once '../Models/Exceptions.php';

$user = AuthController::getUser();

if($user == null){
    header("Location: ../../pages/login.php");
    exit;
}

if($user['idRole'] != 1){
    header("Refresh: 2, url=../../pages/frontdesk.php");
    $exc = new ExceptionAlert("Você não tem permissão para excluir atendimentos.");
    echo $exc->alerts("error", "Acesso negado");
    exit;
}

if(isset($_GET['id'])){

    $id = intval($_GET['id']);   

    $frontdesk = new FrontdeskController(); 
    $r = $frontdesk->deleteFrontdeskById($id);

    if($r[0] == 'success'){
        header("Refresh: 2, url=../../pages/frontdesk.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts();
    }else{
        header("Refresh: 3, url=../../pages/frontdesk.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts("error", "Erro");
    }

}else{
    header("Refresh: 0, url=../../pages/frontdesk.php");
    $exc = new ExceptionAlert("ID inválido.");
    echo $exc->alerts('error','Erro');
}