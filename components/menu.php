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
<div class="logout-div">
    <div>
        <p><?=AuthController::getUser()['name']?></p>
        <p>
            <?=$role?>
        </p>
    </div>
    <button class="logout" type="button">
        Sair <i class="fa-solid fa-right-from-bracket"></i>
    </button>
</div>

<nav id="sidebar" class="active">
    <!-- active -->
    <h1><a href="index.php" class="logo"> SGPI </a></h1>

    <ul class="list-unstyled components mb-5">
        <li>
            <!-- <button class="logout" type="button">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button> -->
        </li>
        <li class="active">
            <a href="<?= $router->run('/'); ?>"><span class="fa fa-house-chimney"></span> Home</a>
        </li>
        <li>
            <a href="<?= $router->run('/petitions');?>"><span class="fa fa-file-pen"></span>Petições</a>
        </li>

        <li>
            <a href="<?= $router->run('/distributed');?>"><span class="fa fa-archive"></span>Petições Distribuídas</a>
        </li>

        <?php
            if(AuthController::getUser()['idRole']==1){
        ?>
        <li>
            <a href="<?= $router->run('/users');?>"><span class="fa-solid fa-users"></span>Usuários</a>
        </li>
        <?php
            }
        ?>

        <li>
            <a href="<?= $router->run('/profile');?>"><span class="fa fa-user"></span> Perfil</a>
        </li>


    </ul>


    <div class="footer">
        <p></p>
    </div>
</nav>