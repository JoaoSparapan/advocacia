<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/GlobalController.php";

    class ReceptionController extends GlobalController{

        function construct(){
            parent::construct();
        }

        public function createReception($arrival=NULL, $leave=NULL, $idResponsavel=NULL, $idClient=NULL,$assunto=NULL,$prov=NULL){

            if(in_array(NULL,array($arrival, $idResponsavel, $idClient, $assunto))){
                throw new Exception('Parâmetros inválidos');
            }

            $conn = $this->connectDB();

            $query = "INSERT INTO reception (`arrival`, `leave`, `subject`,`providence`,idResponsavel, idClient)
            VALUES ('$arrival',null, '$assunto', '$prov',$idResponsavel, $idClient)";
            //echo $query;exit;
            $result=NULL;   
            $result = mysqli_query($conn, $query);
    
            if($result){
                return ['success','Sucesso ao cadastrar registro de recepção'];
            }else{
                return ['error','Erro ao cadastrar registro de recepção'];
            }
    
            $this->disconnectDB($conn);
            return $dados;
        }

        public function getAllReception($order_direction="DESC"){
            $conn = $this->connectDB();
    
            $query = "SELECT * FROM reception ORDER BY arrival $order_direction";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
            $dados = array();
            if($result){
                $dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }else{
                throw new Exception('Erro ao requisitar recepções!');
            }
    
            $this->disconnectDB($conn);
            return $dados;
        }

        public function getAllReceptionById($receptionId=NULL,$order_direction="DESC"){
            $conn = $this->connectDB();

            $query = "SELECT * FROM reception WHERE idReception=$receptionId ORDER BY arrival $order_direction";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
    
            $dados = mysqli_fetch_assoc($result);
    
            $this->disconnectDB($conn);
            return $dados;
        }
        
        public function getAllReceptionBySubject($subject=NULL,$order_direction="DESC"){
            $conn = $this->connectDB();

            $query = "SELECT * FROM reception  WHERE subject=$subject ORDER BY arrival $order_direction";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
    
            $dados = mysqli_fetch_assoc($result);
    
            $this->disconnectDB($conn);
            return $dados;
        }
        
        public function getAllReceptionByResponsavel($responsavelId=NULL,$order_direction="DESC"){
            $conn = $this->connectDB();

            $query = "SELECT * FROM reception WHERE idResponsavel=$responsavelId ORDER BY arrival $order_direction";

            $result=NULL;
    
            $result = mysqli_query($conn, $query);
            $dados = array();
            if($result){
                $dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }else{
                throw new Exception('Erro ao requisitar recepções!');
            }
    
            $this->disconnectDB($conn);
            return $dados;
        }

        public function getAllReceptionByClientId($clientId=NULL,$order_direction="DESC"){
            $conn = $this->connectDB();

            $query = "SELECT * FROM reception  WHERE idClient=$clientId ORDER BY arrival $order_direction";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
            $dados = array();
            if($result){
                $dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }else{
                throw new Exception('Erro ao requisitar recepções!');
            }
    
            $this->disconnectDB($conn);
            return $dados;
        }

        public function getAllReceptionByClient($clientName=NULL,$order_direction="DESC"){
            $conn = $this->connectDB();

            $query = "SELECT * FROM reception r
            INNER JOIN client c ON (c.idClient = r.idClient)
            WHERE name LIKE '%$clientName%' ORDER BY arrival $order_direction";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
            $dados = array();
            if($result){
                $dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }else{
                throw new Exception('Erro ao requisitar recepções!');
            }
    
            $this->disconnectDB($conn);
            return $dados;
        }

        public function getAllReceptionByCpfClient($clientCpf=NULL,$order_direction="DESC"){
            $conn = $this->connectDB();

            $query = "SELECT * FROM reception r
            INNER JOIN client c ON (c.idClient = r.idClient)
            WHERE cpf LIKE '%$clientCpf%' ORDER BY arrival $order_direction";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
            $dados = array();
            if($result){
                $dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }else{
                throw new Exception('Erro ao requisitar recepções!');
            }
    
            $this->disconnectDB($conn);
            return $dados;
        }

        public function getAllReceptionByemailClient($clientEmail=NULL,$order_direction="DESC"){
            $conn = $this->connectDB();

            $query = "SELECT * FROM reception r
            INNER JOIN client c ON (c.idClient = r.idClient)
            WHERE email LIKE '%$clientEmail%' ORDER BY arrival $order_direction";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
            $dados = array();
            if($result){
                $dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }else{
                throw new Exception('Erro ao requisitar recepções!');
            }
    
            $this->disconnectDB($conn);
            return $dados;
        }

        public function getAllReceptionByPhoneClient($clientPhone=NULL,$order_direction="DESC"){
            $conn = $this->connectDB();

            $query = "SELECT * FROM reception r
            INNER JOIN client c ON (c.idClient = r.idClient)
            WHERE phone LIKE '%$clientPhone%' ORDER BY arrival $order_direction";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
            $dados = array();
            if($result){
                $dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }else{
                throw new Exception('Erro ao requisitar recepções!');
            }
    
            $this->disconnectDB($conn);
            return $dados;
        }

        public function getReceptionByData($date_start=''){
            $conn = $this->connectDB();
            
            if($date_start==""){
                return ['error', 'Data de referência não informada'];
            }
            $init=$date_start.' 00:00:00';
            $end=$date_start.' 23:59:59';
            $query = "SELECT * FROM reception WHERE arrival>='$init' AND arrival<='$end' ORDER BY arrival ASC";
            //echo $query;exit;
            $result=null;
            
            $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command, MYSQLI_ASSOC);
            }
    
            $this->disconnectDB($conn);
            return $result;
        }
    
        public function getReceptionByPeriod($date_start='',$date_end=''){
            $conn = $this->connectDB();
        
            if($date_start==""){
                return ['error', 'Data inicial não informada'];
            }
            if($date_end==""){
                return ['error', 'Data final não informada'];
            }
            
            $query = "SELECT * FROM reception WHERE arrival>='$date_start' AND arrival<='$date_end'";
            //echo $query;exit;
            $result=null;
    
            $command = mysqli_query($conn, $query);
            if($command){
                $result = mysqli_fetch_all($command, MYSQLI_ASSOC);
            }
    
            $this->disconnectDB($conn);
            return $result;
        }

        public function updateReceptionFinalizedById($id=NULL, $leave=NULL){
            if($id==NULL){
                return ['error','Erro ao finalizar atendimento!'];
            }

            $conn = $this->connectDB();
            $query="UPDATE reception SET `leave`='$leave' WHERE idReception=$id";
            //echo $query;exit;
            $result=NULL;
            $result = mysqli_query($conn, $query);
    
            if(!$result){
                return ['error','Erro ao finalizar atendimento!'];
            }
    
            $this->disconnectDB($conn);
            return ['success',"Sucesso ao finalizar atendimento!"];
        }

        public function updateReceptionById($id=NULL, $subject=NULL, $prov=NULL,$arrival=NULL, $leave=NULL, $responsavelId=NULL,$clientId=NULL){
            if($id==NULL){
                return ['error','Erro ao alterar registro de recepção'];
            }

            $conn = $this->connectDB();
            $query="UPDATE reception SET ";
            if($subject!=NULL){
                $query .= "`subject`='$subject'";
            }
            if($prov!=NULL){
                $query .= ", `providence`='$prov'";
            }
            if($responsavelId!=NULL){
                $query .= ", idResponsavel=$responsavelId";
            }
            if($arrival!=NULL){
                $query .= ", `arrival`='$arrival'";
            }
            if($leave!=NULL){
                $query .= ", `leave`='$leave'";
            }
            if($clientId!=NULL){
                $query .= ", idClient=$clientId";
            }

            if($query=="UPDATE reception SET "){
                return ['error','Erro ao alterar registro de recepção'];
            }
            
            $query.=" WHERE idReception=$id";
            //echo $query;exit;
            $result=NULL;
            $result = mysqli_query($conn, $query);
    
            if(!$result){
                return ['error','Erro ao alterar registro de recepção'];
            }
    
            $this->disconnectDB($conn);
            return ['success',"Sucesso ao alterar recepção!"];
        }

        public function deleteReceptionById($id=NULL){
            if($id==NULL){
                return ['error','Erro ao deletar registro de recepção'];
            }
            $conn = $this->connectDB();

            $query = "DELETE FROM reception WHERE idReception=$id";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
    
            if(!$result){
                return ['error','Erro ao deletar registro de recepção'];
            }
    
            $this->disconnectDB($conn);
            return ['success',"Sucesso ao deletar registro de recepção!"];
        }

    }