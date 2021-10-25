<?php

namespace MVC\Core;

use MVC\Core\Router;
use MVC\Core\Kernel;

class App
{
    public static $router;
    public static $kernel;
    public static $logger;
    public static $config;

    public function init()
    {
        self::$router = new Router;
        self::$kernel = new Kernel;
        self::$config = include( __DIR__ . '/../../configs/appconfig.php');
    }

}