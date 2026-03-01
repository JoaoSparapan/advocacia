<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
include_once $_SERVER['DOCUMENT_ROOT'] . "/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/UserController.php';
include_once '../services/Controller/FrontdeskController.php';

$userController = new UserController();
$users = $userController->getAllForTable();
$frontdeskController = new FrontdeskController();
$fronts = $frontdeskController->getAllFrontdesk();


if (AuthController::getUser() == null) {
    header("location:./login.php");
    exit;
}


$params = str_replace("/advocacia/pages/frontdesk.php?", "", $_SERVER["REQUEST_URI"]);
$url = str_replace("/advocacia/pages/", "", $_SERVER["REQUEST_URI"]);


$index = strrpos($params, 'pagination_number=');

if ($index . "" == "0") {

    $params = str_replace(substr($params, $index, $index + 20), "", $params);
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
    <link rel="stylesheet" href="../styles/css/providences.css">
    <link rel="stylesheet" href="../styles/css/sidebar-hide.css">
    <link rel="stylesheet" href="../styles/css/navbar.css">
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="shortcut icon" href="../images/logotipo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/css/modelMaterialize.css" />
    <link rel="stylesheet" href="./styles/css/swal/sweetalert2.min.css">
    

    <title>Atendimentos</title>
</head>

<body>

    <div id="modal2" class="modal" style="padding: 20px; height: auto;">
        <center>
            <h4>Cadastro de Atendimento</h4>
        </center><br>

        <form class="col s12" method="POST" action="../services/Controller/CreateFrontdesk.php">

            <div class="row" style="margin-bottom: 25px;">

                <div class="card"
                    style="center-align; padding: 15px 25px; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">

                    <div class="row">
                        <div class="col s12">
                            <p>
                                <label>
                                    <input type="checkbox" id="select-all-docs" class="filled-in" />
                                    <span><b>Selecionar todos</b></span>
                                </label>
                            </p>
                        </div>
                    </div>

                    <div class="row" id="doc-options">
                        <div class="col s6">
                            <p><label><input type="checkbox" class="filled-in doc-option" id="doc1" value="1"
                                        name="documentos[]"><span> Atendimento Inicial</span></label></p>
                            <p><label><input type="checkbox" class="filled-in doc-option" value="2"
                                        name="documentos[]"><span> Procuração</span></label></p>
                            <p><label><input type="checkbox" class="filled-in doc-option" value="3"
                                        name="documentos[]"><span> Declaração de hipossuficiência</span></label></p>
                        </div>
                        <div class="col s6">
                            <p><label><input type="checkbox" class="filled-in doc-option" value="4"
                                        name="documentos[]"><span> Procuração INSS</span></label></p>
                            <p><label><input type="checkbox" class="filled-in doc-option" value="5"
                                        name="documentos[]"><span> Contrato de prestação de serviços
                                        advocatícios</span></label></p>
                            <p><label><input type="checkbox" class="filled-in doc-option" value="6"
                                        name="documentos[]"><span> Termo de revogação de procuração</span></label></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s6">
                    <input type="text" id="nome" name="nome" required>
                    <label for="nome">Nome</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" id="nacionalidade" name="nacionalidade" required>
                    <label for="nacionalidade">Nacionalidade</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" id="estadoCivil" name="estadoCivil" required>
                    <label for="estadoCivil">Estado Civil</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s6">
                    <input type="text" id="profissao" name="profissao" required>
                    <label for="profissao">Profissão</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" id="rg" name="rg" required>
                    <label for="rg">RG</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s6">
                    <input type="text" id="cpf" name="cpf" required>
                    <label for="cpf">CPF</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" id="endereco" name="endereco" required>
                    <label for="endereco">Endereço</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s4">
                    <input type="text" id="bairro" name="bairro" required>
                    <label for="bairro">Bairro</label>
                </div>
                <div class="input-field col s4">
                    <input type="text" id="cidade" name="cidade" required>
                    <label for="cidade">Cidade</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s6">
                    <input type="text" id="cep" name="cep" required>
                    <label for="cep">CEP</label>
                </div>
                <div class="input-field col s4">
                    <input type="text" id="estado" name="estado" required>
                    <label for="estado">Estado</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12 m6">
                    <select name="pastaHibrida" id="pastaHibrida" required>
                        <option value="" disabled selected>Pasta híbrida</option>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                    <label>Pasta híbrida</label>
                </div>
            </div>

            <div class="row" style="margin-top: -15px;">
                <div class="input-field col s12 m6">
                    <select id="situacao" name="situacao" required>
                        <option value="" disabled selected>Tipo do cliente</option>
                        <option value="maior">Maior de idade</option>
                        <option value="menor_pubere">Menor púbere</option>
                        <option value="menor_impubere">Menor impúbere</option>
                    </select>
                </div>
            </div>

            <div id="responsavel-fields" style="display:none;">
                <div class="row">
                    <div class="input-field col s6">
                        <input type="text" id="nomeDependente" name="nomeDependente">
                        <label for="nomeDependente">Nome do dependente</label>
                    </div>
                    <div class="input-field col s6">
                        <input type="text" id="nacionalidadeDependente" name="nacionalidadeDependente">
                        <label for="nacionalidadeDependente">Nacionalidade do dependente</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input type="text" id="rgDependente" name="rgDependente">
                        <label for="rgDependente">RG dependente</label>
                    </div>
                    <div class="input-field col s6">
                        <input type="text" id="cpfDependente" name="cpfDependente">
                        <label for="cpfDependente">CPF dependente</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" id="relacaoResponsavel" name="relacaoResponsavel">
                        <label for="relacaoResponsavel">Relação com o dependente</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12 m6">
                    <input type="date" id="dataReferencia" name="dataReferencia" value="<?= date('Y-m-d'); ?>"
                        class="validate" required>
                    <label class="active" for="dataReferencia">Data Referência</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12 m6">
                    <input type="text" id="indicacaoNome" name="indicacaoNome">
                    <label for="indicacaoNome">Nome Indicação</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="text" id="indicacao" name="indicacao">
                    <label for="indicacao">Contato Indicação</label>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const situacaoSelect = document.getElementById('situacao');
                    const responsavelFields = document.getElementById('responsavel-fields');
                    const dependenteInputs = responsavelFields.querySelectorAll('input');

                    situacaoSelect.addEventListener('change', function () {
                        const valor = this.value;

                        if (valor === 'menor_pubere' || valor === 'menor_impubere') {
                            responsavelFields.style.display = 'block';
                            dependenteInputs.forEach(input => input.setAttribute('required', 'required'));
                        } else {
                            responsavelFields.style.display = 'none';
                            dependenteInputs.forEach(input => input.removeAttribute('required'));
                        }
                    });
                });
            </script>


            <div id="extra-fields" style="display: none;">
                <div class="row">
                    <div class="input-field col s6">
                        <input type="email" id="email" name="email">
                        <label for="email">E-mail</label>
                    </div>
                    <div class="input-field col s6">
                        <input type="text" id="telefone1" name="telefone1">
                        <label for="telefone1">Telefone 01</label>
                    </div>
                    <div class="input-field col s6">
                        <input type="text" id="telefone2" name="telefone2">
                        <label for="telefone2">Telefone 02</label>
                    </div>

                </div>

                <div class="row">
                    <div class="input-field col s6">
                        <input type="text" id="parteadversa" name="parteadversa">
                        <label for="parteadversa">Parte Adversa</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12" style="padding-top: 10px;">
                        <textarea id="fatos" name="fatos" class="materialize-textarea"
                            style="min-height: 200px;"></textarea>
                        <label for="fatos">Fatos</label>
                    </div>
                </div>
            </div>

            <div class="row center-align createFrontdesk" style="margin-top: 25px;">
                <button class="btn waves-effect waves-light" type="submit" style="background-color:#27c494;">
                    Cadastrar
                </button>
            </div>
        </form>
    </div>


    <div class="wrapper d-flex align-items-stretch">
        <?php
        include_once $_SERVER['DOCUMENT_ROOT'] . "/advocacia/components/navbar.php";

        include_once '../services/Models/Pagination.php';

        $user = AuthController::getUser();

        ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5" style="background-color: #fff;">

            <nav class="navbar navbar-expand-lg navbar-light bg-light"
                style="background-color: transparent !important;">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    </button>
                    Atendimentos
                </div>
            </nav>
            <form method="GET" action="#" class="filter">
                <div>

                    <div id="resp-sel" class="invisible">
                        <select name="search-resp" value="">
                            <option value="-1" selected disabled>Selecionar</option>
                            <?php
                            for ($i = 0; $i < sizeof($users); $i++) {
                                echo "<option value='" . $users[$i]['idUser'] . "'>" . $users[$i]['name'] . "</option>";
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
                    <input type="text" placeholder="Parte adversa" name="adversa" value="" id="adversa"
                        class="invisible" />
                    <div id="hibrida" class="invisible">
                        <select name="hibrida" id="hibrida">
                            <option value="" disabled selected>Pasta híbrida</option>
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                    </div>
                    <input type="text" placeholder="Nome Indicação" name="indicacaoF" value="" id="indicacaoF"
                        class="invisible" />

                    <div id="search_period" class="invisible">
                        <div>
                            <input type="text" placeholder="Data Inicial" name="data_start" value="" id="date-start" />
                            <span>&nbsp;&nbsp;&nbsp;Até&nbsp;&nbsp;&nbsp;</span>
                            <input type="text" placeholder="Data Final" name="data_end" value="" id="date-end" />

                        </div>
                    </div>

                    <input type="text" placeholder="Nome do Cliente" name="search-ncli" value="" id="ncli"
                        class="visible" />
                </div>
                <div>
                    <select name="search-type" id="sel-type" value="" style="padding: 0;" class="browser-default">
                        <option value="ncli">Nome do cliente</option>
                        <option value="end">CPF do cliente</option>
                        <option value="new">Email do cliente</option>
                        <option value="start">Celular do cliente</option>
                        <option value="adversa">Parte adversa</option>
                        <option value="hibrida">Pasta híbrida</option>
                        <option value="indicacaoF">Nome Indicação</option>
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
            if (isset($_GET['search-type'])) {
                $type = $_GET['search-type'];
                if ($type === "ncli") {
                    if ($_GET['search-ncli'] == '') {
                        $fronts = $frontdeskController->getAllFrontdesk();
                    } else {
                        $name = addslashes($_GET['search-ncli']);
                        $fronts = $frontdeskController->getByName($name);
                    }
                } else if ($type === "resp-sel") {
                    if (isset($_GET['search-resp'])) {
                        $resp = addslashes($_GET['search-resp']);
                        $fronts = $frontdeskController->getByResponsavel($resp);
                    } else {
                        $fronts = $frontdeskController->getAllFrontdesk();
                    }
                } else if ($type === "start") {
                    if ($_GET['search-phone'] != "") {
                        $phone = addslashes($_GET['search-phone']);
                        $fronts = $frontdeskController->getByPhone($phone);
                    } else {
                        $fronts = $frontdeskController->getAllFrontdesk();
                    }
                } else if ($type == "end") {
                    if ($_GET['search-cpf'] != "") {
                        $cpf = addslashes($_GET['search-cpf']);
                        $fronts = $frontdeskController->getByCpf($cpf);
                    } else {
                        $fronts = $frontdeskController->getAllFrontdesk();
                    }
                } else if ($type == "new") {
                    if ($_GET['search-email'] != "") {
                        $email = addslashes($_GET['search-email']);
                        $fronts = $frontdeskController->getByEmail($email);
                    } else {
                        $fronts = $frontdeskController->getAllFrontdesk();
                    }
                } else if ($type == "adversa") {
                    if ($_GET['adversa'] != "") {
                        $parteAdversa = addslashes($_GET['adversa']);
                        $fronts = $frontdeskController->getByParteAdversa($parteAdversa);
                    } else {
                        $fronts = $frontdeskController->getAllFrontdesk();
                    }
                } else if ($type == "indicacaoF") {
                    if ($_GET['indicacaoF'] != "") {
                        $indicacao = addslashes($_GET['indicacaoF']);
                        $fronts = $frontdeskController->getByIndicacaoNome($indicacao);
                    } else {
                        $fronts = $frontdeskController->getAllFrontdesk();
                    }
                } else if ($type == "hibrida") {
                    if (isset($_GET['hibrida']) && $_GET['hibrida'] !== "") {
                        $hibrida = addslashes($_GET['hibrida']);
                        $fronts = $frontdeskController->getByPastaHibrida($hibrida);
                    } else {
                        $fronts = $frontdeskController->getAllFrontdesk();
                    }
                }
            }

            if ($fronts != null) {

                $table = '
                <table class="centered responsive-table highlight">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Celular 1</th>
                        <th>Parte Adversa</th>
                        <th>Pasta Híbrida</th>
                        <th>Nome Indicação</th>
                        <th>Cadastrado por</th>
                        <th>Atualização</th>
                        <th>Arquivos gerados</th>
                        <th>Operações</th>
                    </tr>
                </thead>

                <tbody>';
                $color;
                $st;
                $pagination = "";
                $start = 0;

                $end = sizeof($fronts) < 10 ? sizeof($fronts) : 10;
                $paginationModel = new Pagination();
                if (isset($_GET['pagination_number'])) {

                    if (intval($_GET['pagination_number']) >= 1) {

                        $page = $_GET['pagination_number'];
                        $diff = (sizeof($fronts) - (intval($page) * 10));

                        if ($diff == 0) {
                            $start = sizeof($fronts) - 10;
                            $end = sizeof($fronts);
                        } else {
                            if ($diff < 0) {
                                $start = (intval($page) - 1) * 10;
                                $end = sizeof($fronts);
                            } else {
                                $start = (intval($page) - 1) * 10;
                                $end = sizeof($fronts) - $diff;
                            }
                        }
                        $pagination = $paginationModel->createPagination($page, $fronts, 'frontdesk.php', $params);
                    } else {
                        $pagination = $paginationModel->createPagination("1", $fronts, 'frontdesk.php', $params);
                    }

                } else {
                    $pagination = $paginationModel->createPagination("1", $fronts, 'frontdesk.php', $params);
                }

                for ($i = $start; $i < $end; $i++) {
                    $u = $fronts[$i];
                    $phoneCli1 = $u["telefone1"];
                    $phoneCli2 = $u["telefone2"];
                    $email = $u["email"];
                    $parteAdversa = $u['parteadversa'];
                    $documentShortNames = [
                        1 => "Atendimento Inicial",
                        2 => "Procuração",
                        3 => "Declaração",
                        4 => "Procuração INSS",
                        5 => "Contrato",
                        6 => "Revogação"
                    ];
                    $docsIds = explode(',', $u["documentos"]);
                    $docsIdsInt = array_map('intval', $docsIds);

                    if (count(array_intersect(range(1, 6), $docsIdsInt)) === 6) {
                        $docsDisplayString = "Todos";
                    } else {
                        $docsDisplay = [];
                        foreach ($docsIdsInt as $id) {
                            if (isset($documentShortNames[$id])) {
                                $docsDisplay[] = $documentShortNames[$id];
                            }
                        }
                        $docsDisplayString = implode(', ', $docsDisplay);
                    }
                    if ($phoneCli1 == NULL) {
                        $phoneCli1 = 'Sem registro';
                    }
                    if ($phoneCli2 == NULL) {
                        $phoneCli2 = 'Sem registro';
                    }
                    if ($email == NULL) {
                        $email = 'Sem registro';
                    }
                    if ($parteAdversa == NULL) {
                        $parteAdversa = 'Não informado';
                    }
                    $email = $u["email"];
                    if ($email == NULL) {
                        $email = 'Não informado';
                    }
                    $indicacao = $u["indicacaoNome"];
                    if ($indicacao == NULL) {
                        $indicacao = 'Não';
                    }
                    $button_action = '';
                    $table .= '<tr>
                                <td>' . $u["nome"] . '</td>
                                <td>' . $u["cpf"] . '</td>
                                <td>' . $email . '</td>
                                <td>' . $phoneCli1 . '</td>
                                <td>' . $parteAdversa . '</td>
                                <td>' . $u["pastaHibrida"] . '</td>
                                <td>' . $indicacao . '</td>
                                <td>' . $u["usuario"] . '</td>
                                <td>' . date('d-m-Y H:i:s', strtotime($u["data_cadastro"])) . '</td>
                                <td>' . $docsDisplayString . '</td>';

                    $button_action = '
                        <a class="btn-floating btn-small green" title="Baixar documentos" href="../services/Controller/DownloadFrontdesk.php?id='.$u['idFrontdesk'].'">
                            <i class="fa-solid fa-download"></i>
                        </a>

                        <a class="btn-floating btn-small blue" title="Editar atendimento" href="./updateFrontDesk.php?id='.$u['idFrontdesk'].'">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    ';

                    if($_SESSION['user']['idRole'] == 1){
                        $button_action .= '
                            <a class="btn-floating btn-small red deleteUserBtn" title="Excluir atendimento" href="../services/Controller/DeleteFront.php?id='.$u['idFrontdesk'].'">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        ';
                    }

                    $table .= '<td style="
                        display: flex;
                        align-items: center; 
                        gap: 5px; 
                        justify-content: center;
                    ">' . $button_action . '</td>

                            </tr>';

                }

                $table .= '
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
            } else {
                $table = '
                <table class="centered responsive-table highlight">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>CPF</th>
                        <th>Celular 1</th>
                        <th>Parte Adversa</th>
                        <th>Pasta Híbrida</th>
                        <th>Nome Indicação</th>
                        <th>Cadastrado por</th>
                        <th>Atualização</th>
                        <th>Arquivos gerados</th>
                        <th>Operações</th>
                    </tr>
                </thead>
                </table>
                ';
                echo $table;
            }


            ?>


            <?php
            function isMobile()
            {
                return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
            }
            if ($fronts != null && isMobile()) {
                ?>

                <a href="<?php echo "../services/Controller/GeneratePDFControllerAI.php?id=0&" . $params; ?>">
                    <button style="margin-left: 10px;background-color: #27c494; color:white;"
                        class="btn waves-effect waves-light" type="button" id="back">
                        Exportar PDF
                    </button>
                </a>

                <?php
            } else if ($fronts != null && !isMobile()) {
                ?>

                    <a target="_blank" href="<?php echo "../services/Controller/GeneratePDFControllerAI.php?id=0&" . $params; ?>">
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
    <script src="../js/frontdesk.js"></script>
</body>

</html>