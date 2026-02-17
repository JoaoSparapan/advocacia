<?php
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/Router.php";
include_once $_SERVER['DOCUMENT_ROOT']."/advocacia/services/Controller/AuthController.php";

$router = new Router();

header('Location: '.$router->run('/login'));