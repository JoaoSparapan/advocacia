<?php
    include_once './ClientController.php';
    include_once '../Models/Exceptions.php';

    
    $name = addslashes($_POST['name']);
    $email=addslashes($_POST['email']);
    $cpf=addslashes($_POST['cpf']);
    $phone=addslashes($_POST['phone']);
    $id = addslashes($_POST['id']);

    if(isset($_POST['name'])==false || isset($_POST['email'])==false || isset($_POST['cpf'])==false ||
    isset($_POST['phone'])==false){
        header("Refresh: 3, url=../../pages/clients.php");
        $exc = new ExceptionAlert('Preencha todos os campos!');
        echo $exc->alerts('error','Erro');
    }

    $cliController = new ClientController();
    $r = $cliController->updateClient($name,$phone,$cpf,$email,$id);

    if($r[0]=='success'){
        header("Refresh: 2, url=../../pages/clients.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts();
    }else{
        header("Refresh: 3, url=../../pages/clients.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts('error','Erro');
    }