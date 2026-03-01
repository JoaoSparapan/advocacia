<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/GlobalController.php";

class ProcessController extends GlobalController{

    function __construct(){
        parent::__construct();
    }

    public function insertProcess($numproc='', $client='', $idCourt='',$idUser){
        $conn = $this->connectDB();
        
        if($this->isAlreadyExists($numproc)){
            $this->disconnectDB($conn);
            $result=['error','Este processo já existe!'];
            return $result;
        }

        $query = "INSERT INTO process(numProcess,clientName,idCourt,idUser) VALUES('$numproc','$client',$idCourt,$idUser)";
        $result=NULL;
        $result = mysqli_query($conn, $query);
        if($result){
            $result = ['success', 'Sucesso ao cadastrar processo!'];
        }else{
            $result=['error','Erro ao cadastrar processo!'];
        }
        $this->disconnectDB($conn);
        return $result;
    }

    public function deleteProcess($id='0'){
        if($id=='0'){
            return ['error','Parâmetros incorretos!'];
        }
        $result=false;
        
        if(AuthController::getUser()['idRole']==1){
            $conn = $this->connectDB();
            $query = "DELETE FROM process WHERE idProcess=$id";
            $result = mysqli_query($conn, $query);
            $this->disconnectDB($conn);
        }else{
            return ['error', 'Sem permissão de acesso!'];
        }

        if($result){
            return ['success', 'Sucesso ao deletar processo'];
        }
        return ['error', 'Erro inesperado'];
    }

    public function updateProcess($numproc='', $clientName='', $idCourt='', $idUser='',$idProcess=''){
        $result=0;
        
        $conn = $this->connectDB();
        
        $query = "UPDATE process SET numProcess='$numproc',clientName='$clientName',idCourt='$idCourt', idUser=$idUser WHERE idProcess=$idProcess";          
        $r = mysqli_query($conn, $query);
        if($r){
            $result=['success', 'Sucesso ao alterar informações!'];
        }else{
            $result = ['error', 'Erro ao alterar informações!'];
        }   

        $this->disconnectDB($conn);

        return $result;
    }

    public function getById($idProcess=1){
        $conn = $this->connectDB();
        $query = "SELECT * FROM process WHERE idProcess=$idProcess";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_assoc($result);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getProcessByCourt($idCourt=0){
        $conn = $this->connectDB();
        $query = "";
        if($idCourt==0)
        {
            $query = "SELECT * FROM process";
        }else{
            $query = "SELECT * FROM process WHERE idCourt=$idCourt";
        }
        $result=null;
        $command = mysqli_query($conn, $query);
        if($command){

            $result = mysqli_fetch_all($command,MYSQLI_ASSOC);
        }
        $this->disconnectDB($conn);
        return $result;
    }

    public function getByClientName($nome=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM process WHERE clientName LIKE '%$nome%'";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getProcessResponsibleUser($nome=''){
        $conn = $this->connectDB();
        $query = "SELECT p.* FROM process p 
        INNER JOIN user u on (p.idUser = u.idUser) 
        WHERE u.name LIKE '%$nome%'";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    } 
    
        public function getByNumber($numproc=1, $equal=false, $include_vara=false){
            $conn = $this->connectDB();
            $query="";
            $vara="";
            
            if($include_vara){
                $vara="INNER JOIN court ON (court.idCourt = process.idCourt)";
            }

            $query = "SELECT * FROM process INNER JOIN user ON (user.idUser = process.idUser) $vara WHERE numProcess LIKE '%$numproc%'";
            
            if($equal=="true"){
                $query = "SELECT * FROM process INNER JOIN user ON (user.idUser = process.idUser) $vara WHERE numProcess = '$numproc'";
            }
            
            
            $result = mysqli_query($conn, $query);

            $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
            
            $this->disconnectDB($conn);
            return $r;
        }

        

    public function isAlreadyExists($numproc='', $clientName='', $idCourt=''){
        $conn = $this->connectDB();
        
        $query = "SELECT * FROM process WHERE process.numProcess='$numproc'";
      
        $result=NULL;
        $result = mysqli_query($conn, $query);
        $rows = mysqli_num_rows($result);

        $this->disconnectDB($conn);

        if($rows>0){
            return true;
        }

        return false;
    }
    
    public function getAllForTable($table='process'){
        return parent::getAllForTable($table);
    }

    public function updateUserResponsible($idProcess='', $idUser=''){
        $result=0;
        
        $conn = $this->connectDB();
        
        $query = "UPDATE process SET idUser=$idUser WHERE idProcess=$idProcess";          
        $r = mysqli_query($conn, $query);
        if($r){
            $result=['success', 'Responsável alterado com sucesso!'];
        }else{
            $result = ['error', 'Erro ao alterar responsável!'];
        }   

        $this->disconnectDB($conn);

        return $result;
    }

}


?>