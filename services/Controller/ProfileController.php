<?php
    session_start();
    include_once './UserController.php';
    include_once './AuthController.php';
    include_once '../Models/Exceptions.php';

    $nome = addslashes($_POST['name']);
    $email = addslashes($_POST['email']);
    $senhaAnt = addslashes($_POST['senhaantiga']);
    $cpf = addslashes($_POST['cpf']);
    $novaSenha = addslashes($_POST['senhaagr']);
    $confirmSenha = addslashes($_POST['senhaagrconfirm']);

    $pass_current_user = AuthController::getUser()['password'];
    $contUser = new UserController();
    
    if(crypt($senhaAnt, '$1$rasmusle$')!=$pass_current_user){
        header("Refresh: 3, url=../../pages/profile.php");
        $exc = new ExceptionAlert("Senha antiga não corresponde com a informada!",3000);
        echo $exc->alerts("error", "Erro ao alterar informações");
        
        
    }else{
        $userContr = new UserController();
        $res=null;
        if(trim($novaSenha)=="" || trim($confirmSenha)=="")
        {
            $res = $userContr->updateUserProfile($nome, $cpf,crypt($senhaAnt,'$1$rasmusle$'),$email, AuthController::getUser()['idUser']);
        }else{
            $res = $userContr->updateUserProfile($nome, $cpf,crypt($novaSenha,'$1$rasmusle$'),$email, AuthController::getUser()['idUser']);
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



    

