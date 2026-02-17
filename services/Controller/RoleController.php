<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/GlobalController.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/Router.php";

class RoleController extends GlobalController{

    function __construct(){
        parent::__construct();
    }

    public function getByID($id=1,$conn=NULL){
        if($conn==NULL){
            $conn = $this->connectDB();
        }
        $query = "SELECT * FROM role WHERE idRole = $id";
        $result = mysqli_query($conn,$query);
        $r=false;
        if($result){
            $r = mysqli_fetch_assoc($result);
        }
        $this->disconnectDB($conn);
        return $r;
    }
    public function getAllForTable($table='role'){
        
       return  parent::getAllForTable($table);
    }
     
}
$router = new Router;
if(AuthController::getUser()==null){
    header('Location: '.$router->run('/login'));
}

?>