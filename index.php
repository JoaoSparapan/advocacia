<?php 
session_start(); 
date_default_timezone_set('America/Sao_Paulo');
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/Router.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/UserController.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Models/Exceptions.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/PetitionController.php";
$route = new Router();
$pController = new PetitionController();
if(AuthController::getUser()==null){
    header('Location: '.$router->run('/login'));
}

//$providencesInprogressCurrentDay = $pController->getProvidencesInProgressFromCurrentDay();

/* var_dump($providencesInprogressCurrentDay);
exit; */

?>
<html lang="en">

<head>
    <title>Advocacia Bertoldi</title>
    <meta charset="utf-8">
    <meta http-equiv="Location" content="/pages/login.php">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./styles/css/home.css">
    <link rel="stylesheet" href="./styles/css/navbar.css">
    <link rel="stylesheet" href="./styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="shortcut icon" href="./logotipo.ico" type="image/x-icon">

</head>

<body>

    <?php
        include_once './components/navbar.php';
        /* include_once './services/Controller/ProvidenceController.php';
        $provController = new ProvidenceController(); */
        $pAnalise = $pController->getPetitionInProgress();
        $pConc = $pController->getPetitionDistributed();
    ?>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5">

        <h2 class="mb-4" style="margin-left: 1rem;">Bem-vindo, <?=AuthController::getUser()["name"]?>!</h2>
        <p style="margin-left: 1rem;">Sistema de gerenciamento de petições da Advocacia Bertoldi</p>
        <div class="tables-content">
            <div class="proj-table">
                <a href="./pages/petition.php">
                    <h3>Petições em Andamento</h3>
                    <table class="centered">
                        <thead>
                            <tr>
                                <th>Contratação</th>
                                <th>Natureza</th>
                                <th>Cliente</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $len=3;

                            if(sizeof($pAnalise)<3){
                                $len= sizeof($pAnalise);
                            }
                            
                            if($len>0){
                                for($i=0; $i < $len; $i++){
                                    $proj = $pAnalise[$i];                                     
                                    echo "<tr>
                                            <td>".date("d-m-Y", strtotime($proj[1]))."</td>
                                            <td>".$proj[5]."</td>
                                            <td>".$proj[2]."</td>
                                        </tr>";

                                }

                                
                            }else{
                                echo "<tr>
                                            <td colspan='3'>Nenhuma petição em andamento</td>
                                            
                                        </tr>";
                            }
                    
                            ?>

                        </tbody>
                    </table>
                </a>
            </div>

            <div class="proj-table">
                <a href="./pages/distributed.php">
                    <h3>Petições Concluídas</h3>
                    <table class="centered responsive-table">
                        <thead>
                            <tr>
                                <th>Contratação</th>
                                <th>Natureza</th>
                                <th>Cliente</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $len=3;

                            if(sizeof($pConc)<3){
                                $len= sizeof($pConc);
                            }
                            
                            if($len>0){
                                for($i=0; $i < $len; $i++){
                                    $proj = $pConc[$i];                                     
                                    echo "<tr>
                                            <td>".date("d-m-Y", strtotime($proj[1]))."</td>
                                            <td>".$proj[5]."</td>
                                            <td>".$proj[2]."</td>
                                        </tr>";

                                }

                                
                            }else{
                                echo "<tr>
                                            <td colspan='3'>Nenhuma petição concluída</td>
                                            
                                        </tr>";
                            }
                    
                            ?>

                        </tbody>
                    </table>
                </a>
            </div>

        </div>
    </div>

    <script src="./js/jquery.min.js"></script>
    <script src="./js/popper.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/jqueryAjax.min.js"></script>
    <script src="./js/main.js"></script>
    <?php
    
      //echo ($modal);
    
    ?>
</body>

</html>