<?php
	date_default_timezone_set('America/Sao_Paulo');
    include_once './ReceptionController.php';
    include_once './ClientController.php';
	include_once './UserController.php';

	$userController = new UserController();
	$receptionController = new ReceptionController();
    $clientController = new ClientController();
	$clis = $clientController->getAllForTable();
	$receptions = $receptionController->getAllReception();
	
	$title='<span style="font-size: 32px; font-weight: 700;">Advocacia Bertoldi</span> - Relatório da Recepção';
	$num='';
	if(isset($_GET['search-type'])){
		$type = $_GET['search-type'];
		//echo $type;
		if($type==="ncli"){
			if($_GET['search-ncli']=='')
			{
				$receptions = $receptionController->getAllReception();
			}else{
			$name = addslashes($_GET['search-ncli']);
			//echo $name;exit;
			$receptions = $receptionController->getAllReceptionByClient($name);
			}
		}else if($type==="resp-sel"){
		  if(isset($_GET['search-resp'])){
			$resp = addslashes($_GET['search-resp']);
			$receptions = $receptionController->getAllReceptionByResponsavel($resp);
		  }else{
			$receptions = $receptionController->getAllReception();
		  }
		}else if($type=="dta-contract"){
			$start= explode("/",addslashes($_GET['dta-contract']));
			if(sizeof($start)>1){

				$start = $start[2]."-".$start[1]."-".$start[0];
			}else{
				$start="";
			}
			if($start==""){
				$receptions = $receptionController->getAllReception();
			}else{
				$receptions = $receptionController->getReceptionByData($start);
			}
		}else if($type=="search_period"){
			$start= explode("/",addslashes($_GET['data_start']));
			$end= explode("/",addslashes($_GET['data_end']));
			if(sizeof($end)==1 || sizeof($start)==1)
			{
				$receptions = $receptionController->getAllReception();
			}else{
			if(sizeof($end)>1){

				$end = $end[2]."-".$end[1]."-".$end[0];
				$end.=' 23:59:59';
			}
			if(sizeof($start)>1){

				$start = $start[2]."-".$start[1]."-".$start[0];
				$start.=' 00:00:00';
			}
			$receptions = $receptionController->getReceptionByPeriod($start, $end);
			}
			
		}else if($type==="start"){
			if($_GET['search-phone']!=""){
			  $phone = addslashes($_GET['search-phone']);
			  $receptions = $receptionController->getAllReceptionByPhoneClient($phone);
			}else{
			  $receptions = $receptionController->getAllReception();
			}
		  }else if($type=="end"){
			  if($_GET['search-cpf']!=""){
				  $cpf = addslashes($_GET['search-cpf']);
				  $receptions = $receptionController->getAllReceptionByCpfClient($cpf);
				}else{
				  $receptions = $receptionController->getAllReception();
				}
		  }else if($type=="new"){
			  if($_GET['search-email']!=""){
				  $email = addslashes($_GET['search-email']);
				  $receptions = $receptionController->getAllReceptionByEmailClient($email);
				}else{
				  $receptions = $receptionController->getAllReception();
				}
		  }
	}
    

	$html = '<div>';
	
	$html.='<hr style="margin-bottom: 30px;"/>';
	
	$html .= '<table class="centered">';	
	$html .= '<thead>';
	$html .= '<tr style="background-color: rgb(209,209,209);">';
	$html .= '<th><b>#</b></th>';
	$html .= '<th><b>Chegada</b></th>';
	$html .= '<th><b>Saída</b></th>';
	$html .= '<th><b>Cliente</b></th>';
	$html .= '<th><b>Celular</b></th>';
	$html .= '<th><b>CPF</b></th>';
    $html .= '<th><b>Responsável</b></th>';
    $html .= '<th><b>Assunto</b></th>';
    $html .= '<th><b>Providência</b></th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	
	for($i=0;$i<sizeof($receptions);$i++){
        $p = $receptions[$i];
		$id = $i+1;
        $status="";
		$style="padding:5px 10px;";

        $resp=$userController->getById($p["idResponsavel"]);
    	$clo=$clientController->getById($p["idClient"]);
        $phoneCli=$clo["phone"];
        $cpfCli=$clo["cpf"];
        if($clo["phone"]=='')
        {
            $phoneCli="Não cadastrado.";
        }
        if($clo["cpf"]=='')
        {
            $cpfCli="Não cadastrado.";
        }
        $saida='';
        if($p["leave"]==null)
        {
            $saida='Atendimento não finalizado'; 
        }else{
            $saida=date("d-m-Y H:i:s", strtotime($p["leave"]));
        }
		$n=$p["providence"];
        if($n==NULL)
    	{
        	$n="---";
        }
		$html .= '<tr style="border: none;border-bottom: 1px solid rgb(209,209,209);"><td style="'.$style.'">'.$id.'</td>';
        $html .= '<td style="'.$style.'">'.date("d-m-Y", strtotime($p["arrival"])).'</td>';
		$html .= '<td style="'.$style.'">'.$saida.'</td>';
		$html .= '<td style="'.$style.'">'.$clo["name"].'</td>';
        $html .= '<td style="'.$style.'">'.$phoneCli.'</td>';
        $html .= '<td style="'.$style.'">'.$cpfCli.'</td>';
        $html .= '<td style="'.$style.'">'.$resp["name"].'</td>';
        $html .= '<td style="'.$style.'">'.$p["subject"].'</td>';
		$html .= '<td style="'.$style.'">'.$n.'</td></tr>';

	}
	$html .= '</tbody>';
	$html .= '</table>';
    $html .='</div>';

	require_once '../vendor/autoload.php';
	$mpdf = new \Mpdf\Mpdf();

	$mpdf->AddPage('L');
	
	$stylesheet = file_get_contents('../../styles/css/materialize/materialize.css');
	$footer = '<div style="text-align: center; font-style: italic;">Gerado em: '.date('d/m/y H:i:s').'</div>';
	$mpdf->SetHTMLFooter($footer);
	$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
	$mpdf->WriteHTML('<div>'.$title.'</div>'
		.$html.'
	',\Mpdf\HTMLParserMode::HTML_BODY);

	// Output a PDF file directly to the browser
	$mpdf->Output('recepcao-'.date('d-m-Y').'.pdf', 'I');
?>