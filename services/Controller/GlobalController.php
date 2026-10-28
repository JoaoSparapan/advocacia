<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/advocacia/config.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/advocacia/services/Controller/AuthController.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/advocacia/services/Controller/Router.php";

// escapa um valor para saída em HTML (conteúdo e atributos)
if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
  }
}

class GlobalController
{

  private $servidor;
  private $usuario;
  private $senha;
  private $banco;

  // 1. Variável estática para segurar a conexão única
  private static $connection = null;

  function __construct()
  {
    $this->servidor = Config::$server;
    $this->usuario = Config::$user;
    $this->senha = Config::$pass;
    $this->banco = Config::$db;
  }

  private function detect_encoding($string)
  {
    if (preg_match('%^(?: [\x09\x0A\x0D\x20-\x7E] | [\xC2-\xDF][\x80-\xBF] | \xE0[\xA0-\xBF][\x80-\xBF] | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} | \xED[\x80-\x9F][\x80-\xBF] | \xF0[\x90-\xBF][\x80-\xBF]{2} | [\xF1-\xF3][\x80-\xBF]{3} | \xF4[\x80-\x8F][\x80-\xBF]{2} )*$%xs', $string))
      return 'UTF-8';

    return mb_detect_encoding($string, array('UTF-8', 'ASCII', 'ISO-8859-1', 'JIS', 'EUC-JP', 'SJIS'));
  }

  public function convert_encoding($string, $to_encoding = 'UTF-8', $from_encoding = '')
  {
    if ($from_encoding == '')
      $from_encoding = $this->detect_encoding($string);

    if ($from_encoding == $to_encoding)
      return $string;

    return mb_convert_encoding($string, $to_encoding, $from_encoding);
  }

  protected function connectDB()
  {
    // 2. Conecta apenas se a conexão ainda não existir
    if (self::$connection == null) {
        self::$connection = mysqli_connect($this->servidor, $this->usuario, $this->senha, $this->banco) or die("Erro na conexão do banco!");
        mysqli_query(self::$connection, "SET time_zone = '-03:00'");
        date_default_timezone_set('America/Sao_Paulo');
    }

    // Retorna a conexão aberta
    return self::$connection;
  }

  protected function getAllForTable($table)
  {
    $conexao = $this->connectDB();
    $command = "select * from $table";
    $result = mysqli_query($conexao, $command);
    $r = null;
    if ($result) {
      $r = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    $this->disconnectDB($conexao);
    return $r;
  }

  protected function disconnectDB($con)
  {
    // 3. Método desativado para o padrão Singleton funcionar.
    // Não fecharemos a conexão aqui para que o loop na página providences.php
    // possa reaproveitá-la. O PHP encerra a conexão no fim da execução.
    
    /* 
    if ($con) {
      mysqli_close($con);
    } 
    */
  }

  protected function getServidor()
  {
    return $this->servidor;
  }

}

$router = new Router;
if (file_exists('/AuthController.php')) {
  if (AuthController::getUser() == null) {
    header('Location: ' . $router->run('/login'));
  }
}
?>