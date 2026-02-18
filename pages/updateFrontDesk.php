<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";
include_once '../services/Controller/UserController.php';
include_once '../services/Controller/ClientController.php';
include_once '../services/Controller/FrontdeskController.php';

$userController = new UserController();
$users = $userController->getAllForTable();
$clientController = new ClientController();
$frontdeskController = new FrontdeskController();
$clis = $clientController->getAllForTable();

if(AuthController::getUser()==null){
    header("location:./login.php");
    exit;
}

$frontdeskSelected=NULL;
if(isset($_GET['id'])){
    if(AuthController::getUser()['idRole']==1 ||AuthController::getUser()['idRole']==2){
        $frontdeskSelected = $frontdeskController->getById($_GET['id']);
    }else{
        header("location:./frontdesk.php");
        exit;
    }
}else{
    header("location:./frontdesk.php");
    exit;
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
    <link rel="stylesheet" href="../styles/css/navbar.css"/>
    <link rel="stylesheet" href="../styles/css/sidebar-hide.css"/>
    <link rel="stylesheet" href="../styles/css/providences.css"/>
    <link rel="stylesheet" href="../styles/css/selectStyle.css" />
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../styles/fontawesome-free-6.1.1-web/css/all.css" />
    <link rel="shortcut icon" href="../logotipo.ico" type="image/x-icon">
    <title>Editar atendimento</title>
</head>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);

    const situacaoSelect = document.getElementById('situacao');
    const nomeDep = document.getElementById('nomeDependente').closest('.row').parentNode;
    const dependenteRows = nomeDep.querySelectorAll('.row');

    function toggleResponsavelFields() {
        const valor = situacaoSelect.value;
        if (valor === 'menor_pubere' || valor === 'menor_impubere') {
            dependenteRows.forEach(row => row.style.display = 'flex');
            dependenteRows.forEach(row => {
                row.querySelectorAll('input').forEach(input => input.required = true);
            });
        } else {
            dependenteRows.forEach(row => row.style.display = 'none');
            dependenteRows.forEach(row => {
                row.querySelectorAll('input').forEach(input => input.required = false);
            });
        }
    }

    situacaoSelect.addEventListener('change', toggleResponsavelFields);
    toggleResponsavelFields();
});
</script>

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
                    Alterar informações do atendimento
                </div>
            </nav>
            <div class="content-info-user">
                <form class="col s12" method="POST" action="../services/Controller/UpdateFrontdesk.php">
                    <input name="id" type="hidden" value="<?= $frontdeskSelected['idFrontdesk'] ?>">

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <i class="fa-solid fa-calendar-check prefix"></i>
                            <input type="date" id="dataReferencia" name="dataReferencia"
                                value="<?= $frontdeskSelected['data_referencia'] ?>" 
                                class="validate" required>
                            <label class="active" for="dataReferencia">Data Referência</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa-user prefix"></i>
                            <input name="nome" id="nome" type="text" class="validate" required value="<?= $frontdeskSelected['nome'] ?>">
                            <label for="nome">Nome</label>
                        </div>
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa-globe prefix"></i>
                            <input name="nacionalidade" id="nacionalidade" type="text" class="validate" required value="<?= $frontdeskSelected['nacionalidade'] ?>">
                            <label for="nacionalidade">Nacionalidade</label>
                        </div>
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa fa-user-friends prefix"></i>
                            <input name="estadoCivil" id="estadoCivil" type="text" class="validate" required value="<?= $frontdeskSelected['estadoCivil'] ?>">
                            <label for="estadoCivil">Estado Civil</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa-briefcase prefix"></i>
                            <input name="profissao" id="profissao" type="text" class="validate" required value="<?= $frontdeskSelected['profissao'] ?>">
                            <label for="profissao">Profissão</label>
                        </div>
                        <div class="input-field col l6 m6 s12">
                            <i class="fa-solid fa-id-card prefix"></i>
                            <input name="rg" id="rg" type="text" class="validate" required value="<?= $frontdeskSelected['rg'] ?>">
                            <label for="rg">RG</label>
                        </div>
                        <div class="input-field col l6 m6 s12">
                            <i class="fa-solid fa-id-card prefix"></i>
                            <input name="cpf" id="cpf" type="text" class="validate" required value="<?= $frontdeskSelected['cpf'] ?>">
                            <label for="cpf">CPF</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa-location-dot prefix"></i>
                            <input name="endereco" id="endereco" type="text" required value="<?= $frontdeskSelected['endereco'] ?>">
                            <label for="endereco">Endereço</label>
                        </div>
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa-map-pin prefix"></i>
                            <input name="bairro" id="bairro" type="text" required value="<?= $frontdeskSelected['bairro'] ?>">
                            <label for="bairro">Bairro</label>
                        </div>
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa-city prefix"></i>
                            <input name="cidade" id="cidade" type="text" required value="<?= $frontdeskSelected['cidade'] ?>">
                            <label for="cidade">Cidade</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col l6 m6 s12">
                            <i class="fa-solid fa-flag prefix"></i>
                            <input name="estado" id="estado" type="text" required value="<?= $frontdeskSelected['estado'] ?>">
                            <label for="estado">Estado</label>
                        </div>
                        <div class="input-field col l6 m6 s12">
                            <i class="fa-solid fa-mail-bulk prefix"></i>
                            <input name="cep" id="cep" type="text" required value="<?= $frontdeskSelected['cep'] ?>">
                            <label for="cep">CEP</label>
                        </div>
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa-gavel prefix"></i>
                            <input name="parteadversa" id="parteadversa" type="text" value="<?= $frontdeskSelected['parteadversa'] ?>">
                            <label for="parteadversa">Parte Adversa (Opcional)</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <i class="fa-solid fa-folder-open prefix"></i>
                            <select name="pastaHibrida" id="pastaHibrida" required>
                                <option value="Sim" <?= ($frontdeskSelected['pastaHibrida'] ?? '') == 'Sim' ? 'selected' : '' ?>>Sim</option>
                                <option value="Não" <?= ($frontdeskSelected['pastaHibrida'] ?? '') == 'Não' ? 'selected' : '' ?>>Não</option>
                            </select>
                            <label for="pastaHibrida" class="active">Pasta híbrida</label>
                        </div>

                        <div class="input-field col s12 m6">
                            <i class="fa-solid fa-user-check prefix"></i>
                            <input type="text" id="indicacaoNome" name="indicacaoNome"
                                value="<?= htmlspecialchars($frontdeskSelected['indicacaoNome'] ?? '') ?>">
                            <label for="indicacaoNome" class="active">Nome Indicação</label>
                        </div>

                        <div class="input-field col s12 m6">
                            <i class="fa-solid fa-phone-volume prefix"></i>
                            <input type="text" id="indicacao" name="indicacao"
                                value="<?= htmlspecialchars($frontdeskSelected['indicacao'] ?? '') ?>">
                            <label for="indicacao" class="active">Contato Indicação</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <i class="fa-solid fa-user-group prefix"></i>
                            <select id="situacao" name="situacao">
                                <option value="">Tipo do cliente</option>
                                <option value="maior" <?= $frontdeskSelected['situacao'] == 'maior' ? 'selected' : '' ?>>Maior de idade</option>
                                <option value="menor_pubere" <?= $frontdeskSelected['situacao'] == 'menor_pubere' ? 'selected' : '' ?>>Menor púbere</option>
                                <option value="menor_impubere" <?= $frontdeskSelected['situacao'] == 'menor_impubere' ? 'selected' : '' ?>>Menor impúbere</option>
                            </select>
                            <label for="situacao">Tipo do cliente</label>
                        </div>
                    </div>
                    <div id="responsavel-fields">
                        <div class="row" >
                            <div class="input-field col s6">
                                <input type="text" id="nomeDependente" name="nomeDependente"
                                    value="<?= htmlspecialchars($frontdeskSelected['nomeDependente'] ?? '') ?>">
                                <label for="nomeDependente">Nome do dependente</label>
                            </div>
                            <div class="input-field col s6">
                                <input type="text" id="nacionalidadeDependente" name="nacionalidadeDependente"
                                    value="<?= htmlspecialchars($frontdeskSelected['nacionalidadeDependente'] ?? '') ?>">
                                <label for="nacionalidadeDependente">Nacionalidade do dependente</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input type="text" id="rgDependente" name="rgDependente"
                                    value="<?= htmlspecialchars($frontdeskSelected['rgDependente'] ?? '') ?>">
                                <label for="rgDependente">RG do dependente</label>
                            </div>
                            <div class="input-field col s6">
                                <input type="text" id="cpfDependente" name="cpfDependente"
                                    value="<?= htmlspecialchars($frontdeskSelected['cpfDependente'] ?? '') ?>">
                                <label for="cpfDependente">CPF do dependente</label>
                            </div>
                            <div class="input-field col s12">
                                <input type="text" id="relacaoResponsavel" name="relacaoResponsavel"
                                    value="<?= htmlspecialchars($frontdeskSelected['relacaoResponsavel'] ?? '') ?>">
                                <label for="relacaoResponsavel">Relação com o dependente</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa-envelope prefix"></i>
                            <input name="email" id="email" type="email" value="<?= $frontdeskSelected['email'] ?>">
                            <label for="email">E-mail (Opcional)</label>
                        </div>
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa-phone prefix"></i>
                            <input name="telefone1" id="telefone1" type="text" value="<?= $frontdeskSelected['telefone1'] ?>">
                            <label for="telefone1">Telefone 1 (Opcional)</label>
                        </div>
                        <div class="input-field col l4 m6 s12">
                            <i class="fa-solid fa-phone-alt prefix"></i>
                            <input name="telefone2" id="telefone2" type="text" value="<?= $frontdeskSelected['telefone2'] ?>">
                            <label for="telefone2">Telefone 2 (Opcional)</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12" style="padding-top: 10px;">
                            <i class="fa-solid fa-clipboard-list prefix"></i>
                            <textarea id="fatos" name="fatos" class="materialize-textarea" style="min-height: 200px;"><?= htmlspecialchars($frontdeskSelected['fatos'] ?? '') ?></textarea>
                            <label for="fatos">Fatos</label>
                        </div>
                    </div>

                    <!-- Documentos Selecionados -->
                     <?php
                        $documentOptions = [
                            1 => "01 - NOVO ATENDIMENTO INICIAL",
                            2 => "02 - NOVA PROCURAÇÃO",
                            3 => "03 - NOVA DECLARAÇÃO DE HIPOSSUFICIENCIA",
                            4 => "04 - NOVA PROCURAÇÃO INSS",
                            5 => "05 - NOVO CONTRATO DE PRESTAÇÃO DE SERVIÇOS ADVOCATÍCIOS",
                            6 => "06 - TERMO DE REVOGAÇÃO DE PROCURAÇÃO AD JUDICIA"
                        ];

                        $docsSelecionados = [];
                        if (!empty($frontdeskSelected['documentos'])) {
                            $docsSelecionados = explode(',', $frontdeskSelected['documentos']);
                        }
                    ?>
                    <div class="row">
                    <div class="col s12">
                        <label style="font-weight:bold; font-size:16px; display:block; margin-bottom:8px;">
                            Documentos Selecionados:
                        </label>
                        <div class="row" style="margin-top:0;">
                            <?php foreach ($documentOptions as $id => $label): ?>
                                <div class="col s12 m6" style="margin-bottom:10px;">
                                    <label style="display:flex; align-items:center; gap:8px;">
                                        <input type="checkbox" name="documentos[]" value="<?=$id?>" 
                                            <?= in_array((string)$id, $docsSelecionados) ? 'checked' : '' ?> />
                                        <span><?=$label?></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    </div>

                    <!-- Botões -->
                    <div class="row">
                        <button class="btn waves-effect waves-light" type="submit" name="action" id="reg-user"> 
                            <i class="fa-solid fa-pencil"></i>Alterar 
                        </button>
                        <button style="margin-left: 10px;" class="btn waves-effect waves-light" type="button" id="back" onClick="goBack()"> 
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
    <script src="../js/updateFrontdesk.js"></script>
</body>

</html>