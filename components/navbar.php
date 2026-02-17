<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/Router.php";

$router = new Router();
$role="";
if(AuthController::getUser()['idRole']=="1"){
    $role="Administrador";
}

if(AuthController::getUser()['idRole']=="2"){
    $role="Colaborador";
}

?>

<nav id="navbar" class="navbar-horizontal">
    <div class="navbar-container">
        <div class="navbar-logo">
            <a href="<?= $router->run('/'); ?>">SGPI</a>
        </div>

        <ul class="navbar-menu">
            <li class="navbar-item">
                <a href="<?= $router->run('/'); ?>" class="navbar-link active">
                    <span class="fa fa-house-chimney"></span> Home
                </a>
            </li>
            <li class="navbar-item">
                <a href="<?= $router->run('/petitions');?>" class="navbar-link">
                    <span class="fa fa-file-pen"></span> Petições
                </a>
            </li>
            <li class="navbar-item">
                <a href="<?= $router->run('/distributed');?>" class="navbar-link">
                    <span class="fa fa-archive"></span> Petições Distribuídas
                </a>
            </li>

            <?php
                if(AuthController::getUser()['idRole']==1){
            ?>
            <li class="navbar-item">
                <a href="<?= $router->run('/users');?>" class="navbar-link">
                    <span class="fa-solid fa-users"></span> Usuários
                </a>
            </li>
            <?php
                }
            ?>

            <li class="navbar-item">
                <a href="<?= $router->run('/profile');?>" class="navbar-link">
                    <span class="fa fa-user"></span> Perfil
                </a>
            </li>
        </ul>

        <div class="navbar-right">
            <div class="navbar-user">
                <p class="user-name"><?=AuthController::getUser()['name']?></p>
                <p class="user-role"><?=$role?></p>
            </div>
            <button class="navbar-logout logout" type="button">
                Sair <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </div>

        <button class="navbar-toggle" id="navbarToggle" type="button">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>
