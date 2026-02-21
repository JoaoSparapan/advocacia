<?php
  include_once './ProcessController.php';
  

  $numproc='';
  $equal=true;
  $include=true;
  if(isset($_POST['numproc'])){
    if(isset($_POST['equal'])){
      $equal=addslashes($_POST['equal']);
    }
    if(isset($_POST['include_vara'])){
      $include=addslashes($_POST['include_vara']);
    }

    $numproc = addslashes($_POST['numproc']);
    $obj = new ProcessController();
    

    $result = $obj->getByNumber($numproc,$equal,$include,true);
    
    echo json_encode($result);
  }