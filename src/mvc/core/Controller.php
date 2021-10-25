<?php

namespace MVC\Core;

use MVC\Core\Model;

abstract class Controller
{
    /**
     * @var Model
     */
    protected $model;

    public abstract function handle();
}