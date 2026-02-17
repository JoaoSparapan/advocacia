<?php 
session_start();

include_once './PetitionController.php';
include_once '../Models/Exceptions.php';

$url_callback="../../index.php";

if(AuthController::getUser()!=null){

    $url_callback="../../pages/petition.php";
       
}else{
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert('Sem permissão para isso!');
    echo $exc->alerts("error", "Erro");
    exit;
}

$startDate = addslashes($_POST['dta-contact']);
$clientName= addslashes($_POST['clientName']);
$adverse = addslashes($_POST['adverse']);
$adv = addslashes($_POST['adv']);
// echo $adv;
// exit;
$typeAction= addslashes($_POST['typeAction']);
$petitionId= addslashes($_POST['petitionId']);
$url_callback=addslashes($_POST['callback']);


if($startDate=="" || $clientName=="" || $startDate=="" || $adverse=="" || $adv=="" || $typeAction==""){
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert('Por favor insira informe todos os campos!');
    echo $exc->alerts("error", "Erro");
    exit;
}
$priority=0;
$deca=0;
$presc=0;
if(isset($_POST['priority']))
{
    $priority=1;
}
if(isset($_POST['decProvidedModal']))
{
    $deca=1;
}
if(isset($_POST['prescProvidedModal']))
{
    $presc=1;
}


$origin = date_create(date('Y-m-d', strtotime($startDate)));
$target = date_create(date('Y-m-d'));
$interval = date_diff($origin, $target);
$aux=intval($interval->format('%a'));


if($aux<0){
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert('A data da contratação deve ser menor ou igual a hoje!');
    echo $exc->alerts("error", "Erro");
    exit;
}

$obj = new PetitionController();
$pet = $obj->getPetitionById($petitionId);
$result = $obj->updatePetition($petitionId,$startDate, $clientName, $typeAction, $adv, $adverse,$priority,$presc,$deca);

if($result[0]=='success'){
    
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts();

}else{
    header("Refresh: 2, url=$url_callback");
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts("error", "Erro");
}
 

?>