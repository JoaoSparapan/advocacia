<?php
    include_once './ProvidenceController.php';
    include_once '../Models/Exceptions.php';

    $idProv=0;
    $statusDone=0;
    $statusProvided=0;
    $url_callback=addslashes($_POST['callback']);
    if(isset($_POST['idProvidence'])){
        $idProv = addslashes($_POST['idProvidence']);
    }else{
        header("Refresh: 2, url=$url_callback");
        $exc = new ExceptionAlert('Erro inesperado');
        echo $exc->alerts('error','Erro');
        exit;
    }

    if(isset($_POST['provDoneModal'])){
        $statusDone=1;
    }

    if(isset($_POST['provProvidedModal'])){
        $statusProvided=1;
    }

    $provController = new ProvidenceController();
    
    $response = $provController->updateProvidenceFullStatus($idProv, $statusProvided, $statusDone);

    if($response[0]=='success'){
        
        $exc = new ExceptionAlert($response[1]);
        echo $exc->alerts('success','Sucesso');
    }else{
        
        $exc = new ExceptionAlert($response[1]);
        echo $exc->alerts('error','Erro');
    }
    if(isset($_POST['providenced'])){
        header("Refresh: 2, url=$url_callback");
    }else{
        header("Refresh: 2, url=$url_callback");
    }
    