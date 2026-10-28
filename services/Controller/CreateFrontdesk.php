<?php
ob_start(); // INICIA O BUFFER PARA IMPEDIR CORRUPÇÃO DO ZIP
session_start();
date_default_timezone_set('America/Sao_Paulo');

require $_SERVER['DOCUMENT_ROOT']."/advocacia/services/vendor/autoload.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/FrontdeskController.php";

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\TextRun;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido');
}

$controller = new FrontdeskController();

$nome = trim($_POST['nome'] ?? '');
$documentosSelecionados = $_POST['documentos'] ?? [];
$empresa = trim($_POST['empresa'] ?? '');
$cnpj = trim($_POST['cnpj'] ?? '');
$cargo = trim($_POST['cargo'] ?? '');
$enderecoPJ = trim($_POST['endereco_pj'] ?? '');
$cidadePJ = trim($_POST['cidade_pj'] ?? '');
$bairroPJ = trim($_POST['bairro_pj'] ?? '');
$estadoPJ = trim($_POST['estado_pj'] ?? '');
$cepPJ = trim($_POST['cep_pj'] ?? '');

// Captura dos novos campos da Parte Adversa
$cpfCnpjAdversa = trim($_POST['cpf_cnpj_adversa'] ?? '');
$enderecoAdversa = trim($_POST['endereco_adversa'] ?? '');
$telefone1Adversa = trim($_POST['telefone1_adversa'] ?? '');
$telefone2Adversa = trim($_POST['telefone2_adversa'] ?? '');

if ($nome === '' || empty($documentosSelecionados)) {
    http_response_code(400);
    exit('Nome ou documentos não enviados');
}

// --- Prepara dados para o banco ---
$dataInsert = [
    'nome' => $nome,
    'nacionalidade' => trim($_POST['nacionalidade'] ?? ''),
    'estadoCivil' => trim($_POST['estadoCivil'] ?? ''),
    'profissao' => trim($_POST['profissao'] ?? ''),
    'rg' => trim($_POST['rg'] ?? ''),
    'cpf' => trim($_POST['cpf'] ?? ''),
    'endereco' => trim($_POST['endereco'] ?? ''),
    'cidade' => trim($_POST['cidade'] ?? ''),
    'bairro' => trim($_POST['bairro'] ?? ''),
    'estado' => trim($_POST['estado'] ?? ''),
    'cep' => trim($_POST['cep'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'telefone1' => trim($_POST['telefone1'] ?? ''),
    'telefone2' => trim($_POST['telefone2'] ?? ''),
    'documentos' => $documentosSelecionados,
    'parteadversa' => trim($_POST['parteadversa'] ?? ''),
    'fatos' => trim($_POST['fatos'] ?? ''),
    'situacao' => trim($_POST['situacao'] ?? ''),
    'nomeDependente' => trim($_POST['nomeDependente'] ?? ''),
    'nacionalidadeDependente' => trim($_POST['nacionalidadeDependente'] ?? ''),
    'rgDependente' => trim($_POST['rgDependente'] ?? ''),
    'cpfDependente' => trim($_POST['cpfDependente'] ?? ''),
    'relacaoResponsavel' => trim($_POST['relacaoResponsavel'] ?? ''),
    'dataAtual' => trim($_POST['dataReferencia'] ?? ''),
    'pastaHibrida' => trim($_POST['pastaHibrida'] ?? ''),
    'indicacao' => trim($_POST['indicacao'] ?? ''),
    'indicacaoNome' => trim($_POST['indicacaoNome'] ?? ''),
    'empresa' => $empresa,
    'cnpj' => $cnpj,
    'cargo' => $cargo,
    'endereco_pj' => $enderecoPJ,
    'cidade_pj' => $cidadePJ,
    'bairro_pj' => $bairroPJ,
    'estado_pj' => $estadoPJ,
    'cep_pj' => $cepPJ,
    'nome_litis' => trim($_POST['nome_litis'] ?? ''),
    'nacionalidade_litis' => trim($_POST['nacionalidade_litis'] ?? ''),
    'estadoCivil_litis' => trim($_POST['estadoCivil_litis'] ?? ''),
    'rg_litis' => trim($_POST['rg_litis'] ?? ''),
    'cpf_litis' => trim($_POST['cpf_litis'] ?? ''),
    'profissao_litis' => trim($_POST['profissao_litis'] ?? ''),
    'endereco_litis' => trim($_POST['endereco_litis'] ?? ''),
    'bairro_litis' => trim($_POST['bairro_litis'] ?? ''),
    'cidade_litis' => trim($_POST['cidade_litis'] ?? ''),
    'cep_litis' => trim($_POST['cep_litis'] ?? ''),
    'estado_litis' => trim($_POST['estado_litis'] ?? ''),
    'palavra_chave' => trim($_POST['palavra_chave'] ?? ''),
    
    // Novos campos adicionados para o banco
    'cpf_cnpj_adversa' => $cpfCnpjAdversa,
    'endereco_adversa' => $enderecoAdversa,
    'telefone1_adversa' => $telefone1Adversa,
    'telefone2_adversa' => $telefone2Adversa,
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

$modelosDir = $_SERVER['DOCUMENT_ROOT']."/modelos/";

// --- Cria o ZIP ---
$zip = new ZipArchive();
$tempZip = tempnam(sys_get_temp_dir(), 'zip');

// CORREÇÃO: Adicionado o ZipArchive::OVERWRITE para não gerar aviso
if ($zip->open($tempZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
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
    
    // CORREÇÃO: Resgatada a variável no começo
    $situacao = trim($_POST['situacao'] ?? '');
    $relacao = trim($_POST['relacaoResponsavel'] ?? '');
    
    if ($situacao === 'pj') {
        $empresaStr = trim($_POST['empresa'] ?? '');
        $cnpjStr = trim($_POST['cnpj'] ?? '');
        $enderecoStr = trim($_POST['endereco_pj'] ?? '');
        $cidadeStr = trim($_POST['cidade_pj'] ?? '');
        $estadoStr = trim($_POST['estado_pj'] ?? '');
        $cepStr = trim($_POST['cep_pj'] ?? '');
        $cargoStr = trim($_POST['cargo'] ?? '');

        $partes = [];

        if ($empresaStr !== '') $partes[] = ", pessoa jurídica de direito privado";
        if ($cnpjStr !== '') $partes[] = "inscrita no CNPJ sob nº {$cnpjStr}";

        if ($enderecoStr !== '') {
            $local = $enderecoStr;
            if ($cidadeStr !== '' && $estadoStr !== '') $local .= ", {$cidadeStr}/{$estadoStr}";
            if ($cepStr !== '') $local .= ", CEP: {$cepStr}";
            $partes[] = "situada na {$local}";
        }

        if ($cargoStr !== '') $partes[] = "neste ato representada por seu/sua {$cargoStr}";

        $descricao = implode(', ', $partes);
        $nomeDependente = trim(strtoupper($_POST['empresa'] ?? ''));
    }
    else if ($situacao !== 'maior' && $situacao !== 'litis') {
        $descricao .= ', ' . trim($_POST['nacionalidadeDependente'] ?? '') . ', solteiro(a)';

        $identidade = [];
        if (!empty($_POST['rgDependente'])) $identidade[] = "portador(a) da cédula de identidade RG nº " . trim($_POST['rgDependente']);
        if (!empty($_POST['cpfDependente'])) $identidade[] = "inscrito(a) no CPF sob nº " . trim($_POST['cpfDependente']);
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
    
    // CORREÇÃO: Bloco litis padronizado usando TextRun igual ao Script 1
    if ($situacao === 'litis') {
        $textRun = new TextRun();
        $fontNormal = ['name' => 'Bookman Old Style', 'size' => 12];
        $fontBold = ['name' =>'Bookman Old Style', 'size' => 12, 'bold' => true];

        $nomeLitis = mb_strtoupper(trim($_POST['nome_litis'] ?? ''), 'UTF-8');
        $nacionalidadeLitis = trim($_POST['nacionalidade_litis'] ?? '');
        $estadoCivilLitis = trim($_POST['estadoCivil_litis'] ?? '');
        $profissaoLitis = trim($_POST['profissao_litis'] ?? '');
        $rgLitis = trim($_POST['rg_litis'] ?? '');
        $cpfLitis = trim($_POST['cpf_litis'] ?? '');
        $enderecoLitis = trim($_POST['endereco_litis'] ?? '');
        $bairroLitis = trim($_POST['bairro_litis'] ?? '');
        $cidadeLitis = trim($_POST['cidade_litis'] ?? '');
        $estadoLitis = trim($_POST['estado_litis'] ?? '');
        $cepLitis = trim($_POST['cep_litis'] ?? '');

        if ($nomeLitis !== '') {
            $textRun->addText("Em conjunto com, ", $fontNormal);
            $textRun->addText($nomeLitis, $fontBold);
        }

        $resto = [];
        if ($nacionalidadeLitis !== '') $resto[] = $nacionalidadeLitis;
        if ($estadoCivilLitis !== '') $resto[] = $estadoCivilLitis;
        if ($profissaoLitis !== '') $resto[] = $profissaoLitis;
        if ($rgLitis !== '') $resto[] = "portador(a) da cédula de identidade RG nº {$rgLitis}";
        if ($cpfLitis !== '') $resto[] = "inscrito(a) no CPF sob nº {$cpfLitis}";

        if ($enderecoLitis !== '') {
            $enderecoCompleto = "residente e domiciliado(a) à {$enderecoLitis}";
            if ($bairroLitis !== '') $enderecoCompleto .= ", Bairro {$bairroLitis}";
            if ($cidadeLitis !== '' && $estadoLitis !== '') $enderecoCompleto .= ", {$cidadeLitis}/{$estadoLitis}";
            if ($cepLitis !== '') $enderecoCompleto .= ", CEP: {$cepLitis}";
            $resto[] = $enderecoCompleto;
        }

        if (!empty($resto)) {
            $textRun->addText(', ' . implode(', ', $resto) . '.', $fontNormal);
        }

        $template->setComplexValue('litis', $textRun);
        $descricao = '';
        $nomeDependente = '';
    } else {
        $template->setValue('litis', '');
    }

    $template->setValue('descricao', trim($descricao) . ' ');
    $template->setValue('NOMEDEPENDENTE', trim($nomeDependente));

    $nomePrincipal = trim(strtoupper($_POST['nome'] ?? ''));

    $ASS_LEFT  = '';
    $ASS_RIGHT = '';
    $ASS_CENTER = '';

    // CORREÇÃO: Assinaturas padronizadas
    if ($situacao === 'menor_pubere') {
        $ASS_LEFT = strtoupper($nomeDependente);
        $ASS_RIGHT = strtoupper($nomePrincipal);
        $ASS_CENTER = "\xc2\xa0";
    } elseif ($situacao === 'menor_impubere') {
        $ASS_LEFT = "\xc2\xa0";
        $ASS_RIGHT = "\xc2\xa0";
        $ASS_CENTER = $nomePrincipal ?: "\xc2\xa0";
    } elseif ($situacao === 'litis') {
        $nomeLitisAssinatura = mb_strtoupper(trim($_POST['nome_litis'] ?? ''), 'UTF-8');
        $ASS_LEFT = mb_strtoupper($nomePrincipal, 'UTF-8');
        $ASS_RIGHT = $nomeLitisAssinatura !== '' ? $nomeLitisAssinatura : "\xc2\xa0";
        $ASS_CENTER = "\xc2\xa0";
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

        // CORREÇÃO: Formatação dos NOVOS CAMPOS igual ao script 1
        $cnpjAdversaPost = trim($_POST['cpf_cnpj_adversa'] ?? '');
        $template->setValue('CNPJPARTEADVERSA', $cnpjAdversaPost !== '' ? 'CNPJ/CPF: ' . $cnpjAdversaPost : '');

        $enderecoAdversaPost = trim($_POST['endereco_adversa'] ?? '');
        $template->setValue('ENDERECOPARTEADVERSA', $enderecoAdversaPost !== '' ? 'ENDEREÇO: ' . $enderecoAdversaPost : '');

        $tel1AdversaPost = trim($_POST['telefone1_adversa'] ?? '');
        $template->setValue('TEL1PARTEADVERSA', $tel1AdversaPost !== '' ? 'TELEFONE 01: ' . $tel1AdversaPost : '');

        $tel2AdversaPost = trim($_POST['telefone2_adversa'] ?? '');
        $template->setValue('TEL2PARTEADVERSA', $tel2AdversaPost !== '' ? 'TELEFONE 02: ' . $tel2AdversaPost : '');
        // -----------------------------------------------------

        $fatos = str_replace(['&','<','>','"',"'" ], ['&amp;','&lt;','&gt;','&quot;','&apos;'], $_POST['fatos'] ?? '');
        $template->setValue('fatos', $fatos);

        $template->setValue('telefone1', trim($_POST['telefone1'] ?? ''));
        $template->setValue('telefone2', trim($_POST['telefone2'] ?? ''));
        $template->setValue('email', trim($_POST['email'] ?? ''));

        $telefones = array_filter([trim($_POST['telefone1'] ?? ''), trim($_POST['telefone2'] ?? '')]);
        $complementoContato = '';
        if ($telefones && !empty($_POST['email'])) {
            $complementoContato = 'Telefone(s) ' . implode(' e ', $telefones) . ' e e-mail ' . trim($_POST['email']) . '.';
        } elseif ($telefones) {
            $complementoContato = 'Telefone(s) ' . implode(' e ', $telefones) . '.';
        } elseif (!empty($_POST['email'])) {
            $complementoContato = 'E-mail ' . trim($_POST['email']) . '.';
        }
        $template->setValue('complementoContato', $complementoContato);

        $indicacao = trim($_POST['indicacao'] ?? '');
        $indicacaoNome = trim($_POST['indicacaoNome'] ?? '');

        $textoIndicacao = '';
        if ($indicacao !== '' && $indicacaoNome !== '') {
            $textoIndicacao = "Indicação: {$indicacaoNome} - Contato: {$indicacao}.";
        } elseif ($indicacao !== '') {
            $textoIndicacao = "Contato Indicação: {$indicacao}.";
        } elseif ($indicacaoNome !== '') {
            $textoIndicacao = "Indicação: {$indicacaoNome}.";
        }
        $template->setValue('INDICACAO', $textoIndicacao);

        // Prevenção de Bug: Declarar $text_palavra sempre, mesmo vazia
        $text_palavra = ''; 
        $palavra_secreta = trim($_POST['palavra_chave'] ?? '');
        if ($palavra_secreta !== '') {
            $text_palavra = "Palavra-chave: {$palavra_secreta}";
        }
        $template->setValue('PALAVRA_CHAVE', $text_palavra);
        
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

// O ob_end_clean() joga fora qualquer texto (avisos, espaços) que tenha escapado no buffer
if (ob_get_length()) {
    ob_end_clean();
}

header('Content-Type: application/zip');
header("Content-Disposition: attachment; filename=\"$zipFileName\"");
header('Content-Length: ' . filesize($tempZip));
header('Pragma: no-cache');
header('Expires: 0');
readfile($tempZip);
unlink($tempZip);
exit;