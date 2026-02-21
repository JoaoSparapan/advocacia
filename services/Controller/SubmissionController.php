<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/GlobalController.php";

class SubmissionController extends GlobalController{

    function __construct(){
        parent::__construct();
    }

    public function getLastSub(){
        $conn = $this->connectDB();

        $query = "SELECT * FROM submission ORDER BY idSubmission DESC LIMIT 1";

        $result=NULL;

        $result = mysqli_query($conn, $query);

        $dados = mysqli_fetch_assoc($result);

        $this->disconnectDB($conn);
        return $dados;
    }
 
    public function updateSubmission($updateData=''){
        $result=0;

        $conn = $this->connectDB();

        $query = "UPDATE submission SET updateDate='$updateData' WHERE idSubmission=1";

        $r = mysqli_query($conn, $query);
        if($r){
            $result=['success', 'Data de referência alterada com sucesso!'];
        }else{
            $result = ['error', 'Erro ao alterar informações!'];
        }

        $this->disconnectDB($conn);

        return $result;
    }

}


?>