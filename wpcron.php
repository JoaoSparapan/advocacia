<?php 
date_default_timezone_set('America/Sao_Paulo');
require __DIR__ . "/services/Models/UserMail.php";
require __DIR__ . "/services/Controller/PetitionController.php";
include_once "./services/Controller/LogController.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/services/Mailer/PHPMailer/src/PHPMailer.php";
require __DIR__ . "/services/Mailer/PHPMailer/src/SMTP.php";

$files = array();
$p = new PetitionController();
$log = new LogController(); 
$petitions = $p->getPetitions();
$max_file = ceil(sizeof($petitions)/1500);
for($x=0;$x<$max_file;$x++)
{
$title='<p style="text-align: right; margin: 0;">'.date("d/m/Y H:i:s").'</p>'.'<span style="font-size: 32px; font-weight: 700;">Advocacia Bertoldi</span> - Relatório de Petições';
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
	$html .= '<th><b>Data da distribuição</b></th>';
	$html .= '<th><b>Status</b></th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	$limite = ($x*1500)+1500;
    if($limite>sizeof($petitions))
    {
        $limite=sizeof($petitions);
    }
	for($i=$x*1500;$i<$limite;$i++){
        $p = $petitions[$i];
		$id = $i+1;
        $status="";
		$style="padding:5px 10px;";
        $num = $p[4];
			
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
        	if($aux>15 && $aux<=30 && $p[4]==0){
                $style="color: green;";
            }else if($aux>30 && $p[4]==0){
                $style="color: red;";
            }
		}else{
			    $l = $log->getLastLogByPetition($p[0]);
                $day = explode(" ", $l['edited_by']);
                $target = date_create(date('Y-m-d'));
                $date = date_create(date('Y-m-d', strtotime($day[0])));
                $aux=0;
                $d="";
                $interval = date_diff($date, $target);
                if(strtotime($date->format('d-m-Y'))==strtotime($target->format('d-m-Y')))
                {
                    $aux=1;
                    $date->modify('+1 day'); // adiciona um dia
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
				$status="Distribuída";
		}
		
        $html .= '<tr style="border: none;border-bottom: 1px solid rgb(209,209,209);"><td style="'.$style.'">'.$id.'</td>';
        $html .= '<td style="'.$style.'">'.$p[2].'</td>';
		$html .= '<td style="'.$style.'">'.$p[3].'</td>';
		$html .= '<td style="'.$style.'">'.date("d-m-Y", strtotime($p[1])).'</td>';
		$html .= '<td style="'.$style.'">'.$p[5].'</td>';
		$html .= '<td style="'.$style.'">'.$p[7].'</td>';
		$html .= '<td style="'.$style.'">'.$d.'</td>';
		if($num==1){
			$html .= '<td style="'.$style.'">'.date('d-m-Y', strtotime($day[0])).'</td>';}
		else{
			$html .= '<td style="'.$style.'">---</td>';
		}
        $html .= '<td style="'.$style.'">'.$status.'</td></tr>';	
	
	}
	
	$html .= '</tbody>';
	$html .= '</table>';
    $html .='</div>';

	
    require __DIR__ . "/services/vendor/autoload.php";
	// Create an instance of the class:
	$mpdf = new \Mpdf\Mpdf();

	$mpdf->AddPage('L');
	
	$stylesheet = file_get_contents('./styles/css/materialize/materialize.css');
	$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
	$mpdf->WriteHTML('<div>'.$title.'</div>'
		.$html.'
	',\Mpdf\HTMLParserMode::HTML_BODY);
    
    
        // Output a PDF file directly to the browser
	$mpdf->Output('pdfs\iniciais-'.$x.'.pdf', \Mpdf\Output\Destination::FILE);
    array_push($files,'pdfs\iniciais-'.$x.'.pdf');
    }

    $zip = new ZipArchive;
    $ret = $zip->open('backup-iniciais.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
    if ($ret == TRUE) {
        if ( !empty( $files ) ) {
            foreach ( $files as $file ) {
                $zip->addFile( $file, basename( $file ) );
            }
            
        }
        
        $zip->close();
    
        
    } else {
        echo 'failed';
    }
	
$phpmailer = new PHPMailer();
$phpmailer->isSMTP();
$phpmailer->SMTPAuth = true;
$phpmailer->SMTPSecure = 'ssl';
$phpmailer->CharSet="UTF-8";
$phpmailer->Host = 'smtp.hostinger.com';
$phpmailer->Port = 465;
$phpmailer->Username = 'sendemail@advbertoldi.com.br';
$phpmailer->Password = 'Email@123';

$orig='Advocacia Bertoldi';
$phpmailer->setFrom('sendemail@advbertoldi.com.br',$orig); //Origem
$phpmailer->addAddress('sparapan15@hotmail.com');     //Destinatario      

//Conteudo
$phpmailer->isHTML(true);
$phpmailer->Subject = 'Backup Petições - Advocacia Bertoldi';                                  
$phpmailer->Body    = 'Backup do sistema de petições iniciais feito em: '.date('d-m-Y H:i:s').'.';
$phpmailer->AddAttachment('./backup.zip');
$phpmailer->send();

?>
