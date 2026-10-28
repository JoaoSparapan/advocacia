<?php
    session_start();

    include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
    include_once './UserController.php';
    include_once '../Models/Exceptions.php';

    $user = AuthController::getUser();

    if($user == null){
        header("Location: ../../pages/login.php");
        exit;
    }

    if($user['idRole'] != 1){
        header("Refresh: 2, url=../../pages/providences.php");
        $exc = new ExceptionAlert("Você não tem permissão para alterar usuários.");
        echo $exc->alerts("error", "Acesso negado");
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location: ../../pages/users.php");
        exit;
    }

    $name = addslashes($_POST['nome']);
    $email = addslashes($_POST['email']);
    $cpf = addslashes($_POST['cpf']);
    $id = (int) $_POST['id'];
    $idRole = (int) $_POST['idRole'];

    $contUser = new UserController();
    $r = $contUser->updateUser($name,$cpf,$email,$idRole,$id);

    if($r[0]=='success'){
        header("Refresh: 2, url=../../pages/users.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts();
    }else{
        header("Refresh: 3, url=../../pages/users.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts('error','Erro');
    }
