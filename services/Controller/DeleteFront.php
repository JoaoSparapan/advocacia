<?php
    session_start();
    include_once './FrontdeskController.php';
    include_once '../Models/Exceptions.php';
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];   

        $frontdesk = new FrontdeskController(); 
        $r = $frontdesk->deleteFrontdeskById($id);
        if($r[0]=='success'){
            header("Refresh: 2, url=../../pages/frontdesk.php");
            $exc = new ExceptionAlert($r[1]);
            echo $exc->alerts();
        }else{
            header("Refresh: 3, url=../../pages/frontdesk.php");
            $exc = new ExceptionAlert($r[1]);
            echo $exc->alerts("error", "Erro");
        }

    }else{
        header("Refresh: 0, url=../../pages/frontdesk.php");
        $exc = new ExceptionAlert("Sem permissão");
        echo $exc->alerts('error','Erro');
    }


