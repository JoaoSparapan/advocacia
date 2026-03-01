<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/UserController.php';
include_once '../services/Controller/ClientController.php';
include_once '../services/Controller/ReceptionController.php';

$userController = new UserController();
$users = $userController->getAllForTable();
$clientController = new ClientController();
$clis = $clientController->getAllForTable();
$receptionController = new ReceptionController();
$receptions = $receptionController->getAllReception();


if(AuthController::getUser()==null){
    header("location:./login.php");
    exit;
}

$params = str_replace("/advocacia/pages/recepcao.php?", "", $_SERVER["REQUEST_URI"]);
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
    <link rel="stylesheet" href="../styles/css/providences.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="./styles/css/swal/sweetalert2.min.css">

    <title>Recepções</title>
</head>

<body>

    <!-- Modal Structure -->
    <div id="modal2" class="modal" style="padding:20px;height:auto;">
        <div class="modal-content" style="border:0;padding:0;">
            <center>
                <h4>Cadastro de Recepção</h4>
            </center><br>

            <form class="col s12" method="POST" action="../services/Controller/CreateRecepcao.php">

                <div class="row">
                    <div class="input-field col s3" style="flex: 1 0 300px;">

                        <i class="fa-solid fa-calendar-days prefix"></i>
                        <input name="data-chegada" id="data-chegada" type="datetime-local" min="2000-01-01T00:00"
                                max="9999-12-31T00:00" required value="">
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
                        <input name="client-id" id="client-id" type="hidden" value="" />
                        <input name="client-front" id="client" type="text" required value=""
                            class="validate autocomplete" data-clientlist="<?= $nameList;?>"
                            data-clientids="<?= $cods;?>">
                        <label for="autocomplete-input">Cliente</label>
                    </div>


                </div>

                <div class="row">

                    <div class="input-field col s3">
                        <i class="fa-solid fa-list-ul prefix"></i>
                        <input name="assunto" id="assunto" type="text" class="validate" required value="">
                        <label for="assunto">Assunto</label>
                        <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                    </div>

                </div>

                <div class="row">

                    <div class="input-field col s3" style="margin-top:-10px;">
                        <i class="fa-solid fa-list-ol prefix"></i>
                        <input name="providencia" id="providencia" type="text" value="">
                        <label for="providencia">Providência</label>
                    </div>

                </div>

                <div class="row">
                    <div class="input-field col s3" style="margin-top:-10px;">
                        <i class="fa fa-user-tie prefix"></i>
                        <select name="resp" id="resp">

                            <option value="-1" disabled selected>Responsável</option>

                            <?php
                                for($i=0;$i<sizeof($users);$i++)
                                {

                                    if($users[$i]['idRole']!=3){
                                        echo "<option value='".$users[$i]['idUser']."'>".$users[$i]['name']."</option>";
                                    }
                                }
                            ?>

                        </select>

                    </div>

                </div>

                <div class="row">
                    <button class="btn waves-effect waves-light createUser" type="submit" name="action" id="reg-user" style="background-color:#27c494;">
                        Cadastrar
                    </button>

                </div>
            </form>

        </div>
    </div>


    <div class="wrapper d-flex align-items-stretch">
        <?php
        include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php";

        include_once '../services/Models/Pagination.php';
        
        $user = AuthController::getUser();

      ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5" style="background-color: #fff;">

            <nav class="navbar navbar-expand-lg navbar-light bg-light"
                style="background-color: transparent !important;">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>
                    Recepções
                </div>
            </nav>
            <form method="GET" action="#" class="filter">
                <div>

                    <div id="resp-sel" class="invisible">
                        <select name="search-resp" value="">
                            <option value="-1" selected disabled>Selecionar</option>
                            <?php
                                for($i=0;$i<sizeof($users);$i++)
                                {
                                        echo "<option value='".$users[$i]['idUser']."'>".$users[$i]['name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <input type="text" placeholder="Dta. de Referência" name="dta-contract" value="" id="dta-contract"
                        class="invisible" />

                    <input type="text" placeholder="Celular do cliente" name="search-phone" value="" id="start"
                        class="invisible" />
                    <input type="text" placeholder="CPF do cliente" name="search-cpf" value="" id="end"
                        class="invisible" />
                    <input type="text" placeholder="Email do cliente" name="search-email" value="" id="new"
                        class="invisible" />

                    <div id="search_period" class="invisible">
                        <div>
                            <input type="text" placeholder="Data Inicial" name="data_start" value="" id="date-start" />
                            <span>&nbsp;&nbsp;&nbsp;Até&nbsp;&nbsp;&nbsp;</span>
                            <input type="text" placeholder="Data Final" name="data_end" value="" id="date-end" />

                        </div>
                    </div>

                    <input type="text" placeholder="Nome do Cliente" name="search-ncli" value="" id="ncli" class="visible"/>
                </div>
                <div>
                    <select name="search-type" id="sel-type" value="" style="padding: 0;" class="browser-default">
                        <option value="ncli">Nome do cliente</option>
                        <option value="end">CPF do cliente</option>
                        <option value="new">Email do cliente</option>
                        <option value="start">Celular do cliente</option>
                        <option value="resp-sel">Responsável</option>
                        <option value="dta-contract">Data de referência</option>
                        <option value="search_period">Período</option>
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
                //echo $type;
                if($type==="ncli"){
                    if($_GET['search-ncli']=='')
                    {
                        $receptions = $receptionController->getAllReception();
                    }else{
                    $name = addslashes($_GET['search-ncli']);
                    //echo $name;exit;
                    $receptions = $receptionController->getAllReceptionByClient($name);
                    }
                }else if($type==="resp-sel"){
                  if(isset($_GET['search-resp'])){
                    $resp = addslashes($_GET['search-resp']);
                    $receptions = $receptionController->getAllReceptionByResponsavel($resp);
                  }else{
                    $receptions = $receptionController->getAllReception();
                  }
                }else if($type=="dta-contract"){
                    $start= explode("/",addslashes($_GET['dta-contract']));
                    if(sizeof($start)>1){

                        $start = $start[2]."-".$start[1]."-".$start[0];
                    }else{
                        $start="";
                    }
                    if($start==""){
                        $receptions = $receptionController->getAllReception();
                    }else{
                        $receptions = $receptionController->getReceptionByData($start);
                    }
                }else if($type=="search_period"){
                    $start= explode("/",addslashes($_GET['data_start']));
                    $end= explode("/",addslashes($_GET['data_end']));
                    if(sizeof($end)==1 || sizeof($start)==1)
                    {
                        $receptions = $receptionController->getAllReception();
                    }else{
                    if(sizeof($end)>1){

                        $end = $end[2]."-".$end[1]."-".$end[0];
                        $end.=' 23:59:59';
                    }
                    if(sizeof($start)>1){

                        $start = $start[2]."-".$start[1]."-".$start[0];
                        $start.=' 00:00:00';
                    }
                    $receptions = $receptionController->getReceptionByPeriod($start, $end);
                    }
                    
                }else if($type==="start"){
                    if($_GET['search-phone']!=""){
                      $phone = addslashes($_GET['search-phone']);
                      $receptions = $receptionController->getAllReceptionByPhoneClient($phone);
                    }else{
                      $receptions = $receptionController->getAllReception();
                    }
                  }else if($type=="end"){
                      if($_GET['search-cpf']!=""){
                          $cpf = addslashes($_GET['search-cpf']);
                          $receptions = $receptionController->getAllReceptionByCpfClient($cpf);
                        }else{
                          $receptions = $receptionController->getAllReception();
                        }
                  }else if($type=="new"){
                      if($_GET['search-email']!=""){
                          $email = addslashes($_GET['search-email']);
                          $receptions = $receptionController->getAllReceptionByEmailClient($email);
                        }else{
                          $receptions = $receptionController->getAllReception();
                        }
                  }
            }
                          
                if($receptions!=null){
                $showOperationsColumn = false;
                if($user['idRole'] == 1){
                    $showOperationsColumn = true;
                } else {
                    foreach($receptions as $rec){
                        if($rec['leave'] == null){
                            $showOperationsColumn = true;
                            break;
                        }
                    }
                }
                $table='
                <table class="centered responsive-table highlight">
                <thead>
                    <tr>
                        <th>Chegada</th>
                        <th>Saída</th>
                        <th>Cliente</th>
                        <th>Celular</th>
                        <th>CPF</th>
                        <th>Responsável</th>
                        <th>Assunto</th>
                        <th>Providência</th>
                        '.($showOperationsColumn ? '<th>Operações</th>' : '').'
                    </tr>
                </thead>

                <tbody>';
                $color;
                $st;
                $pagination="";
                $start=0;
                
                $end= sizeof($receptions)<10 ? sizeof($receptions) : 10;
                $paginationModel = new Pagination();
                if(isset($_GET['pagination_number'])){
                    
                    if(intval($_GET['pagination_number']) >=1){
                        
                        $page = $_GET['pagination_number'];
                        $diff=(sizeof($receptions) - (intval($page)*10));
                
                        if($diff==0){
                            $start=sizeof($receptions)-10;
                            $end=sizeof($receptions);
                        }else{
                            if($diff<0){
                                $start = (intval($page)-1)*10;
                                $end = sizeof($receptions);
                            }else{
                                $start = (intval($page)-1)*10;
                                $end = sizeof($receptions)-$diff;
                            }
                        }
                        //echo "  - ".$params." ( pagination_number>=1 query param)";
                        $pagination = $paginationModel->createPagination($page,$receptions,'recepcao.php', $params);
                    }else{
                        // echo "  - ".$params." ( pagination_number<1 query param)";
                        $pagination = $paginationModel->createPagination("1",$receptions,'recepcao.php', $params);
                    }

                }else{
                    // echo "  - ".$params." (no pagination_number query param)";
                    $pagination = $paginationModel->createPagination("1",$receptions,'recepcao.php', $params);
                }

                for($i=$start;$i<$end;$i++){
                    $u = $receptions[$i];
                    $resp=$userController->getById($u["idResponsavel"]);
                    $clo=$clientController->getById($u["idClient"]);
                    $phoneCli=$clo["phone"];
                    $cpfCli=$clo["cpf"];
                    if($clo["phone"]=='')
                    {
                        $phoneCli="Não cadastrado.";
                    }
                    if($clo["cpf"]=='')
                    {
                        $cpfCli="Não cadastrado.";
                    }
                    $saida='';
                    if($u["leave"]==null)
                    {
                        $saida='Atendimento não finalizado'; 
                    }else{
                        $saida=date("d-m-Y H:i:s", strtotime($u["leave"]));
                    }
                    $n=$u["providence"];
                    if($n==NULL)
                    {
                        $n="---";
                    }
                    $button_action='';
                    $table.='<tr>
                                <td>'.date("d-m-Y H:i:s", strtotime($u["arrival"])).'</td>
                                <td>'.$saida.'</td>
                                <td>'.$clo["name"].'</td>
                                <td>'.$phoneCli.'</td>
                                <td>'.$cpfCli.'</td>
                                <td>'.$resp["name"].'</td>
                                <td>'.$u["subject"].'</td>
                                <td>'.$n.'</td>';
                    
                    
                    $button_action = '';
                    if($u["leave"] == null){
                        $button_action .= '
                            <a class="btn-floating btn-small green" 
                            title="Finalizar atendimento" 
                            href="../services/Controller/RecepcaoFinalized.php?id='.$u['idReception'].'">
                            <i class="fa-solid fa-check"></i>
                            </a>
                        ';
                    }
                    if($user['idRole'] == 1){

                        $button_action .= '
                            <a class="btn-floating btn-small blue" 
                            title="Editar recepção" 
                            href="./updateRecepcao.php?id='.$u['idReception'].'">
                            <i class="fa-solid fa-pencil"></i>
                            </a>

                            <a class="btn-floating btn-small red deleteUserBtn" 
                            title="Excluir recepção" 
                            href="../services/Controller/DeleteRecepcao.php?id='.$u['idReception'].'">
                            <i class="fa-solid fa-trash"></i>
                            </a>
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
                        <th>Chegada</th>
                        <th>Saída</th>
                        <th>Cliente</th>
                        <th>Celular</th>
                        <th>Responsável</th>
                        <th>Assunto</th>
                        <th>Providência</th>
                        <th>Operações</th>
                    </tr>
                </thead>
                </table>
                ';
                echo $table;
            }
            
            
            ?>

            <?php
            function isMobile() {
                return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
            }
                if($receptions!=null && isMobile()){
            ?>

            <a href="<?php echo "../services/Controller/GeneratePDFControllerRecepcao.php?id=0&".$params;?>">
                <button style="margin-left: 10px;background-color: #27c494; color:white;"
                    class="btn waves-effect waves-light" type="button" id="back">
                    Exportar PDF
                </button>
            </a>

            <?php
                }else if($receptions!=null && !isMobile())
                {
            ?>

            <a target="_blank" href="<?php echo "../services/Controller/GeneratePDFControllerRecepcao.php?id=0&".$params;?>">
                <button style="margin-left: 10px;background-color: #27c494; color:white;"
                    class="btn waves-effect waves-light" type="button" id="back">
                    Exportar PDF
                </button>
            </a>

            <?php
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
    <script src="../js/recepcao.js"></script>
</body>

</html>