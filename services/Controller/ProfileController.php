<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/RequireLogin.php";
    include_once './UserController.php';
    include_once './AuthController.php';
    include_once '../Models/Exceptions.php';

    $nome = addslashes($_POST['name']);
    $email = addslashes($_POST['email']);
    $senhaAnt = trim($_POST['senhaantiga']);
    $cpf = addslashes($_POST['cpf']);
    $novaSenha = trim($_POST['senhaagr']);
    $confirmSenha = trim($_POST['senhaagrconfirm']);

    $contUser = new UserController();
    $pass_current_user = $contUser->getById(AuthController::getUser()['idUser'])['password'];
    
    if(!AuthController::checkPassword($senhaAnt, $pass_current_user)){
        header("Refresh: 3, url=../../pages/profile.php");
        $exc = new ExceptionAlert("Senha antiga não corresponde com a informada!",3000);
        echo $exc->alerts("error", "Erro ao alterar informações");
        
        
    }else{
        $userContr = new UserController();
        $res=null;
        if(trim($novaSenha)=="" || trim($confirmSenha)=="")
        {
            $res = $userContr->updateUserProfile($nome, $cpf,AuthController::hashPassword($senhaAnt),$email, AuthController::getUser()['idUser']);
        }else{
            $res = $userContr->updateUserProfile($nome, $cpf,AuthController::hashPassword($novaSenha),$email, AuthController::getUser()['idUser']);
        }
        
        if($res[0]=="success"){
            header("Refresh: 3, url=../../pages/profile.php");
            $exc = new ExceptionAlert("Informações alteradas com sucesso!",3000);
            echo $exc->alerts("success", "Sucesso ao alterar!");
            
        }else{
            header("Refresh: 3, url=../../pages/profile.php");
            $exc = new ExceptionAlert($res[1],3000);
            echo $exc->alerts("error", "Erro");
            
        }

    }



    

