<?php
    session_start();
    include_once './PetitionController.php';
    include_once '../Models/Exceptions.php';
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];   

        $petition = new PetitionController();
        $verif=$petition->getPetitionById($id);
        $r = $petition->deletePetition($id);

        if($r[0]=='success'){
            if($verif['distributed']==0)
            {
                header("Refresh: 2, url=../../pages/petition.php");
                $exc = new ExceptionAlert($r[1]);
                echo $exc->alerts();
            }else{
                header("Refresh: 2, url=../../pages/distributed.php");
                $exc = new ExceptionAlert($r[1]);
                echo $exc->alerts();
            }
        }else{
            if($verif['distributed']==0)
            {
                header("Refresh: 3, url=../../pages/petition.php");
                $exc = new ExceptionAlert($r[1]);
                echo $exc->alerts("error", "Erro");
            }else{
                header("Refresh: 3, url=../../pages/distributed.php");
                $exc = new ExceptionAlert($r[1]);
                echo $exc->alerts("error", "Erro");
            }
        }
        
        
    }else{
        if($verif['distributed']==0)
            {
                header("Refresh: 0, url=../../pages/petition.php");
                $exc = new ExceptionAlert("Sem permissão");
                echo $exc->alerts('error','Erro');
            }else{
                header("Refresh: 0, url=../../pages/distributed.php");
                $exc = new ExceptionAlert("Sem permissão");
                echo $exc->alerts('error','Erro');
            }
        
    }


