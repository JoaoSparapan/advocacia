<?php
session_start(); 
date_default_timezone_set('America/Sao_Paulo');
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once "../services/Controller/LogController.php";

$logController = new LogController();
if(AuthController::getUser()==null){
    header("location:./login.php");
}

$params = str_replace("/advocacia/pages/petition.php?", "", $_SERVER["REQUEST_URI"]);
$url = str_replace("/advocacia/pages/", "", $_SERVER["REQUEST_URI"]);

$userAtual = AuthController::getUser()['idUser'];
$index = strrpos($params,'pagination_number=');

if($index.""=="0"){
    
    $params = str_replace(substr($params,$index, $index+20), "", $params);
}
include_once '../services/Controller/UserController.php';
$userController = new UserController();
$u = $userController->getAdminAndColaboradorAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../styles/css/sidebar-hide.css">
    <link rel="stylesheet" href="../styles/css/providences.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />

    <title>Petições</title>
</head>

<body>
    <!-- Modal Structure -->
    <div id="modalcreateProvidence" class="modal" style="height:auto;
    padding: 20px;
    background-color: white;
    
    ">
        <div class="modal-content" style="padding:0 !important; border: 0;">
            <h4 style="text-align: center;">Cadastro de Petição</h4>

            <form class="col s12" style="" method="POST" action="../services/Controller/CreatePetition.php"
                style="padding: 0 !important;">


                <div class="row computer">
                    <div class="input-field col s3">

                        <i class="fa-solid fa-calendar-days prefix"></i>
                        <input name="dta-contact" id="data-intim" type="date" min="2000-01-01T00:00"
                            max="<?=date('Y-m-d')?>" required value="">
                        <label for="data-intim" style="top: -5px !important;">Data da
                            Contratação</label>
                        <span class="helper-text" data-error="Formato de Data incorreto" data-success="Correto!"></span>

                    </div>

                    <div class="input-field col s3">
                        <i class="fa-regular fa-file-lines prefix"></i>
                        <input name="clientName" id="clientName" type="text" class="validate" required value="">
                        <label for="providencia">Cliente</label>
                        <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                    </div>

                    <div class="input-field col s3">
                        <i class="fa-regular fa-file-lines prefix"></i>
                        <input name="adverse" id="adverse" type="text" class="validate" required value="">
                        <label for="providencia">Adverso</label>
                        <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row mobile">
                    <div class="input-field">

                        <i class="fa-solid fa-calendar-days prefix"></i>
                        <input name="dta-contact" id="data-intim_mobile" type="date" min="2000-01-01T00:00"
                            max="<?=date('Y-m-d')?>" required value="" style="min-width: 80% !important;">
                        <label for="data-intim_mobile" style="top: -5px !important;">Data de Contratação</label>
                        <span class="helper-text" data-error="Formato de Data incorreto" data-success="Correto!"></span>

                    </div>

                </div>
                <div class="row mobile">
                    <div class="input-field">
                        <i class="fa-regular fa-file-lines prefix"></i>
                        <input name="clientName" id="client_name_mobile" type="text" class="validate" required value="">
                        <label for="providencia">Cliente</label>
                        <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row mobile">
                    <div class="input-field">
                        <i class="fa-regular fa-file-lines prefix"></i>
                        <input name="adverse" id="adverse_mobile" type="text" class="validate" required value="">
                        <label for="providencia">Adverso</label>
                        <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                    </div>
                </div>

                <div class="row computer">
                    <div class="input-field col s3" style="display: flex;">
                        <i class="fa fa-id-card prefix"></i>
                        <select name="adv" required id="select-adv">
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

                    <div class="input-field col s3">
                        <i class="fa-regular fa-file-lines prefix"></i>
                        <input name="typeAction" id="typeAction" type="text" class="validate" required value="">
                        <label for="providencia">Natureza da Ação</label>
                        <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row mobile">
                    <div class="input-field" style="display: flex;">
                        <i class="fa fa-id-card prefix"></i>
                        <select name="adv" required id="select-adv_mobile">
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
                        <!-- <label>Adv. Responsável</label> -->

                    </div>
                </div>
                <div class="row mobile">
                    <div class="input-field">
                        <i class="fa-regular fa-file-lines prefix"></i>
                        <input name="typeAction" id="typeAction_mobile" type="text" class="validate" required value="">
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
                                <input type="checkbox" name="prescProvidedModal" id="prescProvidedModal">
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
                                <input type="checkbox" name="decProvidedModal" id="decProvidedModal">
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
                                <input type="checkbox" name="priority" id="priority">
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

                <div class="row computer">
                    <button class="btn waves-effect waves-light createPetition loading" type="submit" name="action"
                        id="reg-user" style="background-color:#27c494;">
                        Cadastrar
                    </button>
                    <a href="#!" class="modal-close waves-effect waves-red btn-flat">Fechar</a>


                </div>
                <div class="row mobile">
                    <button class="btn waves-effect waves-light createProvidenceMobile loading" type="submit"
                        name="action" id="reg-petition">
                        Cadastrar
                    </button>
                    <a href="#!" class="modal-close waves-effect waves-red btn-flat">Fechar</a>

                </div>
            </form>

        </div>
    </div>

    <!-- Modal Structure -->
    <div id="modal3" class="modal" style="height:auto;
    padding: 20px;
    background-color: white;
    ">
        <div class="modal-content" style="padding:0 !important; border: 0;">
            <h4 style="text-align: center;">Atualizar status da petição</h4>

            <form class="col s12" method="POST" action="../services/Controller/UpdateStatusPetition.php">
                <input type="hidden" id="idProvidenceModal" name="idProvidence" value="" />
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
                                <input type="checkbox" name="provProvidedModal" id="provProvidedModal">
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
                    <button class="btn waves-effect waves-light loading"
                        style="background-color: #3e64ff; color: white;" type="submit" name="action" id="reg-user">
                        Alterar
                    </button>

                </div>
            </form>

        </div>
    </div>

    <?php
        include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/components/navbar.php";
        include_once '../services/Models/Pagination.php';
        include_once '../services/Controller/PetitionController.php';
        
        $pController = new PetitionController();
        $petitions = $pController->getPetitionInProgress();

      ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    Petições
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

                    </select>
                </div>
                <button type="submit" class="btn waves-effect waves-light"><i class="fa-solid fa-filter"></i>
                    &nbsp;&nbsp;BUSCAR</button>

            </form><br><br>

            <?php
            if(AuthController::getUser()['idRole']==1)
            {
            ?>
            <a class="btn modal-trigger" href="#modalcreateProvidence" title="Adicionar usuário" style="    background-color: #27c494 !important;
                border-radius: 5px !important;
                margin: 10px 0px;
                margin-left: 5px;
                color: white;"><i class="fa-solid fa-plus"></i></a>
            <?php
            }
            ?>

            <?php
            
            if(isset($_GET['search-type'])){
                $type = $_GET['search-type'];
                //echo $type;
                if($type==="ncli"){
                    $name = addslashes($_GET['search-ncli']);
                    
                    $petitions = $pController->getPetitionByClient($name,0,1,0);
                    
                }else if($type==="adverso"){
                  if(isset($_GET['search-adverso'])){
                    $adverse = addslashes($_GET['search-adverso']);
                    $petitions = $pController->getPetitionByAdverse($adverse,0,1);
                  }else{
                    $petitions = $pController->getPetitionInProgress();
                  }
                }else if($type=="dta-contract"){
                    $start= explode("/",addslashes($_GET['dta-contract']));
                    if(sizeof($start)>1){

                        $start = $start[2]."-".$start[1]."-".$start[0];
                    }else{
                        $start="";
                    }
                    if($start==""){
                        $petitions = $pController->getPetitionInProgress();
                    }else{
                        $petitions = $pController->getProvidenceByData($start,0,1);
                    }
                }else if($type=="search_period"){
                    $start= explode("/",addslashes($_GET['data_start']));
                    $end= explode("/",addslashes($_GET['data_end']));
                    if(sizeof($end)==1 || sizeof($start)==1)
                    {
                        $petitions = $pController->getPetitionInProgress();
                    }else{
                    if(sizeof($end)>1){

                        $end = $end[2]."-".$end[1]."-".$end[0];
                    }
                    if(sizeof($start)>1){

                        $start = $start[2]."-".$start[1]."-".$start[0];
                    }
                    $petitions = $pController->getProvidenceByPeriod($start, $end,0,1);
                    }
                    
                }else if($type=='type-action'){

                        $type_action=addslashes($_GET['type-action']);
                        // echo $type_action;
                        if($type_action!=""){
                            $petitions = $pController->getPetitionByActionType($type_action,0,1);
                            
                        }else{
                            $petitions = $pController->getPetitionInProgress();
                        }
                }else if($type=='responsible-user'){
                    $responsible_user = addslashes($_GET['responsible-user']);
                    // echo $responsible_user;
                    if($responsible_user!=""){
                        $petitions = $pController->getPetitionByResponsibleUser($responsible_user,0,1);
                        
                    }else{

                        $petitions = $pController->getPetitionInProgress();
                    }
                }
                    
                
                }
            
           
            
            if($petitions!=NULL){

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
                  ';
                  if(AuthController::getUser()['idRole']==1 || AuthController::getUser()['idRole']==2){
                  $table.=
                  '
                  <th>Operações</th>';}
                  $table.='
                </tr>
              </thead>

              <tbody>';
              $color;
              $st;
              $pagination="";
              $start=0;
              
              $end= sizeof($petitions)<10 ? sizeof($petitions) : 10;
              $paginationModel = new Pagination();
              if(isset($_GET['pagination_number'])){
                  
                  if(intval($_GET['pagination_number']) >=1){
                      
                      $page = $_GET['pagination_number'];
                      $diff=(sizeof($petitions) - (intval($page)*10));
              
                      if($diff==0){
                          $start=sizeof($petitions)-10;
                          $end=sizeof($petitions);
                      }else{
                          if($diff<0){
                              $start = (intval($page)-1)*10;
                              $end = sizeof($petitions);
                          }else{
                              $start = (intval($page)-1)*10;
                              $end = sizeof($petitions)-$diff;
                          }
                      }

                      $pagination = $paginationModel->createPagination($page,$petitions,'petition.php', $params);
                  }else{
                      $pagination = $paginationModel->createPagination("1",$petitions,'petition.php', $params);
                  }

              }else{
                  $pagination = $paginationModel->createPagination("1",$petitions,'petition.php', $params);
              }


              for($i=$start;$i<$end;$i++){
                
                  $p = $petitions[$i];
                  $origin = date_create(date('Y-m-d', strtotime($p[1])));
                  $target = date_create(date('Y-m-d'));
                  $interval = date_diff($origin, $target);
                  $aux=intval($interval->format('%a'));
                  $status="";
                  $color_style="";
                  $d="";
                  $l = $logController->getLogByPetition($p[0]);
                  if($aux==1)
                  {
                    $d = $interval->format('%a dia');
                  }else{
                    $d = $interval->format('%a dias');
                  }
                  if($p[8]=='1')
                  {
                    $color_style="color: purple;font-weight: bold;";
                  }else{
                    if($aux>15 && $aux<=30 && $p[4]==0){
                        $color_style="color: green;";
                        
                      }else if($aux>30 && $p[4]==0){
                        $color_style="color: red;";
                      }
                  }
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
                  $id = $i+1;                  
                  $button_action='';
                  $table.='<tr style="'.$color_style.'">
                              <td>'.$id.'</td>
                              <td>'.$p[2].'</td>
                              <td>'.$p[3].'</td>
                              <td>'.date("d-m-Y", strtotime($p[1])).'</td>
                              <td>'.$p[9].'</td>
                              <td>'.$p[11].'</td>
                              <td>'.$d.'</td>
                              <td>'.$pres.'</td>
                              <td>'.$deca.'</td>';
                              
                if(AuthController::getUser()['idRole']==1){
                  $button_action='
                  
                        
                          <a class="btn-floating btn-small green modal-trigger openModalStatus" 
                          data-prov="'.$p[0].'" 
                          data-provided="'.$p[4].'"
                          data-sf="'.$p[7].'"
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
                  ';
                  if(AuthController::getUser()['idRole']==1){
                  $table.=
                  '
                  <th>Operações</th>';}
                  $table.='
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
                if($petitions!=null && isMobile()){
            ?>

            <a href="<?php echo "../services/Controller/GeneratePDFController.php?id=0&".$params;?>">
                <button style="margin-left: 10px;background-color: #27c494; color:white;"
                    class="btn waves-effect waves-light" type="button" id="back">
                    Exportar PDF
                </button>
            </a>

            <?php
                }else if($petitions!=null && !isMobile())
                {
            ?>

            <a target="_blank" href="<?php echo "../services/Controller/GeneratePDFController.php?id=0&".$params;?>">
                <button style="margin-left: 10px;background-color: #27c494; color:white;"
                    class="btn waves-effect waves-light" type="button" id="back">
                    Exportar PDF
                </button>
            </a>

            <?php
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

    <script src="../js/petition_new.js"></script>

</body>

</html>