<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/UserController.php';
include_once '../services/Controller/PetitionController.php';
if(AuthController::getUser()==null){
    header("location:./login.php");
    exit;
}


$p= new PetitionController();
$userController = new UserController();
$petitionSelected=null;
$u=null;
if(isset($_GET['id'])){
    
    $petitionSelected = $p->getPetitionById($_GET['id']);    
    $u = $userController->getAdminAndColaboradorAll();
}else{
    header("location:./process.php");
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
    <link rel="stylesheet" href="../styles/css/providences.css">
    <link rel="stylesheet" href="../styles/css/profile.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="stylesheet" href="../styles/css/sidebar-hide.css">
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <title>Editar petição</title>

</head>

<body>
<?php
            include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php";
            
        ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">
            <div class="content-info-user">


                <h4 style="text-align: center;">Edição de Petição</h4>

                <form class="col s12" style="" method="POST" action="../services/Controller/UpdatePetition.php"
                    style="padding: 0 !important;">
                    <input type="hidden" name="petitionId" value="<?=$petitionSelected['idPetition']?>">
                    <input type="hidden" name="callback" value="<?=$_SERVER['HTTP_REFERER']?>">

                    <div class="row computer">
                        <div class="input-field col s3">

                            <i class="fa-solid fa-calendar-days prefix"></i>
                            <input name="dta-contact" id="data-intim" type="date" min="2000-01-01T00:00"
                                max="<?=date('Y-m-d')?>" required value="<?=$petitionSelected['startDate']?>">
                            <label for="data-intim" style="top: -5px !important;">Data da
                                Contratação</label>
                            <span class="helper-text" data-error="Formato de Data incorreto"
                                data-success="Correto!"></span>

                        </div>

                        <div class="input-field col s3">
                            <i class="fa-regular fa-file-lines prefix"></i>
                            <input name="clientName" id="clientName" type="text" class="validate" required
                                value="<?=$petitionSelected['clientName']?>">
                            <label for="providencia">Cliente</label>
                            <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                        </div>

                        <div class="input-field col s3">
                            <i class="fa-regular fa-file-lines prefix"></i>
                            <input name="adverse" id="adverse" type="text" class="validate" required
                                value="<?=$petitionSelected['adverse']?>">
                            <label for="providencia">Adverso</label>
                            <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                        </div>
                    </div>
                    <div class="row mobile">
                        <div class="input-field">

                            <i class="fa-solid fa-calendar-days prefix"></i>
                            <input name="dta-contact" id="data-intim_mobile" type="date" min="2000-01-01T00:00"
                                max="<?=date('Y-m-d')?>" required value="<?=$petitionSelected['startDate']?>"
                                style="min-width: 80% !important;">
                            <label for="data-intim_mobile" style="top: -5px !important;">Data de Contratação</label>
                            <span class="helper-text" data-error="Formato de Data incorreto"
                                data-success="Correto!"></span>

                        </div>

                    </div>
                    <div class="row mobile">
                        <div class="input-field">
                            <i class="fa-regular fa-file-lines prefix"></i>
                            <input name="clientName" id="client_name_mobile" type="text" class="validate" required
                                value="<?=$petitionSelected['clientName']?>">
                            <label for="providencia">Cliente</label>
                            <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                        </div>
                    </div>
                    <div class="row mobile">
                        <div class="input-field">
                            <i class="fa-regular fa-file-lines prefix"></i>
                            <input name="adverse" id="adverse_mobile" type="text" class="validate" required
                                value="<?=$petitionSelected['adverse']?>">
                            <label for="providencia">Adverso</label>
                            <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                        </div>
                    </div>

                    <div class="row computer">
                        <div class="input-field col s3" style="display: flex;">
                            <i class="fa fa-id-card prefix"></i>
                            <select name="adv" required id="select-adv">
                                <option value="-1" disabled>Advogado Responsável</option>
                                <?php
            
            for($i=0;$i<sizeof($u);$i++)
            {
                $selected = "selected";

                if($petitionSelected['idUser'] != $u[$i]['idUser']){
                    $selected = "";
                }
               $namaUser =$u[$i]['name'];
               $idUser = $u[$i]['idUser'];
               echo '<option '.$selected.' value="'.$idUser.'">'.$namaUser.'</option>';
            
            }
            
            ?>

                            </select>
                            <label>Adv. Responsável</label>


                        </div>

                        <div class="input-field col s3">
                            <i class="fa-regular fa-file-lines prefix"></i>
                            <input name="typeAction" id="typeAction" type="text" class="validate" required
                                value="<?=$petitionSelected['typeAction']?>">
                            <label for="providencia">Natureza da Ação</label>
                            <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                        </div>
                    </div>
                    <div class="row mobile">
                        <div class="input-field" style="display: flex;">
                            <i class="fa fa-id-card prefix"></i>
                            <select name="adv" required id="select-adv_mobile">
                                <option value="-1" disabled>Advogado Responsável</option>
                                <?php
            
            for($i=0;$i<sizeof($u);$i++)
            {
                $selected = "selected";

                if($petitionSelected['idUser'] != $u[$i]['idUser']){
                    $selected = "";
                }
               $namaUser =$u[$i]['name'];
               $idUser = $u[$i]['idUser'];
               
               echo '<option '.$selected.' value="'.$idUser.'">'.$namaUser.'</option>';
            
            }
            
            ?>

                            </select>
                            <label>Adv. Responsável</label>

                        </div>
                    </div>
                    <div class="row mobile">
                        <div class="input-field">
                            <i class="fa-regular fa-file-lines prefix"></i>
                            <input name="typeAction" id="typeAction_mobile" type="text" class="validate" required
                                value="<?=$petitionSelected['typeAction']?>">
                            <label for="providencia">Natureza da Ação</label>
                            <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                        </div>
                    </div>
                    <div class="row">
                    <div class="input-field col s3" style="
                      display: flex;
                      align-items: center;
                      
                      flex-direction: column;
                    ">
                        Prescrição?
                        <div class="switch">
                            <label>
                                Não
                                <input value=1 type="checkbox" name="prescProvidedModal" id="prescProvidedModal"
                                <?php if ($petitionSelected['prescription'] == 1) echo 'checked'; ?>>
                                <span class="lever"></span>
                                Sim
                            </label>
                        </div>

                    </div>
                    <div class="input-field col s3" style="
                      display: flex;
                      align-items: center;
                      
                      flex-direction: column;
                    ">
                        Decadência?
                        <div class="switch">
                            <label>
                                Não
                                <input value=1 type="checkbox" name="decProvidedModal" id="decProvidedModal"
                                <?php if ($petitionSelected['decadence'] == 1) echo 'checked'; ?>>
                                <span class="lever"></span>
                                Sim
                            </label>
                        </div>

                    </div>
                </div>
                <div class="row">
                <div class="input-field col s3" style="
                          display: flex;
                          align-items: center;          
                          flex-direction: column;
                        ">
                        Petição urgente?
                        <div class="switch">
                            <label>
                                Não
                                <input value=1 type="checkbox" name="priority" id="priority"
                                <?php if ($petitionSelected['priority'] == 1) echo 'checked'; ?>>
                                <span class="lever"></span>
                                Sim
                            </label>
                        </div>

                    </div>
                </div>

                    <div class="row computer">
                        <button class="btn waves-effect waves-light updateInfoUser" type="submit" name="action"
                            id="reg-user">
                            <i class="fa-solid fa-pencil"></i>Alterar
                        </button>
                        <button style="margin-left: 10px;" class="btn waves-effect waves-light" type="button" id="back"
                            onClick="goBack()">
                            Cancelar
                        </button>


                    </div>
                    <div class="row mobile">
                        <button class="btn waves-effect waves-light" type="submit" name="action" id="reg-user">
                            <i class="fa-solid fa-pencil"></i>Alterar
                        </button>
                        <button style="margin-left: 10px;" class="btn waves-effect waves-light" type="button" id="back"
                            onClick="goBack()">
                            Cancelar
                        </button>

                    </div>
                </form>


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
    <script type="text/javascript" src="../js/swal/sweetalert2.all.min.js"></script>
    <script src="../js/updatePetition.js"></script>

    <script src="../js/providences.js"></script>
</body>

</html>