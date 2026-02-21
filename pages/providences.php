<?php
session_start(); 
date_default_timezone_set('America/Sao_Paulo');
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/ProcessController.php';
include_once '../services/Controller/VaraController.php';
include_once '../services/Controller/SubmissionController.php';
include_once '../services/Controller/UserController.php';
if(AuthController::getUser()==null){
    header("location:./login.php");
}

$params = str_replace("/advocacia/pages/providences.php?", "", $_SERVER["REQUEST_URI"]);
$url = str_replace("/advocacia/pages/", "", $_SERVER["REQUEST_URI"]);


$index = strrpos($params,'pagination_number=');

$p = new ProcessController();
$proc = $p->getAllForTable();
if($index.""=="0"){
    
    $params = str_replace(substr($params,$index, $index+20), "", $params);
}

$userController = new UserController();
$u = $userController->getAdminAndColaboradorAll();
//exit;
/* echo $params;
exit; */
$c = new VaraController();
$court = $c->getAll();
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
    <link rel="shortcut icon" href="../logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />

    <title>Providências</title>
</head>

<body>
    <?php 
    /* echo "-----> ";
        echo $params; */
    ?>
    <!-- Modal Structure proccess-->
    <div id="modalproc" class="modal" style="padding:20px;height:auto;">
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
                        <select name="court" id="select-vara" required>

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
                        <select name="adv" id="select-adv">
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
                    <button class="btn waves-effect waves-light createProcInProvidence" type="button" name="action"
                        id="reg-user">
                        Cadastrar
                    </button>

                </div>
            </form>

        </div>
    </div>
    <!-- Modal Structure -->
    <div id="modalcreateProvidence" class="modal" style="height:auto;
    padding: 20px;
    background-color: white;
    
    ">
        <div class="modal-content" style="padding:0 !important; border: 0;">
            <h4 style="text-align: center;">Cadastro de Providência</h4>

            <form class="col s12" id='myform' style="" method="POST" action="../services/Controller/CreateProvidence.php"
                style="padding: 0 !important;">


                <div class="row computer">
                    <div class="input-field col s3">

                        <i class="fa-solid fa-calendar-days prefix"></i>
                        <input name="data-intim" id="data-intim" type="date" min="2000-01-01T00:00"
                            max="9999-12-31T00:00" required value="">
                        <label for="data-intim" style="top: -5px !important;">Data de
                            disponibilização</label>
                        <span class="helper-text" data-error="Formato de Data incorreto" data-success="Correto!"></span>

                    </div>
                    <div class="input-field col s3">

                        <i class="fa-solid fa-calendar-days prefix"></i>
                        <input name="data-publi" id="data-publi" type="date" min="2000-01-01T00:00"
                            max="9999-12-31T00:00" required value="">
                        <label for="data-publi" style="top: -5px !important;">Data da publicação</label>
                        <span class="helper-text" data-error="Formato de Data incorreto" data-success="Correto!"></span>

                    </div>
                </div>
                <div class="row mobile">
                    <div class="input-field">

                        <i class="fa-solid fa-calendar-days prefix"></i>
                        <input name="data-intim" id="data-intim_mobile" type="date" min="2000-01-01T00:00"
                            max="9999-12-31T00:00" required value="" style="min-width: 80% !important;">
                        <label for="data-intim_mobile" style="top: -5px !important;">Data de disponibilização</label>
                        <span class="helper-text" data-error="Formato de Data incorreto" data-success="Correto!"></span>

                    </div>
                </div>
                <div class="row mobile">
                    <div class="input-field">

                        <i class="fa-solid fa-calendar-days prefix"></i>
                        <input name="data-publi" id="data-publi_mobile" type="date" min="2000-01-01T00:00"
                            max="9999-12-31T00:00" required value="" style="min-width: 80% !important;">
                        <label for="data-publi_mobile" style="top: -5px !important;">Data da publicação</label>
                        <span class="helper-text" data-error="Formato de Data incorreto" data-success="Correto!"></span>

                    </div>
                </div>

                <div class="row computer">
                    <div class="input-field col s3" style="display: flex;">
                        <?php
                        $numProcList="";
                        for($i=0;$i<sizeof($proc);$i++)
                        {
                           $numProcList.=$proc[$i]['numProcess'];
                           if($i< sizeof($proc)-1 || sizeof($proc)==1){
                            $numProcList.=",";
                           }
                        }
                        
                        ?>
                        <i class="fa fa-id-card prefix"></i>
                        <input data-listnproc="<?= $numProcList;?>" name="process" id="search_numProcess_inp"
                            type="text" class="autocomplete validate" required>
                        <label for="qte-dias">Num. Processo</label>
                        <a class="modal-trigger" href="#modalproc">
                            <div id="addprov" class="invisible"
                                style="margin: 1rem 0; display: flex; justify-content:center; align-items: center;">

                                <i class="fa-solid fa-plus new-providence-proc"></i>


                            </div>
                        </a>

                    </div>
                    <div class="input-field col s3">

                        <i class="fa-solid fa-tags prefix"></i>
                        <input name="vara" id="vara" disabled type="text" class="validate" required value="">
                        <label for="vara">Vara</label>
                        <span class="helper-text" data-error="Vara incorreta!" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row computer">
                    <div class="input-field col s3">

                        <i class="fa-solid fa-person prefix"></i>
                        <input name="cliente" id="cliente" disabled type="text" class="validate" required value="">
                        <label for="cliente">Cliente</label>
                        <span class="helper-text" data-error="Cliente incorreto!" data-success="Correto!"></span>
                    </div>
                    <div class="input-field col s3">

                        <i class="fa fa-id-card prefix"></i>
                        <input name="adv" id="adv" disabled type="text" class="validate" required value="">
                        <label for="adv">Advogado Responsável</label>
                        <span class="helper-text" data-error="Advogado incorreto!" data-success="Correto!"></span>
                    </div>
                </div>

                <div class="row mobile">
                    <div class="input-field" style="display: flex;">
                        <?php
                        $numProcList="";
                        for($i=0;$i<sizeof($proc);$i++)
                        {
                           $numProcList.=$proc[$i]['numProcess'];
                           if($i< sizeof($proc)-1 || sizeof($proc)==1){
                            $numProcList.=",";
                           }
                        }
                        
                        ?>
                        <i class="fa fa-id-card prefix"></i>
                        <input data-listnproc="<?= $numProcList;?>" name="process" id="search_numProcess_inp_mobile"
                            type="text" class="autocomplete validate" required>
                        <label for="qte-dias">Num. Processo</label>
                        <a class="modal-trigger" href="#modalproc">
                            <div id="addprov_mobile" class="invisible"
                                style="margin: 1rem 0; display: flex; justify-content:center; align-items: center;">

                                <i class="fa-solid fa-plus new-providence-proc"></i>


                            </div>
                        </a>




                    </div>
                </div>
                <div class="row mobile">
                    <div class="input-field col s6">

                        <i class="fa-solid fa-tags prefix"></i>
                        <input name="vara" id="vara_mobile" disabled type="text" class="validate" required value="">
                        <label for="vara_mobile">Vara</label>
                        <span class="helper-text" data-error="Vara incorreta!" data-success="Correto!"></span>
                    </div>
                    <div class="input-field col s6">

                        <i class="fa-solid fa-person prefix"></i>
                        <input name="cliente" id="cliente_mobile" disabled type="text" class="validate" required
                            value="">
                        <label for="cliente_mobile">Cliente</label>
                        <span class="helper-text" data-error="Cliente incorreto!" data-success="Correto!"></span>
                    </div>
                </div>
                <div class="row mobile">
                    <div class="input-field">
                    <i class="fa fa-id-card prefix"></i>
                    <input name="adv_mobile" id="adv_mobile" disabled type="text" class="validate" required value="">
                    <label for="adv_mobile">Advogado Responsável</label>
                    <span class="helper-text" data-error="Advogado incorreto!" data-success="Correto!"></span>
                    </div>
                </div>

                <div class="row computer">
                    <div class="input-field col s3">
                        <i class="fa-regular fa-file-lines prefix"></i>
                        <input name="providencia" id="providencia" type="text" class="validate" required value="">
                        <label for="providencia">Providência</label>
                        <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>


                    </div>
                    <div class="input-field col s3">

                        <i class="fa-solid fa-list-ol prefix"></i>
                        <input name="qte-dias" min="1" id="qte-dias" type="number" class="validate" required value="5">
                        <label for="qte-dias">Quantidade de dias</label>
                        <span class="helper-text" data-error="Insira o número de dias" data-success="Correto!"></span>
                    </div>
                    <div class="input-field col s3">
                        
                        <i class="fa-solid fa-list prefix"></i>
                        <input name="sel-days-value" id="sel-days-value" class="invisible" type="number" class="validate" required
                            value="">
                        <select name="sel-days" id="sel-days" required>
                            <option value="-1">Contagem de dias</option>
                            <option value="0">Úteis</option>
                            <option value="1">Corridos</option>
                        </select>
                    </div>
                </div>
                <div class="row mobile">
                    <div class="input-field">
                        <i class="fa-regular fa-file-lines prefix"></i>
                        <input name="providencia" id="providencia_mobile" type="text" class="validate" required
                            value="">
                        <label for="providencia_mobile">Providência</label>
                        <span class="helper-text" data-error="Campo obrigatório" data-success="Correto!"></span>


                    </div>
                </div>
                <div class="row mobile">
                    <div class="input-field col s6">

                        <i class="fa-solid fa-list-ol prefix"></i>
                        <input name="qte-dias" min="1" id="qte-dias_mobile" type="number" class="validate" required
                            value="1">
                        <label for="qte-dias_mobile">Quantidade de dias</label>
                        <span class="helper-text" data-error="Insira o número de dias" data-success="Correto!"></span>
                    </div>
                    <div class="input-field col s6">

                        <i class="fa-solid fa-list prefix"></i>

                        <select name="sel-days" id="sel-days_mobile" required>
                            <option value="-1">Contagem de dias</option>
                            <option value="0">Úteis</option>
                            <option value="1">Corridos</option>
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
                                <input type="checkbox" name="national-holiday" id="national-holiday">
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
                                <input type="checkbox" name="state-holiday" id="state-holiday">
                                <span class="lever"></span>
                                Sim
                            </label>
                        </div>

                    </div>

                </div>

                <div class="row">
                    <div class="input-field col s3">
                        <i class="fa-solid fa-calendar-days prefix"></i>
                        <input disabled name="prazo" id="prazo" type="datetime" class="validate" required value="">
                        <label for="prazo" style="top: -25px !important;">Prazo estimado</label>
                        <span class="helper-text" data-error="prazo estimado incorreto" data-success="Correto!"></span>
                    </div>
                </div>



                <div class="row computer">
                    <button class="btn waves-effect waves-light createProvidence" type="submit" name="action"
                        id="reg-user">
                        Cadastrar
                    </button>
                    <a href="#!" class="modal-close waves-effect waves-red btn-flat">Fechar</a>


                </div>
                <div class="row mobile">
                    <button class="btn waves-effect waves-light createProvidenceMobile" type="submit"
                        name="action" id="reg-user">
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
            <h4 style="text-align: center;">Atualizar status da providência</h4>

            <form class="col s12" method="POST" action="../services/Controller/UpdateStatusProvidence.php">
                <input type="hidden" id="idProvidenceModal" name="idProvidence" value="" />
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
                                <input type="checkbox" name="provProvidedModal" id="provProvidedModal">
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
                    <button class="btn waves-effect waves-light loading"
                        style="background-color: #3e64ff; color: white;" type="submit" name="action" id="reg-user">
                        Alterar
                    </button>

                </div>
            </form>

        </div>
    </div>

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
        $providences = $provController->getProvidenceInProgress();
        $processController = new ProcessController();  
        $submissionController = new SubmissionController();
        $lastSub = $submissionController->getLastSub();
      ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="margin: 1rem 0px;">
                <div class="container-fluid">
                    Providências
                </div>

                <br>
            </nav>
            
            <form method="POST" action="../services/Controller/CreateSubmission.php"
                style="display: flex; justify-content: end; margin-bottom: 1rem;">
                
                <div class="row " style="width: 250px; align-items: center; padding-right:40px;">
                <?php
                    if(AuthController::getUser()['idRole']==1)
                    {
                    ?>
                    <div class="input-field col s8" style="padding: 0; margin: 0;">
                    
                        <input placeholder="Placeholder" id="datesubmission" name="datesubmission" type="date"
                            class="validate" value="<?=$lastSub['updateDate']?>"
                            style="background-color: transparent !important;height: 30px;margin:0;">
                        <label for="datesubmission" style="margin: 0;">Data</label>
                    </div>                
                    <div class="input-field col s4" style="padding: 0; margin: 0;">
                        <button type='submit' class='btn' style="    background-color: #27c494 !important;
                border-radius: 5px !important;
               
                margin-left: 5px;
                color: white;">Salvar</button>
                    </div>
                    <?php
                    }else{
                    ?>
                    <div class="input-field col s8" style="padding: 0; margin: 0;">
                    
                    <input disabled placeholder="Placeholder" id="first_name" name="datesubmission" type="date"
                        class="validate" value="<?=$lastSub['updateDate']?>"
                        style="background-color: transparent !important;height: 30px;margin-right: 1rem; margin:0;">
                    <label for="first_name" style="margin: 0;">Data</label>
                </div>
                    <?php
                    }
                    ?>
                </div>
            </form>

            <form method=" GET" action="#" class="filter">
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

                    <input type="text" placeholder="Nome do Cliente" name="search-ncli" value="" id="ncli"
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

            </form>

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
                    $providences = $provController->getProvidenceByClientName($name);

                }else if($type==="vara-sel"){
                  if(isset($_GET['search-vara'])){
                    $vara = addslashes($_GET['search-vara']);
                    $providences = $provController->getProvidenceByCourt($vara);
                  }else{
                    $providences = $provController->getProvidenceInProgress();
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
                    

                   
                    
                    $providences = $provController->getProvidenceByDataTerm($start." 00:00:00", $end." 23:59:59");
                }else if($type=='responsible-user'){
                    $responsible_user = addslashes($_GET['responsible-user']);
                    if($responsible_user!=""){
                        $providences = $provController->getProvidenceByResponsibleUser($responsible_user);
                        
                    }else{

                        $providences = $provController->getProvidenceInProgress();
                    }
                }
                else{
                    $nproc=addslashes($_GET['search-nproc']);
                    if($nproc==""){
                        $providences = $provController->getProvidenceInProgress();
                        
                    }else{

                        $providences = $provController->getProvidenceByNumProcess($nproc);
                    }
                    
                }
            }
            
           
            
            if($providences!=NULL){

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
                  ';
                  if(AuthController::getUser()['idRole']==1){
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

                      $pagination = $paginationModel->createPagination($page,$providences,'providences.php', $params);
                  }else{
                      $pagination = $paginationModel->createPagination("1",$providences,'providences.php', $params);
                  }

              }else{
                  $pagination = $paginationModel->createPagination("1",$providences,'providences.php', $params);
              }


              for($i=$start;$i<$end;$i++){
                  $p = $providences[$i];
                  $status="";
                  if($p[11]==0)
                  {
                    $status="Pendente";
                  }
                  
                  $dta_end_providence_time = strtotime($p[8]);
                  $current_date_time = strtotime(date('Y-m-d H:i:s'));
                  $style_status="";
                  $diff_hour = ($dta_end_providence_time-$current_date_time)/60/60;
                  $hour="0";
                  $minute="0";
                  $explode_hour_diff = explode('.',$diff_hour);
                  
                  if(sizeof($explode_hour_diff)>1){
                    $hour= $explode_hour_diff[0];
                    
                    $minute = round('0.'.$explode_hour_diff[1],2)*60;
                    $minute=round($minute);
                    
                  }else if(sizeof($explode_hour_diff)==1){
                    $hour= $explode_hour_diff[0];
                  }

                  $diff = $hour."h ".$minute."m";
                  $title="";
                  
                  
                  $color_style="";
                  $aux= date('Y-m-d',strtotime($p[8]));
                  if($p[11]==0 && strtotime($aux)==strtotime(date('Y-m-d')))
                  {
                    $color_style="green";
                    $status="Vence hoje";
                    $style_status='<i class="fa-solid fa-triangle-exclamation" style="font-size:18px;"></i>';
                  }
                  if($diff_hour<=2 && strtotime($aux)==strtotime(date('Y-m-d')) && $p[11]==0){
                    if($diff_hour>0){
                        $title = "Falta(m) $diff para a providência alcançar o prazo";
                      }else{
                        $title = "Essa providência deveria ter sido entregue às 18h de hoje";
                      }
                    $color_style="orange";
                    $status="Urgente";
                    
                  }else if(strtotime($aux)<strtotime(date('Y-m-d')) && $p[11]==0){
                    $color_style="red";
                    $status="Atrasado";
                    $title="";
                  }
                  
                  
                  if($diff_hour<=2 && $p[11]==0){
                    
                    $style_status='<i class="fa-solid fa-triangle-exclamation" style="font-size:18px;"></i>';
                    
                  }
                  
                  $id = $i+1;  
                  $proc = $processController->getById($p[1]);
                  $user = $userController->getById($p[17]);
                  $vara = $c->getById($proc['idCourt']);
                  $button_action='';
                  $table.='<tr style=" color:'.$color_style.';">
                              <td>'.$id.'</td>
                              <td>'.date("d-m-Y", strtotime($p[6])).'</td>
                              <td>'.date("d-m-Y", strtotime($p[7])).'</td>
                              <td>'.date("d-m-Y", strtotime($p[8])).'</td>
                              <td>'.$proc["numProcess"].'</td>
                              <td>'.$vara["sigla"].'</td>
                              <td>'.$user["name"].'</td>
                              <td>'.$proc["clientName"].'</td>
                              <td>'.$p[9].'</td>
                              <td title="'.$title.'" style="color: '.$color_style.'">'.$style_status." ".$status.'</td>';
                              
                if(AuthController::getUser()['idRole']==1){
                  $button_action='
                  
                        
                          <a class="btn-floating btn-small green modal-trigger openModalStatus" 
                          data-prov="'.$p[0].'" 
                          data-done="'.$p[10].'"
                          data-provided="'.$p[11].'"
                          title="Alterar status" href="#modal3"><i class="fa fa-th-list"></i></a>
                          <a class="btn-floating btn-small blue" title="Editar providência" href="./updateProvidence.php?id='.$p[0].'"><i class="fa-solid fa-pencil"></i></a>
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
                if($providences!=null && isMobile()){
            ?>

            <a href="<?php echo "../services/Controller/GeneratePDFControllerProv.php?id=0&".$params;?>">
                <button style="margin-left: 10px;background-color: #27c494; color:white;"
                    class="btn waves-effect waves-light" type="button" id="back">
                    Exportar PDF
                </button>
            </a>

            <?php
                }else if($providences!=null && !isMobile())
                {
            ?>

            <a target="_blank" href="<?php echo "../services/Controller/GeneratePDFControllerProv.php?id=0&".$params;?>">
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

    <script src="../js/process.js"></script>
    <script src="../js/providences.js"></script>

</body>

</html>