<?php
	date_default_timezone_set('America/Sao_Paulo');
    include_once './VaraController.php';
    include_once './ProvidenceController.php';
    include_once './ProcessController.php';
    include_once './SubmissionController.php';
	include_once './UserController.php';
	$providences = '';
	$provController = new ProvidenceController();
	$userController = new UserController();
	$c = new VaraController();
    $court = $c->getAll();
    $processController = new ProcessController();
	$lastSub = new SubmissionController();
	$last=$lastSub->getLastSub();
	
	$title='<p style="text-align: right; margin: 0; font-weight: bold;"> Data de referência: '.date("d/m/Y",strtotime($last['updateDate'])).'</p>'.'<span style="font-size: 32px; font-weight: 700;">Advocacia Bertoldi</span> - Relatório de Providências';
	$num='';
	if(isset($_GET['id'])){
		$num=$_GET['id'];
		if(isset($_GET['search-type'])){
			$type = $_GET['search-type'];
			//echo $type;
			if($type==="ncli"){
				$name = addslashes($_GET['search-ncli']);
				$providences = $provController->getProvidenceByClientName($name,$num);
			}else if($type==="vara-sel"){
			  if(isset($_GET['search-vara'])){
				$vara = addslashes($_GET['search-vara']);
				$providences = $provController->getProvidenceByCourt($vara,$num);
			  }else{
				$providences = $provController->getProvidenceInProgress($num);
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
                    
	
				$providences = $provController->getProvidenceByDataTerm($start." 00:00:00", $end." 23:59:59",$num);
			}else if($type=='responsible-user'){
				$responsible_user = addslashes($_GET['responsible-user']);
				// echo $responsible_user;
				if($responsible_user!=""){
					$providences = $provController->getProvidenceByResponsibleUser($responsible_user,$num);
					
				}else{

					$providences = $provController->getProvidenceInProgress();
				}
			}else{
				$nproc=addslashes($_GET['search-nproc']);
				if($nproc==""){
					$providences = $provController->getProvidenceInProgress($num);
					
				}else{
	
					$providences = $provController->getProvidenceByNumProcess($nproc,$num);
				}
				
			}
		}else{
		$providences = $provController->getProvidenceInProgress($num);
		}
		if($num==1)
			$title.=" Concluídas";

	}
    

	$html = '<div>';
	
	$html.='<hr style="margin-bottom: 30px;"/>';
	
	$html .= '<table class="centered">';	
	$html .= '<thead>';
	$html .= '<tr style="background-color: rgb(209,209,209);">';
	$html .= '<th><b>#</b></th>';
	$html .= '<th><b>Data da Disponibilização</b></th>';
	$html .= '<th><b>Prazo</b></th>';
	$html .= '<th><b>Processo</b></th>';
	$html .= '<th><b>Vara</b></th>';
	$html .= '<th><b>Responsável</b></th>';
	$html .= '<th><b>Cliente</b></th>';
    $html .= '<th><b>Providência</b></th>';
    if($num!=1)
		$html .= '<th><b>Status</b></th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	
	for($i=0;$i<sizeof($providences);$i++){
        $p = $providences[$i];
		$id = $i+1;
        $status="";
		$style="padding:5px 10px;";

		if($num!=1){
			$status="A fazer";
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
			$style="";
            $aux= date('Y-m-d',strtotime($p[8]));
            if($p[11]==0 && strtotime($aux)==strtotime(date('Y-m-d')))
            {
                $style="color: green;";
                $status="Vence hoje";
                $style_status='<i class="fa-solid fa-triangle-exclamation" style="font-size:18px;"></i>';
            }
            if($diff_hour<=2 && strtotime($aux)==strtotime(date('Y-m-d')) && $p[11]==0){
                $style="color: orange;";
                $status="Urgente";  
            }else if(strtotime($aux)<strtotime(date('Y-m-d')) && $p[11]==0){
                $style="color: red;";
                $status="Atrasado";
            }      	 
        	
		}
		
		
        $proc = $processController->getById($p[1]);
		$user = $userController->getById($p[17]);
        $vara = $c->getById($proc['idCourt']);
		$html .= '<tr style="border: none;border-bottom: 1px solid rgb(209,209,209);"><td style="'.$style.'">'.$id.'</td>';
        $html .= '<td style="'.$style.'">'.date("d-m-Y", strtotime($p[6])).'</td>';
		$html .= '<td style="'.$style.'">'.date("d-m-Y", strtotime($p[8])).'</td>';
		$html .= '<td style="'.$style.'">'.$proc["numProcess"].'</td>';
		$html .= '<td style="'.$style.'">'.$vara["sigla"].'</td>';
		$html .= '<td style="'.$style.'">'.$user["name"].'</td>';
        $html .= '<td style="'.$style.'">'.$proc["clientName"].'</td>';
		if($num!=1){
        	$html .= '<td style="'.$style.'">'.$p[9].'</td>';
		}else{
			$html .= '<td style="'.$style.'">'.$p[9].'</td></tr>';
		}

		if($num!=1)
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
	$footer = '<div style="text-align: center; font-style: italic;">Gerado em: '.date('d/m/y H:i:s').'</div>';
	$mpdf->SetHTMLFooter($footer);
	$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
	$mpdf->WriteHTML('<div>'.$title.'</div>'
		.$html.'
	',\Mpdf\HTMLParserMode::HTML_BODY);

	// Output a PDF file directly to the browser
	$mpdf->Output('providencias-'.date('d-m-Y').'.pdf', 'I');
?>