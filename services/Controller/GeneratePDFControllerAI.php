<?php
require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');
include_once './FrontdeskController.php';
include_once './UserController.php';
$frontdeskController = new FrontdeskController();
$userController = new UserController();
$fronts = $frontdeskController->getAllFrontdesk();

$title = '<span style="font-size: 32px; font-weight: 700;">Advocacia Bertoldi</span> - Relatório de Atendimentos';
$num = '';
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


$html = '<div>';

$html .= '<hr style="margin-bottom: 30px;"/>';

$html .= '<table class="centered">';
$html .= '<thead>';
$html .= '<tr style="background-color: rgb(209,209,209);">';
$html .= '<th><b>#</b></th>';
$html .= '<th><b>Cliente</b></th>';
$html .= '<th><b>CPF</b></th>';
$html .= '<th><b>E-mail</b></th>';
$html .= '<th><b>Celular 1</b></th>';
$html .= '<th><b>Parte Adversa</b></th>';
$html .= '<th><b>Pasta Híbrida</b></th>';
$html .= '<th><b>Nome Indicação</b></th>';
$html .= '<th><b>Contato Indicação</b></th>';
$html .= '<th><b>Cadastrado por</b></th>';
$html .= '<th><b>Atualização</b></th>';
$html .= '<th><b>Arquivos gerados</b></th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

for ($i = 0; $i < sizeof($fronts); $i++) {
	$p = $fronts[$i];
	$idTabela = $i + 1;
	$status = "";
	$style = "padding:5px 10px;";

	$u = $fronts[$i];
	$phoneCli1 = $u["telefone1"];
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

	$indicacao = $u["indicacao"];
    if ($indicacao == NULL) {
        $indicacao = 'Não';
    }

	$indicacaoNome = $u["indicacaoNome"];
    if ($indicacaoNome == NULL) {
        $indicacaoNome = 'Não';
    }

	$html .= '<tr style="border: none;border-bottom: 1px solid rgb(209,209,209);"><td style="' . $style . '">' . $idTabela . '</td>';
	$html .= '<td style="' . $style . '">' . $u["nome"] . '</td>';
	$html .= '<td style="' . $style . '">' . $u["cpf"] . '</td>';
	$html .= '<td style="' . $style . '">' . $email . '</td>';
	$html .= '<td style="' . $style . '">' . $phoneCli1 . '</td>';
	$html .= '<td style="' . $style . '">' . $parteAdversa . '</td>';
	$html .= '<td style="' . $style . '">' . $u["pastaHibrida"] . '</td>';
	$html .= '<td style="' . $style . '">' . $indicacaoNome . '</td>';
	$html .= '<td style="' . $style . '">' . $indicacao . '</td>';
	$html .= '<td style="' . $style . '">' . $u["usuario"] . '</td>';
	$html .= '<td style="' . $style . '">' . date("d-m-Y", strtotime($u["data_cadastro"])) . '</td>';
	$html .= '<td style="' . $style . '">' . $docsDisplayString . '</td>';

}
$html .= '</tbody>';
$html .= '</table>';
$html .= '</div>';



$mpdf = new \Mpdf\Mpdf();


$mpdf->AddPage('L');

$stylesheet = file_get_contents('../../styles/css/materialize/materialize.css');
$footer = '<div style="text-align: center; font-style: italic;">Gerado em: ' . date('d/m/y H:i:s') . '</div>';
$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML('<div>' . $title . '</div>'
	. $html . '
	', \Mpdf\HTMLParserMode::HTML_BODY);

$mpdf->Output('atendimentos-inicias-' . date('d-m-Y') . '.pdf', 'I');
?>