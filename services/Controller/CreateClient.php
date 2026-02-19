<?php
  include_once './ClientController.php';
  include_once '../Models/Exceptions.php';

  $name = addslashes($_POST['nome']);
  $email= addslashes($_POST['email']);
  $phone= addslashes($_POST['phone']);
  $cpf=addslashes($_POST['cpf']);
  $group='';
   
  $obj_cli = new ClientController();

  $result = $obj_cli->insertClient($name, $cpf, $phone, $email);

  if($result[0]=="error"){
    // echo $result[1]."<br/><br/>";
    header("Refresh: 2, url=../../pages/clients.php");
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts('error', 'Erro inesperado');
    
  }else{
    // echo $result[1]."<br/><br/>";
    header("Refresh: 2, url=../../pages/clients.php");
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts('success', 'Sucesso');
  }