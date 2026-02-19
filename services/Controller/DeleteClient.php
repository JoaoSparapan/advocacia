<?php
    session_start();
    include_once './ClientController.php';
    include_once '../Models/Exceptions.php';
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];   

        $clientC = new ClientController(); 
        $r = $clientC->deleteClient($id);
        if($r[0]=='success'){
            header("Refresh: 2, url=../../pages/clients.php");
            $exc = new ExceptionAlert($r[1]);
            echo $exc->alerts();
        }else{
            header("Refresh: 3, url=../../pages/clients.php");
            $exc = new ExceptionAlert($r[1]);
            echo $exc->alerts("error", "Erro");
        }

    }else{
        header("Refresh: 0, url=../../pages/clients.php");
        $exc = new ExceptionAlert("Sem permissão");
        echo $exc->alerts('error','Erro');
    }


