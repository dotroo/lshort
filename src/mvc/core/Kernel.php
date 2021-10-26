<?php

namespace MVC\Core;

use MVC\Core\App;
use MVC\Controllers\ShortenController;
use MVC\Controllers\ResolveController;

class Kernel
{
    private $defaultControllerName = 'ResolveController';
    private $defaultActionName = 'handle';
    
    public function launch()
    {
        $controllerName = '';
        $actionName = '';
        $resolvedRequest = App::$router->resolve();
        switch (count($resolvedRequest)) {
            case 1:
                $controllerName = $resolvedRequest[0];
                break;
            default:
                list($controllerName, $actionName) = $resolvedRequest;
        }
        $this->launchAction($controllerName, $actionName);
    }

    public function launchAction($controllerName, $actionName)
    {
        $controllerName = file_exists(__DIR__ . '/../controllers/' . ucfirst($controllerName) . 'Controller.php') ? ucfirst($controllerName) . 'Controller' : $this->defaultControllerName;

        $actionName = empty($actionName) ? $this->defaultActionName : $actionName;
        
        $controllerName = '\MVC\Controllers\\' . $controllerName;
        if (!method_exists($controllerName, $actionName)){
            die('No such method');
        }

        $controller = new $controllerName();

        return $controller->$actionName();
    }
}