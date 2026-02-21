<?php session_start(); 
    include_once './ProcessController.php';
    include_once './ProvidenceController.php';
    include_once './HolidayController.php';
    include_once './AuthController.php';
    include_once '../Models/Exceptions.php';

    $url_callback="../../pages/index.php";
    if(AuthController::getUser()==null){
        header("Refresh: 2, url=$url_callback");
        $exc = new ExceptionAlert('Sem permissão para isso!');
        echo $exc->alerts("error", "Erro");
        exit;
    }
    $startDate = addslashes($_POST['data-intim']);
    $postDate = addslashes($_POST['data-publi']);
    $numProcess = addslashes($_POST['process']);
    $providencia = addslashes($_POST['providencia']);
    $id = addslashes($_POST['id']);
    $url_callback=addslashes($_POST['callback']);
    

    if($numProcess==""){
        header("Refresh: 2, url=$url_callback");
        $exc = new ExceptionAlert('Número do processo não informado');
        echo $exc->alerts("error", "Erro");
        exit;
    }
    $type=-1;
    if(isset($_POST['sel-days'])){
        $type= addslashes($_POST['sel-days']);
    }
    
    $prazo = addslashes($_POST['qte-dias']);
    $state=-1;
    if(isset($_POST['state-holiday']))
    {
        $state=0;
    }else{
        $state=1;
    }
    
    $national=-1;
    if(isset($_POST['national-holiday']))
    {
        $national=1;
    }else{
        $national=0;
    }
    $holiday = new HolidayController();
    $endDate = $holiday->estimateTerm($postDate,$prazo,$state,$national,$type);
    
    $endDate = $endDate." 18:00:00";
    
    $obj = new ProcessController();
    $process = $obj->getByNumber($numProcess,true);
    $proc = new ProvidenceController();
    $result = $proc->updateProvidence($id,$process[0]['idProcess'], $startDate,$postDate, $endDate,$providencia,$prazo,$state,$national,$type);
    if($result[0]=='success'){
        
        header("Refresh: 2, url=$url_callback");
        $exc = new ExceptionAlert("Sucesso ao editar providência!");
        echo $exc->alerts();
    
    }else{
        header("Refresh: 2, url=$url_callback");
        $exc = new ExceptionAlert($result[1]);
        echo $exc->alerts("error", "Erro");
    }

?>