<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/GlobalController.php";

class VaraController extends GlobalController{

    function __construct(){
        parent::__construct();
    }

    public function insertVara($sigla=''){
        $conn = $this->connectDB();
        
        $query = "INSERT INTO court(sigla) VALUES('$sigla')";
        
        $result=NULL;
        
        $result = mysqli_query($conn, $query);
        if($result){
          $result = ['success', 'Sucesso ao cadastrar vara!'];
        }else{
          $result=['error','Erro ao cadastrar vara!'];
        }
      
        
        $this->disconnectDB($conn);
        return $result;
    }

    public function getAll(){
      $conexao = $this->connectDB();
      $command = "SELECT * FROM court ORDER BY sigla ASC";
      $result = mysqli_query($conexao, $command);
      $r=null;
      if($result){
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
      }
      $this->disconnectDB($conexao);
      return $r;
      
    }

   public function getById($id=1){
    $conn = $this->connectDB();
    $query = "SELECT * FROM court WHERE idCourt=$id";
    $result = mysqli_query($conn, $query);
    $r = mysqli_fetch_assoc($result);
    $this->disconnectDB($conn);
    return $r;
    }

  public function deleteCourt($id='0'){
    if($id=='0'){
        return ['error','Parâmetros incorretos!'];
    }
    $result=false;
    $conn = $this->connectDB();
    $q="SELECT * FROM process p where p.idCourt=$id";
    $command = mysqli_query($conn, $q);
    $result = mysqli_fetch_assoc($command);
    if($result)
    {
      return ['error', 'Essa vara possui processo(s) vinculado(s), exclua-o(s) antes!'];
    }
    
    if(AuthController::getUser()['idRole']==1){
        $conn = $this->connectDB();
        $query = "DELETE FROM court WHERE idCourt=$id";
        $result = mysqli_query($conn, $query);
        $this->disconnectDB($conn);
    }else{
        return ['error', 'Sem permissão de acesso!'];
    }

    if($result){
        return ['success', 'Sucesso ao deletar vara'];
    }
    return ['error', 'Erro inesperado'];
}

  public function getLastVara(){
      $conn = $this->connectDB();
      
      $query = "SELECT * FROM court ORDER BY idCourt DESC LIMIT 1";
      
      $result=NULL;
      
      $result = mysqli_query($conn, $query);
      
      $dados = mysqli_fetch_assoc($result);
      
      $this->disconnectDB($conn);
      return $dados;
  }

  public function getVaraBySigla($sigla){
    $conn = $this->connectDB();
    
    $query = "SELECT * FROM court WHERE sigla LIKE '%$sigla%'";
    
    $result=NULL;
    
    $result = mysqli_query($conn, $query);
    
    $dados = mysqli_fetch_assoc($result);
    
    $this->disconnectDB($conn);
    return $dados;
}
  


}


?>