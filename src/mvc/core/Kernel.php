<?php

namespace MVC\Core;

use MVC\Core\App;

class Kernel
{
    private $defaultControllerName = 'ResolveController';
    private $defaultActionName = 'handle';
    
    public function launch()
    {
        list($controllerName, $actionName) = App::$router->resolve();
        $this->launchAction($controllerName, $actionName);
    }

    public function launchAction($controllerName, $actionName)
    {
        $controllerName = file_exists(__DIR__ . "/../controllers/{$controllerName}.php") ? ucfirst($controllerName) . 'Controller' : $this->defaultControllerName;

        $actionName = empty($actionName) ? $this->defaultActionName : $actionName;
        
        $controllerName = '\MVC\Controllers\\' . $controllerName;
        if (!method_exists($controllerName, $actionName)){
            die('No such method');
        }

        $controller = new $controllerName();

        return $controller->$actionName();
    }
}