<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
if(AuthController::getUser()==null){
    header("location:./login.php");
    exit;
}else if(AuthController::getUser()['idRole']==2){
    header("location:../index.php");
    exit;
}

$params = str_replace("/advocacia/pages/users.php?", "", $_SERVER["REQUEST_URI"]);
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
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="stylesheet" href="../styles/css/sidebar-hide.css">
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/member.css">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="./styles/css/swal/sweetalert2.min.css">

    <title>Usuários</title>
</head>

<body>

    <!-- Modal Structure -->
    <div id="modal2" class="modal" style="padding:20px;height:auto;">
        <div class="modal-content" style="border:0;padding:0;">
            <h4 style="text-align: center;">Cadastro de Usuário</h4>

            <form class="col s12" style="padding:0;" method="POST" action="../services/Controller/MemberController.php">

                <div class="row">
                    <div class="input-field col s3">
                        <i class="fa-solid fa-user prefix"></i>
                        <input name="nome" id="name" type="text" class="validate" required value="">
                        <label for="name">Nome</label>
                        <span class="helper-text" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s3">

                        <i class="fa fa-envelope prefix"></i>
                        <input name="email" id="email-reg" type="email" class="validate" required value="">
                        <label for="email-reg">Email</label>
                        <span class="helper-text" data-error="Formato de email incorreto"
                            data-success="Correto!"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s3">
                        <i class="fa fa-id-card prefix" aria-hidden="true"></i>
                        <input name="cpf" id="cpf" type="text" class="validate" required value="">
                        <label for="cpf">CPF</label>
                        <span class="helper-text" data-error="CPF inválido!" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s3">

                        <i class="fa-solid fa-lock prefix"></i>
                        <input name="senha" id="senha" type="password" class="validate" required value="">
                        <label for="senha">Senha</label>
                        <span class="helper-text" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s3">
                        <i class="fa-solid fa-address-book prefix"></i>
                        <select name="role">

                            <option value="" disabled selected>Função</option>
                            <option value="1">Administrador</option>
                            <option value="2">Colaborador</option>

                        </select>

                    </div>
                </div>



                <div class="row">
                    <button class="btn waves-effect waves-light createUser loading" type="submit" name="action"
                        id="reg-user">
                        Cadastrar
                    </button>

                </div>
            </form>

        </div>
    </div>

    <?php
        include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php";
        include_once '../services/Controller/RoleController.php';
        include_once '../services/Controller/UserController.php';
        include_once '../services/Models/Pagination.php';
        $role = new RoleController();
        $userController = new UserController();
        $user = AuthController::getUser();
        $users = $userController->getAllForTable();
        
      ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <form method="GET" action="#" class="filter">
                <div>
                    <input type="text" name="filter-name" value="" id="nome" class="visible"
                        placeholder="Nome a pesquisar">

                </div>

                <button type="submit" class="btn btn-small waves-effect waves-light"><i class="fa-solid fa-filter"></i>
                    &nbsp;&nbsp;Buscar</button>
            </form>
            <a class="btn modal-trigger" href="#modal2" title="Adicionar usuário" style="    background-color: #27c494 !important;
            border-radius: 5px !important;
            margin: 10px 0px;
            margin-left: 5px;
            color: white;"><i class="fa-solid fa-plus"></i></a>
            <?php
            

            if(isset($_GET['filter-name'])){
                $name = $_GET['filter-name'];
                $users = $userController->getByName($name);
            }

                
            
                
                
                if($users!=null){
                $table='
                <table class="centered responsive-table highlight">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>CPF</th>
                        <th>Função</th>
                        <th>Operações</th>
                    </tr>
                </thead>

                <tbody>';
                $color;
                $st;
                $pagination="";
                $start=0;
                
                $end= sizeof($users)<10 ? sizeof($users) : 10;
                $paginationModel = new Pagination();
                if(isset($_GET['pagination_number'])){
                    
                    if(intval($_GET['pagination_number']) >=1){
                        
                        $page = $_GET['pagination_number'];
                        $diff=(sizeof($users) - (intval($page)*10));
                
                        if($diff==0){
                            $start=sizeof($users)-10;
                            $end=sizeof($users);
                        }else{
                            if($diff<0){
                                $start = (intval($page)-1)*10;
                                $end = sizeof($users);
                            }else{
                                $start = (intval($page)-1)*10;
                                $end = sizeof($users)-$diff;
                            }
                        }

                        $pagination = $paginationModel->createPagination($page,$users,'users.php',$params);
                    }else{
                        $pagination = $paginationModel->createPagination("1",$users,'users.php',$params);
                    }

                }else{
                    $pagination = $paginationModel->createPagination("1",$users,'users.php',$params);
                }


                for($i=$start;$i<$end;$i++){
                    $u = $users[$i];
                    $funcao = $userController->getRoleByIdUser($u['idRole']);
                    $button_action='';
                    $table.='<tr>
                                <td>'.$u["name"].'</td>
                                <td>'.$u["email"].'</td>
                                <td>'.$u["cpf"].'</td>
                                <td>'.$funcao.'</td>';
                    


                    if($user['idUser']== $u['idUser']){

                        $button_action='
                            <a class="btn-floating btn-small blue" title="Editar usuário" href="./updateUser.php?id='.$u['idUser'].'"><i class="fa-solid fa-pencil"></i></a>
                                    
                                ';
            
                    }else{
                        $button_action='
                            <a class="btn-floating btn-small blue" title="Editar usuário" href="./updateUser.php?id='.$u['idUser'].'"><i class="fa-solid fa-pencil"></i></a>
                            <a class="btn-floating btn-small red deleteUserBtn" title="Excluir usuário" href="../services/Controller/DeleteUser.php?id='.$u['idUser'].'"><i class="fa-solid fa-trash"></i></a>
                                ';
                   }

                           
                            
                    
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
                        <th>CPF</th>
                        <th>Operações</th>
                    </tr>
                </thead>
                </table>
                ';
                echo $table;
            }
            
            
            ?>




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
    <script src="../js/member.js"></script>
</body>

</html>