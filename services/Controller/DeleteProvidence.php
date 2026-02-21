<?php
    session_start();
    include_once './ProvidenceController.php';
    include_once '../Models/Exceptions.php';
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];   

        $providence = new ProvidenceController();
        $verif=$providence->getProvidenceById($id);
        $r = $providence->deleteProvidence($id);

        if($r[0]=='success'){
            if($verif['providenced']==0)
            {
                header("Refresh: 2, url=../../pages/providences.php");
                $exc = new ExceptionAlert($r[1]);
                echo $exc->alerts();
            }else{
                header("Refresh: 2, url=../../pages/providenced.php");
                $exc = new ExceptionAlert($r[1]);
                echo $exc->alerts();
            }
        }else{
            if($verif['providenced']==0)
            {
                header("Refresh: 3, url=../../pages/providences.php");
                $exc = new ExceptionAlert($r[1]);
                echo $exc->alerts("error", "Erro");
            }else{
                header("Refresh: 3, url=../../pages/providenced.php");
                $exc = new ExceptionAlert($r[1]);
                echo $exc->alerts("error", "Erro");
            }
        }
        
        
    }else{
        if($verif['providenced']==0)
            {
                header("Refresh: 0, url=../../pages/providences.php");
                $exc = new ExceptionAlert("Sem permissão");
                echo $exc->alerts('error','Erro');
            }else{
                header("Refresh: 0, url=../../pages/providenced.php");
                $exc = new ExceptionAlert("Sem permissão");
                echo $exc->alerts('error','Erro');
            }
        
    }


