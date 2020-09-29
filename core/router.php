<?php

class Router
{
    private $path;
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->path = Path::getInstance();
    }

    function start(){
        $routes = Router::parseRoutes();
        $moduleName = Router::extractModuleName($routes);
        $action = Router::extractActionName($routes);
        $_GET = Router::extractGetParams();

        $controller = Router::createController($moduleName);

        Router::executeActionFromController($controller, $action);
    }

    private function parseRoutes(){
        $urlAndParams = explode('?', $this->request);
        return explode('/', $urlAndParams[0]);
    }

    private function extractModuleName($routes){
        return !empty($routes[1]) ? $routes[1] : "main";
    }

    private function extractActionName($routes){
        return !empty($routes[2]) ? $routes[2] : "index";
    }

    private function createController($moduleName){
        $controllerName = 'Controller_' . $moduleName;
        $controllerFile = strtolower($controllerName) . '.php';
        $controllerPath = $this->path->getPath("controller",  $controllerFile );

        $controller = false;

        if ( $controllerPath != null ) {
            include $controllerPath;
            $controller = new $controllerName;

            $model = Router::createModel($moduleName);
            if( $model ){
                $controller->model = $model;
            }
        }

        return  $controller;
    }

    private function createModel($controllerName){
        $model = false;

        $modelName = 'Model_' . $controllerName;
        $modelFile = strtolower($modelName) . '.php';
        $modelPath = $this->path->getPath("model",  $modelFile );

        if (file_exists($modelPath)) {
            include $modelPath;
            $model = new $modelName;
        }

        return $model;
    }

    private function executeActionFromController($controller, $action)
    {
        if (method_exists($controller, $action)) {
            //Ejecuto el metodo
            $controller->$action();
        } else {
            Router::ErrorPage404();
        }
    }

    function ErrorPage404(){
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'error/error404');
        exit();
    }

    private function extractGetParams() {

        $getParams = array();
        if (isset($_SERVER["REQUEST_URI"])) {

            // Separo la URL de los parametros tipo GET
            $requestPath = explode("?", $this->request);

            // Obtengo los parametros tipo GET si es que existen
            if (isset($requestPath[1])) {

                $path["query_utf8"] = $requestPath[1];
                $path["query"] = utf8_decode($path["query_utf8"]);

                // Parseo los parametros tipo GET en un array asociativo
                $vars = explode('&', $path["query"]);
                foreach ($vars as $var) {
                    $param = explode('=', $var, 2);
                    if (count($param) == 2) {
                        $getParams[$param[0]] = $param[1];
                    }
                }
            }
        }
        return $getParams;
    }
}