<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/UserController.php';
include_once '../services/Controller/ClientController.php';
include_once '../services/Controller/ReceptionController.php';

$userController = new UserController();
$users = $userController->getAllForTable();
$clientController = new ClientController();
$receptionController = new ReceptionController();
$clis = $clientController->getAllForTable();

if(AuthController::getUser()==null){
    header("location:./login.php");
    exit;
}

$receptionSelected=NULL;
$cliSelected=NULL;
if(isset($_GET['id'])){
    if(AuthController::getUser()['idRole']==1 ||AuthController::getUser()['idRole']==2){
        $receptionSelected = $receptionController->getAllReceptionById($_GET['id']);
        $cliSelected = $clientController->getById($receptionSelected['idClient']);
    }else{
        header("location:./recepcao.php");
        exit;
    }
}else{
    header("location:./recepcao.php");
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
    <link rel="stylesheet" href="../styles/css/profile.css">
    <link rel="stylesheet" href="../styles/css/sidebar-hide.css">
    <link rel="stylesheet" href="../styles/css/selectStyle.css" />
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="shortcut icon" href="../logotipo.ico" type="image/x-icon">
    <title>Editar recepção</title>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <?php
            include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php";
            
        ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>
                    Alterar informações da recepção
                </div>
            </nav>
            <div class="content-info-user">

                <form class="col s12" method="POST" action="../services/Controller/UpdateRecepcao.php">
                    <input name="id" style="display: none" type="text" required
                        value="<?=$receptionSelected['idReception']?>">

                    
                    <div class="row">
                        <div class="input-field col s3" style="flex: 1 0 300px;">

                            <i class="fa-solid fa-calendar-days prefix"></i>
                            <input name="data-chegada" id="data-chegada" type="datetime-local" min="2000-01-01T00:00"
                                    max="9999-12-31T00:00" required value="<?=$receptionSelected['arrival']?>">
                            <label for="data-chegada" style="top: -35px !important;">Data/Hora da chegada</label>

                        </div>
                    </div>

                    <div class="row">
                        <?php
                        $nameList="";
                        $cods="";
                        for($i=0;$i<sizeof($clis);$i++)
                        {
                           $nameList.=$clis[$i]['name']."-".$clis[$i]['cpf'];
                           $cods.=$clis[$i]['idClient'];
                           if($i< sizeof($clis)-1 || sizeof($clis)==1){
                            $nameList.=",";
                            $cods.=",";
                           }
                        }
                        
                    ?>

                    <div class="input-field col s12">
                        <i class="fa-solid fa-user prefix"></i>
                        <input name="client-id" id="client-id" type="hidden" value="<?= $cliSelected['idClient'];?>" />
                        <input name="client-front" id="client" type="text" required value="<?= $cliSelected['name'];?>"
                            class="validate autocomplete" data-clientlist="<?= $nameList;?>"
                            data-clientids="<?= $cods;?>">
                        <label for="autocomplete-input">Cliente</label>

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s3">

                            <i class="fa fa-envelope prefix"></i>
                            <input name="assunto" id="assunto" type="text" class="validate" required
                                value="<?=$receptionSelected['subject']?>">
                            <label for="assunto">Assunto</label>
                        </div>
                    </div>
                    
                <div class="row">

                    <div class="input-field col s3">
                        <i class="fa fa-list-ol prefix"></i>
                        <input name="providencia" id="providencia" type="text" value="<?=$receptionSelected['providence']?>">
                        <label for="providencia">Providência</label>
                    </div>

                </div>

                    <div class="row">
                        <div class="input-field col s3">
                            <i class="fa fa-user-tie prefix"></i>
                            <select name="resp" id="resp" style="display: none;">

                                <option value="-1" disabled selected>Responsável</option>

                                <?php
                                    for($i=0;$i<sizeof($users);$i++)
                                    {
                                            $selected='';
                                            
                                            if($users[$i]['idUser'] == $receptionSelected['idResponsavel']){
                                                $selected='selected';
                                            }
                                                echo "<option
                                                ".$selected."
                                                value='".$users[$i]['idUser']."'>".$users[$i]['name']."
                                                </option>";
                                        
                                    }
                                ?>

                            </select>

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

    <script src="../js/updateRecepcao.js"></script>
</body>

</html>