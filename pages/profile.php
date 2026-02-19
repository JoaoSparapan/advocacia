<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
if(AuthController::getUser()==null){
    header("location:./login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../styles/css/sidebar-hide.css">
    <link rel="stylesheet" href="../styles/css/profile.css">
    <link rel="stylesheet" href="../styles/css/providences.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <title>Perfil</title>
</head>

<body>
    <?php
        include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php";
        $user_info = AuthController::getUser();
        // var_dump($user_info);
    ?>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5">
            <nav class="navbar navbar-expand-lg navbar-light bg-light"
                style="background-color: transparent !important;">
                <div class="container-fluid">
                    Perfil
                </div>
            </nav>
            <div class="content-files">
                <div class="row">
                    <!-- <button class="btn-large toggleUpdate"><i class="fa-solid fa-pen"></i></button> -->
                    <form class="col s12" method="POST" action="../services/Controller/ProfileController.php">
                        <div class="row">
                            <div class="input-field col s6">

                                <i class="fa-solid fa-user prefix"></i>
                                <input name="name" id="nome" type="text" value="<?=$user_info['name']?>">
                                <label for="nome">Nome</label>

                            </div>

                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <i class="fa fa-envelope prefix"></i>
                                <input name="email" id="email" type="email" class="validate"
                                    value="<?=$user_info['email']?>">
                                <label for="email">Email</label>
                                <span class="helper-text" data-error="Formato de email incorreto"
                                    data-success="Correto!"></span>
                            </div>

                            <div class="input-field col s6">
                                <i class="fa fa-id-card prefix" aria-hidden="true"></i>
                                <input name="cpf" id="cpf" type="text" class="form-control "
                                    value="<?=$user_info['cpf']?>">
                                <label for="cpf">CPF</label>
                                <span class="helper-text" data-error="CPF inválido!" data-success="Correto!"></span>
                            </div>
                        </div>
                        <br>
                        <!-- <div class="row invisible in">
                            
                        </div> -->
                        <div class="row invisible in">
                            <div class="input-field col s6 invisible in">
                                <i class="fa-solid fa-lock prefix"></i>
                                <input name="senhaantiga" id="passant" type="password" class="validate" placeholder="Senha atual">
                                <!-- <label for="pass">Senha Atual</label> -->
                            </div>
                        </div>
                        <div class="row invisible in">
                            <div class="input-field col s4">
                                <i class="fa-solid fa-lock prefix"></i>
                                <input name="senhaagr" id="passagr" type="password" class="validate" placeholder="Nova senha">
                                <!-- <label for="pass">Nova senha</label> -->
                            </div>
                        </div>
                        <div class="row invisible in">
                            <div class="input-field col s12">
                                <i class="fa-solid fa-lock prefix"></i>
                                <input name="senhaagrconfirm" id="passagrconfirm" type="password" class="validate" placeholder="Confirmar nova senha">
                                <!-- <label for="pass">Confirmar nova senha</label> -->
                            </div>
                        </div>

                        <div>

                            <button class="btn sub invisible in updateProfile" type="submit" style="background-color:#27c494;">
                                <i class="fa-solid fa-floppy-disk"></i> &nbsp;&nbsp;Salvar Alterações

                            </button>
                        </div>
                        <button class="btn-large toggleUpdate" type='button'><i class="fa-solid fa-pen"></i> Editar
                            Informações</button>
                    </form>

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

    <script src="../js/profile.js"></script>
</body>

</html>