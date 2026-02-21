<?php 
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../Mailer/PHPMailer/src/PHPMailer.php';
require '../Mailer/PHPMailer/src/SMTP.php';

include_once './ProcessController.php';
include_once './ProvidenceController.php';
include_once './HolidayController.php';
include_once '../Models/Exceptions.php';

$url_callback="../../pages/index.php";
$startDate = addslashes($_POST['data-intim']);
$postDate = addslashes($_POST['data-publi']);
$numProcess = addslashes($_POST['process']);
if($numProcess==""){
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert('Número do processo não informado');
    echo $exc->alerts("error", "Erro");
    exit;
}
$description= addslashes($_POST['providencia']);

$type=-1;
if(isset($_POST['sel-days-value'])){
    $type= addslashes($_POST['sel-days-value']);
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

if(AuthController::getUser()!=null){

    $url_callback="../../pages/providences.php";
       
}else{
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert('Sem permissão para isso!');
    echo $exc->alerts("error", "Erro");
    exit;
}

$obj = new ProcessController();
$process = $obj->getByNumber($numProcess);

$proc = new ProvidenceController();
$result = $proc->insertProvidence($process[0]['idProcess'], $startDate,$postDate, $endDate,$description,$prazo,$state,$national,$type);
if($result[0]=='success'){
    
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert("Sucesso ao cadastrar providência!");
    echo $exc->alerts();

}else{
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts("error", "Erro");
}
 

?>