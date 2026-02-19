<?php
    include_once './ReceptionController.php';
    include_once '../Models/Exceptions.php';

    $id = addslashes($_POST['id']);
    $subject = addslashes($_POST['assunto']);
    $prov=null;
    if(addslashes($_POST['providencia']))
    {
        $prov = addslashes($_POST['providencia']);
    }
    $arrive = addslashes($_POST['data-chegada']);
    $resp = addslashes($_POST['resp']);
    $idClient = addslashes($_POST['client-id']);
    $arrive = date('Y-m-d H:i:s', strtotime($arrive));


    $reception = new ReceptionController();
    $r = $reception->updateReceptionById($id,$subject,$prov,$arrive,NULL,$resp,$idClient);

    if($r[0]=='success'){
        header("Refresh: 2, url=../../pages/recepcao.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts();
    }else{
        header("Refresh: 2, url=../../pages/recepcao.php");
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts('error','Erro');
    }