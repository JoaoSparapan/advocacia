<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/GlobalController.php";

class LogController extends GlobalController{

    function __construct(){
        parent::__construct();
    }

    public function insertLog($edited_by='',$description=0,$idUser=0,$idPetition=0){
        $conn = $this->connectDB();
        $aux = $this->getAllLastLogByPetition($idPetition);
        $query = "";
        if($aux==null)
        {
            $query = "INSERT INTO log(edited_by,description,idUser,idPetition) VALUES('$edited_by', $description,$idUser,$idPetition)";
        }else{
            if($aux['description']==$description)
            {
                return true;
            }else{
                $query = "INSERT INTO log(edited_by,description,idUser,idPetition) VALUES('$edited_by', $description,$idUser,$idPetition)";
            }
        }
        
        $result = mysqli_query($conn, $query);
        $this->disconnectDB($conn);
        return $result;
    }

    public function deleteLog($idPetition)
    {
        $conn = $this->connectDB();
        $query = "DELETE FROM log WHERE idPetition=$idPetition";
        $result = mysqli_query($conn, $query);
        $this->disconnectDB($conn);
        return $result;
    }

    public function getLogByPetition($idPetition)
    {
        $conn = $this->connectDB();
        $query = "SELECT log.*,u.name FROM log INNER JOIN user u on (u.idUser = log.idUser) WHERE idPetition=$idPetition ORDER BY edited_by ASC";
        // echo $query;
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getLastLogByPetition($idPetition)
    {
        $conn = $this->connectDB();
        $query = "SELECT * FROM log WHERE idPetition='$idPetition' AND description=1 or description=2 ORDER BY edited_by DESC LIMIT 1";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_assoc($result);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getAllLastLogByPetition($idPetition)
    {
        $conn = $this->connectDB();
        $query = "SELECT * FROM log WHERE idPetition='$idPetition' ORDER BY edited_by DESC LIMIT 1";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_assoc($result);
        $this->disconnectDB($conn);
        return $r;
    }


}