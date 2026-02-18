<?php
require $_SERVER['DOCUMENT_ROOT'].'/advocacia/services/vendor/autoload.php';
include_once $_SERVER['DOCUMENT_ROOT'] . "/advocacia/services/Controller/GlobalController.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PhpOffice\PhpWord\TemplateProcessor;

class FrontdeskController extends GlobalController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertFrontdesk($data)
    {
        $conn = $this->connectDB();
        
        $nome = addslashes($data['nome']);
        $nacionalidade = addslashes($data['nacionalidade']);
        $estadoCivil = addslashes($data['estadoCivil']);
        $profissao = addslashes($data['profissao']);
        $rg = addslashes($data['rg']);
        $cpf = addslashes($data['cpf']);
        $endereco = addslashes($data['endereco']);
        $cidade = addslashes($data['cidade']);
        $bairro = addslashes($data['bairro']);
        $estado = addslashes($data['estado']);
        $cep = addslashes($data['cep']);
        $email = addslashes($data['email']);
        $telefone1 = isset($data['telefone1']) ? addslashes($data['telefone1']) : '';
        $telefone2 = isset($data['telefone2']) ? addslashes($data['telefone2']) : '';
        $parteadversa = addslashes($data['parteadversa']);
        $fatos = addslashes($data['fatos']);
        $data_referencia = addslashes($data['dataAtual']);
        $documentos = implode(',', $data['documentos']);
        $usuario = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Desconhecido';
        $situacao = addslashes($data['situacao']);
        $nomeDependente = addslashes($data['nomeDependente']);
        $nacionalidadeDependente = addslashes($data['nacionalidadeDependente']);
        $rgDependente = addslashes($data['rgDependente']);
        $cpfDependente = addslashes($data['cpfDependente']);
        $relacaoResponsavel = addslashes($data['relacaoResponsavel']);
        $pastaHibrida = addslashes($data['pastaHibrida']);
        $indicacao = addslashes($data['indicacao']);
        $indicacaoNome = addslashes($data['indicacaoNome']);

        $query = "INSERT INTO frontdesk 
            (nome,nacionalidade,estadoCivil,profissao,rg,cpf,endereco,cidade,bairro,estado,cep,email,telefone1,telefone2,documentos,parteadversa,fatos,data_referencia,usuario,
            situacao,nomeDependente,nacionalidadeDependente,rgDependente,cpfDependente,relacaoResponsavel,pastaHibrida,indicacao,indicacaoNome) 
            VALUES 
            ('$nome','$nacionalidade','$estadoCivil','$profissao','$rg','$cpf','$endereco','$cidade','$bairro','$estado','$cep','$email',
            '$telefone1','$telefone2','$documentos','$parteadversa','$fatos','$data_referencia','$usuario','$situacao','$nomeDependente','$nacionalidadeDependente',
            '$rgDependente','$cpfDependente','$relacaoResponsavel', '$pastaHibrida', '$indicacao', '$indicacaoNome')";
        $result = mysqli_query($conn, $query);
        $id = mysqli_insert_id($conn);
        $this->disconnectDB($conn);

        if ($result) {
            return ['success', 'Registro salvo com sucesso!', $id];
        } else {
            return ['error', 'Erro ao salvar registro!'];
        }
    }

    public function getByCpf($cpf=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM frontdesk WHERE cpf='$cpf' ORDER BY data_cadastro DESC";
        $result = mysqli_query($conn, $query);
        if($result){
            $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return $r;
    }

    public function getByEmail($email=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM frontdesk WHERE email like '%$email%' ORDER BY data_cadastro DESC";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getByParteAdversa($parteAdversa=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM frontdesk WHERE parteadversa like '%$parteAdversa%' ORDER BY data_cadastro DESC";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getByIndicacao($indicacao=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM frontdesk WHERE indicacao like '%$indicacao%' ORDER BY data_cadastro DESC";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getByIndicacaoNome($indicacaoNome=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM frontdesk WHERE indicacaoNome like '%$indicacaoNome%' ORDER BY data_cadastro DESC";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getByPastaHibrida($pastaHibrida=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM frontdesk WHERE pastaHibrida = '$pastaHibrida' ORDER BY data_cadastro DESC";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getByPhone($phone=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM frontdesk WHERE '$phone' IN (telefone1, telefone2) ORDER BY data_cadastro DESC";
        $result = mysqli_query($conn, $query);
        $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $this->disconnectDB($conn);
        return $r;
    }

    public function getByName($name=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM frontdesk WHERE nome LIKE '%$name%' ORDER BY data_cadastro DESC";
        $result = mysqli_query($conn, $query);
        $r=null;
        if($result){
            $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        $this->disconnectDB($conn);
        return $r;
    }

    public function getByResponsavel($name=''){
        $conn = $this->connectDB();
        $query = "SELECT * FROM frontdesk WHERE usuario LIKE '%$name%' ORDER BY data_cadastro DESC";
        $result = mysqli_query($conn, $query);
        $r=null;
        if($result){
            $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        $this->disconnectDB($conn);
        return $r;
    }

    public function getAllFrontdesk(){
        $conn = $this->connectDB();
    
            $query = "SELECT * FROM frontdesk ORDER BY data_cadastro DESC";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
            $dados = array();
            if($result){
                $dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }else{
                throw new Exception('Erro ao requisitar recepções!');
            }
    
            $this->disconnectDB($conn);
            return $dados;
    }

    public function getById($id = 0)
    {
        $conn = $this->connectDB();
        $id = intval($id);
        $query = "SELECT * FROM frontdesk WHERE idFrontdesk=$id";
        $result = mysqli_query($conn, $query);
        $registro = null;
        if ($result) {
            $registro = mysqli_fetch_assoc($result);
        }
        $this->disconnectDB($conn);
        return $registro;
    }

    public function updateFrontdesk(
        $id = NULL,
        $nome = NULL,
        $nacionalidade = NULL,
        $estadoCivil = NULL,
        $profissao = NULL,
        $rg = NULL,
        $cpf = NULL,
        $endereco = NULL,
        $cidade = NULL,
        $bairro = NULL,
        $estado = NULL,
        $cep = NULL,
        $parteadversa = NULL,
        $email = NULL,
        $telefone1 = NULL,
        $telefone2 = NULL,
        $docs = NULL,
        $fatos = NULL,
        $data_referencia = NULL,
        $usuario = NULL,
        $situacao = NULL,
        $nomeDependente = NULL,
        $nacionalidadeDependente = NULL,
        $rgDependente = NULL,
        $cpfDependente = NULL,
        $relacaoResponsavel = NULL,
        $pastaHibrida = NULL,
        $indicacao = NULL,
        $indicacaoNome = NULL
    ) {
        if ($id == NULL) {
            return ['error', 'Erro ao atualizar registro de atendimento'];
        }

        $conn = $this->connectDB();

        $query = "
            UPDATE frontdesk SET 
                nome = '$nome',
                nacionalidade = '$nacionalidade',
                estadoCivil = '$estadoCivil',
                profissao = '$profissao',
                rg = '$rg',
                cpf = '$cpf',
                endereco = '$endereco',
                cidade = '$cidade',
                bairro = '$bairro',
                estado = '$estado',
                cep = '$cep',
                parteadversa = '$parteadversa',
                email = '$email',
                telefone1 = '$telefone1',
                telefone2 = '$telefone2',
                documentos = '$docs',
                fatos = '$fatos',
                data_referencia = '$data_referencia',
                usuario = '$usuario',
                data_cadastro = NOW(),
                situacao = '$situacao',
                nomeDependente = '$nomeDependente',
                nacionalidadeDependente = '$nacionalidadeDependente',
                rgDependente = '$rgDependente',
                cpfDependente = '$cpfDependente',
                relacaoResponsavel = '$relacaoResponsavel',
                pastaHibrida = '$pastaHibrida',
                indicacao = '$indicacao',
                indicacaoNome = '$indicacaoNome'
            WHERE idFrontdesk = $id
        ";

        // echo $query;exit;
        $result = mysqli_query($conn, $query);

        $result = mysqli_real_escape_string($conn, $endereco);

        if (!$result) {
            $this->disconnectDB($conn);
            return ['error', 'Erro ao atualizar registro de atendimento'];
        }

        $this->disconnectDB($conn);
        return ['success', 'Sucesso ao atualizar registro de atendimento!'];
    }


    public function deleteFrontdeskById($id=NULL){
            if($id==NULL){
                return ['error','Erro ao deletar registro de atendimento'];
            }
            $conn = $this->connectDB();

            $query = "DELETE FROM frontdesk WHERE idFrontDesk=$id";
    
            $result=NULL;
    
            $result = mysqli_query($conn, $query);
    
            if(!$result){
                return ['error','Erro ao deletar registro de atendimento'];
            }
    
            $this->disconnectDB($conn);
            return ['success',"Sucesso ao deletar registro de atendimento!"];
    }
}
