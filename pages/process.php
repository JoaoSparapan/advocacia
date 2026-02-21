<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/UserController.php';
include_once '../services/Controller/VaraController.php';
if(AuthController::getUser()==null){
    header("location:./login.php");
    exit;
}else if(AuthController::getUser()['idRole']==2){
    header("location:../index.php");
    exit;

}

$params = str_replace("/advocacia/pages/process.php?", "", $_SERVER["REQUEST_URI"]);
$url = str_replace("/advocacia/pages/", "", $_SERVER["REQUEST_URI"]);
$userController = new UserController();
$u = $userController->getAdminAndColaboradorAll();

$index = strrpos($params,'pagination_number=');

if($index.""=="0"){
    
    $params = str_replace(substr($params,$index, $index+20), "", $params);
}

$c = new VaraController();
$court = $c->getAll();
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
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="shortcut icon" href="../logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/profile.css">
    <link rel="stylesheet" href="../styles/css/processo.css">
    <link rel="stylesheet" href="../styles/css/member.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="./styles/css/swal/sweetalert2.min.css">

    <title>Processos</title>
</head>

<body>

    <!-- Modal Structure -->
    <div id="modal2" class="modal" style="padding:20px;height:auto;">
        <div class="modal-content" style="border:0;padding:0;">
            <center>
                <h4>Cadastro de Processo</h4>
            </center>

            <form class="col s12" method="POST" action="../services/Controller/CreateProccess.php">

                <div class="row">
                    <div class="input-field col s3">
                        <i class="fa fa-newspaper-o prefix"></i>
                        <input name="num-proc" id="num-proc" type="text" class="validate" required value="">
                        <label for="num-proc">Num. Processo</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s3">
                        <i class="fa-solid fa-user prefix"></i>
                        <input name="client" id="client" type="text" class="validate" required value="">
                        <label for="client">Cliente</label>
                        <span class="helper-text" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s3">
                        <i class="fa fa-gavel prefix"></i>
                        <select name="court" id="select-vara">

                            <option value="-1" disabled selected>Vara</option>

                            <?php
                                for($i=0;$i<sizeof($court);$i++)
                                {
                                    echo "<option value='".$court[$i]['idCourt']."'>".$court[$i]['sigla']."</option>";
                                }
                            ?>

                        </select>

                        <input type="text" id="new-vara-id" name='new-vara' value="" placeholder="Informe a nova vara"
                            class="invisible" />

                    </div>
                    <div style="margin: 1rem 0; display: flex; justify-content:center; align-items: center;">

                        <i class="fa-solid fa-plus new-vara"></i>
                        <i class="fa-solid fa-trash deleteVara"></i>

                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s3" style="display: flex;">
                        <i class="fa fa-id-card prefix"></i>
                        <select name="adv" id="select-adv" required>
                            <option value="-1" disabled selected>Advogado Responsável</option>
                            <?php
                        
                        for($i=0;$i<sizeof($u);$i++)
                        {
                           $namaUser =$u[$i]['name'];
                           $idUser = $u[$i]['idUser'];
                           echo '<option value="'.$idUser.'">'.$namaUser.'</option>';
                        
                        }
                        
                        ?>

                        </select>
                    </div>
                </div>

                <div class="row">
                    <button class="btn waves-effect waves-light createUser" type="submit" name="action" id="reg-user">
                        Cadastrar
                    </button>

                </div>
            </form>

        </div>
    </div>

    <div class="wrapper d-flex align-items-stretch">
        <?php
        include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php";
        include_once '../services/Controller/RoleController.php';
        include_once '../services/Controller/ProcessController.php';
        include_once '../services/Controller/VaraController.php';
        include_once '../services/Models/Pagination.php';
        $c = new VaraController();
        $court = $c->getAll();
        $role = new RoleController();
        $processController = new ProcessController();
        $user = AuthController::getUser();
        $process = $processController->getAllForTable('process'); 
      ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5" style="background-color: #fff;">

            <nav class="navbar navbar-expand-lg navbar-light bg-light"
                style="background-color: transparent !important;">
                <div class="container-fluid">
                    Processos
                </div>
            </nav>
            <form method="GET" action="#" class="filter">
                <div>
                    <input type="text" name="filter-proc" value="" id="num-proc2" class="validate"
                        placeholder="Número do processo">
                    <div id="vara-sel" class="invisible">
                        <select name="search-vara" value="">
                            <option value="-1" selected disabled>Selecionar</option>
                            <?php
                                for($i=0;$i<sizeof($court);$i++)
                                {
                                    echo "<option value='".$court[$i]['idCourt']."'>".$court[$i]['sigla']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <input type="text" placeholder="Responsável" name="responsible-user" value="" id="responsible-user"
                        class="invisible" />
                    <input type="text" placeholder="Nome do Cliente" name="search-ncli" value="" id="ncli"
                        class="invisible" />
                </div>
                <div>
                    <select name="search-type" id="sel-type" value="" style="padding: 0;" class="browser-default">
                        <option value="num-proc2">N° do processo</option>
                        <option value="vara-sel">Vara</option>
                        <option value="ncli">Nome do cliente</option>
                        <option value="responsible-user">Responsável</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-small waves-effect waves-light"><i class="fa-solid fa-filter"></i>
                    &nbsp;&nbsp;BUSCAR</button>

            </form>
            <a class="btn modal-trigger" href="#modal2" title="Adicionar usuário" style="    background-color: #27c494 !important;
            border-radius: 5px !important;
            margin: 10px 0px;
            margin-left: 5px;
            color: white;"><i class="fa-solid fa-plus"></i></a>

            <?php
            if(isset($_GET['search-type'])){
                $type = $_GET['search-type'];
                if($type==="ncli"){
                    $name = addslashes($_GET['search-ncli']);
                    $process = $processController->getByClientName($name);

                }else if($type==="vara-sel"){
                  if(isset($_GET['search-vara'])){
                    $vara = addslashes($_GET['search-vara']);
                    $process = $processController->getProcessByCourt($vara);
                  }else{
                    $process = $processController->getAllForTable();
                  }
                }else if($type=='responsible-user'){
                    $responsible_user = addslashes($_GET['responsible-user']);
                    if($responsible_user!=""){
                        $process = $processController->getProcessResponsibleUser($responsible_user);
                        
                    }else{

                        $process = $processController->getAllForTable();
                    }
                }
                else{
                    $nproc=addslashes($_GET['filter-proc']);
                    $process = $processController->getByNumber($nproc);
                }
            }
                          
                if($process!=null){

                $table='
                <table class="centered responsive-table highlight">
                <thead>
                    <tr>
                        <th>Processo</th>
                        <th>Vara</th>
                        <th>Cliente</th>
                        <th>Adv. Responsável</th>
                        <th>Operações</th>
                    </tr>
                </thead>

                <tbody>';
                $color;
                $st;
                $pagination="";
                $start=0;
                
                $end= sizeof($process)<10 ? sizeof($process) : 10;
                $paginationModel = new Pagination();
                if(isset($_GET['pagination_number'])){
                    
                    if(intval($_GET['pagination_number']) >=1){
                        
                        $page = $_GET['pagination_number'];
                        $diff=(sizeof($process) - (intval($page)*10));
                
                        if($diff==0){
                            $start=sizeof($process)-10;
                            $end=sizeof($process);
                        }else{
                            if($diff<0){
                                $start = (intval($page)-1)*10;
                                $end = sizeof($process);
                            }else{
                                $start = (intval($page)-1)*10;
                                $end = sizeof($process)-$diff;
                            }
                        }
                        $pagination = $paginationModel->createPagination($page,$process,'process.php', $params);
                    }else{
                        $pagination = $paginationModel->createPagination("1",$process,'process.php', $params);
                    }

                }else{
                    $pagination = $paginationModel->createPagination("1",$process,'process.php', $params);
                }


                for($i=$start;$i<$end;$i++){
                    $u = $process[$i];
                    $user = $userController->getById($u['idUser']);
                    $vara = $c->getById($u['idCourt']);
                    $button_action='';
                    $table.='<tr>
                                <td>'.$u["numProcess"].'</td>
                                <td>'.$vara["sigla"].'</td>
                                <td>'.$u["clientName"].'</td>
                                <td>'.$user["name"].'</td>';
                    
                    $button_action='
                            <a class="btn-floating btn-small blue" title="Editar processo" href="./updateProcess.php?id='.$u['idProcess'].'"><i class="fa-solid fa-pencil"></i></a>
                            <a class="btn-floating btn-small red deleteUserBtn" title="Excluir processo" href="../services/Controller/DeleteProcess.php?id='.$u['idProcess'].'"><i class="fa-solid fa-trash"></i></a>
                            ';
                   

                           
                            
                    
                    $table.='<td style="
                        display: flex;
                        align-items: center; 
                        gap: 5px; 
                        justify-content: center;
                    ">'.$button_action.'</td>

                            </tr>';
                       
                    
                }

                $table.='
                </tbody>
            </table>
                ';
                echo $table;
            
                echo '
                <ul class="pagination">
                ';
                echo $pagination;                
                echo '
                </ul>
                ';
            }else{
                $table='
                <table class="centered responsive-table highlight">
                <thead>
                    <tr>
                        <th>Processo</th>
                        <th>Vara</th>
                        <th>Cliente</th>
                        <th>Adv. Responsável</th>
                        <th>Operações</th>
                    </tr>
                </thead>
                </table>
                ';
                echo $table;
            }
            
            
            ?>




        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jqueryAjax.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
        integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../js/materialize/materialize.min.js"></script>
    <script type="text/javascript" src="../js/swal/sweetalert2.all.min.js"></script>
    <script src="../js/process.js"></script>
</body>

</html>