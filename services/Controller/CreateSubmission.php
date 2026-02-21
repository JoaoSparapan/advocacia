<?php
    session_start();
    include_once './SubmissionController.php';
    include_once '../Models/Exceptions.php';
    
    $dateSubmission = addslashes($_POST['datesubmission']);

    $controller = new SubmissionController();

    $r = $controller->updateSubmission($dateSubmission);

    if($r[0]=='success'){
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts();
    }else{
        $exc = new ExceptionAlert($r[1]);
        echo $exc->alerts("error", "Erro");
    }
    header("Refresh: 2, url=../../pages/providences.php");
?>