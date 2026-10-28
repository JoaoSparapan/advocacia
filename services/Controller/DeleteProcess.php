<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/RequireLogin.php";
    include_once './ProcessController.php';
    include_once '../Models/Exceptions.php';
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];   
        //echo $id;

        $process = new ProcessController();

        $r = $process->deleteProcess($id);

        if($r[0]=='success'){
            header("Refresh: 2, url=../../pages/process.php");
            $exc = new ExceptionAlert($r[1]);
            echo $exc->alerts();
        }else{
            header("Refresh: 3, url=../../pages/process.php");
            $exc = new ExceptionAlert($r[1]);
            echo $exc->alerts("error", "Erro");
        }
        
        
    }else{
        header("Refresh: 0, url=../../pages/process.php");
        $exc = new ExceptionAlert("Sem permissão");
        echo $exc->alerts('error','Erro');
    }


