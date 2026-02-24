<?php
date_default_timezone_set('America/Sao_Paulo');
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/GlobalController.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/LogController.php";

class PetitionController extends GlobalController{


    function __construct(){
        parent::__construct();
    }

    public function insertPetition($startDate='', $clientName='', $typeAction='', $idUser=0, $adverse='',$priority=0,$prescription=0,$decadence=0,$unfounded=0){
        $distributed=0;
        $logC = new LogController();
        $conn = $this->connectDB();
        $query = "INSERT INTO petition(startDate,clientName,distributed,prescription,decadence,unfounded,priority,typeAction,idUser, adverse) VALUES('$startDate', '$clientName','$distributed','$prescription','$decadence','$unfounded','$priority','$typeAction','$idUser', '$adverse')";
        $result=NULL;

        $result=NULL;
        $result = mysqli_query($conn, $query);
        
        if($result){
            $result = ['success', 'Sucesso ao cadastrar petição!'];
            $aux = $this->getLastPetition();
            if($unfounded==1)
            {
                $logC->insertLog(date('Y-m-d H:i:s'),2,$idUser,$aux['idPetition']);
            }
        }else{
            $result=['error','Erro ao cadastrar petição!'];
        }
        $this->disconnectDB($conn);
        return $result;
    }

    public function getPetitionById($id){
        $conn = $this->connectDB();
        $query = "SELECT * FROM petition WHERE idPetition=$id";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_assoc($result);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getPetitionByDaysForTrial($days=1, $petition_distributed=0,$include_name_responsible_user=0){
        $conn = $this->connectDB();
        $inner="";
        $select="petition.*";
        $distributed="";
        

        if($include_name_responsible_user==1){
            $inner= "INNER JOIN user u on (u.idUser = petition.idUser)";
            $select.=", u.name";
        }
        $query = "SELECT $select FROM petition $inner WHERE DATEDIFF(now(), petition.startDate)=$days AND petition.distributed=$petition_distributed ORDER BY priority DESC";
        
        $result = mysqli_query($conn, $query);
        $registro = mysqli_fetch_all($result);
        return $registro;
    }

    public function getPetitionByClient($name='', $petition_distributed=0,$include_name_responsible_user=0,$petition_sf=0){
        $conn = $this->connectDB();
        $inner="";
        $select="petition.*";
        $distributed="";
        
        if($include_name_responsible_user==1){
            $inner= "INNER JOIN user u on (u.idUser = petition.idUser)";
            $select.=", u.name";
        }
        $sf="";
        if($petition_sf==0)
        {
            $sf="AND unfounded=0";
        }else{
            $sf="OR unfounded=1";
        }
        $query = "SELECT $select FROM petition $inner WHERE clientName LIKE '%$name%'AND (petition.distributed=$petition_distributed $sf) ORDER BY priority DESC,startDate ASC";
        //echo $query;
        $result = mysqli_query($conn, $query);
        $registro = mysqli_fetch_all($result);
        return $registro;
    }

    public function getPetitionByActionType($action='', $petition_distributed=0,$include_name_responsible_user=0,$petition_sf=0){
        $conn = $this->connectDB();
        $inner="";
        $select="petition.*";
        $distributed="";
        $sf="";
        if($petition_sf==0)
        {
            $sf="AND unfounded=0";
        }else{
            $sf="OR unfounded=1";
        }
        if($include_name_responsible_user==1){
            $inner= "INNER JOIN user u on (u.idUser = petition.idUser)";
            $select.=", u.name";
        }
        $query = "SELECT $select FROM petition $inner WHERE typeAction LIKE '%$action%' AND (petition.distributed=$petition_distributed $sf) ORDER BY priority DESC";
        //echo $query;exit;
        $result = mysqli_query($conn, $query);
        $registro = mysqli_fetch_all($result);
        return $registro;
    }

    public function getPetitionByResponsibleUser($username='', $petition_distributed=0,$include_name_responsible_user=0,$petition_sf=0){
        $conn = $this->connectDB();
        $inner="";
        $select="petition.*";
        $distributed="";
        $sf="";
        if($petition_sf==0)
        {
            $sf="AND unfounded=0";
        }else{
            $sf="OR unfounded=1";
        }
        if($include_name_responsible_user==1){
            $inner= "INNER JOIN user u on (u.idUser = petition.idUser)";
            $select.=", u.name";
        }
        $query = "SELECT $select FROM petition $inner WHERE u.name LIKE '%$username%' AND (petition.distributed=$petition_distributed $sf) ORDER BY priority DESC,startDate ASC";
        // echo $query;
        $result = mysqli_query($conn, $query);
        $registro = mysqli_fetch_all($result);
        return $registro;
    }

    public function getPetitionByAdverse($adverse='', $petition_distributed=0,$include_name_responsible_user=0,$petition_sf=0){
        $conn = $this->connectDB();
        $inner="";
        $select="petition.*";
        $distributed="";
        $sf="";
        if($petition_sf==0)
        {
            $sf="AND unfounded=0";
        }else{
            $sf="OR unfounded=1";
        }
        if($include_name_responsible_user==1){
            $inner= "INNER JOIN user u on (u.idUser = petition.idUser)";
            $select.=", u.name";
        }
        $query = "SELECT $select FROM petition $inner WHERE adverse LIKE '%$adverse%' AND (petition.distributed=$petition_distributed $sf) ORDER BY priority DESC,startDate ASC";
        //echo $query;exit;
        $result = mysqli_query($conn, $query);
        $registro = mysqli_fetch_all($result);
        return $registro;
    }

    public function getAllForTable($table='petition'){
        return parent::getAllForTable($table);
    }

    public function getProvidenceByData($date_start='', $distributed=0, $include_name_responsible_user=0,$petition_sf=0){
        $conn = $this->connectDB();
        $inner="";
        $select="p.*";
        if($date_start==""){
            return ['error', 'Data inicial não providenciada'];
        }
        if($include_name_responsible_user==1){
            $inner= "INNER JOIN user u on (u.idUser = p.idUser)";
            $select.=", u.name";
        }
        $sf="";
        if($petition_sf==0)
        {
            $sf="AND unfounded=0";
        }else{
            $sf="OR unfounded=1";
        }
        $query = "SELECT $select FROM petition p $inner WHERE p.startDate= '$date_start' AND (p.distributed=$distributed $sf) ORDER BY priority DESC,startDate ASC";
        $result=null;
        
        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);
            

            return $result;
    }

    public function getProvidenceByPeriod($date_start='',$date_end='', $distributed=0,$include_name_responsible_user=0,$petition_sf=0){
        $conn = $this->connectDB();
        $inner="";
        $select="p.*";
        if($date_start==""){
            return ['error', 'Data inicial não providenciada'];
        }
        if($date_end==""){
            return ['error', 'Data final não providenciada'];
        }
        if($include_name_responsible_user==1){
            $inner= "INNER JOIN user u on (u.idUser = p.idUser)";
            $select.=", u.name";
        }
        $sf="";
        if($petition_sf==0)
        {
            $sf="AND unfounded=0";
        }else{
            $sf="OR unfounded=1";
        }
        $query = "SELECT $select FROM petition p $inner WHERE p.startDate>='$date_start' AND p.startDate<='$date_end' AND (p.distributed=$distributed $sf) ORDER BY priority DESC,startDate ASC";
        //echo $query;exit;
        $result=null;

        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);


            return $result;
    }

    public function getPetitionInProgress($distributed=0){
        $conn = $this->connectDB();
        $query = "SELECT p.*, u.name FROM petition p inner join user u on (u.idUser=p.idUser) WHERE distributed=$distributed AND unfounded=0 ORDER BY priority DESC,startDate ASC";
        $result=null;
        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);
            

            return $result;
    }

    public function getPetitions(){
        $conn = $this->connectDB();
        $query = "SELECT p.*, u.name FROM petition p inner join user u on (u.idUser=p.idUser) ORDER BY startDate ASC";
        $result=null;
        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);
            

            return $result;
    }

    public function getLastPetition()
    {
        $conn = $this->connectDB();
        $query = "SELECT * FROM petition ORDER BY idPetition DESC LIMIT 1";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_assoc($result);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getPetitionDistributed(){
        $conn = $this->connectDB();
        $query = "SELECT p.*, u.name FROM petition p inner join user u on (u.idUser=p.idUser) WHERE distributed=1 OR unfounded=1 ORDER BY priority DESC,startDate ASC";
        $result=null;
        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);
            

            return $result;
    }

    public function deletePetition($id='0'){
        $logC = new LogController();
        if($id=='0'){
            return ['error','Parâmetros incorretos!'];
        }
        $result=false;
        
        if(AuthController::getUser()['idRole']==1){
            $conn = $this->connectDB();
            $query = "DELETE FROM petition WHERE idPetition=$id";
            $result = mysqli_query($conn, $query);
            $this->disconnectDB($conn);
        }else{
            return ['error', 'Sem permissão de acesso!'];
        }

        if($result){
            $logC->deleteLog($id);
            return ['success', 'Sucesso ao deletar petição'];
        }
        return ['error', 'Erro inesperado'];
    }

    public function updatePetition($idPetition, $startDate='', $clientName='', $typeAction='', $idUser=0, $adverse='',$priority=0,$prescription=0,$decadence=0){
        $result=0;
        
        $conn = $this->connectDB();
        
        // echo "diff"."<br>";
        $vrf=($this->getPetitionById($idPetition));
        
        if($vrf){
            $query = "UPDATE petition SET startDate='$startDate',clientName='$clientName',typeAction='$typeAction',idUser=$idUser,adverse='$adverse',prescription='$prescription',decadence='$decadence',priority='$priority' WHERE idPetition=$idPetition";            
            // echo $query;
            $r = mysqli_query($conn, $query);

            if($r){
                $result=['success', 'Sucesso ao alterar informações!'];
            }else{
                $result = ['error', 'Erro ao alterar informações!'];
                }   
        }else{
             $result = ['error', 'Petição não encontrada'];
        }
            
        $this->disconnectDB($conn);

        return $result;
    }

    public function updatePetitionToDistributed($idPetition,$dis,$sf,$idUser){
        $logC = new LogController();
        $result=0;
        
        $conn = $this->connectDB();
        
        $vrf=($this->getPetitionById($idPetition));
        
        if($vrf){
            $query = "UPDATE petition SET distributed=$dis, unfounded=$sf WHERE idPetition=$idPetition";
            $r = mysqli_query($conn, $query);
            if($r){
                $result=['success', 'Sucesso ao alterar informações!'];
                if($sf==1)
                {
                    $logC->insertLog(date('Y-m-d H:i:s'),2,$idUser,$idPetition);
                }else{
                    $logC->insertLog(date('Y-m-d H:i:s'),$dis,$idUser,$idPetition);
                }
            }else{
                $result = ['error', 'Erro ao alterar informações!'];
                }   
        }else{
             $result = ['error', 'Petição não encontrada'];
        }
            
        $this->disconnectDB($conn);

        return $result;
    }

}


?>