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
    $exc = new ExceptionAlert("Você não tem permissão para editar recepções.");
    echo $exc->alerts("error", "Acesso negado");
    exit;
}

if(!isset($_POST['id'])){
    header("Location: ../../pages/recepcao.php");
    exit;
}

$id = intval($_POST['id']);
$subject = trim($_POST['assunto']);
$prov = !empty($_POST['providencia']) ? trim($_POST['providencia']) : null;
$arrive = trim($_POST['data-chegada']);
$resp = intval($_POST['resp']);
$idClient = intval($_POST['client-id']);

// Formata data corretamente
$arrive = date('Y-m-d H:i:s', strtotime($arrive));

$reception = new ReceptionController();
$r = $reception->updateReceptionById(
    $id,
    $subject,
    $prov,
    $arrive,
    NULL,
    $resp,
    $idClient
);

if($r[0] == 'success'){
    header("Refresh: 2, url=../../pages/recepcao.php");
    $exc = new ExceptionAlert($r[1]);
    echo $exc->alerts();
}else{
    header("Refresh: 2, url=../../pages/recepcao.php");
    $exc = new ExceptionAlert($r[1]);
    echo $exc->alerts('error','Erro');
}