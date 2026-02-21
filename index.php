<?php 
session_start(); 
date_default_timezone_set('America/Sao_Paulo');

include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/Router.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/UserController.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Models/Exceptions.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/PetitionController.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/ProvidenceController.php";

$route = new Router();
$pController = new PetitionController();
$provController = new ProvidenceController();

if(AuthController::getUser()==null){
    header('Location: '.$route->run('/login'));
    exit;
}

$pAnalise = $pController->getPetitionInProgress();
$pConc = $pController->getPetitionDistributed();

$provsAnalise = $provController->getProvidenceInProgress();
$provsConc = $provController->getProvidenceProvidenced();
$providencesInprogressCurrentDay = $provController->getProvidencesInProgressFromCurrentDay();

$modal="";
if(!isset($_SESSION['isOpenModal'])){
    echo '<link rel="stylesheet" href="./styles/css/swal/sweetalert2.min.css">';
    $_SESSION['isOpenModal']=1;

    if(sizeof($providencesInprogressCurrentDay)>0 && date('H')<18){
        $op = new ExceptionAlert("Há providências em andamento que devem ser concluídas até às 18:00", 1000);
        $modal= $op->alertNewProv();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Advocacia Bertoldi</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./styles/css/home.css">
    <link rel="stylesheet" href="./styles/css/navbar.css">
    <link rel="stylesheet" href="./styles/css/sidebar-hide.css">
    <link rel="stylesheet" href="./styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="shortcut icon" href="./images/logotipo.ico" type="image/x-icon">
</head>

<body>

<?php include_once './components/navbar.php'; ?>

<div class="dashboard-container">

    <div>
        <div class="dashboard-title">
            Bem-vindo, <?=AuthController::getUser()["name"]?>!
        </div>
        <div class="dashboard-subtitle">
            Sistema de gerenciamento da Advocacia Bertoldi
        </div>
    </div>

    <div class="dashboard-grid">

        <a href="<?= $route->run('/petitions') ?>" class="dashboard-link">
            <div class="dashboard-card">
                <h3>Petições em Andamento</h3>

                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Contratação</th>
                            <th>Natureza</th>
                            <th>Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $len = min(3, sizeof($pAnalise));

                    if ($len > 0) {
                        for ($i = 0; $i < $len; $i++) {
                            $proj = $pAnalise[$i];
                            echo "<tr>
                                    <td>" . date('d-m-Y', strtotime($proj[1])) . "</td>
                                    <td>{$proj[5]}</td>
                                    <td>{$proj[2]}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr>
                                <td colspan='3' class='empty-message'>
                                    Nenhuma petição em andamento
                                </td>
                            </tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </a>

        <a href="<?= $route->run('/distributed') ?>" class="dashboard-link">
            <div class="dashboard-card">
                <h3>Petições Concluídas</h3>

                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Contratação</th>
                            <th>Natureza</th>
                            <th>Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $len = min(3, sizeof($pConc));

                    if ($len > 0) {
                        for ($i = 0; $i < $len; $i++) {
                            $proj = $pConc[$i];
                            echo "<tr>
                                    <td>" . date('d-m-Y', strtotime($proj[1])) . "</td>
                                    <td>{$proj[9]}</td>
                                    <td>{$proj[2]}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr>
                                <td colspan='3' class='empty-message'>
                                    Nenhuma petição concluída
                                </td>
                            </tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </a>

        <a href="<?= $route->run('/providences') ?>" class="dashboard-link">
            <div class="dashboard-card">
                <h3>Providências em Andamento</h3>

                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Publicação</th>
                            <th>Prazo</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $len = min(3, sizeof($providencesInprogressCurrentDay));

                    if ($len > 0) {
                        for ($i = 0; $i < $len; $i++) {
                            $prov = $providencesInprogressCurrentDay[$i];
                            echo "<tr>
                                    <td>" . date('d-m-Y', strtotime($prov[7])) . "</td>
                                    <td>" . date('d-m-Y', strtotime($prov[8])) . "</td>
                                    <td>{$prov[9]}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr>
                                <td colspan='3' class='empty-message'>
                                    Nenhuma providência em andamento
                                </td>
                            </tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </a>

        <a href="<?= $route->run('/providenced') ?>" class="dashboard-link">
            <div class="dashboard-card">
                <h3>Providências Concluídas</h3>

                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Publicação</th>
                            <th>Prazo</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $len = min(3, sizeof($provsConc));

                    if ($len > 0) {
                        for ($i = 0; $i < $len; $i++) {
                            $prov = $provsConc[$i];
                            echo "<tr>
                                    <td>" . date('d-m-Y', strtotime($prov[7])) . "</td>
                                    <td>" . date('d-m-Y', strtotime($prov[8])) . "</td>
                                    <td>{$prov[9]}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr>
                                <td colspan='3' class='empty-message'>
                                    Nenhuma providência concluída
                                </td>
                            </tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </a>
    </div>
</div>

<script src="./js/jquery.min.js"></script>
<script src="./js/popper.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/jqueryAjax.min.js"></script>
<script src="./js/main.js"></script>
<script src="./js/swal/sweetalert2.all.min.js"></script>

<?php echo $modal; ?>

</body>
</html>