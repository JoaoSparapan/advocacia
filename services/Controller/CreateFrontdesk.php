<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

require $_SERVER['DOCUMENT_ROOT']."/advocacia/services/vendor/autoload.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/FrontdeskController.php";

use PhpOffice\PhpWord\TemplateProcessor;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido');
}

$controller = new FrontdeskController();

$nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$documentosSelecionados = isset($_POST['documentos']) ? $_POST['documentos'] : [];

if ($nome === '' || empty($documentosSelecionados)) {
    http_response_code(400);
    exit('Nome ou documentos não enviados');
}

// --- Prepara dados para o banco ---
$dataInsert = [
    'nome' => $nome,
    'nacionalidade' => $_POST['nacionalidade'] ?? '',
    'estadoCivil' => $_POST['estadoCivil'] ?? '',
    'profissao' => $_POST['profissao'] ?? '',
    'rg' => $_POST['rg'] ?? '',
    'cpf' => $_POST['cpf'] ?? '',
    'endereco' => $_POST['endereco'] ?? '',
    'cidade' => $_POST['cidade'] ?? '',
    'bairro' => $_POST['bairro'] ?? '',
    'estado' => $_POST['estado'] ?? '',
    'cep' => $_POST['cep'] ?? '',
    'email' => $_POST['email'] ?? '',
    'telefone1' => $_POST['telefone1'] ?? '',
    'telefone2' => $_POST['telefone2'] ?? '',
    'documentos' => $documentosSelecionados,
    'parteadversa' => $_POST['parteadversa'] ?? '',
    'fatos' => $_POST['fatos'] ?? '',
    'situacao' => $_POST['situacao'] ?? '',
    'nomeDependente' => $_POST['nomeDependente'] ?? '',
    'nacionalidadeDependente' => $_POST['nacionalidadeDependente'] ?? '',
    'rgDependente' => $_POST['rgDependente'] ?? '',
    'cpfDependente' => $_POST['cpfDependente'] ?? '',
    'relacaoResponsavel' => $_POST['relacaoResponsavel'] ?? '',
    'dataAtual' => $_POST['dataReferencia'] ?? '',
    'pastaHibrida' => $_POST['pastaHibrida'] ?? '',
    'indicacao' => $_POST['indicacao'] ?? '',
    'indicacaoNome' => $_POST['indicacaoNome'] ?? ''
];

$resultInsert = $controller->insertFrontdesk($dataInsert);
if ($resultInsert[0] !== 'success') {
    http_response_code(500);
    exit($resultInsert[1]);
}

// --- Map dos modelos ---
$documentNames = [
    1 => "01 - NOVO ATENDIMENTO INICIAL.docx",
    2 => "02 - NOVA PROCURAÇÃO.docx",
    3 => "03 - NOVA DECLARAÇÃO DE HIPOSSUFICIENCIA.docx",
    4 => "04 - NOVA PROCURAÇÃO INSS.docx",
    5 => "05 - NOVO CONTRATO DE PRESTAÇÃO DE SERVIÇOS ADVOCATÍCIOS.docx",
    6 => "06 - TERMO DE REVOGAÇÃO DE PROCURAÇÃO AD JUDICIA.docx"
];

$modelosDir = $_SERVER['DOCUMENT_ROOT']."/advocacia/modelos/";

// --- Cria o ZIP ---
$zip = new ZipArchive();
$tempZip = tempnam(sys_get_temp_dir(), 'zip');
if ($zip->open($tempZip, ZipArchive::CREATE) !== true) {
    http_response_code(500);
    exit('Não foi possível criar o ZIP');
}

// --- Funções auxiliares ---
function dataFormatadaBR($dataIso) {
    if (!$dataIso) return '';
    return date('d/m/Y', strtotime($dataIso));
}

function dataPorExtenso($dataIso) {
    if (!$dataIso) return '';
    $meses = [
        1 => 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
        'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
    ];
    [$ano, $mes, $dia] = explode('-', $dataIso);
    $dia = str_pad($dia, 2, '0', STR_PAD_LEFT);
    return $dia . ' de ' . $meses[intval($mes)] . ' de ' . $ano;
}

$dataReferenciaIso = $_POST['dataReferencia'] ?? date('Y-m-d');
$dataReferenciaExtenso = dataPorExtenso($dataReferenciaIso);
$dataReferenciaBR = dataFormatadaBR($dataReferenciaIso);

// --- Geração de documentos ---
foreach ($documentosSelecionados as $docId) {
    if (!isset($documentNames[$docId])) continue;
    $modeloFile = $modelosDir.$documentNames[$docId];
    if (!file_exists($modeloFile)) continue;

    $template = new TemplateProcessor($modeloFile);

    // --- Dados básicos ---
    $template->setValue('dataAtual', trim($dataReferenciaExtenso));
    $template->setValue('dataReferenciaNumerica', trim($dataReferenciaBR));
    $template->setValue('NOME', trim(strtoupper($_POST['nome'] ?? '')));
    $template->setValue('cpf', trim($_POST['cpf'] ?? ''));
    $template->setValue('rg', trim($_POST['rg'] ?? ''));
    $template->setValue('endereco', trim($_POST['endereco'] ?? ''));
    $template->setValue('cidade', trim($_POST['cidade'] ?? ''));
    $template->setValue('estado', trim($_POST['estado'] ?? ''));
    $template->setValue('estadoCivil', trim($_POST['estadoCivil'] ?? ''));
    $template->setValue('nacionalidade', trim($_POST['nacionalidade'] ?? ''));
    $template->setValue('profissao', trim($_POST['profissao'] ?? ''));
    $template->setValue('bairro', trim($_POST['bairro'] ?? ''));
    $template->setValue('cep', trim($_POST['cep'] ?? ''));

    // --- Descrição e assinatura ---
    $descricao = '';
    $nomeDependente = '';
    $situacao = trim($_POST['situacao']) ?? '';
    $relacao = trim($_POST['relacaoResponsavel']) ?? '';

    if ($situacao !== 'maior') {
        $descricao .= ', ' . (trim($_POST['nacionalidadeDependente']) ?? '') . ', solteiro(a)';

        $identidade = [];
        if (!empty($_POST['rgDependente'])) $identidade[] = "portador(a) da cédula de identidade RG nº " . $_POST['rgDependente'];
        if (!empty($_POST['cpfDependente'])) $identidade[] = "inscrito(a) no CPF sob nº " . $_POST['cpfDependente'];
        if (!empty($identidade)) $descricao .= ', ' . implode(', ', $identidade);

        if ($situacao === 'menor_pubere') {
            $descricao .= ", menor púbere, neste ato assistido(a) por seu/sua $relacao";
        } elseif ($situacao === 'menor_impubere') {
            $descricao .= ", menor impúbere, neste ato representado(a) por seu/sua $relacao";
        }

        if (trim($descricao) === ', , solteiro') {
            $descricao = '';
        }

        $nomeDependente = trim(strtoupper($_POST['nomeDependente'] ?? ''));
    }

    $template->setValue('descricao', trim($descricao) . ' ');
    $template->setValue('NOMEDEPENDENTE', trim($nomeDependente));

    $nomePrincipal = trim(strtoupper($_POST['nome'] ?? ''));

    $ASS_LEFT  = '';
    $ASS_RIGHT = '';
    $ASS_CENTER = '';

    if ($situacao === 'menor_pubere') {
        $ASS_LEFT = $nomeDependente;
        $ASS_RIGHT = $nomePrincipal;
        $ASS_CENTER = "\xc2\xa0";
    } elseif ($situacao === 'menor_impubere') {
        $ASS_LEFT = "\xc2\xa0";
        $ASS_RIGHT = "\xc2\xa0";
        $ASS_CENTER = $nomePrincipal ?: "\xc2\xa0";
    } else {
        $ASS_LEFT = "\xc2\xa0";
        $ASS_RIGHT = "\xc2\xa0";
        $ASS_CENTER = $nomePrincipal ?: "\xc2\xa0";
    }

    $template->setValue('ASS_LEFT', $ASS_LEFT);
    $template->setValue('ASS_RIGHT', $ASS_RIGHT);
    $template->setValue('ASS_CENTER', $ASS_CENTER);

    // --- Documento 1: parte adversa e contato ---
    if ($docId == 1) {
        $parteAdversa = strtoupper($_POST['parteadversa'] ?? '');
        $parteAdversa = str_replace(['&','<','>','"',"'" ], ['&amp;','&lt;','&gt;','&quot;','&apos;'], $parteAdversa);
        $template->setValue('PARTEADVERSA', $parteAdversa);

        $fatos = str_replace(['&','<','>','"',"'" ], ['&amp;','&lt;','&gt;','&quot;','&apos;'], $_POST['fatos'] ?? '');
        $template->setValue('fatos', $fatos);

        $template->setValue('telefone1', $_POST['telefone1'] ?? '');
        $template->setValue('telefone2', $_POST['telefone2'] ?? '');
        $template->setValue('email', $_POST['email'] ?? '');

        $telefones = array_filter([$_POST['telefone1'] ?? '', $_POST['telefone2'] ?? '']);
        $complementoContato = '';
        if ($telefones && !empty($_POST['email'])) {
            $complementoContato = 'Telefone(s) ' . implode(' e ', $telefones) . ' e e-mail ' . $_POST['email'] . '.';
        } elseif ($telefones) {
            $complementoContato = 'Telefone(s) ' . implode(' e ', $telefones) . '.';
        } elseif (!empty($_POST['email'])) {
            $complementoContato = 'E-mail ' . $_POST['email'] . '.';
        }
        $template->setValue('complementoContato', $complementoContato);

        $indicacao = trim($_POST['indicacao'] ?? '');
        $indicacaoNome = trim($_POST['indicacaoNome'] ?? '');

        $textoIndicacao = '';

        if ($indicacao !== '' && $indicacaoNome !== '') {
            $textoIndicacao = "Indicação: {$indicacao} - Contato: {$indicacaoNome}.";
        } elseif ($indicacao !== '') {
            $textoIndicacao = "Contato Indicação: {$indicacao}.";
        } elseif ($indicacaoNome !== '') {
            $textoIndicacao = "Indicação: {$indicacaoNome}.";
        }

        $template->setValue('INDICACAO', $textoIndicacao);
    } else {
        $template->setValue('telefone1', '');
        $template->setValue('telefone2', '');
        $template->setValue('email', '');
        $template->setValue('complementoContato', '');
    }

    // --- Salva o arquivo e adiciona ao ZIP ---
    $tmpDoc = tempnam(sys_get_temp_dir(), 'docx_') . '.docx';
    $template->saveAs($tmpDoc);
    $zip->addFile($tmpDoc, $documentNames[$docId]);
    register_shutdown_function(function() use ($tmpDoc) { @unlink($tmpDoc); });
}

$zip->close();
clearstatcache(true, $tempZip);

if (!file_exists($tempZip) || filesize($tempZip) === 0) {
    http_response_code(500);
    exit('Erro ao gerar o arquivo ZIP.');
}

$nomeCliente = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nome);
$dataGerada = date('Ymd_His');
$zipFileName = "documentos_{$nomeCliente}_{$dataGerada}.zip";

if (ob_get_length()) ob_end_clean();

header('Content-Type: application/zip');
header("Content-Disposition: attachment; filename=\"$zipFileName\"");
header('Content-Length: ' . filesize($tempZip));
header('Pragma: no-cache');
header('Expires: 0');
readfile($tempZip);
unlink($tempZip);
exit;
