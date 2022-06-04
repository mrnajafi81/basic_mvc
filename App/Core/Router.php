<?php namespace App\core;

class Router
{
    private $url, $controller, $method, $params = [];

    public function __construct()
    {
        $url = $_GET['url'] ?? null;
        $this->explodeUrl($url);
        $this->callController();
    }

    private function explodeUrl($url)
    {
        $this->url = $url ?? "index/indexAction";
        $this->url = explode('/', rtrim($this->url, '/'));
        $this->controller = ucfirst($this->url[0])."Controller";
        $this->method = $this->url[1] ?? "indexAction";
        $this->params = array_slice($this->url, 2) ?? null;
    }

    private function callController(){
        $controller_file = CONTROLLERS_PATH . DS . $this->controller . ".php";
        if (file_exists($controller_file)) {
            $this->controller = "App\Controllers\\".$this->controller;
            $controller = new $this->controller();
            if (method_exists($controller, $this->method)) {
                call_user_func_array([$controller, $this->method], $this->params);
            } else {
                die('Not Found');
            }
        } else {
            die('Not Found');
        }
    }
}