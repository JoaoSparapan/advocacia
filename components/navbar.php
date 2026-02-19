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

$current_page = basename($_SERVER['PHP_SELF']);
$current_path = isset($_GET['page']) ? $_GET['page'] : '';

function isActive($page_name, $current_page) {
    return ($current_page === $page_name) ? 'active' : '';
}

$home_active = ($current_page === 'index.php') ? 'active' : '';
$petitions_active = ($current_page === 'petition.php') ? 'active' : '';
$distributed_active = ($current_page === 'distributed.php') ? 'active' : '';
$users_active = ($current_page === 'users.php') ? 'active' : '';
$frontdesk_active = ($current_page === 'frontdesk.php') ? 'active' : '';
$recepcao_active = ($current_page === 'recepcao.php') ? 'active' : '';
$profile_active = ($current_page === 'profile.php' || $current_page === 'updateUser.php') ? 'active' : '';
$update_petition_active = ($current_page === 'updatePetition.php') ? 'active' : '';
$clients_active = ($current_page === 'clients.php') ? 'active' : '';

?>

<nav id="navbar" class="navbar-horizontal">
    <div class="navbar-container">
        <div class="navbar-logo">
            <a href="<?= $router->run('/'); ?>">
                <img src="/advocacia/images/logotipo.png" alt="SGA" class="logo-img">
            </a>
        </div>

        <ul class="navbar-menu">
            <li class="navbar-item">
                <a href="<?= $router->run('/'); ?>" class="navbar-link <?= $home_active ?>">
                    <span class="fa fa-house-chimney"></span> Home
                </a>
            </li>
            <li class="navbar-item">
                <a href="<?= $router->run('/recepcao');?>" class="navbar-link <?= $recepcao_active ?>">
                    <span class="fa-solid fa-calendar-days"></span> Recepção
                </a>
            </li>
            <li class="navbar-item">
                <a href="<?= $router->run('/atendimento');?>" class="navbar-link <?= $frontdesk_active ?>">
                    <span class="fas fa-clipboard-list"></span> Atendimentos
                </a>
            </li>
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link <?= ($petitions_active || $distributed_active) ? 'active' : '' ?>">
                    <span class="fa fa-folder"></span> Providências
                    <span class="dropdown-arrow fa fa-chevron-down"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="<?= $router->run('/petitions');?>" class="dropdown-link <?= $petitions_active ?>">
                        <span class="fa fa-folder"></span> Em Andamento
                    </a></li>
                    <li><a href="<?= $router->run('/distributed');?>" class="dropdown-link <?= $distributed_active ?>">
                        <span class="fa fa-archive"></span> Concluídas
                    </a></li>
                    <li><a href="<?= $router->run('/distributed');?>" class="dropdown-link <?= $distributed_active ?>">
                        <span class="fa fa-file-text-o"></span> Processos
                    </a></li>
                </ul>
            </li>
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link <?= ($petitions_active || $distributed_active) ? 'active' : '' ?>">
                    <span class="fa fa-file-pen"></span> Petições
                    <span class="dropdown-arrow fa fa-chevron-down"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="<?= $router->run('/petitions');?>" class="dropdown-link <?= $petitions_active ?>">
                        <span class="fa fa-file-pen"></span> Em Andamento
                    </a></li>
                    <li><a href="<?= $router->run('/distributed');?>" class="dropdown-link <?= $distributed_active ?>">
                        <span class="fa fa-archive"></span> Distribuídas
                    </a></li>
                </ul>
            </li>

            <li class="navbar-item">
                <a href="<?= $router->run('/clientes');?>" class="navbar-link <?= $clients_active ?>">
                    <span class="fa-solid fa-people-group"></span> Clientes
                </a>
            </li>
        </ul>

        <div class="navbar-right">
            <?php
                if(AuthController::getUser()['idRole']==1){
            ?>
            <a href="<?= $router->run('/users');?>" class="navbar-link <?= $users_active ?>">
                <span class="fa-solid fa-users"></span> Usuários
            </a>
            <?php
                }
            ?>
            <a href="<?= $router->run('/profile');?>" class="navbar-link <?= $profile_active ?>">
                <span class="fa fa-user"></span> Perfil
            </a>
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
