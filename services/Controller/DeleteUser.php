<?php
    session_start();
    include_once './UserController.php';
    include_once '../Models/Exceptions.php';
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];   
        //echo $id;

        $userCont = new UserController();
        $user = $userCont->getById($id);
        $userName=$user['name'];

        $r = $userCont->deleteUser($id);
        if($r[0]=='success'){
            header("Refresh: 2, url=../../pages/users.php");
            $exc = new ExceptionAlert($r[1]);
            echo $exc->alerts();
        }else{
            header("Refresh: 3, url=../../pages/users.php");
            $exc = new ExceptionAlert($r[1]);
            echo $exc->alerts("error", "Erro");
        }
        
        


    }else{
        var_dump('aqui');
        exit;
        header("Refresh: 0, url=../../pages/users.php");
        $exc = new ExceptionAlert("Sem permissão");
        echo $exc->alerts('error','Erro');
    }


