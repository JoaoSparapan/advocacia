<?php
  include_once './ProcessController.php';
  include_once './VaraController.php';
  include_once '../Models/Exceptions.php';

  $num_proc = addslashes($_POST['num-proc']);
  $vara='';
  if(!isset($_POST['adv']))
  {
    $adv=1;
  }else{
    $adv = addslashes($_POST['adv']);
  }
  $client= addslashes($_POST['client']);
  $vara_obj = new VaraController();

  if($num_proc==""){
    header("Refresh: 2, url=../../pages/process.php");
    $exc = new ExceptionAlert('Informe o número do processo');
    echo $exc->alerts('error', 'Erro');
    return;
  }
  if($client==""){
    header("Refresh: 2, url=../../pages/process.php");
    $exc = new ExceptionAlert('Informe o cliente do processo');
    echo $exc->alerts('error', 'Erro');
    return;
  }

  if(isset($_POST['court'])){
    
    $vara=addslashes($_POST['court']);

  }else{
  if(isset($_POST['new-vara'])){
    if($_POST['new-vara']==""){
      header("Refresh: 2, url=../../pages/process.php");
      $exc = new ExceptionAlert('Informe a vara');
      echo $exc->alerts('error', 'Erro');
      exit;
    }
    if($vara_obj->getVaraBySigla($_POST['new-vara'])){
      header("Refresh: 2, url=../../pages/process.php");
      $exc = new ExceptionAlert('Vara já cadastrada no sistema!');
      echo $exc->alerts('error', 'Erro');
      exit;
    }
    $vara.=addslashes($_POST['new-vara']);
    
    
    $result = $vara_obj->insertVara($vara);
    if($result[0]=="error"){
      header("Refresh: 2, url=../../pages/process.php");
      $exc = new ExceptionAlert($r[1]);
      echo $exc->alerts('error', 'Erro inesperado');
      exit;
    }
    $result = $vara_obj->getLastVara();
    
    $vara = $result['idCourt'];
  }else{
    header("Refresh: 2, url=../../pages/process.php");
    $exc = new ExceptionAlert('Selecione a vara');
    echo $exc->alerts('error', 'Erro');
    exit;
  }
}
 

  $obj_proc = new ProcessController();
  $result = $obj_proc->insertProcess($num_proc,$client,$vara,$adv);

  if($result[0]=="error"){
    header("Refresh: 2, url=../../pages/process.php");
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts('error', 'Erro inesperado');
    
  }else{
    header("Refresh: 2, url=../../pages/process.php");
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts('success', 'Sucesso');
  }