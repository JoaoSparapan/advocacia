<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/GlobalController.php";

class UserController extends GlobalController{

    function __construct(){
        parent::__construct();
    }

    public function insertUser($name='', $cpf='', $password='', $email='', $idRole=3){
        $conn = $this->connectDB();
        $encrypt_pass = crypt($password, '$1$rasmusle$');
        $query = "INSERT INTO user(name,cpf,password,email,idRole) VALUES('$name', '$cpf','$encrypt_pass','$email', $idRole)";
        $vrf = ($this->getByCpf($cpf));
        $vrfEmail=($this->getByEmail($email));
        $result=NULL;
        if($vrf==NULL && $vrfEmail==NULL){
            $result = mysqli_query($conn, $query);
            $result = ['success', 'Sucesso ao cadastrar usuário!'];
        }else{
            $result=['error','Este email ou CPF já está em uso!'];
        }
        $this->disconnectDB($conn);
        return $result;
    }

    public function getByCpf($cpf=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM user WHERE cpf='$cpf'";
        $result = mysqli_query($conn, $query);
        $registro = mysqli_fetch_assoc($result);
        return $registro;
    }

    public function getByEmail($email=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getRoleByIdUser($id){
        $conn = $this->connectDB();
        $query = "SELECT description FROM role WHERE idRole='$id'";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r[0]['description'];
    }

    public function getByRole($role=3){
        $conn = $this->connectDB();
        $query = "SELECT * FROM user WHERE idRole=$role";
        $result = mysqli_query($conn, $query);
        $r=null;
        if($result){
            $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        $this->disconnectDB($conn);
        return $r;
    }

    public function getAdminAndColaboradorAll($role=3){
        $conn = $this->connectDB();
        $query = "SELECT * FROM user WHERE idRole!=$role";
        $result = mysqli_query($conn, $query);
        $r=null;
        if($result){
            $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        $this->disconnectDB($conn);
        return $r;
    }

    public function getAdminAndColaboradorByEmail($email,$role=3){
        $conn = $this->connectDB();
        $query = "SELECT * FROM user WHERE email='$email' and idRole!=$role";
        $result = mysqli_query($conn, $query);
        $r=null;
        if($result){
            $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        $this->disconnectDB($conn);
        return $r;
    }

    public function getByName($name=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM user WHERE name LIKE '%$name%'";
        $result = mysqli_query($conn, $query);
        $r=null;
        if($result){
            $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        $this->disconnectDB($conn);
        return $r;
    }

    public function getById($id=1){
        $conn = $this->connectDB();
        $query = "SELECT * FROM user WHERE idUser=$id";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_assoc($result);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getAllForTable($table='user'){
        return parent::getAllForTable($table);
    }

    public function deleteUser($idUser='0'){
        if($idUser=='0'){
            return ['error','Parâmetros incorretos!'];
        }
        $result=false;
        
        if(AuthController::getUser()['idRole']==1){
            $conn = $this->connectDB();
            $query = "DELETE FROM user WHERE idUser=$idUser";
            $result = mysqli_query($conn, $query);
            $this->disconnectDB($conn);
        }else{
            return ['error', 'Sem permissão de acesso!'];
        }

        if($result){
            return ['success', 'Sucesso ao deletar usuário'];
        }
        return ['error', 'Erro inesperado'];
    }

    public function updatePasswordForget($email='', $newpassword='')
    {
        $conn = $this->connectDB();
        $encrypt_pass = crypt($newpassword, '$1$rasmusle$');
        $query = "UPDATE user SET password='$encrypt_pass' WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        $this->disconnectDB($conn);

        if($result){
            return ['success', 'Sucesso ao alterar senha'];
        }
        
        return ['error', 'Erro inesperado'];
    }

    public function updateUserProfile($name='',$cpf='',$pass='', $email='', $idUser=''){
        $result=0;
        
        $conn = $this->connectDB();
        if(AuthController::getUser()['email']==$email){
            $query = "UPDATE user SET name='$name',password='$pass',cpf='$cpf', email='$email' WHERE idUser=$idUser";            
            $r = mysqli_query($conn, $query);
            if($r){
                $new_user = $this->getById($idUser);
                AuthController::setUser($new_user);
                $result=['success', 'Sucesso ao alterar informações!'];
            }else{
                $result = ['error', 'Erro ao alterar informações!'];
            }
            
            
        }else{
            // echo "diff"."<br>";
            $vrfEmail=($this->getByEmail($email));
            
            if($vrfEmail==NULL){
                // echo "nao encontramos esse emaul na base";
                $query = "UPDATE user SET name='$name', password='$pass',cpf='$cpf', email='$email' WHERE idUser=$idUser";            
                $r = mysqli_query($conn, $query);
                if($r){
                    $new_user = $this->getById($idUser);
                    AuthController::setUser($new_user);
                    $result=['success', 'Sucesso ao alterar informações!'];
                }else{
                    $result = ['error', 'Erro ao alterar informações!'];
                }
            }else{
                $result = ['error', 'Já existe um usuário com esse email!'];
            }
        }
        $this->disconnectDB($conn);

        return $result;
    }

    public function updateUser($name='',$cpf='',$email='', $idUser=''){
        $result=0;
        
        $conn = $this->connectDB();
        
        // echo "diff"."<br>";
        $vrfEmail=($this->getByEmail($email));
        
        if($vrfEmail==NULL){
            // echo "nao encontramos esse emaul na base";
            $query = "UPDATE user SET cpf='$cpf',name='$name', email='$email' WHERE idUser=$idUser";            
            $r = mysqli_query($conn, $query);
            if($r){
                $new_user = $this->getById($idUser);
                AuthController::setUser($new_user);
                $result=['success', 'Sucesso ao alterar informações!'];
            }else{
                $result = ['error', 'Erro ao alterar informações!'];
            }
        }else{
            if($vrfEmail[0]['idUser']==$idUser){
                $query = "UPDATE user SET cpf='$cpf',name='$name', email='$email' WHERE idUser=$idUser";            
                $r = mysqli_query($conn, $query);
                if($r){
                    $new_user = $this->getById($idUser);
                    AuthController::setUser($new_user);
                    $result=['success', 'Sucesso ao alterar informações!'];
                }else{
                    $result = ['error', 'Erro ao alterar informações!'];
                }   
            }else{
                $result = ['error', 'Já existe um usuário com esse email!'];
            }
            
        }
        
        $this->disconnectDB($conn);

        return $result;
    }

}


?>