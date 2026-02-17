<?php
session_start();
date_default_timezone_set('America/Sao_Paulo'); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
if(AuthController::getUser()==null){
    header("location:./login.php");
}
$params = str_replace("/advocacia/pages/distributed.php?", "", $_SERVER["REQUEST_URI"]);
$url = str_replace("/advocacia/pages/", "", $_SERVER["REQUEST_URI"]);

$userAtual = AuthController::getUser()['idUser'];
$index = strrpos($params,'pagination_number=');


include_once "../services/Controller/LogController.php";

$logController = new LogController();

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
    <link rel="stylesheet" href="../styles/css/providences.css">
    <link rel="shortcut icon" href="../logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <title>Petições Distribuídas</title>
</head>

<body>

    <div class="wrapper d-flex align-items-stretch">
        <?php
        include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/menu.php";
        include_once '../services/Models/Pagination.php';
        include_once '../services/Controller/PetitionController.php';
        $provController = new PetitionController();
        $petition = $provController->getPetitionDistributed();  
      ?>

        <div id="modal3" class="modal" style="height:auto;
    padding: 20px;
    background-color: white;
    ">
            <div class="modal-content" style="padding:0 !important; border: 0;">
                <h5 style="">Atualizar status da petição</h5>

                <form class="col s12" method="POST" action="../services/Controller/UpdateStatusPetition.php">
                    <input type="hidden" id="idProvidenceModal" name="idProvidence" value="" />
                    <input type="hidden" id="distributed" name="distributed" value="1" />
                    <input type="hidden" id="usuarioAtual" name="usuarioAtual" value="<?= $userAtual;?>" />

                    <div class="row">

                        <div class="input-field col s3" style="
                      display: flex;
                      align-items: center;
                      
                      flex-direction: column;
                    ">
                            Distribuída?
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
                        <div class="input-field col s3" style="
                            display: flex;
                            align-items: center;
                            
                            flex-direction: column;
                            ">
                                Sem fundamento?
                                <div class="switch">
                                    <label>
                                        Não
                                        <input type="checkbox" name="sfProvidedModal" id="sfProvidedModal">
                                        <span class="lever"></span>
                                        Sim
                                    </label>
                                </div>

                        </div>
                    </div>

                    <div class="row">
                        <button class="btn waves-effect waves-light loading" type="submit" name="action" id="reg-user">
                            Alterar
                        </button>

                    </div>
                </form>
                <hr>
                <h5 style="">Histórico de mudança de status</h5>
                <div id="content-logs" style="
                    display: flex;
                    flex-direction: column;
                    gap: .5rem;
                    max-height: 200px;
                    overflow: auto;
                "></div>
            </div>
        </div>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>
                    Petições Distribuídas
                </div>
            </nav>

            <form method="GET" action="#" class="filter">
                <div>
                    <input type="text" name="search-nproc" value="" id="nproc" class="invisible"
                        placeholder="Número do processo a pesquisar">


                    <input type="text" placeholder="Nome do cliente" name="search-ncli" value="" id="ncli"
                        class="visible" />
                    <input type="text" placeholder="Adverso" name="search-adverso" value="" id="adverso"
                        class="invisible" />
                    <input type="text" placeholder="Natureza da ação" name="type-action" value="" id="type-action"
                        class="invisible" />
                    <input type="text" placeholder="Responsável" name="responsible-user" value="" id="responsible-user"
                        class="invisible" />
                    <input type="number" placeholder="Dias p/ ajuizamento" name="days-to-trial" value=""
                        id="days-to-trial" class="invisible" min="1" />

                    <input type="text" placeholder="Dta. de Contratação" name="dta-contract" value="" id="dta-contract"
                        class="invisible" />
                    <div id="search_period" class="invisible">
                        <div>
                            <input type="text" placeholder="Data Inicial" name="data_start" value="" id="date-start" />
                            <span>&nbsp;&nbsp;&nbsp;Até&nbsp;&nbsp;&nbsp;</span>
                            <input type="text" placeholder="Data Final" name="data_end" value="" id="date-end" />

                        </div>
                    </div>


                </div>
                <div style="margin-right: 1rem;">
                    <select name="search-type" id="sel-type" value="" style="padding: 0;" class="browser-default">
                        <option value="ncli">Nome do cliente</option>
                        <option value="adverso">Adverso</option>
                        <option value="dta-contract">Data da contratação</option>
                        <option value="search_period">Período</option>
                        <option value="type-action">Natureza da ação</option>
                        <option value="responsible-user">Responsável</option>
                        <!-- <option value="days-to-trial">Dias p/ ajuizamento</option> -->

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
                    
                    $petition = $provController->getPetitionByClient($name,1,1,1);
                    
                }else if($type==="adverso"){
                  if(isset($_GET['search-adverso'])){
                    $adverse = addslashes($_GET['search-adverso']);
                    $petition = $provController->getPetitionByAdverse($adverse,1,1,1);
                  }else{
                    $petition = $provController->getPetitionDistributed();
                  }
                }else if($type=="dta-contract"){
                    $start= explode("/",addslashes($_GET['dta-contract']));
                    if(sizeof($start)>1){

                        $start = $start[2]."-".$start[1]."-".$start[0];
                    }else{
                        $start="";
                    }
                    if($start==""){
                        $petition = $provController->getPetitionDistributed();
                    }else{
                        $petition = $provController->getProvidenceByData($start,1,1,1);
                    }
                }else if($type=="search_period"){
                    $start= explode("/",addslashes($_GET['data_start']));
                    $end= explode("/",addslashes($_GET['data_end']));
                    if(sizeof($end)==1 || sizeof($start)==1)
                    {
                        $petition = $provController->getPetitionDistributed();
                    }else{
                    if(sizeof($end)>1){

                        $end = $end[2]."-".$end[1]."-".$end[0];
                    }
                    if(sizeof($start)>1){

                        $start = $start[2]."-".$start[1]."-".$start[0];
                    }
                    $petition = $provController->getProvidenceByPeriod($start,$end,1,1,1);
                    }

                }else if($type=='type-action'){

                        $type_action=addslashes($_GET['type-action']);
                        if($type_action!=""){
                            $petition = $provController->getPetitionByActionType($type_action,1,1,1);
                        
                        }else{

                            $petition = $provController->getPetitionDistributed();
                        }
                }else if($type=='responsible-user'){
                    $responsible_user = addslashes($_GET['responsible-user']);
                    // echo $responsible_user;
                    if($responsible_user!=""){
                        $petition = $provController->getPetitionByResponsibleUser($responsible_user,1,1,1);
                        
                    }else{

                        $petition = $provController->getPetitionDistributed();
                    }
                }else if($type=='days-to-trial'){
                    $days = addslashes($_GET['days-to-trial']);
                    
                    if($days!=""){
                        $petition = $provController->getPetitionByDaysForTrial($days,1,1);
                        
                    }else{

                        $petition = $provController->getPetitionDistributed();
                    }
                }
                    
                }else{
                    $petition = $provController->getPetitionDistributed();
                }
                
            
                
            if($petition!=null){
                $th="";
                if(AuthController::getUser()['idRole']==1){
                    $th="<th>Operações</th>";
                }

              $table='
              <table class="centered responsive-table highlight">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Adverso</th>
                    <th>Contratação</th>
                    <th>Natureza da ação</th>
                    <th>Responsável</th>
                    <th>Dias p/ Ajuizamento</th>
                    <th>Prescrição</th>
                    <th>Decadência</th>
                    <th>Status</th>
                  '.$th.'
                  
                </tr>
              </thead>

              <tbody>';
              $color;
              $st;
              $pagination="";
              $start=0;
              
              $end= sizeof($petition)<10 ? sizeof($petition) : 10;
              $paginationModel = new Pagination();
              if(isset($_GET['pagination_number'])){
                  
                  if(intval($_GET['pagination_number']) >=1){
                      
                      $page = $_GET['pagination_number'];
                      $diff=(sizeof($petition) - (intval($page)*10));
              
                      if($diff==0){
                          $start=sizeof($petition)-10;
                          $end=sizeof($petition);
                      }else{
                          if($diff<0){
                              $start = (intval($page)-1)*10;
                              $end = sizeof($petition);
                          }else{
                              $start = (intval($page)-1)*10;
                              $end = sizeof($petition)-$diff;
                          }
                      }

                      $pagination = $paginationModel->createPagination($page,$petition,'distributed.php', $params);
                  }else{
                      $pagination = $paginationModel->createPagination("1",$petition,'distributed.php', $params);
                  }

              }else{
                  $pagination = $paginationModel->createPagination("1",$petition,'distributed.php', $params);
              }

              for($i=$start;$i<$end;$i++){
                $p = $petition[$i];
                $color_style='';
                $pres="Não";
                $deca="Não";
                if($p[5]==1)
                {
                    $pres="Sim";
                }
                if($p[6]==1)
                {
                    $deca="Sim";
                }
                $l = $logController->getLastLogByPetition($p[0]);
                $allLogs = $logController->getLogByPetition($p[0]);
                $allLogs_str = str_replace('"', "'", json_encode($allLogs));
                $day = explode(" ", $l['edited_by']);
                $target = date_create(date('Y-m-d', strtotime($p[1])));
                $date = date_create(date('Y-m-d', strtotime($day[0])));
                $aux=0;
                $d="";
                $interval = date_diff($date, $target);
                if(strtotime($date->format('d-m-Y'))==strtotime($target->format('d-m-Y')))
                {
                    $interval = date_diff($date, $target);
                }else{
                    $interval = date_diff($date, $target);
                    $aux=intval($interval->format('%a'));
                }
                if($aux==1)
                {
                    $d = $interval->format('%a dia');
                }else{
                    $d = $interval->format('%a dias');
                }
                $status='';
                if($p[7]==1)
                {
                    $status='Sem fundamento';
                    $d='---';
                    $pres='---';
        			$deca='---';
                    $color_style='font-style: italic;';
                }else{
                    $status='Distribuída';
                }
                
                $id = $i+1;
                $button_action='';
                $table.='<tr style="'.$color_style.'">
                            <td>'.$id.'</td>
                            <td>'.$p[2].'</td>
                            <td>'.$p[3].'</td>
                            <td>'.date("d-m-Y",strtotime($p[1])).'</td>
                            <td>'.$p[9].'</td>
                            <td>'.$p[11].'</td>
                            <td>'.$d.'</td>
                            <td>'.$pres.'</td>
                            <td>'.$deca.'</td>
                            <td>'.$status.'</td>';
                  
                    if(AuthController::getUser()['idRole']==1){

                        $button_action='
                                  <a class="btn-floating btn-small green modal-trigger openModalStatus" 
                                  data-prov="'.$p[0].'" 
                                  data-provided="'.$p[4].'"
                                  data-sf="'.$p[7].'"
                                  data-logs="'.$allLogs_str.'"
                                  title="Alterar status" href="#modal3"><i class="fa fa-th-list"></i></a>
                                <a class="btn-floating btn-small blue" title="Editar petição" href="./updatePetition.php?id='.$p[0].'"><i class="fa-solid fa-pencil"></i></a>  
                                <a class="btn-floating btn-small red deleteProvicendeBtn" title="Excluir petição" href="../services/Controller/DeletePetition.php?id='.$p[0].'"><i class="fa-solid fa-trash"></i></a>
                                ';
                    }else{
                        $button_action='
                                  <a class="btn-floating btn-small green modal-trigger openModalStatus" 
                                  data-prov="'.$p[0].'" 
                                  data-provided="'.$p[4].'"
                                  data-sf="'.$p[7].'"
                                  data-logs="'.$allLogs_str.'"
                                  title="Alterar status" href="#modal3"><i class="fa fa-th-list"></i></a>
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
                if(AuthController::getUser()['idRole']==1 || AuthController::getUser()['idRole']==2){
                    $th="<th>Operações</th>";
                }
                $table='
                <table class="centered responsive-table highlight">
                <thead>
                  <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Adverso</th>
                        <th>Contratação</th>
                        <th>Natureza da ação</th>
                        <th>Responsável</th>
                        <th>Dias p/ Ajuizamento</th>
                        <th>Prescrição</th>
                        <th>Decadência</th>
                    '.$th.'
                    
                  </tr>
                </thead>
              </table>
              ';
              echo $table;
          }
          
          
          ?>

            <?php
                if($petition!=null){
            ?>
            <a target="_blank" href="<?php echo "../services/Controller/GeneratePDFController.php?id=1&".$params;?>">
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