<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";

if(AuthController::getUser()==null){
    header("location:./login.php");
    exit;
}

$params = str_replace("/advocacia/pages/clients.php?", "", $_SERVER["REQUEST_URI"]);
$url = str_replace("/advocacia/pages/", "", $_SERVER["REQUEST_URI"]);


$index = strrpos($params,'pagination_number=');

if($index.""=="0"){
    
    $params = str_replace(substr($params,$index, $index+20), "", $params);
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
    <link rel="stylesheet" href="../styles/css/member.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="./styles/css/swal/sweetalert2.min.css">

    <title>Clientes</title>
</head>

<body>

    <div id="modal2" class="modal" style="padding:20px;height:auto;">
        <div class="modal-content" style="border:0;padding:0;">
            <h4 style="text-align: center;">Cadastro de cliente</h4>

            <form class="col s12" style="padding:0;" method="POST" action="../services/Controller/CreateClient.php">

                <div class="row">
                    <div class="input-field col s3">
                        <i class="fa-solid fa-user prefix"></i>
                        <input name="nome" id="nome" type="text" class="validate" required value="">
                        <label for="nome">Nome</label>
                        <span class="helper-text" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s3">

                        <i class="fa fa-envelope prefix"></i>
                        <input name="email" id="email" type="email" class="validate" value="">
                        <label for="email">Email</label>
                        <span class="helper-text" data-error="Formato de email incorreto"
                            data-success="Correto!"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s3">
                        <i class="fa fa-id-card prefix" aria-hidden="true"></i>
                        <input name="cpf" id="cpf" type="text" class="validate" value="">
                        <label for="cpf">CPF</label>
                        <span class="helper-text" data-error="CPF inválido!" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s3">

                        <i class="fa-solid fa-phone prefix"></i>
                        <input name="phone" id="phone" type="text" class="validate" value="">
                        <label for="phone">Celular</label>
                        <span class="helper-text" data-success="Correto!"></span>
                    </div>
                </div>

                <div class="row">
                    <button class="btn waves-effect waves-light createUser loading" type="submit" name="action"
                        id="reg-user" style="background-color:#27c494;">
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
        include_once '../services/Controller/ClientController.php';
        include_once '../services/Models/Pagination.php';
        $role = new RoleController();
        $clientController = new ClientController();
        $clients = $clientController->getAllForTable();
        $user = AuthController::getUser();
      ?>

        <div id="content" class="p-4 p-md-5">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>
                    Clientes
                </div>
            </nav>
            <form method="GET" action="#" class="filter">
                <div>
                    <input type="text" placeholder="Nome do cliente" name="search-name" value="" id="title"
                        class="visible" />
                    <input type="text" placeholder="Celular do cliente" name="search-phone" value="" id="start"
                        class="invisible" />
                    <input type="text" placeholder="CPF do cliente" name="search-cpf" value="" id="end"
                        class="invisible" />
                    <input type="text" placeholder="Email do cliente" name="search-email" value="" id="new"
                        class="invisible" />    
                </div>
                
                <div style="margin-right: 1rem;">
                    <select name="search-type" id="sel-type" value="" style="padding: 0;" class="browser-default">
                        <option value="title">Nome</option>
                        <option value="end">CPF</option>
                        <option value="start">Celular</option>
                        <option value="new">Email</option>
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

                if(isset($_GET['search-type'])){
                    $type = $_GET['search-type'];
                    if($type==="title"){
                        if($_GET['search-name']!=""){
                            $name = addslashes($_GET['search-name']);
                            $clients = $clientController->getByName($name);
                          }else{
                            $clients = $clientController->getAllForTable();
                        }
                    }else if($type==="start"){
                      if($_GET['search-phone']!=""){
                        $phone = addslashes($_GET['search-phone']);
                        $clients = $clientController->getByPhone($phone);
                      }else{
                        $clients = $clientController->getAllForTable();
                      }
                    }else if($type=="end"){
                        if($_GET['search-cpf']!=""){
                            $cpf = addslashes($_GET['search-cpf']);
                            $clients = $clientController->getByCpf($cpf);
                          }else{
                            $clients = $clientController->getAllForTable();
                          }
                    }else if($type=="new"){
                        if($_GET['search-email']!=""){
                            $email = addslashes($_GET['search-email']);
                            $clients = $clientController->getByEmail($email);
                          }else{
                            $clients = $clientController->getAllForTable();
                          }
                    }
                        
                    }
            }

                if($clients!=null){
                $table='
                <table class="centered responsive-table highlight">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>CPF</th>
                        <th>Celular</th>
                        <th>Operações</th>
                    </tr>
                </thead>

                <tbody>';
                $color;
                $st;
                $pagination="";
                $start=0;
                
                $end= sizeof($clients)<10 ? sizeof($clients) : 10;
                $paginationModel = new Pagination();
                if(isset($_GET['pagination_number'])){
                    
                    if(intval($_GET['pagination_number']) >=1){
                        
                        $page = $_GET['pagination_number'];
                        $diff=(sizeof($clients) - (intval($page)*10));
                
                        if($diff==0){
                            $start=sizeof($clients)-10;
                            $end=sizeof($clients);
                        }else{
                            if($diff<0){
                                $start = (intval($page)-1)*10;
                                $end = sizeof($clients);
                            }else{
                                $start = (intval($page)-1)*10;
                                $end = sizeof($clients)-$diff;
                            }
                        }

                        $pagination = $paginationModel->createPagination($page,$clients,'clients.php',$params);
                    }else{
                        $pagination = $paginationModel->createPagination("1",$clients,'clients.php',$params);
                    }

                }else{
                    $pagination = $paginationModel->createPagination("1",$clients,'clients.php',$params);
                }

                for($i=$start;$i<$end;$i++){
                    $u = $clients[$i];
                    $email=$u["email"];
                    $cpf=$u["cpf"];
                    $phone=$u["phone"];
                    if($u["email"]=='')
                    {
                        $email="Não cadastrado.";
                    }
                    if($u["cpf"]=='')
                    {
                        $cpf="Não cadastrado.";
                    }
                    if($u["phone"]=='')
                    {
                        $phone="Não cadastrado.";
                    }
                    $button_action='';
                    $table.='<tr>
                                <td>'.$u["name"].'</td>
                                <td>'.$email.'</td>
                                <td>'.$cpf.'</td>
                                <td>'.$phone.'</td>';               

                    $button_action='
                            <a class="btn-floating btn-small blue" title="Editar cliente" href="./updateClient.php?id='.$u['idClient'].'"><i class="fa-solid fa-pencil"></i></a>
                            <a class="btn-floating btn-small red deleteUserBtn" title="Excluir cliente" href="../services/Controller/DeleteClient.php?id='.$u['idClient'].'"><i class="fa-solid fa-trash"></i></a>
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
                        <th>Nome</th>
                        <th>Email</th>
                        <th>CPF</th>
                        <th>Celular</th>
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
    <script src="../js/clients.js"></script>

</body>

</html>