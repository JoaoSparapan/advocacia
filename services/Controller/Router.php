<?php

require_once __DIR__ . '/RouteSwitch.php';

class Router extends RouteSwitch
{
    public function run(string $requestUri)
    {
        $route = substr($requestUri, 1);

        if ($route === '') {
            return $this->home();
        } else {
            return $this->$route();
        }
    }
    public function test(string $requestUri)
    {
        return $requestUri;
    }
}