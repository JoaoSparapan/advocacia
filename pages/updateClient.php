<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/ClientController.php';

if(AuthController::getUser()==null){
    header("location:./login.php");
    exit;
}

$cli = new ClientController();
$cliSelected=NULL;

if(isset($_GET['id'])){
    if(AuthController::getUser()['idRole']==1 || AuthController::getUser()['idRole']==2){
        $cliSelected = $cli->getById($_GET['id']);
        
    }else{
        header("location:./clients.php");
        exit;
    }
}else{
    header("location:./clients.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../styles/css/sidebar-hide.css">
    <link rel="stylesheet" href="../styles/css/profile.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="stylesheet" href="../styles/css/selectStyle.css" />
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <title>Editar cliente</title>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php";
            
            // var_dump($user_info);
        ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>
                    Alterar informações do cliente: <?= $cliSelected['name'] ?>
                </div>
            </nav>
            <div class="content-info-user">

                <form class="col s12" method="POST" action="../services/Controller/UpdateClient.php">
                    <input name="id" style="display: none" type="text" required value="<?=$cliSelected['idClient']?>">
                    <div class="row">
                        <div class="input-field col s3">

                            <i class="fa-solid fa-user prefix"></i>
                            <input name="name" id="name" type="text" class="validate" required
                                value="<?=$cliSelected['name']?>">
                            <label for="name">Nome</label>
                            <span class="helper-text" data-success="Correto!"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s3">

                            <i class="fa-solid fa-envelope prefix"></i>
                            <input name="email" id="email" type="text" class="validate"
                                value="<?=$cliSelected['email']?>">
                            <label for="email">Email</label>
                            <span class="helper-text" data-success="Correto!"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s3">

                            <i class="fa-solid fa-id-card prefix"></i>
                            <input name="cpf" id="cpf" type="text" class="validate"
                                value="<?=$cliSelected['cpf']?>">
                            <label for="cpf">CPF</label>
                            <span class="helper-text" data-success="Correto!"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s3">

                            <i class="fa-solid fa-phone prefix"></i>
                            <input name="phone" id="phone" type="text" class="validate"
                                value="<?=$cliSelected['phone']?>">
                            <label for="phone">Celular</label>
                            <span class="helper-text" data-success="Correto!"></span>
                        </div>
                    </div>
                    <div class="row">
                        <button class="btn waves-effect waves-light" type="submit" name="action" id="reg-user">
                            <i class="fa-solid fa-pencil"></i>Alterar
                        </button>
                        <button style="margin-left: 10px;" class="btn waves-effect waves-light" type="button" id="back"
                            onClick="goBack()">
                            Cancelar
                        </button>
                    </div>
                </form><br>



            </div>

        </div>
    </div>

    <script src="../js/jqueryAjax.min.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/materialize/materialize.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
        integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="../js/updateFrontdesk.js"></script>
</body>

</html>