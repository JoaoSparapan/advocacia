<?php
  include_once './ReceptionController.php';
  include_once '../Models/Exceptions.php';
  date_default_timezone_set('America/Sao_Paulo');

  $client=addslashes($_POST['client-id']);
  $sub=addslashes($_POST['assunto']);
  $prov=NULL;
  if(addslashes($_POST['providencia']))
  {
      $prov=addslashes($_POST['providencia']);
  }
  $arrive=addslashes($_POST['data-chegada']);
  $resp="";

  if($client==""){
    header("Refresh: 2, url=../../pages/recepcao.php");
    $exc = new ExceptionAlert('Informe o cliente do atendimento');
    echo $exc->alerts('error', 'Erro');
    return;
  }
  if(isset($_POST['resp'])){
    $resp = addslashes($_POST['resp']);
  }else{
    header("Refresh: 2, url=../../pages/recepcao.php");
    $exc = new ExceptionAlert('Selecione o responsável');
    echo $exc->alerts('error', 'Erro');
    exit;
  }
  if($sub=="" || $arrive=="")
  {
    header("Refresh: 2, url=../../pages/recepcao.php");
    $exc = new ExceptionAlert('Informe todos os campos');
    echo $exc->alerts('error', 'Erro');
    return;
  }
  $arrive = date('Y-m-d H:i:s', strtotime($arrive));

  $obj_rec = new ReceptionController();
  $result = $obj_rec->createReception($arrive,NULL,$resp,$client,$sub,$prov);

  if($result[0]=="error"){
    header("Refresh: 2, url=../../pages/recepcao.php");
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts('error', 'Erro inesperado');
    
  }else{
    header("Refresh: 2, url=../../pages/recepcao.php");
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts('success', 'Sucesso');
  }