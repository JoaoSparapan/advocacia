<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/UserController.php';
if(AuthController::getUser()==null){
    header("location:./login.php");
}
$params = str_replace("/advocacia/pages/providenced.php?", "", $_SERVER["REQUEST_URI"]);
$url = str_replace("/advocacia/pages/", "", $_SERVER["REQUEST_URI"]);


$index = strrpos($params,'pagination_number=');

$userController = new UserController();

if($index.""=="0"){
    
    $params = str_replace(substr($params,$index, $index+20), "", $params);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../styles/css/sidebar-hide.css">
    <link rel="stylesheet" href="../styles/css/providences.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <title>Providências Concluídas</title>
</head>

<body>

    <div class="wrapper d-flex align-items-stretch">
        <?php
        include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php";
        include_once '../services/Controller/VaraController.php';
        include_once '../services/Models/Pagination.php';
        include_once '../services/Controller/ProvidenceController.php';
        include_once '../services/Controller/ProcessController.php';
        $c = new VaraController();
        $court = $c->getAll();
        $provController = new ProvidenceController();
        $providences = $provController->getProvidenceProvidenced();
        $processController = new ProcessController();   
      ?>

        <div id="modal3" class="modal" style="height:auto;
    padding: 20px;
    background-color: white;
    ">
            <div class="modal-content" style="padding:0 !important; border: 0;">
                <h4 style="text-align: center;">Atualizar status da providência</h4>

                <form class="col s12" method="POST" action="../services/Controller/UpdateStatusProvidence.php">
                    <input type="hidden" id="idProvidenceModal" name="idProvidence" value="" />
                    <input type="hidden" id="providenced" name="providenced" value="1" />
                    <input type="hidden" name="callback" value="<?=$_SERVER["REQUEST_URI"]?>">

                    <div class="row">

                        <?php
                    if(AuthController::getUser()['idRole']==1){
                    ?>
                        <div class="input-field col s3" style="
                      display: flex;
                      align-items: center;
                      
                      flex-direction: column;
                    ">
                            Providenciada?
                            <div class="switch">
                                <label>
                                    Não
                                    <input type="checkbox" name="provProvidedModal" id="provProvidedModal"
                                        checked="checked">
                                    <span class="lever"></span>
                                    Sim
                                </label>
                            </div>

                        </div>
                        <?php
                    }
                    ?>
                    </div>

                    <div class="row">
                        <button class="btn waves-effect waves-light loading" type="submit" name="action" id="reg-user">
                            Alterar
                        </button>

                    </div>
                </form>

            </div>
        </div>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    Providências Concluídas
                </div>
            </nav>

            <form method="GET" action="#" class="filter">
                <div>
                    <input type="text" name="search-nproc" value="" id="nproc" class="invisible"
                        placeholder="Número do processo a pesquisar">
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

                    <input type="text" placeholder="Nome do cliente" name="search-ncli" value="" id="ncli"
                        class="visible" />

                    <div id="search_period" class="invisible">
                        <div>
                            <input type="text" placeholder="Data Inicial" name="data_start" value="" id="date-start" />
                            <span>&nbsp;&nbsp;&nbsp;Até&nbsp;&nbsp;&nbsp;</span>
                            <input type="text" placeholder="Data Final" name="data_end" value="" id="date-end" />

                        </div>
                    </div>
                    <input type="text" placeholder="Responsável" name="responsible-user" value="" id="responsible-user"
                        class="invisible" />
                </div>
                <div style="margin-right: 1rem;">
                    <select name="search-type" id="sel-type" value="" style="padding: 0;" class="browser-default">
                        <option value="ncli">Nome do cliente</option>
                        <option value="nproc">N° do processo</option>
                        <option value="search_period">Período</option>
                        <option value="vara-sel">Vara</option>
                        <option value="responsible-user">Responsável</option>

                    </select>
                </div>
                <button type="submit" class="btn waves-effect waves-light"><i class="fa-solid fa-filter"></i>
                    &nbsp;&nbsp;BUSCAR</button>

            </form><br><br>

            <?php
            
            if(isset($_GET['search-type'])){
                $type = $_GET['search-type'];
                //echo $type;
                if($type==="ncli"){
                    $name = addslashes($_GET['search-ncli']);
                    
                    $providences = $provController->getProvidenceByClientName($name,1);
                }else if($type==="vara-sel"){
                  if(isset($_GET['search-vara'])){
                    $vara = addslashes($_GET['search-vara']);
                    $providences = $provController->getProvidenceByCourt($vara,1);
                  }else{
                    $providences = $provController->getProvidenceInProgress(1);
                  }
                }else if($type=="search_period"){
                    $start= explode("/",addslashes($_GET['data_start']));
                    $end= explode("/",addslashes($_GET['data_end']));
                    if(sizeof($end)>1){

                        $end = $end[2]."-".$end[1]."-".$end[0];
                    }else{
                        $end="";
                    }
                    if(sizeof($start)>1){

                        $start = $start[2]."-".$start[1]."-".$start[0];
                    }else{
                        $start="";
                    }

                    $providences = $provController->getProvidenceByDataTerm($start." 00:00:00", $end." 23:59:59", 1);
                }else if($type=='responsible-user'){
                    $responsible_user = addslashes($_GET['responsible-user']);
                    // echo $responsible_user;
                    if($responsible_user!=""){
                        $providences = $provController->getProvidenceByResponsibleUser($responsible_user,1);
                        
                    }else{

                        $providences = $provController->getProvidenceInProgress(1);
                    }
                }else{
                    $nproc="";
                    if(isset($_GET['search-nproc'])){

                        $nproc=addslashes($_GET['search-nproc']);
                    }
                    //echo $nproc;
                    if($nproc==""){
                        $providences = $provController->getProvidenceInProgress(1);
                        
                    }else{

                        $providences = $provController->getProvidenceByNumProcess($nproc,1);
                    }
                    
                }
            }
            
            if($providences!=null){
                $th="";
                if(AuthController::getUser()['idRole']==1){
                    $th="<th>Operações</th>";
                }

              $table='
              <table class="centered responsive-table highlight">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Disponibilização</th>
                    <th>Publicação</th>
                    <th>Prazo</th>
                    <th>Processo</th>
                    <th>Vara</th>
                    <th>Responsável</th>
                    <th>Cliente</th>
                    <th>Providência</th>
                    <th>Status</th>
                  '.$th.'
                  
                </tr>
              </thead>

              <tbody>';
              $color;
              $st;
              $pagination="";
              $start=0;
              
              $end= sizeof($providences)<10 ? sizeof($providences) : 10;
              $paginationModel = new Pagination();
              if(isset($_GET['pagination_number'])){
                  
                  if(intval($_GET['pagination_number']) >=1){
                      
                      $page = $_GET['pagination_number'];
                      $diff=(sizeof($providences) - (intval($page)*10));
              
                      if($diff==0){
                          $start=sizeof($providences)-10;
                          $end=sizeof($providences);
                      }else{
                          if($diff<0){
                              $start = (intval($page)-1)*10;
                              $end = sizeof($providences);
                          }else{
                              $start = (intval($page)-1)*10;
                              $end = sizeof($providences)-$diff;
                          }
                      }

                      $pagination = $paginationModel->createPagination($page,$providences,'providenced.php', $params);
                  }else{
                      $pagination = $paginationModel->createPagination("1",$providences,'providenced.php', $params);
                  }

              }else{
                  $pagination = $paginationModel->createPagination("1",$providences,'providenced.php', $params);
              }


              for($i=$start;$i<$end;$i++){
                  $p = $providences[$i];
                  $status="Concluída";
                  $id = $i+1;
                  $proc = $processController->getById($p[1]);
                  $vara = $c->getById($proc['idCourt']);
                  $user = $userController->getById($p[12]);
                  $button_action='';
                  $table.='<tr>
                              <td>'.$id.'</td>
                              <td>'.date("d-m-Y", strtotime($p[6])).'</td>
                              <td>'.date("d-m-Y", strtotime($p[7])).'</td>
                              <td>'.date("d-m-Y", strtotime($p[8])).'</td>
                              <td>'.$proc["numProcess"].'</td>
                              <td>'.$vara["sigla"].'</td>
                              <td>'.$user["name"].'</td>
                              <td>'.$proc["clientName"].'</td>
                              <td>'.$p[9].'</td>
                              <td>'.$status.'</td>';
                  
                    if(AuthController::getUser()['idRole']==1){

                        $button_action='
                                  <a class="btn-floating btn-small green modal-trigger openModalStatus" 
                                  data-prov="'.$p[0].'" 
                                  data-done="'.$p[10].'"
                                  data-provided="'.$p[11].'"
                                  title="Alterar status" href="#modal3"><i class="fa fa-th-list"></i></a>
                                <a class="btn-floating btn-small red deleteProvicendeBtn" title="Excluir providência" href="../services/Controller/DeleteProvidence.php?id='.$p[0].'"><i class="fa-solid fa-trash"></i></a>
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
                $th="";
                if(AuthController::getUser()['idRole']==1){
                    $th="<th>Operações</th>";
                }
                $table='
                <table class="centered responsive-table highlight">
                <thead>
                  <tr>
                      <th>#</th>
                      <th>Disponibilização</th>
                      <th>Publicação</th>
                      <th>Prazo</th>
                      <th>Processo</th>
                      <th>Vara</th>
                      <th>Cliente</th>
                      <th>Providência</th>
                      <th>Status</th>
                    '.$th.'
                    
                  </tr>
                </thead>
              </table>
              ';
              echo $table;
          }
          
          
          ?>

            <?php
                if($providences!=null){
            ?>
            <a target="_blank" href="<?php echo "../services/Controller/GeneratePDFControllerProv.php?id=1&".$params;?>">
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
    <script src="../js/providenced.js"></script>

</body>

</html>