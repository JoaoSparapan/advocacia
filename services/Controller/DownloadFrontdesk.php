<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

require $_SERVER['DOCUMENT_ROOT']."/advocacia/services/vendor/autoload.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/FrontdeskController.php";

use PhpOffice\PhpWord\TemplateProcessor;

// Verifica se o usuário está logado
if (!isset($_SESSION['user'])) {
    http_response_code(403);
    exit('Acesso negado');
}

// Recebe o ID do registro
$idFrontdesk = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($idFrontdesk <= 0) {
    http_response_code(400);
    exit('ID inválido');
}

$controller = new FrontdeskController();
$record = $controller->getById($idFrontdesk);

if (!$record) {
    http_response_code(404);
    exit('Registro não encontrado');
}

// Map dos documentos
$documentNames = [
    1 => "01 - NOVO ATENDIMENTO INICIAL.docx",
    2 => "02 - NOVA PROCURAÇÃO.docx",
    3 => "03 - NOVA DECLARAÇÃO DE HIPOSSUFICIENCIA.docx",
    4 => "04 - NOVA PROCURAÇÃO INSS.docx",
    5 => "05 - NOVO CONTRATO DE PRESTAÇÃO DE SERVIÇOS ADVOCATÍCIOS.docx",
    6 => "06 - TERMO DE REVOGAÇÃO DE PROCURAÇÃO AD JUDICIA.docx"
];

// Caminho da pasta modelos
$modelosDir = $_SERVER['DOCUMENT_ROOT']."/advocacia/modelos/";

// Cria o ZIP
$zip = new ZipArchive();
$tempZip = tempnam(sys_get_temp_dir(), 'zip');

if ($zip->open($tempZip, ZipArchive::CREATE) !== true) {
    http_response_code(500);
    exit('Não foi possível criar o ZIP');
}

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

// Documentos selecionados no registro
$docsIds = explode(',', $record['documentos']);
$docsIdsInt = array_map('intval', $docsIds);

foreach ($docsIdsInt as $docId) {
    if (!isset($documentNames[$docId])) continue;

    $modeloFile = $modelosDir.$documentNames[$docId];
    if (!file_exists($modeloFile)) continue;

    $template = new TemplateProcessor($modeloFile);

    $dataReferenciaIso = $record['data_referencia'] ?? date('Y-m-d');
    $dataReferenciaExtenso = dataPorExtenso($dataReferenciaIso);
    $dataReferenciaBR = dataFormatadaBR($dataReferenciaIso);

    // --- Substituições básicas ---
    $template->setValue('dataAtual', trim($dataReferenciaExtenso));
    $template->setValue('dataReferenciaNumerica', trim($dataReferenciaBR));
    $template->setValue('NOME', trim(strtoupper($record['nome'] ?? '')));
    $template->setValue('cpf', trim($record['cpf'] ?? ''));
    $template->setValue('rg', trim($record['rg'] ?? ''));
    $template->setValue('endereco', trim($record['endereco'] ?? ''));
    $template->setValue('cidade', trim($record['cidade'] ?? ''));
    $template->setValue('estado', trim($record['estado'] ?? ''));
    $template->setValue('estadoCivil', trim($record['estadoCivil'] ?? ''));
    $template->setValue('nacionalidade', trim($record['nacionalidade'] ?? ''));
    $template->setValue('profissao', trim($record['profissao'] ?? ''));
    $template->setValue('bairro', trim($record['bairro'] ?? ''));
    $template->setValue('cep', trim($record['cep'] ?? ''));

    // --- Monta descrição ---
    $descricao = '';
    $nomeDependente='';
    if(trim($record['situacao']) != 'maior')
    {
        $descricao .= ', ' . trim($record['nacionalidadeDependente']) . ', solteiro(a)';

        $identidade = [];
        if (!empty($record['rgDependente'])) $identidade[] = "portador(a) da cédula de identidade RG nº " . $record['rgDependente'];
        if (!empty($record['cpfDependente'])) $identidade[] = "inscrito(a) no CPF sob nº " . $record['cpfDependente'];
        if (!empty($identidade)) $descricao .= ', ' . implode(', ', $identidade);

        $situacao = trim($record['situacao']) ?? '';
        $relacao = trim($record['relacaoResponsavel']) ?? '';
        if ($situacao === 'menor_pubere') {
            $descricao .= ", menor púbere, neste ato assistido(a) por seu/sua $relacao";
        } elseif ($situacao === 'menor_impubere') {
            $descricao .= ", menor impúbere, neste ato representado(a) por seu/sua $relacao";
        }

        if ($descricao === ', , solteiro') {
            $descricao = '';
        }
        $nomeDependente=strtoupper($record['nomeDependente'] ?? '');
    }
    $template->setValue('NOMEDEPENDENTE', trim($nomeDependente));
    $template->setValue('descricao', trim($descricao) . ' ');

    $nomePrincipal=trim(strtoupper($record['nome'] ?? ''));

    $ASS_LEFT  = '';
    $ASS_RIGHT = '';
    $ASS_CENTER = '';

    if ($situacao === 'menor_pubere') {
        $ASS_LEFT = strtoupper($nomeDependente);
        $ASS_RIGHT = strtoupper($nomePrincipal);
        $ASS_CENTER = "\xc2\xa0";
    } elseif ($situacao === 'menor_impubere') {
        $centralName = $nomePrincipal;
        if ($centralName === '') {
            $ASS_CENTER = "\xc2\xa0";
        } else {
            $ASS_CENTER = strtoupper($centralName);
        }
        $ASS_LEFT = "\xc2\xa0";
        $ASS_RIGHT = "\xc2\xa0";
    } else {
        $centralName = $nomePrincipal;
        if ($centralName === '') {
            $ASS_CENTER = "\xc2\xa0";
        } else {
            $ASS_CENTER = strtoupper($centralName);
        }
        $ASS_LEFT = "\xc2\xa0";
        $ASS_RIGHT = "\xc2\xa0";
    }

    $template->setValue('ASS_LEFT', $ASS_LEFT);
    $template->setValue('ASS_RIGHT', $ASS_RIGHT);
    $template->setValue('ASS_CENTER', $ASS_CENTER);

    // --- Documento 1 recebe informações de parte adversa e contato ---
    if ($docId == 1) {
        $parteAdversa = strtoupper($record['parteadversa'] ?? '');
        $parteAdversa = str_replace(['&','<','>','"',"'" ], ['&amp;','&lt;','&gt;','&quot;','&apos;'], $parteAdversa);
        $template->setValue('PARTEADVERSA', $parteAdversa);

        $fatos = str_replace(['&','<','>','"',"'" ], ['&amp;','&lt;','&gt;','&quot;','&apos;'], $record['fatos'] ?? '');
        $template->setValue('fatos', $fatos);

        $template->setValue('telefone1', $record['telefone1'] ?? '');
        $template->setValue('telefone2', $record['telefone2'] ?? '');
        $template->setValue('email', $record['email'] ?? '');

        $telefones = array_filter([$record['telefone1'], $record['telefone2']]);
        $complementoContato = '';
        if ($telefones && !empty($record['email'])) {
            $complementoContato = 'Telefone(s) ' . implode(' e ', $telefones) . ' e e-mail ' . $record['email'] . '.';
        } elseif ($telefones) {
            $complementoContato = 'Telefone(s) ' . implode(' e ', $telefones) . '.';
        } elseif (!empty($record['email'])) {
            $complementoContato = 'E-mail ' . $record['email'] . '.';
        }
        $template->setValue('complementoContato', $complementoContato);
        
        $indicacao = trim($record['indicacao'] ?? '');
        $indicacaoNome = trim($record['indicacaoNome'] ?? '');

        $textoIndicacao = '';

        if ($indicacao !== '' && $indicacaoNome !== '') {
            $textoIndicacao = "Indicação: {$indicacaoNome} - Contato: {$indicacao}.";
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

    // Salva documento temporário e adiciona ao ZIP
    $tmpDoc = tempnam(sys_get_temp_dir(), 'docx_') . '.docx';
    $template->saveAs($tmpDoc);
    $zip->addFile($tmpDoc, $documentNames[$docId]);
    register_shutdown_function(function() use ($tmpDoc) { @unlink($tmpDoc); });
}

// Fecha o ZIP antes de tudo
$zip->close();

// Garante que o arquivo foi gravado corretamente
clearstatcache(true, $tempZip);

if (!file_exists($tempZip) || filesize($tempZip) === 0) {
    http_response_code(500);
    exit('Erro ao gerar o arquivo ZIP.');
}

// Nome do ZIP
$nomeCliente = preg_replace('/[^A-Za-z0-9_\-]/', '_', $record['nome']);
$dataGerada = date('Ymd_His');
$zipFileName = "documentos_{$nomeCliente}_{$dataGerada}.zip";

// Limpa qualquer buffer que possa ter saído antes (importantíssimo)
if (ob_get_length()) ob_end_clean();

// Envia o ZIP para download
header('Content-Type: application/zip');
header("Content-Disposition: attachment; filename=\"$zipFileName\"");
header('Content-Length: ' . filesize($tempZip));
header('Pragma: no-cache');
header('Expires: 0');
readfile($tempZip);

// Remove o ZIP temporário após o envio
unlink($tempZip);
exit;