<?php
    session_start();
    include_once './ReceptionController.php';
    include_once '../Models/Exceptions.php';
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];   

        $receptionC = new ReceptionController(); 
        $r = $receptionC->deleteReceptionById($id);
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


