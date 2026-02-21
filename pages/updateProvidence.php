<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/ProcessController.php';
include_once '../services/Controller/UserController.php';
include_once '../services/Controller/ProvidenceController.php';
if(AuthController::getUser()==null){
    header("location:./login.php");
    exit;
}else if(AuthController::getUser()['idRole']==2){
  header("location:../index.php");
  exit;

}

$p= new ProvidenceController();
$prov = $p->getAllForTable();
$proc = new ProcessController();
$procs = $proc->getAllForTable();
$userController = new UserController();
$procSelected=NULL;

if(isset($_GET['id'])){
    if(AuthController::getUser()['idRole']==1){
        $provSelected = $p->getProvidenceById($_GET['id']);
        $u = $userController->getAdminAndColaboradorAll();
    }else{
        header("location:./process.php");
        exit;
    }
}else{
    header("location:./process.php");
    exit;
}
if($provSelected['type']==1){
  $teste="Úteis";
}
$atual = $proc->getById($provSelected['idProcess']);
$init = explode(" ", $provSelected['startDate']);
$post = explode(" ", $provSelected['postDate']);
$end = explode(" ", $provSelected['endDate']);

$d = date('d-m-Y',strtotime($end[0]));
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
    <link rel="stylesheet" href="../styles/css/providences.css">
    <link rel="stylesheet" href="../styles/css/profile.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <title>Editar providência</title>
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
                    Alterar informações da providência: <?= $provSelected['idProvidence'] ?>
                </div>
            </nav>
            <div class="content-info-user">

                <form class="col s12" method="POST" action="../services/Controller/UpdateProvidence.php">
                    <input name="id" style="display: none" type="text" required
                        value="<?=$provSelected['idProvidence']?>">
                        <input type="hidden" name="callback" value="<?=$_SERVER['HTTP_REFERER']?>">
                    <div class="row">
                    </div>
                    <div class="row computer">
                        <div class="input-field col s3">

                            <i class="fa-solid fa-calendar-days prefix"></i>
                            <input name="data-intim" id="data-intim" type="date" min="2000-01-01T00:00"
                                max="9999-12-31T00:00" required value="<?=$init[0]?>"
                                style="background: transparent !important;">
                            <label for="data-intim">Data de disponibilização</label>
                            <span class="helper-text" data-error="Formato de Data incorreto"
                                data-success="Correto!"></span>

                        </div>
                        <div class="input-field col s3">

                            <i class="fa-solid fa-calendar-days prefix"></i>
                            <input name="data-publi" id="data-publi" type="date" min="2000-01-01T00:00"
                                max="9999-12-31T00:00" required value="<?=$post[0]?>"
                                style="background: transparent !important;">
                            <label for="data-publi">Data da publicação</label>
                            <span class="helper-text" data-error="Formato de Data incorreto"
                                data-success="Correto!"></span>

                        </div>
                    </div>
                    <div class="row mobile">
                        <div class="input-field">

                            <i class="fa-solid fa-calendar-days prefix"></i>
                            <input name="data-intim" id="data-intim_mobile" type="date" min="2000-01-01T00:00"
                                max="9999-12-31T00:00" required value="<?=$init[0]?>"
                                style="min-width: 80% !important; background: transparent !important;">
                            <label for="data-intim_mobile" style="top: -5px !important;">Data de
                                disponibilização</label>
                            <span class="helper-text" data-error="Formato de Data incorreto"
                                data-success="Correto!"></span>

                        </div>
                    </div>
                    <div class="row mobile">
                        <div class="input-field">

                            <i class="fa-solid fa-calendar-days prefix"></i>
                            <input name="data-publi" id="data-publi_mobile" type="date" min="2000-01-01T00:00"
                                max="9999-12-31T00:00" required value="<?=$post[0]?>"
                                style="min-width: 80% !important; background: transparent !important;">
                            <label for="data-publi_mobile" style="top: -5px !important;">Data da publicação</label>
                            <span class="helper-text" data-error="Formato de Data incorreto"
                                data-success="Correto!"></span>

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s3">
                            <i class="fa-regular fa-file-lines prefix"></i>
                            <input name="providencia" id="providencia" type="text" class="validate" required
                                value="<?=$provSelected['description']?>" style="background: transparent !important;">
                            <label for="providencia">Providência</label>
                            <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                        </div>

                        <div class="input-field col s3" style="display: flex; background: transparent !important;">
                            <?php
                        $numProcList="";
                        for($i=0;$i<sizeof($procs);$i++)
                        {
                           $numProcList.=$procs[$i]['numProcess'];
                           if($i< sizeof($procs)-1 || sizeof($procs)==1){
                            $numProcList.=",";
                           }
                        }
                        
                        ?>
                            <i class="fa fa-id-card prefix"></i>
                            <input data-listnproc="<?= $numProcList;?>" name="process" id="search_numProcess_inp"
                                type="text" class="autocomplete validate" required value="<?=$atual['numProcess']?>"
                                style="background: transparent !important;">
                            <label for="qte-dias">Num. Processo</label>
                            <a class="modal-trigger" href="#modalproc">
                                <div id="addprov" class="invisible"
                                    style="margin: 1rem 0; display: flex; justify-content:center; align-items: center;">

                                </div>
                            </a>
                        </div>

                    </div>
                    <div class="row">
                        <div class="input-field col s3">

                            <i class="fa-solid fa-list-ol prefix"></i>
                            <input name="qte-dias" min="1" id="qte-dias" type="number" class="validate" required
                                value="<?=$provSelected['term']?>" style="background: transparent !important;">
                            <label for="qte-dias">Quantidade de dias</label>
                            <span class="helper-text" data-error="Insira o número de dias"
                                data-success="Correto!"></span>
                        </div>
                        <div class="input-field col s3" style="background: transparent !important;">

                            <i class="fa-solid fa-list prefix"></i>
                            <select name="sel-days" id="sel-days">
                                <option value="-1" disabled>Contagem de dias</option>
                                <option value="0" <?php if ($provSelected['type'] == 0) echo 'selected'; ?>>Úteis
                                </option>
                                <option value="1" <?php if ($provSelected['type'] == 1) echo 'selected'; ?>>Corridos
                                </option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="input-field col s3" style="
                          display: flex;
                          align-items: center;
                          
                          flex-direction: column;
                        ">
                            Considerar feriados nacionais?
                            <div class="switch">
                                <label>
                                    Não
                                    <input type="checkbox" name="national-holiday" id="national-holiday"
                                        <?php if ($provSelected['Hnational'] == 1) echo 'checked'; ?>>
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
                            Considerar feriados estaduais?
                            <div class="switch">
                                <label>
                                    Não
                                    <input type="checkbox" name="state-holiday" id="state-holiday"
                                        <?php if ($provSelected['Hstate'] == 0) echo 'checked'; ?>>
                                    <span class="lever"></span>
                                    Sim
                                </label>
                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div class="input-field col s3">
                            <i class="fa-solid fa-calendar-days prefix"></i>
                            <input disabled name="prazo" id="prazo" type="datetime" class="validate" required
                                value="<?=$d?>" style="background: transparent !important;">
                            <label for="prazo" style="top: -25px !important;">Prazo estimado</label>
                            <span class="helper-text" data-error="prazo estimado incorreto"
                                data-success="Correto!"></span>
                        </div>
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
    <script type="text/javascript" src="../js/swal/sweetalert2.all.min.js"></script>
    <script src="../js/updateProvidence.js"></script>
    <script src="../js/process.js"></script>
    <script src="../js/providences.js"></script>
</body>

</html>