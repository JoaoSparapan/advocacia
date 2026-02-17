<?php
	date_default_timezone_set('America/Sao_Paulo');
    include_once './PetitionController.php';
	include_once "./LogController.php";

	$logController = new LogController();
	$petitions = '';
	$pController = new PetitionController();
	$title='<p style="text-align: right; margin: 0;">'.date("d/m/Y H:i:s").'</p>'.'<span style="font-size: 32px; font-weight: 700;">Advocacia Bertoldi</span> - Relatório de Petições';
	$num='';
	if(isset($_GET['id'])){
		$num=$_GET['id'];
		if(isset($_GET['search-type'])){
			$type = $_GET['search-type'];
			if($type==="ncli"){
				$name = addslashes($_GET['search-ncli']);
				
				$petitions = $pController->getPetitionByClient($name,$num,1,$num);
				
			}else if($type==="adverso"){
			  if(isset($_GET['search-adverso'])){
				$adverse = addslashes($_GET['search-adverso']);
				$petitions = $pController->getPetitionByAdverse($adverse,$num,1,$num);
			  }else{
				$petitions = $pController->getPetitionDistributed();
			  }
			}else if($type=="dta-contract"){
				$start= explode("/",addslashes($_GET['dta-contract']));
				if(sizeof($start)==1)
				{
					if($num==1)
					{
						$petitions = $pController->getPetitionDistributed();
					}else if($num==0)
					{
						$petitions = $pController->getPetitionInProgress($num);
					}
				}else
				if(sizeof($start)>1){
					$start = $start[2]."-".$start[1]."-".$start[0];
					$petitions = $pController->getProvidenceByData($start,$num,1);
				}	
				}
			else if($type=="search_period"){
				$start= explode("/",addslashes($_GET['data_start']));
				$end= explode("/",addslashes($_GET['data_end']));
				if(sizeof($end)==1 || sizeof($start)==1)
				{
					if($num==1)
					{
						$petitions = $pController->getPetitionDistributed();
					}else if($num==0)
					{
						$petitions = $pController->getPetitionInProgress($num);
					}
				}else{
				if(sizeof($end)>1){

					$end = $end[2]."-".$end[1]."-".$end[0];
				}
				if(sizeof($start)>1){

					$start = $start[2]."-".$start[1]."-".$start[0];
				}
				//echo $start;echo $end;exit;
				$petitions = $pController->getProvidenceByPeriod($start,$end,$num,1,$num);
				}
			}else if($type=='type-action'){
				
					$type_action=addslashes($_GET['type-action']);
					if($type_action!=""){
						//echo $type_action;exit;
						$petitions = $pController->getPetitionByActionType($type_action,$num,1,$num);
						
					}else{
						if($num==1)
						{
							$petitions = $pController->getPetitionDistributed();
						}else if($num==0)
						{
							$petitions = $pController->getPetitionInProgress($num);
						}
					}
			}else if($type=='responsible-user'){
				$responsible_user = addslashes($_GET['responsible-user']);
				// echo $responsible_user;
				if($responsible_user!=""){
					$petitions = $pController->getPetitionByResponsibleUser($responsible_user,$num,1,$num);
					
				}else{
					if($num==1)
					{
						$petitions = $pController->getPetitionDistributed();
					}else if($num==0)
					{
						$petitions = $pController->getPetitionInProgress($num);
					}
				}
			
				
				
			}else{
				if($num==1)
				{
					$petitions = $pController->getPetitionDistributed();
				}else if($num==0)
				{
					$petitions = $pController->getPetitionInProgress($num);
				}
				
				}
				if($num==1)
					$title.=" Distribuídas";
	}else{
	if($num==1)
	{
		$petitions = $pController->getPetitionDistributed();
	}else if($num==0)
	{
		$petitions = $pController->getPetitionInProgress($num);
	}
}
}

    

    $html = '<div style="background-image: url('."./logotipo.png".')">';
	
	$html.='<hr style="margin-bottom: 30px;"/>';
	
	$html .= '<table class="centered">';	
	$html .= '<thead>';
	$html .= '<tr style="background-color: rgb(209,209,209);">';
	$html .= '<th><b>#</b></th>';
	$html .= '<th><b>Cliente</b></th>';
	$html .= '<th><b>Adverso</b></th>';
	$html .= '<th><b>Data da Contratação</b></th>';
	$html .= '<th><b>Natureza da ação</b></th>';
	$html .= '<th><b>Adv. Responsável</b></th>';
    $html .= '<th><b>Dias p/ Ajuizamento</b></th>';
	$html .= '<th><b>Prescrição</b></th>';
	$html .= '<th><b>Decadência</b></th>';
	$html .= '<th><b>Status</b></th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	
	for($i=0;$i<sizeof($petitions);$i++){
        $p = $petitions[$i];
		$id = $i+1;
        $status="";
		$style="padding:5px 10px;";
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
		if($num!=1){
			$status="A fazer";
			$origin = date_create(date('Y-m-d', strtotime($p[1])));
            $target = date_create(date('Y-m-d'));
            $interval = date_diff($origin, $target);
            $aux=intval($interval->format('%a'));
            $style="";
            $d="";
            if($aux==1)
            {
                $d = $interval->format('%a dia');
            }else{
            	$d = $interval->format('%a dias');
            }
			if($p[8]=='1')
            {
                    $style="color: purple;font-weight: bold;";
            }else{
				if($aux>15 && $aux<=30 && $p[4]==0){
					$style="color: green;";
				}else if($aux>30 && $p[4]==0){
					$style="color: red;";
				}
			}
		}else{
			$style="";
			$l = $logController->getLastLogByPetition($p[0]);
            $day = explode(" ", $l['edited_by']);
            $target = date_create(date('Y-m-d', strtotime($p[1])));
            $date = date_create(date('Y-m-d', strtotime($day[0])));
            $aux=0;
            $d="";
            $interval = date_diff($date, $target);
            if(strtotime($date->format('d-m-Y'))==strtotime($target->format('d-m-Y')))
            {
                $interval = date_diff($date, $target);
            }else{
                $interval = date_diff($date, $target);
                $aux=intval($interval->format('%a'));
            }
            if($aux==1)
            {
                $d = $interval->format('%a dia');
            }else{
                $d = $interval->format('%a dias');
            }
			if($p[7]==1)
                {
                    $status='Sem fundamento';
                    $d='---';
					$pres='---';
        			$deca='---';
                    $style='font-style: italic;';
                }else{
                    $status='Distribuída';
                }
		}
		$html .= '<tr style="border: none;border-bottom: 1px solid rgb(209,209,209);"><td style="'.$style.'">'.$id.'</td>';
        $html .= '<td style="'.$style.'">'.$p[2].'</td>';
		$html .= '<td style="'.$style.'">'.$p[3].'</td>';
		$html .= '<td style="'.$style.'">'.date("d-m-Y", strtotime($p[1])).'</td>';
		$html .= '<td style="'.$style.'">'.$p[9].'</td>';
		$html .= '<td style="'.$style.'">'.$p[11].'</td>';
		$html .= '<td style="'.$style.'">'.$d.'</td>';
		$html .= '<td style="'.$style.'">'.$pres.'</td>';
		$html .= '<td style="'.$style.'">'.$deca.'</td>';
        $html .= '<td style="'.$style.'">'.$status.'</td></tr>';

	}
	$html .= '</tbody>';
	$html .= '</table>';
    $html .='</div>';

	

	require_once '../vendor/autoload.php';
	// Create an instance of the class:
	$mpdf = new \Mpdf\Mpdf();

	$mpdf->AddPage('L');
	// Write some HTML code:
	//$mpdf->Image('../../images/logotipo.png', 40, 30, 20, 20, 'png', '', true, false);
	
	$stylesheet = file_get_contents('../../styles/css/materialize/materialize.css');
	$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
	$mpdf->WriteHTML('<div>'.$title.'</div>'
		.$html.'
	',\Mpdf\HTMLParserMode::HTML_BODY);

	// Output a PDF file directly to the browser
	$mpdf->Output('petition-'.date('d-m-Y').'.pdf', 'I');
?>