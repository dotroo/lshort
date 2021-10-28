<?php

namespace MVC\Core;

class Router
{
    public static function resolve(): array
    {
        $routes = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        //игнорируем хост в routes[0], оставляем только составляющие uri
        for ($i = 0; $i < count($routes)-1; $i++){
            $result[$i] = $routes[$i+1];
        }
        
        return $result;
    }
}