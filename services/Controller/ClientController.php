<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/GlobalController.php";

class ClientController extends GlobalController{

    function __construct(){
        parent::__construct();
    }

    public function insertClient($name='', $cpf='', $phone='', $email=''){
        $conn = $this->connectDB();
        $query = "INSERT INTO client(name,email,phone,cpf) VALUES('$name', '$email','$phone','$cpf')";
        $vrf=null;
        $vrfEmail=null;
        if($cpf!='')
            $vrf = ($this->getByCpf($cpf));
        if($email!='')    
            $vrfEmail=($this->getByEmail($email));
        $result=NULL;
        if($vrf==NULL && $vrfEmail==NULL){
            $result = mysqli_query($conn, $query);
            $result = ['success', 'Sucesso ao cadastrar cliente!'];
        }else{
            $result=['error','Este email ou CPF já está em uso!'];
        }
        $this->disconnectDB($conn);
        return $result;
    }

    public function getByCpf($cpf=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM client WHERE cpf='$cpf'";
        $result = mysqli_query($conn, $query);
        if($result){
            $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return $r;
    }

    public function getByEmail($email=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM client WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getByPhone($phone=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM client WHERE phone='$phone'";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getByName($name=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM client WHERE name LIKE '%$name%'";
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
        $query = "SELECT * FROM client WHERE idClient=$id";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_assoc($result);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getAllForTable($table='client'){
        return parent::getAllForTable($table);
    }

    public function deleteClient($idClient='0'){
        if($idClient=='0'){
            return ['error','Parâmetros incorretos!'];
        }
        $result=false;
        
        if(AuthController::getUser()['idRole']==1 ||AuthController::getUser()['idRole']==2){
            $conn = $this->connectDB();
            $query = "DELETE FROM client WHERE idClient=$idClient";
            $result = mysqli_query($conn, $query);
            $this->disconnectDB($conn);
        }else{
            return ['error', 'Sem permissão de acesso!'];
        }

        if($result){
            return ['success', 'Sucesso ao deletar cliente'];
        }
        return ['error', 'Erro inesperado'];
    }

    public function updateClient($name='',$phone='',$cpf='',$email='',$idClient=''){
        $result=0;
        
        $conn = $this->connectDB();
        
        $query = "UPDATE client SET cpf='$cpf',name='$name', email='$email', phone='$phone' WHERE idClient=$idClient";     
        $r = mysqli_query($conn, $query);
        if($r){
            $result=['success', 'Sucesso ao alterar informações!'];
        }else{
            $result = ['error', 'Erro ao alterar informações!'];
        }   

        $this->disconnectDB($conn);

        return $result;
        
    }

}


?>