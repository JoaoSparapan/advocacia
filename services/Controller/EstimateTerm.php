<?php 
session_start();


include_once './HolidayController.php';


$url_callback="../../pages/login.php";

$startDate = addslashes($_POST['data-publi']);
$type=-1;

if(isset($_POST['sel-days'])){
    $type= addslashes($_POST['sel-days']);
}

$prazo = addslashes($_POST['qte-dias']);
$state=-1;
if($_POST['state-holiday']==1)
{
    $state=0;
}else{
    $state=1;
}

$national=-1;
if($_POST['national-holiday']==1)
{
    $national=1;
}else{
    $national=0;
}

$holiday = new HolidayController();
$endDate = $holiday->estimateTerm($startDate,$prazo,$state,$national,$type);

$date = date('d/m/Y', strtotime($endDate));
echo $date;