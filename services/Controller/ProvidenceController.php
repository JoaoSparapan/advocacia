<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/GlobalController.php";

class ProvidenceController extends GlobalController{

    function __construct(){
        parent::__construct();
    }

    public function insertProvidence($idProcess='', $startDate='', $postDate='',$endDate='', $description='', $prazo=0,$state=0,$national=0,$type=0,$done=0, $providenced=0){
        $conn = $this->connectDB();
        $query = "INSERT INTO providence(idProcess,term,Hstate,Hnational,type,startDate,postDate,endDate,description,done,providenced) VALUES('$idProcess', '$prazo','$state','$national','$type','$startDate','$postDate','$endDate','$description', $done, $providenced)";

        $result=NULL;

        $result=NULL;
        $result = mysqli_query($conn, $query);
        if($result){
            $result = ['success', 'Sucesso ao cadastrar providência!'];
        }else{
            $result=['error','Erro ao cadastrar providência!'];
        }
        $this->disconnectDB($conn);
        return $result;
    }

    public function getProvidenceById($id){
        $conn = $this->connectDB();
        $query = "SELECT * FROM providence WHERE idProvidence=$id";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_assoc($result);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getProvidenceByClient($idClient=''){
        $conn = $this->connectDB();
        $query = "SELECT providence.startDate,providence.endDate,process.numProcess,court.sigla,client.name,providence.description,providence.done,providence.providenced FROM providence 
                        INNER JOIN process ON (process.idProcess = providence.idProcess) 
                        INNER JOIN client ON (process.idClient = client.idClient)
                        INNER JOIN court ON (process.idCourt = court.idCourt)
                 WHERE client.idClient=$idClient";
        $result = mysqli_query($conn, $query);
        $registro = mysqli_fetch_assoc($result);
        return $registro;
    }

    public function getProvidenceByResponsibleUser($name_user="", $providenced=0){
        
        $conn = $this->connectDB();
        $query = "SELECT *
        from providence
        inner join process on (process.idProcess = providence.idProcess) 
        inner join user on (user.idUser = process.idUser) 
        where user.name LIKE '%$name_user%' AND providence.providenced=$providenced order by endDate ASC";
        // echo $query;exit;
        $result=null;
        $command = mysqli_query($conn, $query);
        
        if($command){

            $result = mysqli_fetch_all($command);
        }
        
        
        $this->disconnectDB($conn);
        

        return $result;
    }


    public function getAllForTable($table='providence', $providenced=0){
        return parent::getAllForTable($table);
    }

    public function getProvidenceByDataIntim($date_start='', $date_end='',$providenced=0){
        $conn = $this->connectDB();
        if($date_start==""){
            return ['error', 'Data inicial não providenciada'];
        }
        if($date_end==""){
            return ['error', 'Data final não providenciada'];
        }
        $query = "SELECT * FROM providence p 
        INNER JOIN process on (process.idProcess = p.idProcess)
        WHERE p.startDate>= '$date_start' AND p.startDate<= '$date_end' AND p.providenced=$providenced ORDER BY endDate ASC";
        $result=null;
        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);
            

            return $result;
    }

    public function getProvidenceByDataTerm($date_start='', $date_end='',$providenced=0){
        
        $conn = $this->connectDB();
        $query="";
        if($date_start==""){
            return ['error', 'Data inicial não providenciada'];
        }
        if($date_end==""){
            return ['error', 'Data final não providenciada'];
        }
        if(trim($date_start)=="00:00:00" && trim($date_end)=="23:59:59")
        {
            $query = "SELECT * FROM providence 
            INNER JOIN process on (process.idProcess = providence.idProcess)
            WHERE providenced=$providenced ORDER BY endDate ASC";
        }else{
            $query = "SELECT * FROM providence p 
            INNER JOIN process on (process.idProcess = p.idProcess)
            WHERE p.endDate>= '$date_start' AND p.endDate<= '$date_end' AND p.providenced=$providenced ORDER BY endDate ASC";
        }
        $result=null;
        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);
            

            return $result;
    }

    public function getProvidenceInProgress($providenced=0){
        $conn = $this->connectDB();
        $query = "SELECT * FROM providence 
        INNER JOIN process on (process.idProcess = providence.idProcess)
        WHERE providenced=$providenced ORDER BY endDate ASC";
        $result=null;
        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);
            

            return $result;
    }

    public function getProvidences(){
        $conn = $this->connectDB();
        $query = "SELECT * FROM providence ORDER BY endDate ASC";
        $result=null;
        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);
            

            return $result;
    }

    public function getProvidenceByDate($date=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM providence p 
        INNER JOIN process on (process.idProcess = p.idProcess)
        WHERE p.endDate <= '$date' AND p.providenced=0";
        $result=null;
        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);
            

            return $result;
    }

    public function getProvidenceByNumProcess($numproc=0,$providenced=0){
        
        $conn = $this->connectDB();
        $query = "SELECT 
        *
        from providence 
        inner join process on (process.idProcess = providence.idProcess) 
        where process.numProcess LIKE '%$numproc%' AND providence.providenced=$providenced order by endDate ASC";
        //echo $query;
        $result=null;
        $command = mysqli_query($conn, $query);
        
        if($command){

            $result = mysqli_fetch_all($command);
        }
        
        
        $this->disconnectDB($conn);
        

        return $result;
    }
    public function getProvidencesInProgressFromCurrentDay(){
        $conn = $this->connectDB();
        $query = "SELECT * FROM providence p 
        INNER JOIN process on (process.idProcess = p.idProcess)
        WHERE CAST(p.endDate AS Date) = CAST(now() as Date) AND p.done=00";
        $result=null;
        
        $command = mysqli_query($conn, $query);
        
        if($command){
            $result = mysqli_fetch_all($command);
        }
        
        
        $this->disconnectDB($conn);
        

        return $result;
    }

    public function getProvidenceByCourt($courtId=0, $providenced=0){
        
        $conn = $this->connectDB();
        $query = "SELECT *
        from providence 
        inner join process on (process.idProcess = providence.idProcess) 
        where process.idCourt=$courtId AND providence.providenced=$providenced order by endDate ASC";
        
        $result=null;
        $command = mysqli_query($conn, $query);
        
        if($command){

            $result = mysqli_fetch_all($command);
        }
        
        
        $this->disconnectDB($conn);
        

        return $result;
    }

    public function getProvidenceByClientName($name="", $providenced=0){
        
        $conn = $this->connectDB();
        $query = "SELECT *
        from providence 
        inner join process on (process.idProcess = providence.idProcess) 
        where clientName LIKE '%$name%' AND providence.providenced=$providenced order by endDate ASC";
        
        $result=null;
        $command = mysqli_query($conn, $query);
        
        if($command){

            $result = mysqli_fetch_all($command);
        }
        
        
        $this->disconnectDB($conn);
        

        return $result;
    }

    public function getProvidenceProvidenced(){
        $conn = $this->connectDB();
        $query = "SELECT * FROM providence 
        inner join process on (process.idProcess = providence.idProcess)
        WHERE providenced=1 ORDER BY startDate ASC";
        $result=null;
        $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command);
            }

            $this->disconnectDB($conn);
            

            return $result;
    }

    public function deleteProvidence($id='0'){
        if($id=='0'){
            return ['error','Parâmetros incorretos!'];
        }
        $result=false;
        
        if(AuthController::getUser()['idRole']==1){
            $conn = $this->connectDB();
            $query = "DELETE FROM providence WHERE idProvidence=$id";
            $result = mysqli_query($conn, $query);
            $this->disconnectDB($conn);
        }else{
            return ['error', 'Sem permissão de acesso!'];
        }

        if($result){
            return ['success', 'Sucesso ao deletar providência'];
        }
        return ['error', 'Erro inesperado'];
    }

    public function updateProvidence($idProvidence='',$idProcess='', $startDate='', $postDate='',$endDate='', $description='',$prazo=0,$state=0,$national=0,$type=0){
        $result=0;
        
        $conn = $this->connectDB();
        
        // echo "diff"."<br>";
        $vrf=($this->getProvidenceById($idProvidence));
        
        if($vrf){
            $query = "UPDATE providence SET idProcess='$idProcess',startDate='$startDate',postDate='$postDate',endDate='$endDate',description='$description',term='$prazo',Hstate='$state',Hnational='$national',type='$type' WHERE idProvidence=$idProvidence";            
            $r = mysqli_query($conn, $query);
            if($r){
                $result=['success', 'Sucesso ao alterar informações!'];
            }else{
                $result = ['error', 'Erro ao alterar informações!'];
                }   
        }else{
             $result = ['error', 'Providência não encontrada'];
        }
            
        $this->disconnectDB($conn);

        return $result;
    }

    public function updateProvidenceToDone($idProvidence, $done)
    {
        $result=0;
        
        $conn = $this->connectDB();
        
        $vrf=($this->getProvidenceById($idProvidence));
        
        if($vrf){
            $query = "UPDATE providence SET done='b'.'$done' WHERE idProvidence=$idProvidence";            
            $r = mysqli_query($conn, $query);
            if($r){
                $result=['success', 'Sucesso ao alterar informações!'];
            }else{
                $result = ['error', 'Erro ao alterar informações!'];
                }   
        }else{
             $result = ['error', 'Providência não encontrada'];
        }
            
        $this->disconnectDB($conn);

        return $result;
    }

    public function updateProvidenceToProvidenced($idProvidence,$providenced)
    {
        $result=0;
        
        $conn = $this->connectDB();
        
        $vrf=($this->getProvidenceById($idProvidence));
        
        if($vrf){
            $query = "UPDATE providence SET providenced='b'.'$providenced' WHERE idProvidence=$idProvidence";        
            echo $query;exit;    
            $r = mysqli_query($conn, $query);
            if($r){
                $result=['success', 'Sucesso ao alterar informações!'];
            }else{
                $result = ['error', 'Erro ao alterar informações!'];
                }   
        }else{
             $result = ['error', 'Providência não encontrada'];
        }
            
        $this->disconnectDB($conn);

        return $result;
    }

    public function updateProvidenceFullStatus($idProvidence,$providenced,$done)
    {
        $result=0;
        
        $conn = $this->connectDB();
        
        $vrf=($this->getProvidenceById($idProvidence));
        
        if($vrf){
            $query = "UPDATE providence SET providenced=b'$providenced', done=b'$done' WHERE idProvidence=$idProvidence";            
            $r = mysqli_query($conn, $query);
            if($r){
                $result=['success', 'Sucesso ao alterar informações!'];
            }else{
                
                $result = ['error', 'Erro ao alterar informações!'];
            }   
        }else{
             $result = ['error', 'Providência não encontrada'];
        }
            
        $this->disconnectDB($conn);

        return $result;
    }


}


?>