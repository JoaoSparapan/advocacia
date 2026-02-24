<?php
    include_once './PetitionController.php';
    include_once '../Models/Exceptions.php';

    $idProv=0;
    $statusDistributed=0;
    $sf=0;

    if(isset($_POST['idProvidence'])){
        $idProv = addslashes($_POST['idProvidence']);
    }else{
        header("Refresh: 3, url=../../pages/petition.php");
        $exc = new ExceptionAlert('Erro inesperado');
        echo $exc->alerts('error','Erro');
        exit;
    }

    if(isset($_POST['provProvidedModal'])){
        $statusDistributed=1;
    }

    if(isset($_POST['sfProvidedModal'])){
        $sf=1;
    }
    $user = intval(addslashes($_POST['usuarioAtual']));
    $pController = new PetitionController();
    
    $response = $pController->updatePetitionToDistributed($idProv, $statusDistributed,$sf,$user);

    if($response[0]=='success'){
        
        $exc = new ExceptionAlert($response[1]);
        echo $exc->alerts('success','Sucesso');
    }else{
        
        $exc = new ExceptionAlert($response[1]);
        echo $exc->alerts('error','Erro');
    }
    if(isset($_POST['distributed'])){
        header("Refresh: 2, url=../../pages/distributed.php");
    }else{
        header("Refresh: 2, url=../../pages/petition.php");
    }
    