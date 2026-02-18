<?php
session_start();
include_once './FrontdeskController.php';
include_once '../Models/Exceptions.php';
include_once './AuthController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido');
}

$controller = new FrontdeskController();

// Dados do formulário
$idFrontdesk = intval($_POST['id']);
$nome = trim($_POST['nome']);
$nacionalidade = trim($_POST['nacionalidade']);
$estadoCivil = trim($_POST['estadoCivil']);
$profissao = trim($_POST['profissao']);
$rg = trim($_POST['rg']);
$cpf = trim($_POST['cpf']);
$endereco = trim($_POST['endereco']);
$cidade = trim($_POST['cidade']);
$bairro = trim($_POST['bairro']);
$estado = trim($_POST['estado']);
$cep = trim($_POST['cep']);
$parteadversa = trim($_POST['parteadversa']);
$email = trim($_POST['email'] ?? '');
$telefone1 = trim($_POST['telefone1'] ?? '');
$telefone2 = trim($_POST['telefone2'] ?? '');
$documentosSelecionados = isset($_POST['documentos']) ? $_POST['documentos'] : [];
$fatos = trim($_POST['fatos']);
$data_referencia = trim($_POST['dataReferencia'] ?? '');
$situacao = trim($_POST['situacao'] ?? '');
$nomeDependente = trim($_POST['nomeDependente'] ?? '');
$nacionalidadeDependente = trim($_POST['nacionalidadeDependente'] ?? '');
$rgDependente = trim($_POST['rgDependente'] ?? '');
$cpfDependente = trim($_POST['cpfDependente'] ?? '');
$relacaoResponsavel = trim($_POST['relacaoResponsavel'] ?? '');
$pastaHibrida = trim($_POST['pastaHibrida'] ?? '');
$indicacao = trim($_POST['indicacao'] ?? '');
$indicacaoNome = trim($_POST['indicacaoNome'] ?? '');
$docs = !empty($documentosSelecionados) ? implode(',', $documentosSelecionados) : '';

$nomeUsuario = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Desconhecido';

if (!$nomeUsuario) {
    $exc = new ExceptionAlert('Usuário não autenticado.');
    echo $exc->alerts('error', 'Erro');
    header("Refresh: 2; url=../../pages/login.php");
    exit;
}

$result = $controller->updateFrontdesk(
    $idFrontdesk,
    $nome,
    $nacionalidade,
    $estadoCivil,
    $profissao,
    $rg,
    $cpf,
    $endereco,
    $cidade,
    $bairro,
    $estado,
    $cep,
    $parteadversa,
    $email,
    $telefone1,
    $telefone2,
    $docs,
    $fatos,
    $data_referencia,
    $nomeUsuario,
    $situacao,
    $nomeDependente,
    $nacionalidadeDependente,
    $rgDependente,
    $cpfDependente,
    $relacaoResponsavel,
    $pastaHibrida,
    $indicacao,
    $indicacaoNome
);

// Mensagem de retorno
if ($result[0] === 'success') {
    $exc = new ExceptionAlert('Atendimento atualizado com sucesso!');
    echo $exc->alerts('success', 'Sucesso');
    header("Refresh: 2; url=../../pages/frontdesk.php");
} else {
    $exc = new ExceptionAlert($result[1]);
    echo $exc->alerts('error', 'Erro');
    header("Refresh: 2; url=../../pages/frontdesk.php");
}
?>
