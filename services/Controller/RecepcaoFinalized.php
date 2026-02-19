<?php
    session_start();
    include_once './ReceptionController.php';
    include_once '../Models/Exceptions.php';
    date_default_timezone_set('America/Sao_Paulo');
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];   
        
        $leave = date('Y-m-d H:i:s');
        $receptionC = new ReceptionController();

        $r = $receptionC->updateReceptionFinalizedById($id,$leave);
        if($r[0]=='success'){
            header("Refresh: 2, url=../../pages/recepcao.php");
            $exc = new ExceptionAlert($r[1]);
            echo $exc->alerts();
        }else{
            header("Refresh: 3, url=../../pages/recepcao.php");
            $exc = new ExceptionAlert($r[1]);
            echo $exc->alerts("error", "Erro");
        }

    }else{
        header("Refresh: 0, url=../../pages/recepcao.php");
        $exc = new ExceptionAlert("Sem permissão");
        echo $exc->alerts('error','Erro');
    }


