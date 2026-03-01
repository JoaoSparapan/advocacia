<?php
    include_once './UserController.php';
    include_once '../Models/Exceptions.php';

    $name = addslashes($_POST['nome']);
    $email = addslashes($_POST['email']);
    $cpf = addslashes($_POST['cpf']);
    $id = addslashes($_POST['id']);
    $idRole = addslashes($_POST['idRole']);

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

