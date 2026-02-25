<?php
    include_once './ProcessController.php';
    include_once '../Models/Exceptions.php';

    
    $name = addslashes($_POST['nome']);
    $vara=addslashes($_POST['court']);
    $client=addslashes($_POST['client']);
    $id = addslashes($_POST['id']);
    $adv = addslashes($_POST['adv']);

    if(isset($_POST['name'])==false || isset($_POST['client'])==false || isset($_POST['vara'])==false){
        header("Refresh: 3, url=../../pages/process.php");
        $exc = new ExceptionAlert('Preencha todos os campos!');
        echo $exc->alerts('error','Erro');
    }

    $process = new ProcessController();
    $r = $process->updateProcess($name,$client,$vara,$adv,$id);

    if($r[0]=='success'){
        header("Refresh: 2, url=../../pages/process.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts();
    }else{
        header("Refresh: 3, url=../../pages/process.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts('error','Erro');
    }