<?php

abstract class RouteSwitch
{
    private $actual_link;
    private $indexPath;
    public function __construct(){
        $this->actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $this->indexPath = $this->actual_link.'/advocacia/';
    }
    protected function home()
    {
        return $this->indexPath;
    }

    protected function petitions(string $params='')
    {
        if($params==''){
            return $this->indexPath.'pages/petition.php';
        }else{
            return $this->indexPath.'pages/petition.php?'.$params;
        }
        
    }

     protected function providences(string $params='')
    {
        if($params==''){
            return $this->indexPath.'pages/providences.php';
        }else{
            return $this->indexPath.'pages/providences.php?'.$params;
        }
        
    }

    protected function process(){
        return $this->indexPath.'pages/process.php';
    }

    protected function providenced()
    {
        return $this->indexPath.'pages/providenced.php';
    }

    protected function clientes()
    {
        return $this->indexPath.'pages/clients.php';
    }

    protected function recepcao()
    {
        return $this->indexPath.'pages/recepcao.php';
    }

    protected function atendimento()
    {
        return $this->indexPath.'pages/frontdesk.php';
    }

    protected function login()
    {
        return $this->indexPath.'pages/login.php';
    }

    protected function distributed()
    {
        return $this->indexPath.'pages/distributed.php';
    }

    protected function profile(){
        return $this->indexPath.'pages/profile.php';
    }

    protected function users(){
        return $this->indexPath.'pages/users.php';
    }
    
    public function __call($name, $arguments) #será chamado sempre que nao encontrar a rota
    {
        header('Location: ' . $this->indexPath. 'pages/not-found.php');
        
    }
}